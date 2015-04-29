<?php

class LandingController extends MakerbaseController {

    public function control() {
        parent::control();

        if (Session::isLoggedIn()) {
            //$this->view_mgr->clear_all_cache();
            $this->setViewTemplate('landing.tpl');

            if ($this->shouldRefreshCache() ) {
                $action_dao = new ActionMySQLDAO();
                $user = $this->getLoggedInUser();
                $actions = $action_dao->getActivities();
                if (sizeof($actions) == 0) {
                    $actions = $action_dao->getActivities();
                }
                $this->addToView('actions', $actions);
            }
        } else {
            $is_waitlisted = SessionCache::get('is_waitlisted');
            $is_waitlisted = isset($is_waitlisted);
            SessionCache::unsetKey('is_waitlisted');
            $this->disableCaching();
            $this->addToView('is_waitlisted', $is_waitlisted);
            $this->setViewTemplate('landing-door.tpl');
        }

        // Transfer cached user messages to the view
        $this->setUserMessages();

        $image_proxy_sig = Config::getInstance()->getValue('image_proxy_sig');
        $this->addToView('image_proxy_sig', $image_proxy_sig);

        return $this->generateView();
    }

    private function getLoggedInUser() {
        $logged_in_user = Session::getLoggedInUser();
        $user_dao = new UserMySQLDAO();
        $user = $user_dao->get($logged_in_user);
        return $user;
    }
}
