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


//Read in the text file
$twitter_names = file(dirname(__FILE__).'/add-to-autofills.txt');

$cfg = Config::getInstance();
$oauth_consumer_key = $cfg->getValue('twitter_oauth_consumer_key');
$oauth_consumer_secret = $cfg->getValue('twitter_oauth_consumer_secret');

$twitter_oauth = new TwitterOAuth($oauth_consumer_key, $oauth_consumer_secret,
    /*Fill these in to run this */'',
    /*Fill these in to run this */'');

$api_accessor = new TwitterAPIAccessor();

//Foreach line
foreach ($twitter_names as $name) {
    try {
        //Get the Twitter user from API
        $twitter_user = $api_accessor->getUser(trim($name), $twitter_oauth);
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    //Output SQL to add to autofills table
    echo "INSERT INTO autofills (network_id, network) VALUES ('".$twitter_user['user_id']."', 'twitter');
";
}
