<?php
//Move this to the webapp folder to run it

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

class FillInWaitlistersController extends MakerbaseController {

    public function control() {

        //Get 20 waitlisters who have 0 followers
        $waitlist_dao = new WaitlistMySQLDAO();
        $waitlisters = $waitlist_dao->getWaitlistersWithZeroFollowers(20);
        //For each waitlister, request their profile from Twitter and update their waitlist record
        $cfg = Config::getInstance();
        $oauth_consumer_key = $cfg->getValue('twitter_oauth_consumer_key');
        $oauth_consumer_secret = $cfg->getValue('twitter_oauth_consumer_secret');

        $twitter_oauth = new TwitterOAuth($oauth_consumer_key, $oauth_consumer_secret,
            /* fill these in to use this */ '',
            /* fill these in to use this */ '');

        $api_accessor = new TwitterAPIAccessor();

        foreach ($waitlisters as $waitlister) {
            try {
                $network_id = trim($waitlister['network_id']);
                $network = trim($waitlister['network']);

                //Get the Twitter user from API
                $twitter_user = $api_accessor->getUser($network_id, $twitter_oauth);

                $follower_count = $twitter_user['follower_count'];
                $update_count = $waitlist_dao->setFollowerCount($network_id, 'twitter', $follower_count);
                if ($update_count > 0) {
                    echo "Updated ".$twitter_user['user_name']."
";
                } else {
                    echo "Didn't update ".$twitter_user['user_name']."
";
                }
                $update_count = 0;
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }
}

$controller = new FillInWaitlistersController();
echo $controller->control();

