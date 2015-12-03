<?php
//Move this to the webapp folder to run it
chdir('../..');
date_default_timezone_set('America/New_York');

require_once 'extlibs/isosceles/libs/class.Loader.php';
Loader::register(array(
'libs/',
'libs/model/',
'libs/controller/',
'libs/dao/',
'libs/exceptions/',
));
Loader::addSpecialClass('TwitterOAuth', Config::getInstance()->getValue('source_root_path').
    'webapp/extlibs/twitteroauth/twitteroauth.php');

class NotifyWaitlistersController extends MakerbaseController {

    public function control() {
        //Get a handful of waitlisters_to_notify who haven't had a notification sent
        $waitlist_dao = new WaitlistMySQLDAO();
        $waitlisters = $waitlist_dao->getWaitlistersToNotify(20);

        /**
         * For each waitlister:
         * 3a. Check if the person is a user or not. If not:
         *    3a1. send tweet
         *    3a2. insert into sent_tweets
         * 3b. set is_notif_sent = 1
         */
        $cfg = Config::getInstance();
        $oauth_consumer_key = $cfg->getValue('twitter_oauth_notifier_consumer_key');

        $oauth_consumer_secret = $cfg->getValue('twitter_oauth_notifier_consumer_secret');
        $oauth_token = $cfg->getValue('twitter_oauth_notifier_access_token');
        $oauth_token_secret = $cfg->getValue('twitter_oauth_notifier_access_token_secret');

        $twitter_oauth = new TwitterOAuth($oauth_consumer_key, $oauth_consumer_secret, $oauth_token,
            $oauth_token_secret);

        $api_accessor = new TwitterAPIAccessor();
        $user_dao = new UserMySQLDAO();
        $sent_tweet_dao = new SentTweetMySQLDAO();
        $tweet_notifier = new TweetNotifier();

        foreach ($waitlisters as $waitlister) {
            try {
                $user = $user_dao->getByTwitterUserId($waitlister['twitter_user_id']);
                $waitlist_dao->setIsNotifSent($waitlister['twitter_user_id']);
            } catch (UserDoesNotExistException $e) {
                $tweet_text = $tweet_notifier->getWaitlistNotificationTweetText($waitlister['twitter_user_name'],
                    $waitlister['month_joined']);
                $tweet_result = $api_accessor->postTweet($tweet_text, $twitter_oauth);
                if ($tweet_result[0] == '200') {
                    $waitlist_dao->setIsNotifSent($waitlister['twitter_user_id']);
                    $sent_tweet_dao->insert($waitlister['twitter_user_id'], $waitlister['twitter_user_name']);
                } else {
                    print_r($tweet_result);
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }
}

$controller = new NotifyWaitlistersController();
echo $controller->control();

