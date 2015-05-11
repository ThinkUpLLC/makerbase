<?php

class AdminController extends MakerbaseAuthController {

    public function authControl() {
        parent::authControl();
        $this->setViewTemplate('admin.tpl');
        $admins = Config::getInstance()->getValue('admins');

        if (in_array($this->logged_in_user->twitter_username, $admins)) {
            $this->disableCaching();

            $waitlist_dao = new WaitlistMySQLDAO();
            $waitlisters = $waitlist_dao->listWaitlisters(20);
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