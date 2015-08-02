<?php

class AdminEditController extends MakerbaseAdminController {

    public function authControl() {
        if ($this->hasFrozenMaker()) {
            $this->freezeMaker();
        } elseif ($this->hasFrozenProduct()) {
            $this->freezeProduct();
        } elseif ($this->hasFrozenUser()) {
            $this->freezeUser();
        } elseif ($this->hasDeletedProduct()) {
            $this->deleteProduct();
        } elseif ($this->hasDeletedMaker()) {
            $this->deleteMaker();
        } else {
            //print_r($_POST);
            $this->redirect(Config::getInstance()->getValue('site_root_path'));
        }
    }

    private function hasFrozenMaker() {
        return (
            (isset($_GET['object']) && $_GET['object'] == 'maker')
            && isset($_POST['uid'])
            && isset($_POST['freeze']) && ($_POST['freeze'] == 1 || $_POST['freeze'] == 0)
        );
    }

    private function hasDeletedMaker() {
        return (
            (isset($_GET['object']) && $_GET['object'] == 'maker')
            && isset($_POST['uid'])
            && isset($_POST['delete']) && $_POST['delete'] == 1
        );
    }

    private function hasFrozenProduct() {
        return (
            (isset($_GET['object']) && $_GET['object'] == 'product')
            && isset($_POST['uid'])
            && isset($_POST['freeze']) && ($_POST['freeze'] == 1 || $_POST['freeze'] == 0)
        );
    }

    private function hasDeletedProduct() {
        return (
            (isset($_GET['object']) && $_GET['object'] == 'product')
            && isset($_POST['uid'])
            && isset($_POST['delete']) && $_POST['delete'] == 1
        );
    }

    private function hasFrozenUser() {
        return (
            (isset($_GET['object']) && $_GET['object'] == 'user')
            && isset($_POST['uid'])
            && isset($_POST['freeze']) && ($_POST['freeze'] == 1 || $_POST['freeze'] == 0)
        );
    }

    private function freezeProduct() {
        $product_dao = new ProductMySQLDAO();
        $product = $product_dao->get($_POST['uid']);

        $has_changed_frozen_status = false;
        if ($_POST['freeze'] == 1) {
            if ($product->is_frozen) {
                SessionCache::put('info_message', 'Already frozen');
            } else {
                $has_changed_frozen_status = $product_dao->freeze($_POST['uid']);
                if ($has_changed_frozen_status) {
                    $product->is_frozen = true;
                    SessionCache::put('success_message', 'Froze project');
                    $action_type = 'freeze';
                }
            }
        } else {
            if (!$product->is_frozen) {
                SessionCache::put('info_message', 'Already unfrozen');
            } else {
                $has_changed_frozen_status = $product_dao->unfreeze($_POST['uid']);
                if ($has_changed_frozen_status) {
                    $product->is_frozen = false;
                    SessionCache::put('success_message', 'Unfroze project');
                    $action_type = 'unfreeze';
                }
            }
        }

        if ($has_changed_frozen_status) {
            //Add new action
            $action = new Action();
            $action->user_id = $this->logged_in_user->id;
            $action->severity = Action::SEVERITY_MAJOR;
            $action->object_id = $product->id;
            $action->object_type = get_class($product);
            $action->ip_address = $_SERVER['REMOTE_ADDR'];
            $action->action_type = $action_type;
            $action->is_admin = true;
            $action->metadata = json_encode($product);
            $action_dao = new ActionMySQLDAO();
            $action_dao->insert($action);
        }
        $this->redirect('/p/'.$product->uid.'/'.$product->slug);
    }

    private function deleteProduct() {
        $product_dao = new ProductMySQLDAO();
        $product = $product_dao->get($_POST['uid']);

        $has_been_deleted = false;
        if ($_POST['delete'] == 1) {
            $has_been_deleted = $product_dao->delete($_POST['uid']);

            if (!$product->is_archived) {
                //Remove from ElasticSearch
                SearchHelper::deindexProduct($product);
            }

            //Delete roles
            $role_dao = new RoleMySQLDAO();
            $role_dao->deleteByProduct($product->id);

            //Delete actions by users (the only action for this product will be the admin's delete action)
            $action_dao = new ActionMySQLDAO();
            $action_dao->deleteActionsForProduct($product->id);

            //Delete connections
            $connection_dao = new ConnectionMySQLDAO();
            $connection_dao->deleteConnectionsToProduct($product->id);

            //Delete made-withs
            //While they are only sponsors, just the using products get deleted, not the used products
            $madewith_dao = new MadeWithMySQLDAO();
            $madewith_dao->deleteByProduct($product->id);

            if ($has_been_deleted) {
                SessionCache::put('success_message', 'Deleted project');
                $action_type = 'delete';

                CacheHelper::expireCache('product.tpl', $product->uid, $product->slug);
            }
        }

        if ($has_been_deleted) {
            //Add admin delete action
            $action = new Action();
            $action->user_id = $this->logged_in_user->id;
            $action->severity = Action::SEVERITY_MAJOR;
            $action->object_id = $product->id;
            $action->object_type = get_class($product);
            $action->ip_address = $_SERVER['REMOTE_ADDR'];
            $action->action_type = $action_type;
            $action->is_admin = true;
            $action->metadata = json_encode($product);
            $action_dao = new ActionMySQLDAO();
            $action_dao->insert($action);
        }
        $this->redirect(Config::getInstance()->getValue('site_root_path'));
    }

