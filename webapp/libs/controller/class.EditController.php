<?php

class EditController extends MakerbaseAuthController {

    public function authControl() {
        parent::authControl();
        if ($this->hasSubmittedRoleForm()) {
            $controller = $this->editRole();
            return $controller->go();
        } elseif ($this->hasSubmittedProductForm()) {
            $controller = $this->editProduct();
            return $controller->go();
        } elseif ($this->hasSubmittedMakerForm()) {
            $controller = $this->editMaker();
            return $controller->go();
        } elseif ($this->hasArchivedMaker()) {
            $controller = $this->archiveMaker();
            return $controller->go();
        } elseif ($this->hasArchivedProduct()) {
            $controller = $this->archiveProduct();
            return $controller->go();
        } elseif ($this->hasArchivedRole()) {
            $controller = $this->archiveRole();
            return $controller->go();
        } else {
            //print_r($_POST);
            $this->redirect(Config::getInstance()->getValue('site_root_path'));
        }
    }

    private function hasSubmittedRoleForm() {
        return (
            (isset($_GET['object']) && $_GET['object'] == 'role')
            && isset($_POST['role_uid'])
            && isset($_POST['start_date'])
            && isset($_POST['end_date'])
            && isset($_POST['role'])
            && isset($_POST['originate']) && ($_POST['originate'] == 'product' || $_POST['originate'] == 'maker')
            && isset($_POST['originate_slug'])
            && isset($_POST['originate_uid'])
        );
    }

    private function hasArchivedMaker() {
        return (
            (isset($_GET['object']) && $_GET['object'] == 'maker')
            && isset($_POST['uid'])
            && isset($_POST['archive']) && ($_POST['archive'] == 1 || $_POST['archive'] == 0)
        );
    }

    private function hasArchivedRole() {
        return (
            (isset($_GET['object']) && $_GET['object'] == 'role')
            && isset($_POST['uid'])
            && isset($_POST['archive']) && ($_POST['archive'] == 1 || $_POST['archive'] == 0)
            && isset($_POST['originate']) && ($_POST['originate'] == 'product' || $_POST['originate'] == 'maker')
            && isset($_POST['originate_slug'])
            && isset($_POST['originate_uid'])
        );
    }

    private function hasArchivedProduct() {
        return (
            (isset($_GET['object']) && $_GET['object'] == 'product')
            && isset($_POST['uid'])
            && isset($_POST['archive']) && ($_POST['archive'] == 1 || $_POST['archive'] == 0)
        );
    }

    private function hasSubmittedProductForm() {
        return (
            (isset($_GET['object']) && $_GET['object'] == 'product')
            && isset($_POST['product_uid'])
            && isset($_POST['name'])
            && isset($_POST['description'])
            && isset($_POST['url'])
            && isset($_POST['avatar_url'])
        );
    }

    private function hasSubmittedMakerForm() {
        return (
            (isset($_GET['object']) && $_GET['object'] == 'maker')
            && isset($_POST['maker_uid'])
            && isset($_POST['name'])
            && isset($_POST['url'])
            && isset($_POST['avatar_url'])
        );
    }

    private function archiveRole() {
        $role_dao = new RoleMySQLDAO();
        $role = $role_dao->get($_POST['uid']);

        //Set up controller
        if ($_POST['originate'] == 'product') {
            $controller = new ProductController(true);
        } elseif ($_POST['originate'] == 'maker') {
            $controller = new MakerController(true);
        }

        $has_changed_archive_status = false;
        if ($_POST['archive'] == 1) {
            if ($role->is_archived) {
                $controller->addInfoMessage("Already archived");
            } else {
                $has_changed_archive_status = $role_dao->archive($_POST['uid']);
                if ($has_changed_archive_status) {
                    $role->is_archived = true;
                    $controller->addSuccessMessage('Archived role');
                    $action_type = 'archive';
                }
            }
        } else {
            if (!$role->is_archived) {
                $controller->addInfoMessage("Already unarchived");
            } else {
                $has_changed_archive_status = $role_dao->unarchive($_POST['uid']);
                if ($has_changed_archive_status) {
                    $role->is_archived = false;
                    $controller->addSuccessMessage('Unarchived role');
                    $action_type = 'unarchive';
                }
            }
        }

        //Get maker
        $maker_dao = new MakerMySQLDAO();
        $maker = $maker_dao->getByID($role->maker_id);

        //Get product
        $product_dao = new ProductMySQLDAO();
        $product = $product_dao->getByID($role->product_id);

        //Add new connection
        $connection_dao = new ConnectionMySQLDAO();
        $connection_dao->insert($this->logged_in_user, $maker);
        $connection_dao->insert($this->logged_in_user, $product);

        if ($has_changed_archive_status) {
            //Add new action
            $action = new Action();
            $action->user_id = $this->logged_in_user->id;
            $action->severity = Action::SEVERITY_MAJOR;
            $action->object_id = $maker->id;
            $action->object_type = get_class($maker);
            $action->object2_id = $product->id;
            $action->object2_type = get_class($product);
            $action->ip_address = $_SERVER['REMOTE_ADDR'];
            $action->action_type = $action_type;

            $role->maker = $maker;
            $role->product = $product;
            $action->metadata = json_encode($role);
            $action_dao = new ActionMySQLDAO();
            $action_dao->insert($action);
        }

        $_GET = array();
        $_GET['slug'] = $_POST['originate_slug'];
        $_GET['uid'] = $_POST['originate_uid'];
        $_GET['clear_cache'] = 1;
        $_POST = array();
        return $controller;
    }

