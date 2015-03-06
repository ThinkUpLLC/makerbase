<?php
require_once dirname(__FILE__).'/init.tests.php';

class UserMySQLDAOTest extends MakerbaseUnitTestCase {

    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

    public function testConstructor() {
        $user_dao = new UserMySQLDAO();
        $this->assertNotNull($user_dao);
        $this->assertInstanceOf('UserMySQLDAO', $user_dao);
    }

    /**
     * @expectedException DuplicateUserException
     */
    public function testInsert() {
        $user_dao = new UserMySQLDAO();
        $user = new User();
        $user->twitter_user_id = '930061';
        $user->name = "Giant Airnap";
        $user->twitter_username = "giantairnap";
        $user->url = 'http://example.com';
        $user->avatar_url = 'http://example.com/avatar.jpg';
        $user->twitter_oauth_access_token = 'token';
        $user->twitter_oauth_access_token_secret = 'secret';
        $result = $user_dao->insert($user);
        $this->assertEquals($result, 1);

        $result = $user_dao->insert($user);
    }

    public function testGetAndUpdateLastLogin() {
        $user_dao = new UserMySQLDAO();
        $user = new User();
        $user->twitter_user_id = '930061';
        $user->name = "Giant Airnap";
        $user->twitter_username = "giantairnap";
        $user->url = 'http://example.com';
        $user->avatar_url = 'http://example.com/avatar.jpg';
        $user->twitter_oauth_access_token = 'token';
        $user->twitter_oauth_access_token_secret = 'secret';
        $result = $user_dao->insert($user);
        $this->assertEquals($result, 1);

        $user = $user_dao->get('930061');
        $this->assertNotNull($user);
        $this->assertInstanceOf('User', $user);

        sleep(1);
        $result = $user_dao->updateLastLogin($user);
        $this->assertEquals($result, 1);
    }
}
