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

        if ($_GET['v'] == 'stats') {
            $user_dao = new UserMySQLDAO();
            $signups_by_week = $user_dao->getSignupsByWeek();

            $weekly_signups = array();
            $last_weekly_signup = null;
            foreach ($signups_by_week as $weekly_signup) {
                if (isset($last_weekly_signup)) {
                    $percentage_diff = (($weekly_signup['user_signups_per_week']-$last_weekly_signup)*100)
                        /($weekly_signup['user_signups_per_week']);
                    $weekly_signup['percentage_diff'] = $percentage_diff;
                } else {
                    $weekly_signup['percentage_diff'] = null;
                }
                $last_weekly_signup = $weekly_signup['user_signups_per_week'];
                $weekly_signups[] = $weekly_signup;
            }
            // Figure out average percentage difference over last 6 weeks
            $last_six_weeks = array_slice($weekly_signups, count($weekly_signups)-6, 6);
            $percentage_diff_sum = 0;
            foreach ($last_six_weeks as $week) {
                $percentage_diff_sum += $week['percentage_diff'];
            }
            $last_six_weeks_average = round($percentage_diff_sum / 6);

            $this->addToView('weekly_signups', $weekly_signups);
            $this->addToView('last_six_weeks_average', $last_six_weeks_average);
        }

        $this->addToView('sort_view',  $_GET['v']);

        $user_dao = new UserMySQLDAO();
        $total_users = $user_dao->getTotal();
        $this->addToView('total_users', $total_users);
        $total_users_with_friends = $user_dao->getTotalUsersWithFriends();
        $this->addToView('total_users_with_friends', $total_users_with_friends);
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