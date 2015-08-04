<?php

class LandingController extends MakerbaseController {

    public function control() {
        //Begin terrible, terrible hack to make signing into makerbase.dev when offline possible
        if (isset($_GET['fl']) && $_SERVER['SERVER_NAME'] == 'makerbase.dev') {
            Session::completeLogin($_GET['fl']);
        }
        //End terrible, terrible hack

        parent::control();
        $this->setViewTemplate('landing.tpl');

        if (Session::isLoggedIn()) {
            if ($this->shouldRefreshCache() ) {
                $page_number = (isset($_GET['p']) && is_numeric($_GET['p']))?$_GET['p']:1;
                $limit = 10;
                $action_dao = new ActionMySQLDAO();

                // $actions = $action_dao->getUserConnectionsActivities($this->logged_in_user->uid, $page_number, $limit);
                // right now, let's just show global actions
                $actions[] = '';

                if (count($actions) == 0) {
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
        }
        if ($this->shouldRefreshCache() ) {
            //Featured makers
            $config = Config::getInstance();
            $featured_makers = $config->getValue('featured_makers');
            $this->addToView('featured_makers', $featured_makers);

            //Featured products
            $featured_products = $config->getValue('featured_products');
            $this->addToView('featured_products', $featured_products);

            //Featured users
            $featured_user_uids = $config->getValue('featured_users');
            $user_dao = new UserMySQLDAO();
            $featured_users = array();
            //@TODO Optimize this!
            foreach($featured_user_uids as $featured_user_uid) {
                $featured_users[] = $user_dao->get($featured_user_uid);
            }
            $this->addToView('featured_users', $featured_users);

            if ($this->shouldRefreshCache() ) {
                $page_number = 1;
                $limit = 6;
                $action_dao = new ActionMySQLDAO();
                $actions = $action_dao->getActivities($page_number, $limit);
                $this->addToView('actions', $actions);
            }
        }

        // Transfer cached user messages to the view
        $this->setUserMessages();

        return $this->generateView();
    }
}
