<?php

class TwitterSignInController extends Controller {

    public function control() {
        $cfg = Config::getInstance();
        $oauth_consumer_key = $cfg->getValue('twitter_oauth_consumer_key');
        $oauth_consumer_secret = $cfg->getValue('twitter_oauth_consumer_secret');
        $twitter_oauth = new TwitterOAuth($oauth_consumer_key, $oauth_consumer_secret);

        /* Request tokens from twitter */
        $callback_url = self::getApplicationURL(false, false).'signin/';
        if (isset($_GET['redirect'])) {
            $callback_url .= '?redirect='.$_GET['redirect'];
        } elseif (isset($_SERVER['HTTP_REFERER'])) {
            $callback_url .= '?redirect='.$_SERVER['HTTP_REFERER'];
        }

        $token_array = $twitter_oauth->getRequestToken($callback_url);

        if (isset($token_array['oauth_token']) /*|| Utils::isTest()*/) {
            $token = $token_array['oauth_token'];
            SessionCache::put('oauth_request_token_secret', $token_array['oauth_token_secret']);

            /* Build the authorization URL */
            $sign_in_with_twttr_link = $twitter_oauth->getAuthorizeURL($token);
            $this->redirect($sign_in_with_twttr_link);
        }
    }

    /**
     * Get application URL.
     * This is a copy of the Utils function that ignores the SERVER_PORT.
     * Makerbase's Nginx/Apache setup means the application is running on port 82, but it uses this function
     * for signin redirect. Some users' networks block port 82, so we ignore port here.
     * @param bool $replace_localhost_with_ip
     * @param bool $use_filesystem_path Use filesystem path instead of path specified in config.inc.php
     * @return str application URL
     */
    public static function getApplicationURL($replace_localhost_with_ip = false, $use_filesystem_path = true,
        $should_url_encode = true) {
        $server = Utils::getApplicationHostName();
        if ($replace_localhost_with_ip) {
            $server = ($server == 'localhost')?'127.0.0.1':$server;
        }
        if ($use_filesystem_path) {
            $site_root_path = Utils::getSiteRootPathFromFileSystem();
        } else {
            $cfg = Config::getInstance();
            $site_root_path = $cfg->getValue('site_root_path');
        }
        if ($should_url_encode) {
            //URLencode everything except spaces in site_root_path
            $site_root_path = str_replace('%2f', '/', strtolower(urlencode($site_root_path)));
        }
        // if  (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] !== '80') { //non-standard port
        //     if (isset($_SERVER['HTTPS']) && $_SERVER['SERVER_PORT'] == '443') { //account for standard https port
        //         $port = '';
        //     } else {
        //         $port = ':'.$_SERVER['SERVER_PORT'];
        //     }
        // } else {
        //     $port = '';
        // }
        $port = '';
        return 'http'.(empty($_SERVER['HTTPS'])?'':'s').'://'.$server.$port.$site_root_path;
    }
}
