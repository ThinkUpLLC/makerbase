<?php

class MakerbaseController extends Controller {

    public function control() {
        if (Session::isLoggedIn()) {
            $logged_in_user = Session::getLoggedInUser();
            $user_dao = new UserMySQLDAO();
            $user = $user_dao->get($logged_in_user);
            $this->addToView('logged_in_user', $user);
        } else {
            $start_time = microtime(true);

            $cfg = Config::getInstance();
            $oauth_consumer_key = $cfg->getValue('twitter_oauth_consumer_key');
            $oauth_consumer_secret = $cfg->getValue('twitter_oauth_consumer_secret');
            $twitter_oauth = new TwitterOAuth($oauth_consumer_key, $oauth_consumer_secret);

            /* Request tokens from twitter */
            $callback_url = Utils::getApplicationURL(false, false).'signin/';
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
}
