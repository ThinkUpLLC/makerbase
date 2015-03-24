<?php

class MakerbaseController extends Controller {
    /**
     * @var User $logged_in_user
     */
    var $logged_in_user;

    public function control() {
        $cfg = Config::getInstance();
        $this->addToView('thinkup_uid', $cfg->getValue('thinkup_uid'));
        if (Session::isLoggedIn()) {
            $logged_in_user = Session::getLoggedInUser();
            $user_dao = new UserMySQLDAO();
            $user = $user_dao->get($logged_in_user);
            $this->addToView('logged_in_user', $user);
            $this->logged_in_user = $user;
        } else {
            $start_time = microtime(true);

            $oauth_consumer_key = $cfg->getValue('twitter_oauth_consumer_key');
            $oauth_consumer_secret = $cfg->getValue('twitter_oauth_consumer_secret');
            $twitter_oauth = new TwitterOAuth($oauth_consumer_key, $oauth_consumer_secret);

            /* Request tokens from twitter */
            $callback_url = Utils::getApplicationURL(false, false).'signin/';
            if (isset($_GET['redirect'])) {
                $callback_url .= '?redirect='.$_GET['redirect'];
            } else {
                $callback_url .= '?redirect='.$_SERVER['REQUEST_URI'];
            }
            $token_array = $twitter_oauth->getRequestToken($callback_url);

            if (isset($token_array['oauth_token']) /*|| Utils::isTest()*/) {
                $token = $token_array['oauth_token'];
                SessionCache::put('oauth_request_token_secret', $token_array['oauth_token_secret']);

                /* Build the authorization URL */
                $sign_in_with_twttr_link = $twitter_oauth->getAuthorizeURL($token);
                $this->addToView('sign_in_with_twttr_link', $sign_in_with_twttr_link);

                $end_time = microtime(true);

                if (Profiler::isEnabled()) {
                    $total_time = $end_time - $start_time;
                    $profiler = Profiler::getInstance();
                    $profiler->add($total_time, "Sign in with Twitter", false);
                }
            }
        }
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
}
