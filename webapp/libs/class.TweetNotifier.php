<?php

class TweetNotifier {
    /**
     * @var User Currently logged in user
     */
    var $logged_in_user;

    public function TweetNotifier(User $logged_in_user = null) {
        if ($logged_in_user !== null) {
            $this->logged_in_user = $logged_in_user;
        }
    }
    /**
     * Return whether or not a user should get a maker change tweet.
     * Returns true if:
     * * Maker has an associated Twitter user ID
     * * that Twitter user ID does not equal the logged in users (user isn't editing herself)
     * * the edited maker's associated user has not set his/her email address
     * * the edited maker's associated user hasn't received a tweet notification ever
     *
     * @param  Maker  $maker Edited maker
     * @return bool
     */
    public function shouldSendMakerChangeTweetNotification(Maker $maker) {
        return $should_send_maker_change_tweet;
    }

    /**
     * Send tweet about a maker change.
     * @return void
     */
    public function sendMakerChangeTweetNotification() {
    }

    /**
     * Send a tweet public @reply notification from @makerbase about a new inspiration.
     * This notification counts toward a user's tweet notification balance, so if it is sent, another one won't be sent.
     * @return void
     */
    public function sendNewInspirationTweetNotification() {
    }

    private function shouldTweet(Maker $maker) {
        if (isset($maker->autofill_network_id) && isset($maker->autofill_network)
            && $maker->autofill_network === 'twitter' /* Have Twitter info for maker */
            /* Maker is different than user */
            && $maker->autofill_network_id !== $this->logged_in_user->twitter_user_id ) {
            $sent_tweet_dao = new SentTweetMySQLDAO();
            //If a tweet hasn't been sent to this person, then send one
            return (!$sent_tweet_dao->hasBeenSent($maker->autofill_network_id));
        } else {  //No Twitter info for maker
            return false;
        }
    }

    /**
     * Send a public @ reply from @makerbase to a new maker who was just created.
     * @param  Maker $maker
     * @param  str $add_to_product
     * @return bool
     */
    public function sendNewMakerTweetNotification($maker, $add_to_product = null) {
        if ($this->shouldTweet($maker)) {
            //Tweet a notification
            $tweet_text = $this->getNewMakerTweetText($maker, $add_to_product);

            $cfg = Config::getInstance();
            $oauth_consumer_key = $cfg->getValue('twitter_oauth_notifier_consumer_key');
            $oauth_consumer_secret = $cfg->getValue('twitter_oauth_notifier_consumer_secret');
            $oauth_token = $cfg->getValue('twitter_oauth_notifier_access_token');
            $oauth_token_secret = $cfg->getValue('twitter_oauth_notifier_access_token_secret');

            //Only attempt the tweet if these are set - and they are not set on dev
            if (isset($oauth_consumer_key) && isset($oauth_consumer_secret) ) {
                $twitter_oauth = new TwitterOAuth($oauth_consumer_key, $oauth_consumer_secret, $oauth_token,
                    $oauth_token_secret);

                $api_accessor = new TwitterAPIAccessor();
                // Tweet the tweet
                $results = $api_accessor->postTweet($tweet_text, $twitter_oauth);
                if ($results[0] == 200) {
                    $sent_tweet_dao = new SentTweetMySQLDAO();
                    $sent_tweet_dao->insert($maker->autofill_network_id, $maker->autofill_network_username);
                    return true;
                } else { //API returned a non-200 code, tweet wasn't sent
                    return false;
                }
            } else { //No Twitter credentials set in the config
                return false;
            }
        } else {
            return false; //Shouldn't send a tweet to this maker
        }
    }

