<?php

class EditController extends MakerbaseAuthController {

    public function authControl() {
        parent::authControl();

        if ($this->logged_in_user->is_frozen) {
            SessionCache::put('error_message',
                "Hmm, had some trouble saving your edits. Please try again in a little while.");
        }

        if ($this->hasSubmittedRoleForm()) {
            if (!$this->logged_in_user->is_frozen) {
                CacheHelper::expireLandingAndUserActivityCache($this->logged_in_user->uid);
                $this->editRole();
            }

            if ($_POST['originate'] == 'product') {
                CacheHelper::expireCache('product.tpl', $_POST['originate_uid'], $_POST['originate_slug']);
                $this->redirect('/p/'.$_POST['originate_uid'].'/'.$_POST['originate_slug']);
            } elseif ($_POST['originate'] == 'maker') {
                CacheHelper::expireCache('maker.tpl', $_POST['originate_uid'], $_POST['originate_slug']);
                $this->redirect('/m/'.$_POST['originate_uid'].'/'.$_POST['originate_slug']);
            }
        } elseif ($this->hasSubmittedProductForm()) {
            if (!$this->logged_in_user->is_frozen) {
                CacheHelper::expireLandingAndUserActivityCache($this->logged_in_user->uid);
                $this->editProduct();
            }
            $this->redirect('/p/'.$_POST['product_uid'].'/'.$_POST['product_slug']);
        } elseif ($this->hasSubmittedMakerForm()) {
            if (!$this->logged_in_user->is_frozen) {
                CacheHelper::expireLandingAndUserActivityCache($this->logged_in_user->uid);
                $this->editMaker();
            }
            $this->redirect('/m/'.$_POST['maker_uid'].'/'.$_POST['maker_slug']);
        } elseif ($this->hasArchivedMaker()) {
            if (!$this->logged_in_user->is_frozen) {
                CacheHelper::expireLandingAndUserActivityCache($this->logged_in_user->uid);
                $this->archiveMaker();
            }
            $this->redirect('/m/'.$_POST['uid'].'/'.$_POST['slug']);
        } elseif ($this->hasArchivedProduct()) {
            if (!$this->logged_in_user->is_frozen) {
                CacheHelper::expireLandingAndUserActivityCache($this->logged_in_user->uid);
                $this->archiveProduct();
            }
            $this->redirect('/p/'.$_POST['uid'].'/'.$_POST['slug']);
        } elseif ($this->hasArchivedRole()) {
            if (!$this->logged_in_user->is_frozen) {
                CacheHelper::expireLandingAndUserActivityCache($this->logged_in_user->uid);
                $this->archiveRole();
            }

            if ($_POST['originate'] == 'product') {
                CacheHelper::expireCache('product.tpl', $_POST['originate_uid'], $_POST['originate_slug']);
                $this->redirect('/p/'.$_POST['originate_uid'].'/'.$_POST['originate_slug']);
            } elseif ($_POST['originate'] == 'maker') {
                CacheHelper::expireCache('maker.tpl', $_POST['originate_uid'], $_POST['originate_slug']);
                $this->redirect('/m/'.$_POST['originate_uid'].'/'.$_POST['originate_slug']);
            }
        } elseif ($this->hasArchivedMadeWith()) {
            if (!$this->logged_in_user->is_frozen) {
                CacheHelper::expireLandingAndUserActivityCache($this->logged_in_user->uid);
                $this->archiveMadeWith();
            }

            CacheHelper::expireCache('product.tpl', $_POST['originate_uid'], $_POST['originate_slug']);
            $this->redirect('/p/'.$_POST['originate_uid'].'/'.$_POST['originate_slug']);
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
            && isset($_POST['slug'])
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

    private function hasArchivedMadeWith() {
        return (
            (isset($_GET['object']) && $_GET['object'] == 'madewith')
            && isset($_POST['madewith_uid'])
            && isset($_POST['archive']) && ($_POST['archive'] == 1 || $_POST['archive'] == 0)
            && isset($_POST['originate_slug'])
            && isset($_POST['originate_uid'])
        );
    }

    private function hasArchivedProduct() {
        return (
            (isset($_GET['object']) && $_GET['object'] == 'product')
            && isset($_POST['uid'])
            && isset($_POST['slug'])
            && isset($_POST['archive']) && ($_POST['archive'] == 1 || $_POST['archive'] == 0)
        );
    }

    private function hasSubmittedProductForm() {
        return (
            (isset($_GET['object']) && $_GET['object'] == 'product')
            && isset($_POST['product_uid'])
            && isset($_POST['product_slug'])
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
            && isset($_POST['maker_slug'])
            && isset($_POST['name'])
            && isset($_POST['url'])
            && isset($_POST['avatar_url'])
        );
    }

    private function archiveRole() {
        $role_dao = new RoleMySQLDAO();
        $role = $role_dao->get($_POST['uid']);

        //Get maker
        $maker_dao = new MakerMySQLDAO();
        $maker = $maker_dao->getByID($role->maker_id);

        //Get product
        $product_dao = new ProductMySQLDAO();
        $product = $product_dao->getByID($role->product_id);

        if ($product->is_frozen || $maker->is_frozen) {
            SessionCache::put('error_message',
                'Hmm, had some trouble saving your edits. Please try again in a little while.');
        } else {
            $has_changed_archive_status = false;
            if ($_POST['archive'] == 1) {
                if ($role->is_archived) {
                    SessionCache::put('info_message', "Already archived");
                } else {
                    $has_changed_archive_status = $role_dao->archive($_POST['uid']);
                    if ($has_changed_archive_status) {
                        $role->is_archived = true;
                        SessionCache::put('success_message', 'Archived role');
                        $action_type = 'archive';
                    }
                }
            } else {
                if (!$role->is_archived) {
                    SessionCache::put('info_message', "Already unarchived");
                } else {
                    $has_changed_archive_status = $role_dao->unarchive($_POST['uid']);
                    if ($has_changed_archive_status) {
                        $role->is_archived = false;
                        SessionCache::put('success_message', 'Unarchived role');
                        $action_type = 'unarchive';
                    }
                }
            }

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

                // Send email notification
                $email_notifier = new EmailNotifier($this->logged_in_user);
                if ($email_notifier->shouldSendMakerChangeEmailNotification($maker)) {
                    $email_notifier->sendMakerChangeEmailNotification();
                }

            }
        }
    }

    private function archiveMadeWith() {
        $madewith_dao = new MadeWithMySQLDAO();
        $madewith = $madewith_dao->get($_POST['madewith_uid']);

        //Get products
        $product_dao = new ProductMySQLDAO();
        $product = $product_dao->getByID($madewith->product_id);
        $used_product = $product_dao->getByID($madewith->used_product_id);

        if ($product->is_frozen || $used_product->is_frozen) {
            SessionCache::put('error_message',
                'Hmm, had some trouble saving your edits. Please try again in a little while.');
        } else {
            $has_changed_archive_status = false;
            if ($_POST['archive'] == 1) {
                if ($madewith->is_archived) {
                    SessionCache::put('info_message', "Already archived");
                } else {
                    $has_changed_archive_status = $madewith_dao->archive($_POST['madewith_uid']);
                    if ($has_changed_archive_status) {
                        $madewith->is_archived = true;
                        SessionCache::put('success_message', 'You said '.htmlspecialchars($product->name)
                            .' doesn\'t use '. htmlspecialchars($used_product->name).'.');
                        $action_type = 'archive';
                    }
                }
            } else {
                if (!$madewith->is_archived) {
                    SessionCache::put('info_message', "Already unarchived");
                } else {
                    $has_changed_archive_status = $madewith_dao->unarchive($_POST['madewith_uid']);
                    if ($has_changed_archive_status) {
                        $madewith->is_archived = false;
                        SessionCache::put('success_message', 'You said '.htmlspecialchars($product->name)
                            .' doesn\'t use '. htmlspecialchars($used_product->name).'.');
                        $action_type = 'unarchive';
                    }
                }
            }

            //Add new connection
            $connection_dao = new ConnectionMySQLDAO();
            $connection_dao->insert($this->logged_in_user, $product);
            $connection_dao->insert($this->logged_in_user, $used_product);

            if ($has_changed_archive_status) {
                //Add new action
                $action = new Action();
                $action->user_id = $this->logged_in_user->id;
                $action->severity = Action::SEVERITY_NORMAL;
                $action->object_id = $product->id;
                $action->object_type = get_class($product);
                $action->object2_id = $used_product->id;
                $action->object2_type = get_class($used_product);
                $action->ip_address = $_SERVER['REMOTE_ADDR'];
                $action->action_type = 'not made with';

                $madewith->product = $product;
                $madewith->used_product = $used_product;
                $action->metadata = json_encode($madewith);

                $action_dao = new ActionMySQLDAO();
                $action_dao->insert($action);
            }
        }
    }

    private function archiveProduct() {
        $product_dao = new ProductMySQLDAO();
        $product = $product_dao->get($_POST['uid']);

        if ($product->is_frozen) {
            SessionCache::put('error_message',
                "Hmm, had some trouble saving your edits. Please try again in a little while.");
        } else {
            $has_changed_archive_status = false;
            if ($_POST['archive'] == 1) {
                if ($product->is_archived) {
                    SessionCache::put('info_message', 'Already archived');
                } else {
                    $has_changed_archive_status = $product_dao->archive($_POST['uid']);
                    if ($has_changed_archive_status) {
                        $product->is_archived = true;
                        SessionCache::put('success_message', 'Archived project');
                        $action_type = 'archive';

                        //Remove product from Elasticsearch
                        SearchHelper::deindexProduct($product);
                    }
                }
            } else {
                if (!$product->is_archived) {
                    SessionCache::put('info_message', 'Already unarchived');
                } else {
                    $has_changed_archive_status = $product_dao->unarchive($_POST['uid']);
                    if ($has_changed_archive_status) {
                        $product->is_archived = false;
                        SessionCache::put('success_message', 'Unarchived project');
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
            CacheHelper::expireCache('product.tpl', $product->uid, $product->slug);
        }
    }

    private function archiveMaker() {
        $maker_dao = new MakerMySQLDAO();
        $maker = $maker_dao->get($_POST['uid']);

        if ($maker->is_frozen) {
            SessionCache::put('error_message',
                "Hmm, had some trouble saving your edits. Please try again in a little while.");
        } else {
            $has_changed_archive_status = false;
            if ($_POST['archive'] == 1) {
                if ($maker->is_archived) {
                    SessionCache::put('info_message', "Already archived");
                } else {
                    $has_changed_archive_status = $maker_dao->archive($_POST['uid']);
                    if ($has_changed_archive_status) {
                        $maker->is_archived = true;
                        SessionCache::put('success_message', 'Archived maker');
                        $action_type = 'archive';

                        // Remove maker from Elasticsearch
                        SearchHelper::deindexMaker($maker);
                    }
                }
            } else {
                if (!$maker->is_archived) {
                    SessionCache::put('info_message', "Already unarchived");
                } else {
                    $has_changed_archive_status = $maker_dao->unarchive($_POST['uid']);
                    if ($has_changed_archive_status) {
                        $maker->is_archived = false;
                        SessionCache::put('success_message', 'Unarchived maker');
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

                // Send email notification
                $email_notifier = new EmailNotifier($this->logged_in_user);
                if ($email_notifier->shouldSendMakerChangeEmailNotification($maker)) {
                    $email_notifier->sendMakerChangeEmailNotification();
                }
            }
            CacheHelper::expireCache('maker.tpl', $maker->uid, $maker->slug);
        }
    }

    private function editMaker() {
        $maker_dao = new MakerMySQLDAO();

        //This will throw an exception if the maker doesn't exist
        $original_maker = $maker_dao->get($_POST['maker_uid']);

        if ($original_maker->is_frozen) {
            SessionCache::put('error_message',
                "Hmm, had some trouble saving your edits. Please try again in a little while.");
        } elseif ($original_maker->is_archived) {
            SessionCache::put('error_message',
                "This maker is archived. To edit, unarchive the maker first and try again.");
        } elseif (!empty($_POST['url']) && filter_var($_POST['url'], FILTER_VALIDATE_URL) === false) {
            SessionCache::put('error_message',
                "That doesn't look like a valid web site URL. Please try again.");
        } elseif (!empty($_POST['avatar_url']) && filter_var($_POST['avatar_url'], FILTER_VALIDATE_URL) === false) {
            SessionCache::put('error_message',
                "That doesn't look like a valid avatar URL. Please try again.");
        } elseif (empty($_POST['name'])) {
            SessionCache::put('error_message', "Please enter a name and try again.");
        } else {
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

                // Send email notification
                $email_notifier = new EmailNotifier($this->logged_in_user);
                if ($email_notifier->shouldSendMakerChangeEmailNotification($maker)) {
                    $email_notifier->sendMakerChangeEmailNotification();
                }

                SessionCache::put('success_message', "Updated ".htmlspecialchars($maker->name));
            } else {
                SessionCache::put('error_message', "Looks like there were no changes to save.");
            }
            CacheHelper::expireCache('maker.tpl', $maker->uid, $maker->slug);
        }
    }

    private function editProduct() {
        $product_dao = new ProductMySQLDAO();

        //This will throw an exception if the product doesn't exist
        $original_product = $product_dao->get($_POST['product_uid']);

        if ($original_product->is_frozen) {
            SessionCache::put('error_message',
                "Hmm, had some trouble saving your edits. Please try again in a little while.");
        } elseif ($original_product->is_archived) {
            SessionCache::put('error_message',
                "This project is archived. To edit it, unarchive it first and try again.");
        } elseif (!empty($_POST['url']) && filter_var($_POST['url'], FILTER_VALIDATE_URL) === false) {
            SessionCache::put('error_message',
                "That doesn't look like a valid web site URL. Please try again.");
        } elseif (!empty($_POST['avatar_url']) && filter_var($_POST['avatar_url'], FILTER_VALIDATE_URL) === false) {
            SessionCache::put('error_message',
                "That doesn't look like a valid avatar URL. Please try again.");
        } elseif (empty($_POST['name'])) {
            SessionCache::put('error_message', "Please enter a name and try again.");
        } else {
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

            if ($has_been_updated) {
                SessionCache::put('success_message', "Updated ".htmlspecialchars($product->name));
            } else {
                SessionCache::put('error_message', "Looks like there were no changes to save.");
            }
            CacheHelper::expireCache('product.tpl', $product->uid, $product->slug);
        }
    }

    private function editRole(){
        $role_dao = new RoleMySQLDAO();

        //This will throw an exception if the role doesn't exist
        $original_role = $role_dao->get($_POST['role_uid']);

        //Get maker
        $maker_dao = new MakerMySQLDAO();
        $maker = $maker_dao->getByID($original_role->maker_id);

        //Get product
        $product_dao = new ProductMySQLDAO();
        $product = $product_dao->getByID($original_role->product_id);

        if ($product->is_frozen || $maker->is_frozen) {
            SessionCache::put('error_message',
                'Hmm, had some trouble saving your edits. Please try again in a little while.');
        } else {
            //Check dates
            //Is the start date valid?
            $start_date_str = (empty($_POST['start_date']))?null:$_POST['start_date'];
            if (isset($start_date_str)) {
                if (!Role::isValidDateString($start_date_str)) {
                    SessionCache::put('error_message',
                        'That start date doesn\'t look right. Please try again.');
                    return;
                }
            }
            //Is the end date valid?
            $end_date_str = (empty($_POST['end_date']))?null:$_POST['end_date'];
            if (isset($end_date_str)) {
                if (!Role::isValidDateString($end_date_str)) {
                    SessionCache::put('error_message',
                        'That end date doesn\'t look right. Please try again.');
                    return;
                }
            }
            //Is the start date before or the same as the end date?
            $start_date = (empty($_POST['start_date']))?null:$_POST['start_date']."-01";
            $end_date = (empty($_POST['end_date']))?null:$_POST['end_date']."-01";
            if (isset($start_date) && isset($end_date)) {
                $start_date_time = strtotime($start_date);
                $end_date_time = strtotime($end_date);
                if ($start_date_time > $end_date_time) {
                    SessionCache::put('error_message',
                        'The start date has to be before the end date. Please try again.');
                    return;
                }
            }

            $role = new Role();
            $role->id = $original_role->id;
            $role->uid = $_POST['role_uid'];
            $role->start = (empty($_POST['start_date']))?null:$_POST['start_date']."-01";
            $role->end = (empty($_POST['end_date']))?null:$_POST['end_date']."-01";
            $role->role = $_POST['role'];
            $has_been_updated = $role_dao->update($role);

            $updated_role = $role_dao->get($role->uid);

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

                // Send email notification
                $email_notifier = new EmailNotifier($this->logged_in_user);
                if ($email_notifier->shouldSendMakerChangeEmailNotification($maker)) {
                    $email_notifier->sendMakerChangeEmailNotification();
                }

                SessionCache::put('success_message', "Okay! The role was updated.");
            } else {
                SessionCache::put('error_message', "Looks like there were no changes to save.");
            }
        }
    }
}
