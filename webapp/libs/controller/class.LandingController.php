<?php

class LandingController extends MakerbaseController {

    public function control() {
        //$this->view_mgr->clear_all_cache();
        parent::control();
        $this->setViewTemplate('landing.tpl');

        //Only cache the activity stream for a minute
        $this->view_mgr->cache_lifetime = 60;
        if ($this->shouldRefreshCache() ) {
            $action_dao = new ActionMySQLDAO();
            if (Session::isLoggedIn()) {
                $user = $this->getLoggedInUser();
                $actions = $action_dao->getUserConnectionsActivities($user->id);
                if (sizeof($actions) == 0) {
                    $actions = $action_dao->getActivities();
                }
                $this->addToView('actions', $actions);
            } else {
                $actions = $action_dao->getActivities();
                $this->addToView('actions', $actions);
            }

            $image_proxy_sig = Config::getInstance()->getValue('image_proxy_sig');
            $this->addToView('image_proxy_sig', $image_proxy_sig);
        }
        return $this->generateView();
    }

    private function getLoggedInUser() {
        $logged_in_user = Session::getLoggedInUser();
        $user_dao = new UserMySQLDAO();
        $user = $user_dao->get($logged_in_user);
        return $user;
    }
}