    public function getNewMakerTweetText(Maker $maker, Product $product = null) {
        $maker_url = "https://makerbase.co/m/".$maker->uid."/".$maker->slug;

        if (isset($product)) {
            //Shorten really long product names
            if (strlen($product->name) > 50) {
                $product_name = substr($product->name, 0, 47) . "...";
            } else {
                $product_name = $product->name;
            }

            if ($this->logged_in_user->twitter_username !== 'makerbase') {
                $tweet_with_product_versions = array(
                    "@".$maker->autofill_network_username." Hey, @".
                        $this->logged_in_user->twitter_username.
                        " just added you to ".$product_name.
                        ". Now you can fill in the details: ",
                    "@".$maker->autofill_network_username." Hi! @".
                        $this->logged_in_user->twitter_username.
                        " said you made ".$product_name.
                        ". Add your role & coworkers: ",
                    "@".$maker->autofill_network_username." Hi there, @".
                        $this->logged_in_user->twitter_username.
                        " said you made ".$product_name.
                        ". Fill in what you did: ",
                    "@".$maker->autofill_network_username." Hey there, @".
                        $this->logged_in_user->twitter_username.
                        " just added you to ".$product_name.
                        ". What other projects have you made? ",
                    "@".$maker->autofill_network_username." Hey, @".
                        $this->logged_in_user->twitter_username.
                        " said you made ".$product_name.
                        ". Fill in your role & collaborators: ",
                    "@".$maker->autofill_network_username." Hello, @".
                        $this->logged_in_user->twitter_username.
                        " added you as a maker to ".$product_name.
                        ". Check it out: ",
                );
            } else {
                $tweet_with_product_versions = array(
                    "@".$maker->autofill_network_username." Hey, we just added you to ".$product_name.
                        ". Now you can fill in the details: ",
                    "@".$maker->autofill_network_username." Hi! We see you made ".$product_name.
                        ". Add your role & coworkers: ",
                    "@".$maker->autofill_network_username." Hi there, we noticed you made ".$product_name.
                        ". Fill in what you did: ",
                    "@".$maker->autofill_network_username." Hey there, we just added you to ".$product_name.
                        ". What other projects have you made? ",
                    "@".$maker->autofill_network_username." Hello! We just added you as a maker to "
                        .$product_name. ". Check it out: ",
                );
            }

            $tweet_text = $tweet_with_product_versions[rand(0, count($tweet_with_product_versions)-1)];

            //If this tweet is longer than 140, go with the shorter version
            if ((strlen($tweet_text) + 23) > 140 ) {
                if ($this->logged_in_user->twitter_username !== 'makerbase') {
                    $tweet_text = "@".$maker->autofill_network_username." Hey, @".
                        $this->logged_in_user->twitter_username.
                        " just added you to ".$product_name.
                        ". ";
                } else {
                    $tweet_text = "@".$maker->autofill_network_username." Hey, we just added you to ".
                        $product_name.". ";
                }
            }
            $ga_content = "mwp"; //maker with product
        } else {
            if ($this->logged_in_user->twitter_username !== 'makerbase') {
                $tweet_versions = array(
                    "@".$maker->autofill_network_username." Hey, @".
                        $this->logged_in_user->twitter_username.
                        " listed you as a maker. What projects have you made? ",
                    "@".$maker->autofill_network_username." Hi there, @".
                        $this->logged_in_user->twitter_username.
                        " just said you're a maker. Fill in your projects & collaborators: ",
                    "@".$maker->autofill_network_username." Hello, @".
                        $this->logged_in_user->twitter_username.
                        " added you as a maker. Now you can list your projects: ",
                    "@".$maker->autofill_network_username." Hey there, @".
                        $this->logged_in_user->twitter_username.
                        " just added you to Makerbase. Tell the world what you made: ",
                    "@".$maker->autofill_network_username." Hi, @".
                        $this->logged_in_user->twitter_username.
                        " just added you as a maker. Check out your new page: ",
                );
            } else {
                $tweet_versions = array(
                    "@".$maker->autofill_network_username." Hey, we just listed you as a maker. ".
                        "What projects have you made? ",
                    "@".$maker->autofill_network_username." Hello, we just added you as a maker. ".
                        "Now you can list your projects: ",
                    "@".$maker->autofill_network_username." Hey there, we just added you to Makerbase. ".
                        "Tell the world what you made: ",
                    "@".$maker->autofill_network_username." Hi, we just added you as a maker. ".
                        "Check out your new page: ",
                );

            }

            $tweet_text = $tweet_versions[rand(0, count($tweet_versions)-1)];

            //If this tweet is longer than 140, go with the shorter version
            if ((strlen($tweet_text) + 23) > 140 ) {
                $tweet_text = "@".$maker->autofill_network_username." Hey, @".
                    $this->logged_in_user->twitter_username.
                    " just listed you as a maker. Check it out: ";
            }
            $ga_content = "mwop"; //maker without product
        }
        $ga_campaign_tags = "?utm_source=Twitter&utm_medium=Social&utm_campaign=New%20maker&utm_content=".$ga_content;

        $tweet_text .= $maker_url.$ga_campaign_tags;
        return $tweet_text;
    }
}