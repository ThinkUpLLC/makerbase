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
                try {
                    $maker_id = $maker_dao->insert($maker);
                    $maker->id = $maker_id;

                    $connection_dao = new ConnectionMySQLDAO();
                    $connection_dao->insert($user, $maker);

                    $action = new Action();
                    $action->user_id = $user->id;
                    $action->severity = Action::SEVERITY_NORMAL;
                    $action->object_id = $maker->id;
                    $action->object_type = get_class($maker);
                    $action->ip_address = $_SERVER['REMOTE_ADDR'];
                    $action->action_type = 'create';
                    $action->object_slug = $maker->slug;
                    $action->object_name = $maker->name;

                    $action_dao = new ActionMySQLDAO();
                    $action_dao->insert($action);

                    $this->addSuccessMessage('You added a maker.');
                } catch (DuplicateMakerException $e) {
                    $has_been_updated = $maker_dao->update($maker);
                    $maker = $maker_dao->get($maker->slug);

                    if ($has_been_updated) {
                        $action = new Action();
                        $action->user_id = $user->id;
                        $action->severity = Action::SEVERITY_MINOR;
                        $action->object_id = $maker->id;
                        $action->object_type = get_class($maker);
                        $action->ip_address = $_SERVER['REMOTE_ADDR'];
                        $action->action_type = 'update';
                        $action->object_slug = $maker->slug;
                        $action->object_name = $maker->name;

                        $action_dao = new ActionMySQLDAO();
                        $action_dao->insert($action);
                        $this->addSuccessMessage('You updated a maker.');
                    } else {
                        $this->addSuccessMessage('No changes made to '.$maker->slug);
                    }

                    $connection_dao = new ConnectionMySQLDAO();
                    $connection_dao->insert($user, $maker);
                }
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
