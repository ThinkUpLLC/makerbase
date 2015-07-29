<?php

class AdminDashboardController extends MakerbaseAdminController {

    public function authControl() {
        $this->setViewTemplate('admin.tpl');

        $this->disableCaching();

        $waitlist_dao = new WaitlistMySQLDAO();
        $page_number = (isset($_GET['p']))?$_GET['p']:1;
        $limit = 20;

        if (!isset($_GET['v']) ) {
            $_GET['v'] = 'waitlist_followers';
        }

        if ($_GET['v'] == 'waitlist_followers' || $_GET['v'] == 'waitlist_newest') {
            if ($_GET['v'] == 'waitlist_followers') {
                $waitlisters = $waitlist_dao->listWaitlisters($limit, 'follower_count', $page_number);
            } elseif ($_GET['v'] == 'waitlist_newest') {
                $waitlisters = $waitlist_dao->listWaitlisters($limit, 'creation_time', $page_number);
            }

            if (count($waitlisters) > $limit) {
                array_pop($waitlisters);
                $this->addToView('next_page', $page_number+1);
            }
            if ($page_number > 1) {
                $this->addToView('prev_page', $page_number-1);
            }

            $this->addToView('waitlisters', $waitlisters);
        }
        if ($_GET['v'] == 'actions') {
            $action_dao = new ActionMySQLDAO();
            $actions = $action_dao->getAdminActivities($page_number, $limit);
            $this->addToView('actions', $actions);
        }

        if ($_GET['v'] == 'all-actions') {
            $action_dao = new ActionMySQLDAO();
            $actions = $action_dao->getActivities($page_number, $limit);
            $this->addToView('actions', $actions);
        }

        $this->addToView('sort_view',  $_GET['v']);

        $total_waitlisters = $waitlist_dao->getTotal();
        $this->addToView('total_waitlisters', $total_waitlisters);

        $user_dao = new UserMySQLDAO();
        $total_users = $user_dao->getTotal();
        $this->addToView('total_users', $total_users);
        $total_emails = $user_dao->getTotalEmails();
        $this->addToView('total_emails', $total_emails);

        $product_dao = new ProductMySQLDAO();
        $total_products = $product_dao->getTotal();
        $this->addToView('total_products', $total_products);

        $maker_dao = new MakerMySQLDAO();
        $total_makers = $maker_dao->getTotal();
        $this->addToView('total_makers', $total_makers);

        $role_dao = new RoleMySQLDAO();
        $total_roles = $role_dao->getTotal();
        $this->addToView('total_roles', $total_roles);

        $action_dao = new ActionMySQLDAO();
        $total_actions = $action_dao->getTotal();
        $this->addToView('total_actions', $total_actions);

        return $this->generateView();
    }
}