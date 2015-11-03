<?php
require_once dirname(__FILE__).'/init.tests.php';

class TwitterAPIAccessorTest extends MakerbaseUnitTestCase {

    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

    public function testPostTweet() {
        $cfg = Config::getInstance();
        $oauth_consumer_key = $cfg->getValue('twitter_oauth_notifier_consumer_key');
        $oauth_consumer_secret = $cfg->getValue('twitter_oauth_notifier_consumer_secret');
        $oauth_token = $cfg->getValue('twitter_oauth_notifier_access_token');
        $oauth_token_secret = $cfg->getValue('twitter_oauth_notifier_access_token_secret');

        $twitter_oauth = new TwitterOAuth($oauth_consumer_key, $oauth_consumer_secret, $oauth_token,
            $oauth_token_secret);

        $api_accessor = new TwitterAPIAccessor();
        // This will actually post a tweet; don't do that during routine test runs
        // $results = $api_accessor->postTweet("this is only a test", $twitter_oauth);
        // print_r($results);
    }
}
