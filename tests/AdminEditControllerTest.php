<?php
require_once dirname(__FILE__).'/init.tests.php';

class AdminEditControllerTest extends MakerbaseUnitTestCase {

    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

    protected function buildData() {
        $builders[] = FixtureBuilder::build('users', array('twitter_username'=>'giantairnap', 'uid'=>'blah',
            'is_frozen'=>0, 'is_admin'=>'0'));

        $builders[] = FixtureBuilder::build('makers', array('uid'=>'asdf', 'slug'=>'giantairnap',
            'name'=>'Mary Jane', 'is_frozen'=>0, 'avatar_url'=>'http://example.com'));
        $builders[] = FixtureBuilder::build('makers', array('uid'=>'asdf1', 'slug'=>'anildash',
            'name'=>'Sweet Mary Jane', 'is_frozen'=>0));
        return $builders;
    }

    public function testConstructor() {
        $controller = new AdminEditController(true);
        $this->assertNotNull($controller);
        $this->assertInstanceOf('AdminEditController', $controller);
    }

    public function testEditNotSignedIn() {
        $builders = $this->buildData();
        $controller = new AdminEditController(true);
        $results = $controller->go();
        $this->assertRegexp('/Sign into Makerbase/', $results);
    }

    public function testEditSignedInNotAdmin() {
        $builders = $this->buildData();
        Session::completeLogin('blah');
        $controller = new AdminEditController(true);
        $results = $controller->go();
        //Not an admin, so display nothing to see here/move it along messaging
        $this->assertNotNull($results);
        $this->assertRegexp('/Move it along/', $results);
    }

    public function testEditSignedInAsAdminNoParams() {
        $builders = $this->buildData();
        $builders[] = FixtureBuilder::build('users', array('twitter_username'=>'giantairnapadmin', 'uid'=>'blahad',
            'is_frozen'=>0));

        Config::getInstance()->setValue('admins', array('giantairnapadmin'));

        Session::completeLogin('blahad');
        $controller = new AdminEditController(true);
        $results = $controller->go();

        //No params submitted, so controller will redirect to landing page with no changes
        $this->assertNull($results);
        $this->assertNotNull($controller->redirect_destination);
        $this->assertEquals($controller->redirect_destination, '/');
    }
}