    private function deleteMaker() {
        $maker_dao = new MakerMySQLDAO();
        $maker = $maker_dao->get($_POST['uid']);

        $has_been_deleted = false;
        if ($_POST['delete'] == 1) {
            $has_been_deleted = $maker_dao->delete($_POST['uid']);

            if (!$maker->is_archived) {
                //Remove from ElasticSearch
                SearchHelper::deindexMaker($maker);
            }

            //Delete roles
            $role_dao = new RoleMySQLDAO();
            $role_dao->deleteByMaker($maker->id);

            //Delete actions by users (the only action for this product will be the admin's delete action)
            $action_dao = new ActionMySQLDAO();
            $action_dao->deleteActionsForMaker($maker->id);

            //Delete connections
            $connection_dao = new ConnectionMySQLDAO();
            $connection_dao->deleteConnectionsToMaker($maker->id);

            //TODO Delete made-withs (once made-withs are built)

            if ($has_been_deleted) {
                SessionCache::put('success_message', 'Deleted maker');
                $action_type = 'delete';

                CacheHelper::expireCache('maker.tpl', $maker->uid, $maker->slug);
            }
        }

        if ($has_been_deleted) {
            //Add admin delete action
            $action = new Action();
            $action->user_id = $this->logged_in_user->id;
            $action->severity = Action::SEVERITY_MAJOR;
            $action->object_id = $maker->id;
            $action->object_type = get_class($maker);
            $action->ip_address = $_SERVER['REMOTE_ADDR'];
            $action->action_type = $action_type;
            $action->is_admin = true;
            $action->metadata = json_encode($maker);
            $action_dao = new ActionMySQLDAO();
            $action_dao->insert($action);
        }
        $this->redirect(Config::getInstance()->getValue('site_root_path'));
    }

    private function freezeUser() {
        $user_dao = new UserMySQLDAO();
        $user = $user_dao->get($_POST['uid']);

        $has_changed_frozen_status = false;
        if ($_POST['freeze'] == 1) {
            if ($user->is_frozen) {
                SessionCache::put('info_message', 'Already frozen');
            } else {
                $has_changed_frozen_status = $user_dao->freeze($_POST['uid']);
                if ($has_changed_frozen_status) {
                    $user->is_frozen = true;
                    SessionCache::put('success_message', 'Froze user');
                    $action_type = 'freeze';
                }
            }
        } else {
            if (!$user->is_frozen) {
                SessionCache::put('info_message', 'Already unfrozen');
            } else {
                $has_changed_frozen_status = $user_dao->unfreeze($_POST['uid']);
                if ($has_changed_frozen_status) {
                    $user->is_frozen = false;
                    SessionCache::put('success_message', 'Unfroze user');
                    $action_type = 'unfreeze';
                }
            }
        }

        if ($has_changed_frozen_status) {
            //Add new action
            $action = new Action();
            $action->user_id = $this->logged_in_user->id;
            $action->severity = Action::SEVERITY_MAJOR;
            $action->object_id = $user->id;
            $action->object_type = get_class($user);
            $action->ip_address = $_SERVER['REMOTE_ADDR'];
            $action->action_type = $action_type;
            $action->is_admin = true;
            $action->metadata = json_encode($user);
            $action_dao = new ActionMySQLDAO();
            $action_dao->insert($action);
        }
        $this->redirect('/u/'.$user->uid);
    }

    private function freezeMaker() {
        $maker_dao = new MakerMySQLDAO();
        $maker = $maker_dao->get($_POST['uid']);

        $has_changed_frozen_status = false;
        if ($_POST['freeze'] == 1) {
            if ($maker->is_frozen) {
                SessionCache::put('info_message', "Already frozen");
            } else {
                $has_changed_frozen_status = $maker_dao->freeze($_POST['uid']);
                if ($has_changed_frozen_status) {
                    $maker->is_frozen = true;
                    SessionCache::put('success_message', 'Froze maker');
                    $action_type = 'freeze';
                }
            }
        } else {
            if (!$maker->is_frozen) {
                SessionCache::put('info_message', "Already unfrozen");
            } else {
                $has_changed_frozen_status = $maker_dao->unfreeze($_POST['uid']);
                if ($has_changed_frozen_status) {
                    $maker->is_frozen = false;
                    SessionCache::put('success_message', 'Unfroze maker');
                    $action_type = 'unfreeze';
                }
            }
        }

        if ($has_changed_frozen_status) {
            //Add new action
            $action = new Action();
            $action->user_id = $this->logged_in_user->id;
            $action->severity = Action::SEVERITY_MAJOR;
            $action->object_id = $maker->id;
            $action->object_type = get_class($maker);
            $action->ip_address = $_SERVER['REMOTE_ADDR'];
            $action->action_type = $action_type;
            $action->is_admin = true;
            $action->metadata = json_encode($maker);
            $action_dao = new ActionMySQLDAO();
            $action_dao->insert($action);
        }
        $this->redirect('/m/'.$maker->uid.'/'.$maker->slug);
    }
}
