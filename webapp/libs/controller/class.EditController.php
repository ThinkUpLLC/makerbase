<?php

class EditController extends MakerbaseAuthController {

    public function authControl() {
        parent::authControl();
        if ($this->hasSubmittedRoleForm()) {
            $role_dao = new RoleMySQLDAO();
            $role = new Role();
            $role->id = $_POST['id'];
            $role->start = ($_POST['start_date'] == '')?null:$_POST['start_date']."-01";
            $role->end = ($_POST['end_date'] == '')?null:$_POST['end_date']."-01";
            $role->role = $_POST['role'];
            $has_been_updated = $role_dao->update($role);

            $updated_role = $role_dao->get($role->id);

            //Get maker
            $maker_dao = new MakerMySQLDAO();
            $maker = $maker_dao->getByID($updated_role->maker_id);

            //Get product
            $product_dao = new ProductMySQLDAO();
            $product = $product_dao->getByID($updated_role->product_id);

            //Add new connection
            $connection_dao = new ConnectionMySQLDAO();
            $connection_dao->insert($this->logged_in_user, $maker);
            $connection_dao->insert($this->logged_in_user, $product);

            if ($has_been_updated) {
                //Add new action
                $action = new Action();
                $action->user_id = $this->logged_in_user->id;
                $action->severity = Action::SEVERITY_NORMAL;
                $action->object_id = $maker->id;
                $action->object_type = get_class($maker);
                $action->object2_id = $product->id;
                $action->object2_type = get_class($product);
                $action->ip_address = $_SERVER['REMOTE_ADDR'];
                $action->action_type = 'update';

                $updated_role->maker = $maker;
                $updated_role->product = $product;

                $action->metadata = json_encode($updated_role);
                $action_dao = new ActionMySQLDAO();
                $action_dao->insert($action);
            }

            if ($_POST['originate'] == 'product') {
                $controller = new ProductController(true);
                $_GET = array();
                $_GET['slug'] = $_POST['originate_slug'];
                $_GET['clear_cache'] = 1;
                $_POST = array();
                if ($has_been_updated) {
                    $controller->addSuccessMessage("Updated role successfully");
                }
                return $controller->go();
            } elseif ($_POST['originate'] == 'maker') {
                $controller = new MakerController(true);
                $_GET = array();
                $_GET['slug'] = $_POST['originate_slug'];
                $_GET['clear_cache'] = 1;
                $_POST = array();
                if ($has_been_updated) {
                    $controller->addSuccessMessage("Updated role successfully");
                }
                return $controller->go();
            } else {
                $this->redirect(Config::getInstance()->getValue('site_root_path'));
            }
        } elseif ($this->hasSubmittedProductForm()) {
            $product_dao = new ProductMySQLDAO();
            $product = new Product();
            $product->id = $_POST['product_id'];
            $product->slug = $_POST['product_slug'];
            $product->name = $_POST['name'];
            $product->description = $_POST['description'];
            $product->url = $_POST['url'];
            $product->avatar_url = $_POST['avatar_url'];

            $has_been_updated = $product_dao->update($product);

            //Add new connection
            $connection_dao = new ConnectionMySQLDAO();
            $connection_dao->insert($this->logged_in_user, $product);

            if ($has_been_updated) {
                //Add new action
                $action = new Action();
                $action->user_id = $this->logged_in_user->id;
                $action->severity = Action::SEVERITY_NORMAL;
                $action->object_id = $product->id;
                $action->object_type = get_class($product);
                $action->ip_address = $_SERVER['REMOTE_ADDR'];
                $action->action_type = 'update';

                $action->metadata = json_encode($product);
                $action_dao = new ActionMySQLDAO();
                $action_dao->insert($action);
            }

            $controller = new ProductController(true);
            $_GET = array();
            $_GET['slug'] = $product->slug;
            $_GET['clear_cache'] = 1;
            $_POST = array();
            if ($has_been_updated) {
                $controller->addSuccessMessage("Updated product");
            }
            return $controller->go();
        } else {
            $this->redirect(Config::getInstance()->getValue('site_root_path'));
        }
    }

    private function hasSubmittedRoleForm() {
        return (
            (isset($_GET['object']) && $_GET['object'] == 'role')
            && isset($_POST['id'])
            && isset($_POST['start_date'])
            && isset($_POST['end_date'])
            && isset($_POST['role'])
            && isset($_POST['originate'])
            && isset($_POST['originate_slug'])
        );
    }

    private function hasSubmittedProductForm() {
        return (
            (isset($_GET['object']) && $_GET['object'] == 'product')
            && isset($_POST['product_id'])
            && isset($_POST['product_slug'])
            && isset($_POST['name'])
            && isset($_POST['description'])
            && isset($_POST['url'])
            && isset($_POST['avatar_url'])
        );
    }
}
