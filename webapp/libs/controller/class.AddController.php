<?php

class AddController extends MakerbaseAuthController {

    public function authControl() {
        parent::authControl();
        $this->setViewTemplate('add.tpl');
        $this->addToView('is_manual', true);

        if ($_GET['object'] == 'maker' || $_GET['object'] == 'product' || $_GET['object'] == 'role') {
            $this->addToView('object', $_GET['object']);

            if (isset($_POST['twitter_username'])) {
                $this->addTwitterUsersToView($_POST['twitter_username']);
            } elseif (isset($_GET['q'])) {
                $this->addTwitterUsersToView($_GET['q']);
            } elseif ($_GET['object'] == 'maker' && $this->hasSubmittedMakerForm()) {
                $controller = $this->addMaker();
                return $controller->go();
            } elseif ($_GET['object'] == 'product' && $this->hasSubmittedProductForm()) {
                $controller = $this->addProduct();
                return $controller->go();
            } elseif ($_GET['object'] == 'role' && $this->hasSubmittedRoleForm()) {
                $controller = $this->addRole();
                return $controller->go();
            } elseif (isset($_GET['method']) && $_GET['method'] == 'manual') {
                $this->addToView('is_manual', true);
            }
        } else {
            $this->redirect(Config::getInstance()->getValue('site_root_path'));
        }

        return $this->generateView();
    }

    private function hasSubmittedMakerForm() {
        return (
            isset($_POST['slug'])
            && isset($_POST['name'])
            && isset($_POST['url'])
            && isset($_POST['avatar_url'])
            );
    }

    private function hasSubmittedRoleForm() {
        return (
            isset($_POST['product_uid'])
            && isset($_POST['maker_uid'])
            && isset($_POST['start_date'])
            && isset($_POST['end_date'])
            && isset($_POST['role'])
            && isset($_POST['originate'])
            && isset($_POST['originate_uid'])
            && isset($_POST['originate_slug'])
            );
    }

    private function hasSubmittedProductForm() {
        return (
            isset($_POST['slug'])
            && isset($_POST['name'])
            && isset($_POST['description'])
            && isset($_POST['url'])
            && isset($_POST['avatar_url'])
            );
    }

    private function addTwitterUsersToView($twitter_username) {
        $cfg = Config::getInstance();
        $oauth_consumer_key = $cfg->getValue('twitter_oauth_consumer_key');
        $oauth_consumer_secret = $cfg->getValue('twitter_oauth_consumer_secret');

        $twitter_oauth = new TwitterOAuth($oauth_consumer_key, $oauth_consumer_secret,
            $this->logged_in_user->twitter_oauth_access_token,
            $this->logged_in_user->twitter_oauth_access_token_secret);

        $api_accessor = new TwitterAPIAccessor();
        $twitter_users = $api_accessor->searchUsers($twitter_username, $twitter_oauth);
        $this->addToView('twitter_users', $twitter_users);
    }

    private function addRole() {
        $maker_dao = new MakerMySQLDAO();
        $product_dao = new ProductMySQLDAO();
        if ($_POST['originate'] == 'maker') {
            $controller = new MakerController(true);
        } else {
            $controller = new ProductController(true);
        }
        try {
            $maker = $maker_dao->get($_POST['maker_uid']);
            $product = $product_dao->get($_POST['product_uid']);

            $role = new Role();
            $role->maker_id = $maker->id;
            $role->product_id = $product->id;
            $role->start = ($_POST['start_date'] == '')?null:$_POST['start_date'].'-01';
            $role->end = ($_POST['end_date'] == '')?null:$_POST['end_date'].'-01';
            $role->role = $_POST['role'];

            $role_dao = new RoleMySQLDAO();
            $role = $role_dao->insert($role);

            //Add new connection
            $connection_dao = new ConnectionMySQLDAO();
            $connection_dao->insert($this->logged_in_user, $maker);
            $connection_dao->insert($this->logged_in_user, $product);

            //Add new action
            $action = new Action();
            $action->user_id = $this->logged_in_user->id;
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
            $_GET['slug'] = $_POST['originate_slug'];
            $_GET['uid'] = $_POST['originate_uid'];
        } catch (MakerDoesNotExistException $e) {
            $_GET['slug'] = $_POST['originate_slug'];
            $_GET['uid'] = $_POST['originate_uid'];
            $controller->addErrorMessage('That maker does not exist.');
        } catch (ProductDoesNotExistException $e) {
            $_GET['slug'] = $_POST['originate_slug'];
            $_GET['uid'] = $_POST['originate_uid'];
            $controller->addErrorMessage('That product does not exist.');
        }
        return $controller;
    }

    private function addMaker() {
        $maker = new Maker();
        $maker->slug = $_POST['slug'];
        $maker->name = $_POST['name'];
        $maker->url = $_POST['url'];
        $maker->avatar_url = $_POST['avatar_url'];

        $maker_dao = new MakerMySQLDAO();
        $controller = new MakerController(true);

        $maker = $maker_dao->insert($maker);

        // Add new maker to Elasticsearch
        SearchHelper::indexMaker($maker);

        //Add new connection
        $connection_dao = new ConnectionMySQLDAO();
        $connection_dao->insert($this->logged_in_user, $maker);

        //Add new action
        $action = new Action();
        $action->user_id = $this->logged_in_user->id;
        $action->severity = Action::SEVERITY_NORMAL;
        $action->object_id = $maker->id;
        $action->object_type = get_class($maker);
        $action->ip_address = $_SERVER['REMOTE_ADDR'];
        $action->action_type = 'create';
        $action->metadata = json_encode($maker);

        $action_dao = new ActionMySQLDAO();
        $action_dao->insert($action);

        $controller->addSuccessMessage('You added '.$maker->slug.'.');

        $_GET = null;
        $_GET['uid'] = $maker->uid;
        $_GET['slug'] = $maker->slug;
        return $controller;
    }

    private function addProduct() {
        $product = new Product();
        $product->slug = $_POST['slug'];
        $product->name = $_POST['name'];
        $product->description = $_POST['description'];
        $product->url = $_POST['url'];
        $product->avatar_url = $_POST['avatar_url'];

        $product_dao = new ProductMySQLDAO();
        $controller = new ProductController(true);

        //Insert maker (this may throw a DuplicateMakerException)
        $product = $product_dao->insert($product);

        // Add new product to Elasticsearch
        SearchHelper::indexProduct($product);

        //Add new connection
        $connection_dao = new ConnectionMySQLDAO();
        $connection_dao->insert($this->logged_in_user, $product);

        //Add new action
        $action = new Action();
        $action->user_id = $this->logged_in_user->id;
        $action->severity = Action::SEVERITY_NORMAL;
        $action->object_id = $product->id;
        $action->object_type = get_class($product);
        $action->ip_address = $_SERVER['REMOTE_ADDR'];
        $action->action_type = 'create';
        $action->metadata = json_encode($product);

        $action_dao = new ActionMySQLDAO();
        $action_dao->insert($action);

        $controller->addSuccessMessage('You added '.$product->slug.'.');

        $_GET = null;
        $_GET['slug'] = $product->slug;
        $_GET['uid'] = $product->uid;
        return $controller;
    }
}