    private function archiveProduct() {
        $controller = new ProductController(true);
        $product_dao = new ProductMySQLDAO();
        $product = $product_dao->get($_POST['uid']);

        $has_changed_archive_status = false;
        if ($_POST['archive'] == 1) {
            if ($product->is_archived) {
                $controller->addInfoMessage("Already archived");
            } else {
                $has_changed_archive_status = $product_dao->archive($_POST['uid']);
                if ($has_changed_archive_status) {
                    $product->is_archived = true;
                    $controller->addSuccessMessage('Archived product');
                    $action_type = 'archive';

                    //Remove product from Elasticsearch
                    SearchHelper::deindexProduct($product);
                }
            }
        } else {
            if (!$product->is_archived) {
                $controller->addInfoMessage("Already unarchived");
            } else {
                $has_changed_archive_status = $product_dao->unarchive($_POST['uid']);
                if ($has_changed_archive_status) {
                    $product->is_archived = false;
                    $controller->addSuccessMessage('Unarchived product');
                    $action_type = 'unarchive';

                    // Add product back to Elasticsearch
                    SearchHelper::indexProduct($product);
                }
            }
        }

        //Add new connection
        $connection_dao = new ConnectionMySQLDAO();
        $connection_dao->insert($this->logged_in_user, $product);

        if ($has_changed_archive_status) {
            //Add new action
            $action = new Action();
            $action->user_id = $this->logged_in_user->id;
            $action->severity = Action::SEVERITY_MAJOR;
            $action->object_id = $product->id;
            $action->object_type = get_class($product);
            $action->ip_address = $_SERVER['REMOTE_ADDR'];
            $action->action_type = $action_type;

            $action->metadata = json_encode($product);
            $action_dao = new ActionMySQLDAO();
            $action_dao->insert($action);
        }
        $_GET = array();
        $_GET['slug'] = $product->slug;
        $_GET['uid'] = $product->uid;
        $_GET['clear_cache'] = 1;
        $_POST = array();
        return $controller;
    }

    private function archiveMaker() {
        $controller = new MakerController(true);
        $maker_dao = new MakerMySQLDAO();
        $maker = $maker_dao->get($_POST['uid']);

        $has_changed_archive_status = false;
        if ($_POST['archive'] == 1) {
            if ($maker->is_archived) {
                $controller->addInfoMessage("Already archived");
            } else {
                $has_changed_archive_status = $maker_dao->archive($_POST['uid']);
                if ($has_changed_archive_status) {
                    $maker->is_archived = true;
                    $controller->addSuccessMessage('Archived maker');
                    $action_type = 'archive';

                    // Remove maker from Elasticsearch
                    SearchHelper::deindexMaker($maker);
                }
            }
        } else {
            if (!$maker->is_archived) {
                $controller->addInfoMessage("Already unarchived");
            } else {
                $has_changed_archive_status = $maker_dao->unarchive($_POST['uid']);
                if ($has_changed_archive_status) {
                    $maker->is_archived = false;
                    $controller->addSuccessMessage('Unarchived maker');
                    $action_type = 'unarchive';
                    // Add maker back to Elasticsearch
                    SearchHelper::indexMaker($maker);
                }
            }
        }

        //Add new connection
        $connection_dao = new ConnectionMySQLDAO();
        $connection_dao->insert($this->logged_in_user, $maker);

        if ($has_changed_archive_status) {
            //Add new action
            $action = new Action();
            $action->user_id = $this->logged_in_user->id;
            $action->severity = Action::SEVERITY_MAJOR;
            $action->object_id = $maker->id;
            $action->object_type = get_class($maker);
            $action->ip_address = $_SERVER['REMOTE_ADDR'];
            $action->action_type = $action_type;

            $action->metadata = json_encode($maker);
            $action_dao = new ActionMySQLDAO();
            $action_dao->insert($action);
        }
        $_GET = array();
        $_GET['slug'] = $maker->slug;
        $_GET['uid'] = $maker->uid;
        $_GET['clear_cache'] = 1;
        $_POST = array();
        return $controller;
    }

