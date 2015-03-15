<?php

class AddController extends MakerbaseAuthController {

    public function authControl() {
        parent::authControl();
        $this->setViewTemplate('add.tpl');
        $this->addToView('is_manual', true);

        if ($_GET['object'] == 'maker' || $_GET['object'] == 'product' || $_GET['object'] == 'role') {
            $this->addToView('object', $_GET['object']);

            if (isset($_POST['twitter_username'])) {
                $this->addTwitterUserToView();
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
            isset($_POST['username'])
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

    private function addTwitterUserToView() {
        $cfg = Config::getInstance();
        $oauth_consumer_key = $cfg->getValue('twitter_oauth_consumer_key');
        $oauth_consumer_secret = $cfg->getValue('twitter_oauth_consumer_secret');

        $twitter_oauth = new TwitterOAuth($oauth_consumer_key, $oauth_consumer_secret,
            $this->logged_in_user->twitter_oauth_access_token,
            $this->logged_in_user->twitter_oauth_access_token_secret);

        $api_accessor = new TwitterAPIAccessor();
        $twitter_user_details = $api_accessor->getUser($_POST['twitter_username'], $twitter_oauth);
        $twitter_user_details['avatar'] = str_replace('_normal', '', $twitter_user_details['avatar']);

        $this->addToView('name', $twitter_user_details['full_name']);
        $this->addToView('username', $twitter_user_details['user_name']);
        $this->addToView('slug', $twitter_user_details['user_name']);
        $this->addToView('description', $twitter_user_details['description']);
        $this->addToView('url', $twitter_user_details['url']);
        $this->addToView('avatar_url', $twitter_user_details['avatar']);
        $this->addToView('is_manual', true);
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
        $maker->slug = $_POST['username'];
        $maker->name = $_POST['name'];
        $maker->url = $_POST['url'];
        $maker->avatar_url = $_POST['avatar_url'];

        $maker_dao = new MakerMySQLDAO();
        $controller = new MakerController(true);

        //Insert maker (this may throw a DuplicateMakerException)
        $maker = $maker_dao->insert($maker);

        // Add new maker to Elasticsearch
        $client = new Elasticsearch\Client();
        $params = array();
        $params['body']  = array(
            'id'=>'m/'.$maker->uid,
            'uid'=>$maker->uid,
            'slug'=>$maker->slug,
            'name'=>$maker->name,
            'description'=>'',
            'url'=>$maker->url,
            'avatar_url'=>$maker->avatar_url,
            'type'=>'maker'
        );
        $params['index'] = 'maker_product_index';
        $params['type']  = 'maker_product_type';
        $ret = $client->index($params);
        if ($ret['created'] != 1) {
            $controller->addErrorMessage('Problem adding '.$maker->slug.' to search index.');
        }
        $params['body']['id'] = $maker->uid;
        $params['index'] = 'maker_index';
        $params['type']  = 'maker_type';
        $ret = $client->index($params);
        if ($ret['created'] != 1) {
            $controller->addErrorMessage('Problem adding '.$maker->slug.' to search index.');
        }

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

        // Add new maker to Elasticsearch
        $client = new Elasticsearch\Client();
        $params = array();
        $params['body']  = array(
            'id'=>'p/'.$product->uid,
            'uid'=>$product->uid,
            'slug'=>$product->slug,
            'name'=>$product->name,
            'description'=>$product->description,
            'url'=>$product->url,
            'avatar_url'=>$product->avatar_url,
            'type'=>'product'
        );
        $params['index'] = 'maker_product_index';
        $params['type']  = 'maker_product_type';
        $ret = $client->index($params);
        if ($ret['created'] != 1) {
            $controller->addErrorMessage('Problem adding '.$product->slug.' to search index.');
        }
        $params['body']['id'] = $product->uid;
        $params['index'] = 'product_index';
        $params['type']  = 'product_type';
        $ret = $client->index($params);
        if ($ret['created'] != 1) {
            $controller->addErrorMessage('Problem adding '.$product->slug.' to search index.');
        }

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
