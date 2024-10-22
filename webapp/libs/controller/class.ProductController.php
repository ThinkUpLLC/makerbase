<?php

class ProductController extends MakerbaseController {

    public function control() {
        parent::control();
        $this->setViewTemplate('product.tpl');

        if ($this->shouldRefreshCache() ) {
            $product_dao = new ProductMySQLDAO();

            try {
                // Get product
                $product = $product_dao->get($_GET['uid']);
                $this->addToView('product', $product);

                // Get roles
                $role_dao = new RoleMySQLDAO();
                $roles = $role_dao->getByProduct($product->id);
                $this->addToView('roles', $roles);

                // Get madewiths
                $madewith_dao = new MadeWithMySQLDAO();
                $madewiths = $madewith_dao->getByProduct($product);

                //Get uses this buttons (subtract madewiths from sponsors)
                $sponsors = Config::getInstance()->getValue('sponsors');
                $sponsor_names = array();
                $sponsor_uids = array(); //For later use so we don't have to loop again
                foreach ($sponsors as $key=>$val) {
                    $sponsor_names[] = $key;
                    $sponsor_uids[] = $val['uid'];
                }
                $madewith_names = array();
                foreach ($madewiths as $madewith) {
                    $madewith_names[] = $madewith->used_product->name;
                }
                $uses_this_button_names = array();

                $uses_this_button_names = array_diff($sponsor_names, $madewith_names);
                $uses_this_buttons = array();
                foreach ($uses_this_button_names as $uses_this_name) {
                    $uses_this_buttons[] = $sponsors[$uses_this_name];
                }
                $this->addToView('uses_this_buttons', $uses_this_buttons);

                $active_madewiths = array();
                //Use config-specified sponsor avatars for madewiths
                foreach ($madewiths as $madewith) {
                    if (isset($sponsors[$madewith->used_product->name]['avatar_url'])) {
                        $madewith->used_product->avatar_url = $sponsors[$madewith->used_product->name]['avatar_url'];
                        $active_madewiths[] = $madewith;
                    }
                }
                $this->addToView('madewiths', $active_madewiths);

                // Get used by (if sponsor)
                if (in_array($product->uid, $sponsor_uids) ) {
                    $used_by_madewiths = $madewith_dao->getByUsedProduct($product);
                    $this->addToView('used_by_madewiths', $used_by_madewiths);
                }

                // Get actions
                $page_number = (isset($_GET['p']) && is_numeric($_GET['p']))?$_GET['p']:1;
                $limit = 10;

                $action_dao = new ActionMySQLDAO();
                $actions = $action_dao->getActivitiesPerformedOnProduct($product, $page_number, $limit);

                if (count($actions) > $limit) {
                    array_pop($actions);
                    $this->addToView('next_page', $page_number+1);
                }
                if ($page_number > 1) {
                    $this->addToView('prev_page', $page_number-1);
                }
                $this->addToView('actions', $actions);

                $this->addToView('placeholder', Role::getRandoPlaceholder());

                if (isset($this->logged_in_user) && $this->logged_in_user->is_admin) {
                    $last_admin_activity = $action_dao->getLastAdminActivityPerformedOnProduct($product);
                    $this->addToView('last_admin_activity', $last_admin_activity);
                }

                if (isset($this->logged_in_user)) {
                    $connection_dao = new ConnectionMySQLDAO();
                    $this->logged_in_user->is_following_product =
                        $connection_dao->isFollowingProduct($this->logged_in_user, $product);
                    $this->addToView('logged_in_user', $this->logged_in_user);
                }
            } catch (ProductDoesNotExistException $e) {
                $this->redirect('/404');
            }
        }
        // Transfer cached user messages to the view
        $this->setUserMessages();

        return $this->generateView();
    }
}
