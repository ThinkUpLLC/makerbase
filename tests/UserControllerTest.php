<?php
require_once dirname(__FILE__).'/init.tests.php';

class UserControllerTest extends MakerbaseUnitTestCase {

    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

    protected function buildData() {
        $builders[] = FixtureBuilder::build('users', array('twitter_username'=>'giantairnap', 'uid'=>'blah',
            'is_frozen'=>0));

        // $builders[] = FixtureBuilder::build('makers', array('uid'=>'asdf', 'slug'=>'giantairnap',
        //     'name'=>'Mary Jane', 'is_frozen'=>0, 'avatar_url'=>'http://example.com'));
        // $builders[] = FixtureBuilder::build('makers', array('uid'=>'asdf1', 'slug'=>'anildash',
        //     'name'=>'Sweet Mary Jane', 'is_frozen'=>0));
        return $builders;
    }

    public function testConstructor() {
        $controller = new UserController(true);
        $this->assertNotNull($controller);
        $this->assertInstanceOf('UserController', $controller);
    }

    //TODO testNotLoggedInUserDoesntExist
    //TODO testNotLoggedInUserExists
    //TODO testLoggedInUserExistsNotYou
    //TODO testLoggedInUserExistsItsYou
    //TODO testLoggedInUserExistsItsYouNoEmailSubmitted
    //TODO testLoggedInUserExistsItsYouEmailSubmittedAwaitingConfirmation
    //TODO testLoggedInUserExistsItsYouEmailConfirmed
    //TODO testLoggedInAsAdmin

}
