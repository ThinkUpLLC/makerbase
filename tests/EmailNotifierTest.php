<?php
require_once dirname(__FILE__).'/init.tests.php';

class EmailNotifierTest extends MakerbaseUnitTestCase {

    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

    protected function buildData() {
        $builders[] = FixtureBuilder::build('users', array('twitter_username'=>'giantairnap', 'uid'=>'blah',
            'is_frozen'=>0, 'twitter_user_id'=>'1002'));

        $builders[] = FixtureBuilder::build('makers', array('uid'=>'asdf', 'slug'=>'giantairnap',
            'name'=>'Mary Jane', 'is_frozen'=>0, 'avatar_url'=>'http://example.com'));
        $builders[] = FixtureBuilder::build('makers', array('uid'=>'asdf1', 'slug'=>'anildash',
            'name'=>'Sweet Mary Jane', 'is_frozen'=>0));
        return $builders;
    }

    public function testShouldSendMakerChangeEmailNotification() {
        //Build base data
        $builders = $this->buildData();
        //Instantiate DAO
        $maker_dao = new MakerMySQLDAO();
        //Log in as blah
        Session::completeLogin('blah');

        //Instantiate the notifier with the currently logged in user
        $logged_in_user = Session::getLoggedInUser();
        $user_dao = new UserMySQLDAO();
        $user = $user_dao->get($logged_in_user);

        $email_notifier = new EmailNotifier($user);

        //Test maker with no twitter user ID
        $builders[] = FixtureBuilder::build('makers', array('id'=>601, 'uid'=>'asdf3', 'slug'=>'giantairnap',
            'name'=>'Not from Twitter autofill', 'is_frozen'=>0, 'autofill_network'=>null));
        $maker = $maker_dao->get('asdf3');

        $result = $email_notifier->shouldSendMakerChangeEmailNotification($maker);
        $this->assertFalse($result);

        //Test maker with twitter user ID that equals logged in user's id
        $builders[] = FixtureBuilder::build('makers', array('id'=>602, 'uid'=>'asdf4', 'slug'=>'giantairnap',
            'name'=>'Not from Twitter autofill', 'is_frozen'=>0, 'autofill_network'=>'twitter',
            'autofill_network_id'=>'1002'));
        $maker = $maker_dao->getById(602);

        $result = $email_notifier->shouldSendMakerChangeEmailNotification($maker);
        $this->assertFalse($result);

        //Test maker with twitter user ID that does not equal logged in user's id, user doesn't have notifications on
        //User with notifications OFF
        $builders[] = FixtureBuilder::build('users', array('twitter_username'=>'giantairnap', 'uid'=>'blah1',
            'is_frozen'=>0, 'twitter_user_id'=>'1003', 'is_subscribed_to_email_notifications'=>0));

        $builders[] = FixtureBuilder::build('makers', array('id'=>603, 'uid'=>'asdf5', 'slug'=>'giantairnap',
            'name'=>'Not from Twitter autofill', 'is_frozen'=>0, 'autofill_network'=>'twitter',
            'autofill_network_id'=>'1003'));
        $maker = $maker_dao->getById(603);

        $result = $email_notifier->shouldSendMakerChangeEmailNotification($maker);
        $this->assertFalse($result);

        //Test maker with twitter user ID that does not equal logged in user's id, user does have notifications on,
        //never received a notification
        //User with notifications ON, null last_sent
        $builders[] = FixtureBuilder::build('users', array('twitter_username'=>'giantairnap', 'uid'=>'blah2',
            'is_frozen'=>0, 'twitter_user_id'=>'1004', 'is_subscribed_to_email_notifications'=>1,
            'last_email_notification_sent_time'=>null, 'email'=>'email@example.com', 'is_email_verified'=>1));

        $builders[] = FixtureBuilder::build('makers', array('id'=>604, 'uid'=>'asdf6', 'slug'=>'giantairnap',
            'name'=>'Not from Twitter autofill', 'is_frozen'=>0, 'autofill_network'=>'twitter',
            'autofill_network_id'=>'1004'));
        $maker = $maker_dao->getById(604);

        $result = $email_notifier->shouldSendMakerChangeEmailNotification($maker);
        $this->assertTrue($result);

        //Test maker with twitter user ID that does not equal logged in user's id, user does have notifications on,
        //received a notification 6 hours ago
        //User with notifications ON, last_sent 6 hours ago
        $builders[] = FixtureBuilder::build('users', array('twitter_username'=>'giantairnap', 'uid'=>'blah3',
            'is_frozen'=>0, 'twitter_user_id'=>'1005', 'is_subscribed_to_email_notifications'=>1,
            'last_email_notification_sent_time'=>'-6h'));

        $builders[] = FixtureBuilder::build('makers', array('id'=>605, 'uid'=>'asdf7', 'slug'=>'giantairnap',
            'name'=>'Not from Twitter autofill', 'is_frozen'=>0, 'autofill_network'=>'twitter',
            'autofill_network_id'=>'1005'));
        $maker = $maker_dao->getById(605);

        $result = $email_notifier->shouldSendMakerChangeEmailNotification($maker);
        $this->assertFalse($result);

        //Test maker with twitter user ID that does not equal logged in user's id, user does have notifications on,
        //received a notification 48 hours ago
        //User with notifications ON, last_sent 48 hours ago
        $builders[] = FixtureBuilder::build('users', array('twitter_username'=>'giantairnap', 'uid'=>'blah4',
            'is_frozen'=>0, 'twitter_user_id'=>'1006', 'is_subscribed_to_email_notifications'=>1,
            'last_email_notification_sent_time'=>'-48h', 'email'=>'email@example.com', 'is_email_verified'=>1));

        $builders[] = FixtureBuilder::build('makers', array('id'=>606, 'uid'=>'asdf8', 'slug'=>'giantairnap',
            'name'=>'Not from Twitter autofill', 'is_frozen'=>0, 'autofill_network'=>'twitter',
            'autofill_network_id'=>'1006'));
        $maker = $maker_dao->getById(606);

        $result = $email_notifier->shouldSendMakerChangeEmailNotification($maker);
        $this->assertTrue($result);

        //Test maker with twitter user ID that does not equal logged in user's id, user does have notifications on,
        //never received a notification, email is not verified
        //User with notifications ON, null last_sent
        $builders[] = FixtureBuilder::build('users', array('twitter_username'=>'giantairnap', 'uid'=>'blah5',
            'is_frozen'=>0, 'twitter_user_id'=>'1007', 'is_subscribed_to_email_notifications'=>1,
            'last_email_notification_sent_time'=>null, 'email'=>'email@example.com', 'is_email_verified'=>0));

        $builders[] = FixtureBuilder::build('makers', array('id'=>607, 'uid'=>'asdf9', 'slug'=>'giantairnap',
            'name'=>'Not from Twitter autofill', 'is_frozen'=>0, 'autofill_network'=>'twitter',
            'autofill_network_id'=>'1007'));
        $maker = $maker_dao->getById(607);

        $result = $email_notifier->shouldSendMakerChangeEmailNotification($maker);
        $this->assertFalse($result);

        //Test maker with twitter user ID that does not equal logged in user's id, user does have notifications on,
        //never received a notification, email is not set
        //User with notifications ON, null last_sent
        $builders[] = FixtureBuilder::build('users', array('twitter_username'=>'giantairnap', 'uid'=>'blah6',
            'is_frozen'=>0, 'twitter_user_id'=>'1008', 'is_subscribed_to_email_notifications'=>1,
            'last_email_notification_sent_time'=>null, 'email'=>null, 'is_email_verified'=>0));

        $builders[] = FixtureBuilder::build('makers', array('id'=>608, 'uid'=>'asdf10', 'slug'=>'giantairnap',
            'name'=>'Not from Twitter autofill', 'is_frozen'=>0, 'autofill_network'=>'twitter',
            'autofill_network_id'=>'1008'));
        $maker = $maker_dao->getById(608);

        $result = $email_notifier->shouldSendMakerChangeEmailNotification($maker);
        $this->assertFalse($result);
    }
}
