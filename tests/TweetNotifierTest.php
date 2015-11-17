<?php
require_once dirname(__FILE__).'/init.tests.php';

class TweetNotifierTest extends MakerbaseUnitTestCase {

    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

    public function testConstructor() {
        $tweet_notifier = new TweetNotifier(null);
        $this->assertNotNull($tweet_notifier);
        $this->assertInstanceOf('TweetNotifier', $tweet_notifier);
    }

    public function testGetNewMakerTweetText() {
        $maker = new Maker();

        $maker->autofill_network_username = '123456789012345';
        $maker->slug = 'asdf';
        $maker->uid = 'asdf';

        $user = new User();
        $user->twitter_username = 'sometwitteruser';

        $tweet_notifier = new TweetNotifier($user);

        $product = null;
        $i = 0;
        while ($i<10) {
            $tweet = $tweet_notifier->getNewMakerTweetText($maker, $product);
//             echo $tweet ."
// ";
            //Asserting less than 250 instead of 140 b/c URLs get shortened to 23 chars
            $this->assertTrue(strlen($tweet) < 250);
            $i++;
        }

        $product = new Product();
        $product->name = "This Is a Really Long Product Name I Mean Really Really LOOOOONNNG";
        $i = 0;
        while ($i<10) {
            $tweet = $tweet_notifier->getNewMakerTweetText($maker, $product);
//             echo $tweet ."
// ";
            //Asserting less than 250 instead of 140 b/c URLs get shortened to 23 chars
            $this->assertTrue(strlen($tweet) < 250);
            $i++;
        }

        $product->name = "Short Project Name";
        $i = 0;
        while ($i<10) {
            $tweet = $tweet_notifier->getNewMakerTweetText($maker, $product);
//             echo $tweet ."
// ";
            //Asserting less than 250 instead of 140 b/c URLs get shortened to 23 chars
            $this->assertTrue(strlen($tweet) < 250);
            $i++;
        }

        // Repeat tests, now from @makerbase Twitter handle
        $i = 0;
        $product = null;
        $user->twitter_username = 'makerbase';
        while ($i<10) {
            $tweet = $tweet_notifier->getNewMakerTweetText($maker, $product);
//             echo $tweet ."
// ";
            //Asserting less than 250 instead of 140 b/c URLs get shortened to 23 chars
            $this->assertTrue(strlen($tweet) < 250);
            $i++;
        }

        $product = new Product();
        $product->name = "This Is a Really Long Product Name I Mean Really Really LOOOOONNNG";
        $i = 0;
        while ($i<10) {
            $tweet = $tweet_notifier->getNewMakerTweetText($maker, $product);
//             echo $tweet ."
// ";
            //Asserting less than 250 instead of 140 b/c URLs get shortened to 23 chars
            $this->assertTrue(strlen($tweet) < 250);
            $i++;
        }

        $product->name = "Short Project Name";
        $i = 0;
        while ($i<10) {
            $tweet = $tweet_notifier->getNewMakerTweetText($maker, $product);
//             echo $tweet ."
// ";
            //Asserting less than 250 instead of 140 b/c URLs get shortened to 23 chars
            $this->assertTrue(strlen($tweet) < 250);
            $i++;
        }
    }
}
