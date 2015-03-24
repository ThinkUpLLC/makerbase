<?php

class TwitterSignInController extends Controller {

    public function control() {
        $cfg = Config::getInstance();
        $oauth_consumer_key = $cfg->getValue('twitter_oauth_consumer_key');
        $oauth_consumer_secret = $cfg->getValue('twitter_oauth_consumer_secret');
        $twitter_oauth = new TwitterOAuth($oauth_consumer_key, $oauth_consumer_secret);

        /* Request tokens from twitter */
        $callback_url = Utils::getApplicationURL(false, false).'signin/';
        $callback_url .= '?redirect='.$_SERVER['HTTP_REFERER'];

        $token_array = $twitter_oauth->getRequestToken($callback_url);

        if (isset($token_array['oauth_token']) /*|| Utils::isTest()*/) {
            $token = $token_array['oauth_token'];
            SessionCache::put('oauth_request_token_secret', $token_array['oauth_token_secret']);

            /* Build the authorization URL */
            $sign_in_with_twttr_link = $twitter_oauth->getAuthorizeURL($token);
            $this->redirect($sign_in_with_twttr_link);
        }
    }
}
