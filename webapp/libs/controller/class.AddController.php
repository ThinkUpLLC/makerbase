<?php

class AddController extends AuthController {

    public function authControl() {
        $this->setViewTemplate('add.tpl');

        if ($_GET['object'] == 'maker' || $_GET['object'] == 'product' || $_GET['object'] == 'role') {
            $this->addToView('object', $_GET['object']);

            if (isset($_POST['twitter_username'])) {
                $this->addTwitterUserToView();
            } elseif ($_GET['object'] == 'maker' && $this->hasSubmittedMakerForm()) {
                $controller = $this->addOrUpdateMaker();
                return $controller->go();
            } elseif ($_GET['object'] == 'product' && $this->hasSubmittedProductForm()) {
                $controller = $this->addOrUpdateProduct();
                return $controller->go();
            } elseif ($_GET['object'] == 'role' && $this->hasSubmittedRoleForm()) {
                $controller = $this->addOrUpdateRole();
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

    private function hasSubmittedRoleForm() {
        return (
            isset($_POST['product_slug'])
            && isset($_POST['maker_slug'])
            && isset($_POST['start_date'])
            && isset($_POST['end_date'])
            && isset($_POST['role'])
            );
    }

    private function hasSubmittedProductForm() {
        return (
            isset($_POST['username'])
            && isset($_POST['full_name'])
            && isset($_POST['description'])
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

    private function addOrUpdateRole() {
        $maker_dao = new MakerMySQLDAO();
        $product_dao = new ProductMySQLDAO();
        $user = $this->getLoggedInUser();
        $controller = new ProductController(true);
        try {
            $maker = $maker_dao->get($_POST['maker_slug']);
            $product = $product_dao->get($_POST['product_slug']);

            $role = new Role();
            $role->maker_id = $maker->id;
            $role->product_id = $product->id;
            $role->start = ($_POST['start_date'] == '')?null:$_POST['start_date'];
            $role->end = ($_POST['end_date'] == '')?null:$_POST['end_date'];
            $role->role = $_POST['role'];

            $role_dao = new RoleMySQLDAO();
            $role_dao->insert($role);

            //Add new connection
            $connection_dao = new ConnectionMySQLDAO();
            $connection_dao->insert($user, $maker);
            $connection_dao->insert($user, $product);

            //Add new action
            $action = new Action();
            $action->user_id = $user->id;
            $action->severity = Action::SEVERITY_NORMAL;
            $action->object_id = $maker->id;
            $action->object_type = get_class($maker);
            $action->object2_id = $product->id;
            $action->object2_type = get_class($product);
            $action->ip_address = $_SERVER['REMOTE_ADDR'];
            $action->action_type = 'associate';

            $role->maker = $maker;
            $role->product = $product;

            $action->metadata = json_encode($role);

            $action_dao = new ActionMySQLDAO();
            $action_dao->insert($action);

            $controller->addSuccessMessage('You added '.$maker->slug.' to '.$product->slug.'.');

            $_GET = null;
            $_GET['slug'] = $product->slug;
        } catch (MakerDoesNotExistException $e) {
            $_GET['slug'] = $_POST['product_slug'];
            $controller->addErrorMessage('That maker does not exist. <a href="/add/maker/">Add them</a>.');
        } catch (ProductDoesNotExistException $e) {
            $_GET['slug'] = $_POST['product_slug'];
            $controller->addErrorMessage('That product does not exist. <a href="/add/product/">Add it</a>.');
        }
        return $controller;
    }

    private function addOrUpdateMaker() {
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
        return $controller;
    }

    private function addOrUpdateProduct() {
        $product = new Product();
        $product->slug = $_POST['username'];
        $product->name = $_POST['full_name'];
        $product->description = $_POST['description'];
        $product->url = $_POST['url'];
        $product->avatar_url = $_POST['avatar_url'];

        $user = $this->getLoggedInUser();
        $product_dao = new ProductMySQLDAO();
        $controller = new ProductController(true);

        try {
            //Insert maker (this may throw a DuplicateMakerException)
            $product_id = $product_dao->insert($product);
            $product->id = $product_id;

            // Add new maker to Elasticsearch
            $client = new Elasticsearch\Client();
            $params = array();
            $params['body']  = array(
                'slug'=>$product->slug,
                'name'=>$product->name,
                'description'=>$product->description,
                'url'=>$product->url,
                'avatar_url'=>$product->avatar_url,
                'type'=>'product'
            );
            $params['index'] = 'maker_product_index';
            $params['type']  = 'maker_product_type';
            //$params['id']    = 'my_id';
            $ret = $client->index($params);
            if ($ret['created'] != 1) {
                $controller->addErrorMessage('Problem adding '.$product->slug.' to search index.');
            }

            //Add new connection
            $connection_dao = new ConnectionMySQLDAO();
            $connection_dao->insert($user, $product);

            //Add new action
            $action = new Action();
            $action->user_id = $user->id;
            $action->severity = Action::SEVERITY_NORMAL;
            $action->object_id = $product->id;
            $action->object_type = get_class($product);
            $action->ip_address = $_SERVER['REMOTE_ADDR'];
            $action->action_type = 'create';
            $action->metadata = json_encode($product);

            $action_dao = new ActionMySQLDAO();
            $action_dao->insert($action);

            $controller->addSuccessMessage('You added '.$product->slug.'.');
        } catch (DuplicateProductException $e) {
            //If not inserted, update
            $has_been_updated = $product_dao->update($product);
            $product = $product_dao->get($product->slug);

            if ($has_been_updated) {
                //Update maker in ElasticSearch index
                $client = new Elasticsearch\Client();
                $search_params['index'] = 'maker_product_index';
                $search_params['type']  = 'maker_product_type';
                $search_params['body']['query']['match']['slug'] = $product->slug;
                $query_response = $client->search($search_params);
                $search_id = $query_response['hits']['hits'][0]['_id'];

                $update_params = array();
                $update_params['body']  = array(
                    'slug'=>$product->slug,
                    'name'=>$product->name,
                    'description'=>$product->description,
                    'url'=>$product->url,
                    'avatar_url'=>$product->avatar_url,
                    'type'=>'product'
                );
                $update_params['index'] = 'maker_product_index';
                $update_params['type']  = 'maker_product_type';
                $update_params['id']    = $search_id;
                $ret = $client->index($update_params);

                //Create new action if update changed something
                $action = new Action();
                $action->user_id = $user->id;
                $action->severity = Action::SEVERITY_MINOR;
                $action->object_id = $product->id;
                $action->object_type = get_class($product);
                $action->ip_address = $_SERVER['REMOTE_ADDR'];
                $action->action_type = 'update';
                $action->metadata = json_encode($product);

                $action_dao = new ActionMySQLDAO();
                $action_dao->insert($action);

                $controller->addSuccessMessage('You updated '.$product->slug.'.');
            } else {
                $controller->addSuccessMessage('No changes made to '.$product->slug);
            }

            //Create new connection regardless of whether update changed anything
            $connection_dao = new ConnectionMySQLDAO();
            $connection_dao->insert($user, $product);
        }
        $_GET = null;
        $_GET['slug'] = $product->slug;
        return $controller;
    }
}
