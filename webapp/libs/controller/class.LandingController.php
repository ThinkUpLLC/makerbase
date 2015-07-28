<?php

class LandingController extends MakerbaseController {

    public function control() {
        //Begin terrible, terrible hack to make signing into makerbase.dev when offline possible
        if (isset($_GET['fl']) && $_SERVER['SERVER_NAME'] == 'makerbase.dev') {
            Session::completeLogin($_GET['fl']);
        }
        //End terrible, terrible hack

        parent::control();

        if (Session::isLoggedIn()) {
            //$this->view_mgr->clear_all_cache();
            $this->setViewTemplate('landing.tpl');

            if ($this->shouldRefreshCache() ) {
                $page_number = (isset($_GET['p']) && is_numeric($_GET['p']))?$_GET['p']:1;
                $limit = 10;
                $action_dao = new ActionMySQLDAO();
                $actions = $action_dao->getUserConnectionsActivities(Session::getLoggedInUser(), $page_number, $limit);
                if (count($actions == 0)) {
                    $actions = $action_dao->getActivities($page_number, $limit);
                }
                if (count($actions) > $limit) {
                    array_pop($actions);
                    $this->addToView('next_page', $page_number+1);
                }
                if ($page_number > 1) {
                    $this->addToView('prev_page', $page_number-1);
                }
                $this->addToView('actions', $actions);
            }

            if (!isset($this->logged_in_user->email)) {
                SessionCache::put('success_message',
                    'Get notified when your pages change! <a href="/u/'.$this->logged_in_user->uid
                    .'">Add your email address now.</a>');
            }
        } else {
            $waitlisted_user = SessionCache::get('is_waitlisted');
            $is_waitlisted = isset($waitlisted_user);
            if ($is_waitlisted) {
                $this->addToView('waitlisted_username', $waitlisted_user['user_name']);
                $this->addToView('waitlisted_twitter_id', $waitlisted_user['user_id']);
            }
            $this->addToView('is_waitlisted', $is_waitlisted);
            SessionCache::unsetKey('is_waitlisted');
            $this->disableCaching();
            $this->setViewTemplate('landing-door.tpl');
        }

        // Transfer cached user messages to the view
        $this->setUserMessages();

        return $this->generateView();
    }
}
