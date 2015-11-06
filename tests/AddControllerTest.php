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

    public function testGetNewMakerTweetText() {
        $maker = new Maker();
        $maker->autofill_network_username = '123456789012345';
        $maker->slug = 'asdf';
        $maker->uid = 'asdf';

        $user = new User();
        $user->twitter_username = 'sometwitteruser';

        $product = null;
        $i = 0;
        while ($i<10) {
            $tweet = AddController::getNewMakerTweetText($maker, $user, $product);
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
            $tweet = AddController::getNewMakerTweetText($maker, $user, $product);
//             echo $tweet ."
// ";
            //Asserting less than 250 instead of 140 b/c URLs get shortened to 23 chars
            $this->assertTrue(strlen($tweet) < 250);
            $i++;
        }

        $product->name = "Short Project Name";
        $i = 0;
        while ($i<10) {
            $tweet = AddController::getNewMakerTweetText($maker, $user, $product);
//             echo $tweet ."
// ";
            //Asserting less than 250 instead of 140 b/c URLs get shortened to 23 chars
            $this->assertTrue(strlen($tweet) < 250);
            $i++;
        }
    }

    public function testAddNotSignedIn() {
        $builders = $this->buildData();
        $controller = new AddController(true);
        $results = $controller->go();
        $this->assertRegexp('/Sign into Makerbase/', $results);
    }

    public function testAddInspirationSignedInAsMaker() {
        $builders = $this->buildData();
        $builders[] = FixtureBuilder::build('users', array('twitter_username'=>'airnapgiant', 'uid'=>'la1i',
            'is_frozen'=>0, 'twitter_user_id'=>'1001'));
        $builders[] = FixtureBuilder::build('makers', array('uid'=>'123', 'autofill_network_id'=>'1001',
            'autofill_network'=>'twitter'));
        $builders[] = FixtureBuilder::build('makers', array('uid'=>'124', 'autofill_network_id'=>'1004',
            'autofill_network'=>'twitter'));

        Session::completeLogin('la1i');

        $_GET['object'] = 'inspiration';
        $_POST['maker_uid'] = '124';
        $_POST['inspiration_description'] = "heylo";
        $_POST['originate_uid'] = '123';
        $_POST['originate_slug'] = 'slug';

        $controller = new AddController(true);
        $results = $controller->go();
        //print_r($controller);
        //sleep(1000);
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
