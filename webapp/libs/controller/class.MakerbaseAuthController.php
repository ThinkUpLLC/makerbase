<?php

class MakerbaseAuthController extends AuthController {
    /**
     * @var User $logged_in_user
     */
    var $logged_in_user;

    public function __construct($session_started=false) {
        parent::__construct($session_started);
        if ( $this->profiler_enabled) {
            $this->start_time = microtime(true);
        }
    }

    public function authControl() {
        $logged_in_user = MakerbaseSession::getLoggedInUser();
        $user_dao = new UserMySQLDAO();
        $user = $user_dao->get($logged_in_user);

        //Set admin status
        $cfg = Config::getInstance();
        $admins = $cfg->getValue('admins');
        $user->is_admin = in_array($user->twitter_username, $admins);

        $this->addToView('logged_in_user', $user);
        $this->logged_in_user = $user;

        $this->addToView('thinkup_uid', $cfg->getValue('thinkup_uid'));
        $this->addToView('image_proxy_sig', $cfg->getValue('image_proxy_sig'));
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
        if (MakerbaseSession::isLoggedIn()) {
            array_push($view_cache_key, MakerbaseSession::getLoggedInUser());
        }
        return '.ht'.$this->view_template.self::KEY_SEPARATOR.(implode($view_cache_key, self::KEY_SEPARATOR));
    }
    /**
     * Send Location header
     * @param str $destination
     * @return bool Whether or not redirect header was sent
     */
    protected function redirect($destination=null) {
        if ($this->profiler_enabled && !isset($this->json_data)
        && strpos($this->content_type, 'text/javascript') === false
        && strpos($this->content_type, 'text/csv') === false) {
            $end_time = microtime(true);
            $total_time = $end_time - $this->start_time;
            $profiler = Profiler::getInstance();
            $this->disableCaching();
            $profiler->add($total_time,
                "total page execution time, running ".$profiler->total_queries." queries.");
            $this->setViewTemplate('_isosceles.profiler.tpl');
            $this->addToView('profile_items',$profiler->getProfile());
            $profiler_html = $this->generateView();
            SessionCache::put('last_page_profiled', $profiler_html);
        }
        parent::redirect($destination);
    }
}

