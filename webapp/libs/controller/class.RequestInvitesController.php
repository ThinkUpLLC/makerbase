<?php

class RequestInvitesController extends MakerbaseController {

    public function control() {
        parent::control();

        $this->disableCaching();
        $waitlister_twitter_id = $_GET['id'];

        $waitlist_dao = new WaitlistMySQLDAO();
        $waitlister = $waitlist_dao->get($waitlister_twitter_id);

        if (isset($waitlister)) {
            $cfg = Config::getInstance();

            //Get waitlister's auth tokens
            $oauth_token = $waitlister['twitter_oauth_access_token'];
            $oauth_secret = $waitlister['twitter_oauth_access_token_secret'];

            //If they weren't captured, use ours (at the risk of using too many API calls)
            if ($oauth_token == null || $oauth_secret == null) {
                $oauth_token = $cfg->getValue('twitter_makerbase_oauth_access_token');
                $oauth_secret = $cfg->getValue('twitter_makerbase_oauth_access_token_secret');
            }

            $oauth_consumer_key = $cfg->getValue('twitter_oauth_consumer_key');
            $oauth_consumer_secret = $cfg->getValue('twitter_oauth_consumer_secret');

            $twitter_oauth = new TwitterOAuth($oauth_consumer_key, $oauth_consumer_secret, $oauth_token,
                $oauth_secret);

            // echo "<pre>";
            // print_r($twitter_oauth);
            // echo "</pre>";

            $api_accessor = new TwitterAPIAccessor();
            $follower_ids = $api_accessor->get5KFollowers($waitlister_twitter_id, $twitter_oauth);

            $follow_dao = new WaitlistFollowMySQLDAO();
            foreach ($follower_ids->ids as $follower_id) {
                if ($follower_id != $waitlister_twitter_id) {
                    $follow_dao->insert($follower_id, $waitlister_twitter_id, 'twitter');
                }
            }
            $user_dao = new UserMySQLDAO();
            $follower_users = $user_dao->getFollowersWhoAreUsers($waitlister_twitter_id, 5);

            $this->setJsonData($follower_users);
        } else {
            $this->setJsonData(array());
        }
        return $this->generateView();
    }
}