    private function editMaker() {
        $maker_dao = new MakerMySQLDAO();

        //This will throw an exception if the maker doesn't exist
        $original_maker = $maker_dao->get($_POST['maker_uid']);

        $maker = new Maker();
        $maker->id = $original_maker->id;
        $maker->uid = $_POST['maker_uid'];
        $maker->slug = $original_maker->slug;
        $maker->name = $_POST['name'];
        $maker->url = $_POST['url'];
        $maker->avatar_url = $_POST['avatar_url'];

        $has_been_updated = $maker_dao->update($maker);

        //Add new connection
        $connection_dao = new ConnectionMySQLDAO();
        $connection_dao->insert($this->logged_in_user, $maker);

        if ($has_been_updated) {
            //Add new action
            $action = new Action();
            $action->user_id = $this->logged_in_user->id;
            $action->severity = Action::SEVERITY_NORMAL;
            $action->object_id = $maker->id;
            $action->object_type = get_class($maker);
            $action->ip_address = $_SERVER['REMOTE_ADDR'];
            $action->action_type = 'update';

            $versions = array('before'=>$original_maker, 'after'=>$maker);

            $action->metadata = json_encode($versions);
            $action_dao = new ActionMySQLDAO();
            $action_dao->insert($action);

            //Update search index
            SearchHelper::updateIndexMaker($maker);
        }

        $controller = new MakerController(true);
        $_GET = array();
        $_GET['slug'] = $maker->slug;
        $_GET['uid'] = $maker->uid;
        $_GET['clear_cache'] = 1;
        $_POST = array();
        if ($has_been_updated) {
            $controller->addSuccessMessage("Updated maker");
        }
        return $controller;
    }

    private function editProduct() {
        $product_dao = new ProductMySQLDAO();

        //This will throw an exception if the product doesn't exist
        $original_product = $product_dao->get($_POST['product_uid']);

        $product = new Product();
        $product->id = $original_product->id;
        $product->uid = $_POST['product_uid'];
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

            $versions = array('before'=>$original_product, 'after'=>$product);

            $action->metadata = json_encode($versions);
            $action_dao = new ActionMySQLDAO();
            $action_dao->insert($action);

            //Update search index
            SearchHelper::updateIndexProduct($product);
        }

        $controller = new ProductController(true);
        $_GET = array();
        $_GET['slug'] = $product->slug;
        $_GET['uid'] = $product->uid;
        $_GET['clear_cache'] = 1;
        $_POST = array();
        if ($has_been_updated) {
            $controller->addSuccessMessage("Updated product");
        }
        return $controller;
    }

    private function editRole(){
        $role_dao = new RoleMySQLDAO();

        //This will throw an exception if the role doesn't exist
        $original_role = $role_dao->get($_POST['role_uid']);

        $role = new Role();
        $role->id = $original_role->id;
        $role->uid = $_POST['role_uid'];
        $role->start = ($_POST['start_date'] == '')?null:$_POST['start_date']."-01";
        $role->end = ($_POST['end_date'] == '')?null:$_POST['end_date']."-01";
        $role->role = $_POST['role'];
        $has_been_updated = $role_dao->update($role);

        $updated_role = $role_dao->get($role->uid);

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

            $original_role->maker = $maker;
            $original_role->product = $product;
            $updated_role->maker = $maker;
            $updated_role->product = $product;

            $versions = array('before'=>$original_role, 'after'=>$updated_role);
            $action->metadata = json_encode($versions);
            $action_dao = new ActionMySQLDAO();
            $action_dao->insert($action);
        }

        if ($_POST['originate'] == 'product') {
            $controller = new ProductController(true);
            $_GET = array();
            $_GET['slug'] = $_POST['originate_slug'];
            $_GET['uid'] = $_POST['originate_uid'];
            $_GET['clear_cache'] = 1;
            $_POST = array();
            if ($has_been_updated) {
                $controller->addSuccessMessage("Updated role successfully");
            }
        } elseif ($_POST['originate'] == 'maker') {
            $controller = new MakerController(true);
            $_GET = array();
            $_GET['slug'] = $_POST['originate_slug'];
            $_GET['uid'] = $_POST['originate_uid'];
            $_GET['clear_cache'] = 1;
            $_POST = array();
            if ($has_been_updated) {
                $controller->addSuccessMessage("Updated role successfully");
            }
        }
        return $controller;
    }
}