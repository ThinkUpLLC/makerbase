<?php

class AddController extends AuthController {

    public function authControl() {
        $this->setViewTemplate('add.tpl');

        if ($_GET['object'] == 'maker' || $_GET['object'] == 'product') {
            $this->addToView('object', $_GET['object']);

            if (isset($_POST['twitter_username'])) {
                $this->addTwitterUserToView();
            } elseif ($this->hasSubmittedMakerForm()) {
                $maker = new Maker();
                $maker->slug = $_POST['username'];
                $maker->username = $_POST['username'];
                $maker->name = $_POST['full_name'];
                $maker->url = $_POST['url'];
                $maker->avatar_url = $_POST['avatar_url'];

                $user = $this->getLoggedInUser();
                $maker_dao = new MakerMySQLDAO();
                $controller = new MakerController(true);

                try {
                    //Insert maker (this may throw a DuplicateMakerException)
                    $maker_id = $maker_dao->insert($maker);
                    $maker->id = $maker_id;

                    // Add new maker to Elasticsearch
                    $client = new Elasticsearch\Client();
                    $params = array();
                    $params['body']  = array(
                        'slug'=>$maker->slug,
                        'name'=>$maker->name,
                        'description'=>'',
                        'url'=>$maker->url,
                        'avatar_url'=>$maker->avatar_url,
                        'type'=>'maker'
                    );
                    $params['index'] = 'maker_product_index';
                    $params['type']  = 'maker_product_type';
                    //$params['id']    = 'my_id';
                    $ret = $client->index($params);
                    if ($ret['created'] != 1) {
                        $controller->addErrorMessage('Problem adding '.$maker->slug.' to search index.');
                    }

                    //Add new connection
                    $connection_dao = new ConnectionMySQLDAO();
                    $connection_dao->insert($user, $maker);

                    //Add new action
                    $action = new Action();
                    $action->user_id = $user->id;
                    $action->severity = Action::SEVERITY_NORMAL;
                    $action->object_id = $maker->id;
                    $action->object_type = get_class($maker);
                    $action->ip_address = $_SERVER['REMOTE_ADDR'];
                    $action->action_type = 'create';
                    $action->metadata = json_encode($maker);

                    $action_dao = new ActionMySQLDAO();
                    $action_dao->insert($action);

                    $controller->addSuccessMessage('You added '.$maker->slug.'.');
                } catch (DuplicateMakerException $e) {
                    //If not inserted, update
                    $has_been_updated = $maker_dao->update($maker);
                    $maker = $maker_dao->get($maker->slug);

                    if ($has_been_updated) {
                        //Update maker in ElasticSearch index
                        $client = new Elasticsearch\Client();
                        $search_params['index'] = 'maker_product_index';
                        $search_params['type']  = 'maker_product_type';
                        $search_params['body']['query']['match']['slug'] = $maker->slug;
                        $query_response = $client->search($search_params);
                        $search_id = $query_response['hits']['hits'][0]['_id'];

                        $update_params = array();
                        $update_params['body']  = array(
                            'slug'=>$maker->slug,
                            'name'=>$maker->name,
                            'description'=>'',
                            'url'=>$maker->url,
                            'avatar_url'=>$maker->avatar_url,
                            'type'=>'maker'
                        );
                        $update_params['index'] = 'maker_product_index';
                        $update_params['type']  = 'maker_product_type';
                        $update_params['id']    = $search_id;
                        $ret = $client->index($update_params);

                        //Create new action if update changed something
                        $action = new Action();
                        $action->user_id = $user->id;
                        $action->severity = Action::SEVERITY_MINOR;
                        $action->object_id = $maker->id;
                        $action->object_type = get_class($maker);
                        $action->ip_address = $_SERVER['REMOTE_ADDR'];
                        $action->action_type = 'update';
                        $action->metadata = json_encode($maker);

                        $action_dao = new ActionMySQLDAO();
                        $action_dao->insert($action);

                        $controller->addSuccessMessage('You updated '.$maker->slug.'.');
                    } else {
                        $controller->addSuccessMessage('No changes made to '.$maker->slug);
                    }

                    //Create new connection regardless of whether update changed anything
                    $connection_dao = new ConnectionMySQLDAO();
                    $connection_dao->insert($user, $maker);
                }

                $_GET = null;
                $_GET['slug'] = $maker->slug;
                return $controller->go();
            }
        } else {
            $this->redirect(Config::getInstance()->getValue('site_root_path'));
        }

        return $this->generateView();
    }

    private function hasSubmittedMakerForm() {
        return (
            isset($_POST['username'])
            && isset($_POST['full_name'])
            && isset($_POST['url'])
            && isset($_POST['avatar_url'])
            );
    }

    private function addTwitterUserToView() {
        $cfg = Config::getInstance();
        $oauth_consumer_key = $cfg->getValue('twitter_oauth_consumer_key');
        $oauth_consumer_secret = $cfg->getValue('twitter_oauth_consumer_secret');

        $user = $this->getLoggedInUser();
        $twitter_oauth = new TwitterOAuth($oauth_consumer_key, $oauth_consumer_secret,
            $user->twitter_oauth_access_token, $user->twitter_oauth_access_token_secret);

        $api_accessor = new TwitterAPIAccessor();
        $twitter_user_details = $api_accessor->getUser($_POST['twitter_username'], $twitter_oauth);
        $twitter_user_details['avatar'] = str_replace('_normal', '', $twitter_user_details['avatar']);
        $this->addToView('twitter_user_details', $twitter_user_details);
    }

    private function getLoggedInUser() {
        $logged_in_user = Session::getLoggedInUser();
        $user_dao = new UserMySQLDAO();
        $user = $user_dao->get($logged_in_user);
        return $user;
    }
}
