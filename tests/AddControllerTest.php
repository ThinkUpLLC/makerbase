<?php
require_once dirname(__FILE__).'/init.tests.php';

class AddControllerTest extends MakerbaseUnitTestCase {

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
        $controller = new AddController(true);
        $this->assertNotNull($controller);
        $this->assertInstanceOf('AddController', $controller);
    }

    public function testAddNotSignedIn() {
        $builders = $this->buildData();
        $controller = new AddController(true);
        $results = $controller->go();
        $this->assertRegexp('/Sign into Makerbase/', $results);
    }

    //TODO testAddSignedInNoParams

    //TODO testAddInvalidObject

    //TODO testAddMakerSignedInValidParams
    //TODO testAddMakerSignedInValidParamsFirstTime
    //TODO testAddMakerSignedInValidParamsWithValidTarget
    //TODO testAddMakerSignedInValidParamsWithInvalidTarget
    //TODO testAddMakerSignedInValidParamsUserFrozen
    //TODO testAddMakerSignedInValidParamsProductFrozen
    //TODO testAddMakerSignedInNoChange
    //TODO testAddMakerSignedInIncompleteParams
    //TODO testAddMakerSignedInMissingName
    //TODO testAddMakerSignedInInvalidSiteURL
    //TODO testAddMakerSignedInInvalidAvatarURL

    //TODO testAddProductSignedInValidParams
    //TODO testAddProductSignedInValidParamsFirstTime
    //TODO testAddProductSignedInValidParamsWithValidTarget
    //TODO testAddProductSignedInValidParamsWithInvalidTarget
    //TODO testAddProductSignedInNoChange
    //TODO testAddProductSignedInValidParamsUserFrozen
    //TODO testAddProductSignedInValidParamsMakerFrozen
    //TODO testAddProductSignedInIncompleteParams
    //TODO testAddProductSignedInMissingName
    //TODO testAddProductSignedInInvalidSiteURL
    //TODO testAddProductSignedInInvalidAvatarURL

    //TODO testAddRoleSignedInValidParams
    //TODO testAddRoleSignedInValidParamsFirstTime
    //TODO testAddRoleSignedInNoChange
    //TODO testAddRoleSignedInValidParamsUserFrozen
    //TODO testAddRoleSignedInValidParamsProductFrozen
    //TODO testAddRoleSignedInValidParamsMakerFrozen
    //TODO testAddRoleSignedInIncompleteParams
    //TODO testAddRoleInvalidStartDate
    //TODO testAddRoleInvalidEndDate
    //TODO testAddRoleStartDateAfterEndDate
    //TODO testAddRoleStartDateEqualsEndDate
    //TODO testAddRoleDescriptionLongerThan255Chars

    //TODO testAddMadeWithSignedIn
    //TODO testAddMadeWithSignedInUserFrozen
    //TODO testAddMadeWithSignedInProductFrozen
}
