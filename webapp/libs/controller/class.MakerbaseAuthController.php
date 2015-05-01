<?php

class MakerbaseAuthController extends AuthController {
    /**
     * @var User $logged_in_user
     */
    var $logged_in_user;

    public function authControl() {
        $logged_in_user = Session::getLoggedInUser();
        $user_dao = new UserMySQLDAO();
        $user = $user_dao->get($logged_in_user);
        $this->addToView('logged_in_user', $user);
        $this->logged_in_user = $user;

        $cfg = Config::getInstance();
        $this->addToView('thinkup_uid', $cfg->getValue('thinkup_uid'));
    }

    protected function setUserMessages() {
        $success_message = SessionCache::get('success_message');
        if (isset($success_message)) {
            SessionCache::put('success_message', null);
            $this->addSuccessMessage($success_message);
        }
        $error_message = SessionCache::get('error_message');
        if (isset($error_message)) {
            SessionCache::put('error_message', null);
            $this->addErrorMessage($error_message);
        }
        $info_message = SessionCache::get('info_message');
        if (isset($info_message)) {
            SessionCache::put('info_message', null);
            $this->addInfoMessage($info_message);
        }
    }

    public function getCacheKeyString() {
        $view_cache_key = array();
        $keys = array_keys($_GET);
        foreach ($keys as $key) {
            array_push($view_cache_key, $_GET[$key]);
        }
        if (Session::isLoggedIn()) {
            array_push($view_cache_key, Session::getLoggedInUser());
        }
        return '.ht'.$this->view_template.self::KEY_SEPARATOR.(implode($view_cache_key, self::KEY_SEPARATOR));
    }
}

