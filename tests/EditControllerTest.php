<?php
require_once dirname(__FILE__).'/init.tests.php';

class EditControllerTest extends MakerbaseUnitTestCase {

    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

    protected function buildData() {
        $builders[] = FixtureBuilder::build('users', array('twitter_username'=>'giantairnap', 'uid'=>'blah',
            'is_frozen'=>0));

        $builders[] = FixtureBuilder::build('makers', array('uid'=>'asdf', 'slug'=>'giantairnap',
            'name'=>'Mary Jane', 'is_frozen'=>0, 'avatar_url'=>'http://example.com'));
        $builders[] = FixtureBuilder::build('makers', array('uid'=>'asdf1', 'slug'=>'anildash',
            'name'=>'Sweet Mary Jane', 'is_frozen'=>0));
        return $builders;
    }

    public function testConstructor() {
        $controller = new EditController(true);
        $this->assertNotNull($controller);
        $this->assertInstanceOf('EditController', $controller);
    }

    public function testEditNotSignedIn() {
        $builders = $this->buildData();
        $controller = new EditController(true);
        $results = $controller->go();
        $this->assertRegexp('/Sign into Makerbase/', $results);
    }

    public function testEditSignedInNoParams() {
        $builders = $this->buildData();
        Session::completeLogin('blah');
        $controller = new EditController(true);
        $results = $controller->go();
        //No params submitted, so controller will redirect to landing page with no changes
        $this->assertNull($results);
        $this->assertNotNull($controller->redirect_destination);
    }

    public function testEditMakerSignedIn() {
        $builders = $this->buildData();
        Session::completeLogin('blah');

        //Set edit maker params
        // (isset($_GET['object']) && $_GET['object'] == 'maker')
        $_GET['object'] = 'maker';
        // && isset($_POST['maker_uid'])
        $_POST['maker_uid'] = 'asdf';
        // && isset($_POST['maker_slug'])
        $_POST['maker_slug'] = 'giantairnap';
        // && isset($_POST['name'])
        $_POST['name'] = 'Giant Airnap Edited';
        // && isset($_POST['url'])
        $_POST['url'] = 'http://ginatrapani.org';
        // && isset($_POST['avatar_url'])
        $_POST['avatar_url'] = 'http://ginatrapani.org/headshot.png';
        //Edit IP
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';

        $controller = new EditController(true);
        $results = $controller->go();
        //Controller redirects to maker page
        $this->assertNull($results);
        $this->assertNotNull($controller->redirect_destination);
        $this->assertEquals($controller->redirect_destination, '/m/asdf/giantairnap');
        //Maker has been updated
        $maker_dao = new MakerMySQLDAO();
        $maker = $maker_dao->get('asdf');
        $this->assertEquals($maker->name, 'Giant Airnap Edited');
        $this->assertEquals($maker->avatar_url, 'http://ginatrapani.org/headshot.png');
        //TODO Assert connection
        //TODO Assert action
        //TODO Assert user message
    }

    public function testEditMakerSignedInUserFrozen() {
        $builders = $this->buildData();
        $builders[] = FixtureBuilder::build('users', array('twitter_username'=>'frozen', 'uid'=>'blahfroz',
            'is_frozen'=>1));

        Session::completeLogin('blahfroz');

        //Set edit maker params
        // (isset($_GET['object']) && $_GET['object'] == 'maker')
        $_GET['object'] = 'maker';
        // && isset($_POST['maker_uid'])
        $_POST['maker_uid'] = 'asdf';
        // && isset($_POST['maker_slug'])
        $_POST['maker_slug'] = 'giantairnap';
        // && isset($_POST['name'])
        $_POST['name'] = 'Giant Airnap Edited';
        // && isset($_POST['url'])
        $_POST['url'] = 'http://ginatrapani.org';
        // && isset($_POST['avatar_url'])
        $_POST['avatar_url'] = 'http://ginatrapani.org/headshot.png';
        //Edit IP
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';

        $controller = new EditController(true);
        $results = $controller->go();
        //Controller redirects to maker page
        $this->assertNull($results);
        $this->assertNotNull($controller->redirect_destination);
        $this->assertEquals($controller->redirect_destination, '/m/asdf/giantairnap');
        //Maker has NOT been updated
        $maker_dao = new MakerMySQLDAO();
        $maker = $maker_dao->get('asdf');
        $this->assertNotEquals($maker->name, 'Giant Airnap Edited');
        $this->assertNotEquals($maker->avatar_url, 'http://ginatrapani.org/headshot.png');
        //TODO Assert no connection
        //TODO Assert no action
        //TODO Assert user message
    }

    public function testEditMakerSignedInMakerFrozen() {
        $builders = $this->buildData();
        $builders[] = FixtureBuilder::build('makers', array('uid'=>'froze', 'slug'=>'giantairnap',
            'name'=>'Mary Jane', 'is_frozen'=>1, 'avatar_url'=>'http://example.com'));

        Session::completeLogin('blah');

        //Set edit maker params
        // (isset($_GET['object']) && $_GET['object'] == 'maker')
        $_GET['object'] = 'maker';
        // && isset($_POST['maker_uid'])
        $_POST['maker_uid'] = 'froze';
        // && isset($_POST['maker_slug'])
        $_POST['maker_slug'] = 'giantairnap';
        // && isset($_POST['name'])
        $_POST['name'] = 'Giant Airnap Edited';
        // && isset($_POST['url'])
        $_POST['url'] = 'http://ginatrapani.org';
        // && isset($_POST['avatar_url'])
        $_POST['avatar_url'] = 'http://ginatrapani.org/headshot.png';
        //Edit IP
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';

        $controller = new EditController(true);
        $results = $controller->go();
        //Controller redirects to maker page
        $this->assertNull($results);
        $this->assertNotNull($controller->redirect_destination);
        $this->assertEquals($controller->redirect_destination, '/m/froze/giantairnap');
        //Maker has NOT been updated
        $maker_dao = new MakerMySQLDAO();
        $maker = $maker_dao->get('asdf');
        $this->assertNotEquals($maker->name, 'Giant Airnap Edited');
        $this->assertNotEquals($maker->avatar_url, 'http://ginatrapani.org/headshot.png');
        //TODO Assert no connection
        //TODO Assert no action
        //TODO Assert user message
    }
    //TODO testEditProductSignedIn
    //TODO testEditProductSignedInUserFrozen
    //TODO testEditProductSignedInProductFrozen

    //TODO testEditRoleSignedIn
    //TODO testEditRoleSignedInUserFrozen
    //TODO testEditRoleSignedInProductFrozen
    //TODO testEditRoleSignedInMakerFrozen

    //TODO testArchiveMakerSignedIn
    //TODO testArchiveMakerSignedInUserFrozen
    //TODO testArchiveMakerSignedInProductFrozen

    //TODO testArchiveProductSignedIn
    //TODO testArchiveProductSignedInUserFrozen
    //TODO testArchiveProductSignedInProductFrozen

    //TODO testArchiveRoleSignedIn
    //TODO testArchiveRoleSignedInUserFrozen
    //TODO testArchiveRoleSignedInProductFrozen
    //TODO testArchiveRoleSignedInMakerFrozen

    //TODO testArchiveMadeWithSignedIn
    //TODO testArchiveMadeWithSignedInUserFrozen
    //TODO testArchiveMadeWithSignedInProductFrozen
}
