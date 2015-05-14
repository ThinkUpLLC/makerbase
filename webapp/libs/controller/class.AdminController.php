<?php

class AdminController extends MakerbaseAuthController {

    public function authControl() {
        parent::authControl();
        $this->setViewTemplate('admin.tpl');
        $admins = Config::getInstance()->getValue('admins');

        if (in_array($this->logged_in_user->twitter_username, $admins)) {
            $this->disableCaching();

            $waitlist_dao = new WaitlistMySQLDAO();
            $page_number = (isset($_GET['p']))?$_GET['p']:1;
            $limit = 20;

            if (!isset($_GET['sort']) || $_GET['sort'] == 'follower_count') {
                $waitlisters = $waitlist_dao->listWaitlisters($limit, 'follower_count', $page_number);
            } else {
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

            $total_waitlisters = $waitlist_dao->getTotal();
            $this->addToView('total_waitlisters', $total_waitlisters);
            // TODO: Show total users, waitlisters, projects, roles, activity

            $image_proxy_sig = Config::getInstance()->getValue('image_proxy_sig');
            $this->addToView('image_proxy_sig', $image_proxy_sig);
        } else {
            $this->addErrorMessage("These are not the droids you are looking for.");
        }
        return $this->generateView();
    }
}