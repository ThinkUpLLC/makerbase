<?php

class AdminDashboardController extends MakerbaseAdminController {

    public function authControl() {
        $this->setViewTemplate('admin.tpl');

        $this->disableCaching();

        $page_number = (isset($_GET['p']))?$_GET['p']:1;
        $limit = 20;

        if (!isset($_GET['v']) ) {
            $_GET['v'] = 'all-actions';
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

        if ($_GET['v'] == 'top-users') {
            $user_dao = new UserMySQLDAO();
            $top_users = $user_dao->getUsersWithMostActions(7);
            $this->addToView('top_users', $top_users);
        }

        $this->addToView('sort_view',  $_GET['v']);

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