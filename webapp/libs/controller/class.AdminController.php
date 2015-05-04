<?php

class AdminController extends MakerbaseAuthController {

    public function authControl() {
        parent::authControl();
        $this->setViewTemplate('admin.tpl');

        if ($this->logged_in_user->twitter_username == 'makerbase') {
            $waitlist_dao = new WaitlistMySQLDAO();
            $waitlisters = $waitlist_dao->get();
            $this->addToView('waitlisters', $waitlisters);
            // TODO: Show total users, waitlisters, projects, roles, activity

            $image_proxy_sig = Config::getInstance()->getValue('image_proxy_sig');
            $this->addToView('image_proxy_sig', $image_proxy_sig);
        } else {
            $this->addErrorMessage("These are not the droids you are looking for.");
        }
        return $this->generateView();
    }
}