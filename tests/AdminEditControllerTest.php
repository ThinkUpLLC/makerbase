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

        $builders[] = FixtureBuilder::build('makers', array('id'=>1, 'uid'=>'asdf', 'slug'=>'giantairnap',
            'name'=>'Mary Jane', 'is_frozen'=>0, 'avatar_url'=>'http://example.com'));
        $builders[] = FixtureBuilder::build('makers', array('id'=>2, 'uid'=>'asdf1', 'slug'=>'anildash',
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

    public function testEditSignedInAsAdminDeleteProduct() {
        //Set up base data
        $builders = $this->buildData();

        //Admin user
        $builders[] = FixtureBuilder::build('users', array('id'=>1001, 'twitter_username'=>'giantairnapadmin',
            'uid'=>'blahad', 'is_frozen'=>0));
        //Products
        $builders[] = FixtureBuilder::build('products', array('id'=>20, 'uid'=>'deleteme', 'slug'=>'DeletableProject',
            'name'=>'Sweet Mary Jane', 'is_frozen'=>0));
        $builders[] = FixtureBuilder::build('products', array('id'=>21, 'uid'=>'dontdel',
            'slug'=>'UnDeletableProject', 'name'=>'Sweet Mary Jane', 'is_frozen'=>0));

        //Roles
        $builders[] = FixtureBuilder::build('roles', array('product_id'=>20, 'maker_id'=>1, 'uid'=>'adfeb'));
        $builders[] = FixtureBuilder::build('roles', array('product_id'=>20, 'maker_id'=>2, 'uid'=>'adfea'));
        $builders[] = FixtureBuilder::build('roles', array('product_id'=>21, 'maker_id'=>1, 'uid'=>'adfec'));

        //Made withs
        $builders[] = FixtureBuilder::build('made_withs', array('product_id'=>20, 'used_product_id'=>1,
            'uid'=>'adfeb'));
        $builders[] = FixtureBuilder::build('made_withs', array('product_id'=>20, 'used_product_id'=>2,
            'uid'=>'adfea'));
        $builders[] = FixtureBuilder::build('made_withs', array('product_id'=>21, 'used_product_id'=>1,
            'uid'=>'adfec'));

        //Actions
        $test_json = '"just some valid test JSON"';
        $builders[] = FixtureBuilder::build('actions', array('uid'=>'delete1', 'object_id'=>20,
            'object_type'=>'Product', 'user_id'=>1001));
        $builders[] = FixtureBuilder::build('actions', array('uid'=>'delete2', 'object2_id'=>20,
            'object2_type'=>'Product', 'user_id'=>1001));
        $builders[] = FixtureBuilder::build('actions', array('uid'=>'nodel2', 'object2_id'=>21,
            'object2_type'=>'Product', 'user_id'=>1001, 'metadata'=>$test_json));

        //Connections
        $builders[] = FixtureBuilder::build('connections', array('object_id'=>20, 'object_type'=>'Product',
            'user_id'=>1001));
        $builders[] = FixtureBuilder::build('connections', array('object_id'=>20, 'object_type'=>'Maker',
            'user_id'=>1001));
        $builders[] = FixtureBuilder::build('connections', array('object_id'=>21, 'object_type'=>'Product',
            'user_id'=>1001));

        //Set admins
        Config::getInstance()->setValue('admins', array('giantairnapadmin'));

        //Log in as admin
        Session::completeLogin('blahad');

        //Set GET and POST params
        // (isset($_GET['object']) && $_GET['object'] == 'product')
        $_GET['object'] = 'product';
        // && isset($_POST['uid'])
        $_POST['uid'] = 'deleteme';
        // && isset($_POST['delete']) && $_POST['delete'] == 1)
        $_POST['delete'] = '1';
        //Edit IP
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';

        //Assert project exists
        $product_dao = new ProductMySQLDAO();
        $product_predelete = $product_dao->get('deleteme');

        $this->assertNotNull($product_predelete);
        $this->assertEquals($product_predelete->slug, 'DeletableProject');

        $controller = new AdminEditController(true);
        $results = $controller->go();

        //Assert controller redirects to landing page post delete
        $this->assertNull($results);
        $this->assertNotNull($controller->redirect_destination);
        $this->assertEquals($controller->redirect_destination, '/');

        //Assert project has been deleted
        try {
            $product = $product_dao->get('deleteme');
            $this->fail('An expected exception has not been raised.');
        } catch (ProductDoesNotExistException $e) {
            $this->assertEquals($e->getMessage(), 'Product deleteme does not exist.');
        }

        //Assert roles have been deleted
        $role_dao = new RoleMySQLDAO();
        //Don't delete role for the other project
        $role = $role_dao->get('adfec');
        $this->assertNotNull($role);
        $role = null;
        //Two roles should have been deleted
        try {
            $role = $role_dao->get('adfeb');
            $this->fail('An expected exception has not been raised.');
        } catch (RoleDoesNotExistException $e) {
            $this->assertEquals($e->getMessage(), 'Role adfeb does not exist.');
        }
        try {
            $role = $role_dao->get('adfea');
            $this->fail('An expected exception has not been raised.');
        } catch (RoleDoesNotExistException $e) {
            $this->assertEquals($e->getMessage(), 'Role adfea does not exist.');
        }

        //Assert actions got deleted
        $action_dao = new ActionMySQLDAO();
        //Don't delete action for the other project
        $action = $action_dao->get('nodel2');
        $this->assertNotNull($action);

        $action = null;
        try {
            $action_dao->get('delete1');
            $this->fail('An expected exception has not been raised.');
        } catch (ActionDoesNotExistException $e) {
            $this->assertEquals($e->getMessage(), 'Action delete1 does not exist.');
        }
        try {
            $action_dao->get('delete2');
            $this->fail('An expected exception has not been raised.');
        } catch (ActionDoesNotExistException $e) {
            $this->assertEquals($e->getMessage(), 'Action delete2 does not exist.');
        }

        //Assert admin action got inserted
        $admin_action = $action_dao->getLastAdminActivityPerformedOnProduct($product_predelete);
        $this->assertEquals($admin_action[0]->is_admin, 1);
        $this->assertEquals($admin_action[0]->action_type, 'delete');
        $this->assertEquals($admin_action[0]->user_id, 1001);

        //Assert connections have been deleted
        $connection_dao = new ConnectionMySQLDAO();
        try {
            $connection = $connection_dao->get(1001, 20, 'Product');
            $this->fail('An expected exception has not been raised.');
        } catch (ConnectionDoesNotExistException $e) {
            $this->assertEquals($e->getMessage(), 'Connection does not exist.');
        }
        $connection = $connection_dao->get(1001, 21, 'Product');
        $this->assertNotNull($connection);

        //Assert madewiths have been deleted
        $made_with_dao = new MadeWithMySQLDAO();
        $made_with = $made_with_dao->getByProductUsedProductID(20, 1);
        $this->assertNull($made_with);
        $made_with = $made_with_dao->getByProductUsedProductID(20, 2);
        $this->assertNull($made_with);
        $made_with = $made_with_dao->getByProductUsedProductID(21, 1);
        $this->assertNotNull($made_with);
    }

    public function testEditSignedInAsAdminDeleteMaker() {
        //Set up base data
        $builders = $this->buildData();

        //Admin user
        $builders[] = FixtureBuilder::build('users', array('id'=>1001, 'twitter_username'=>'giantairnapadmin',
            'uid'=>'blahad', 'is_frozen'=>0));
        //Products
        $builders[] = FixtureBuilder::build('makers', array('id'=>20, 'uid'=>'deleteme', 'slug'=>'DeletableMaker',
            'name'=>'Sweet Mary Jane', 'is_frozen'=>0));
        $builders[] = FixtureBuilder::build('makers', array('id'=>21, 'uid'=>'dontdel',
            'slug'=>'UnDeletableProject', 'name'=>'Sweet Mary Jane', 'is_frozen'=>0));

        //Roles
        $builders[] = FixtureBuilder::build('roles', array('product_id'=>1, 'maker_id'=>20, 'uid'=>'adfeb'));
        $builders[] = FixtureBuilder::build('roles', array('product_id'=>2, 'maker_id'=>20, 'uid'=>'adfea'));
        $builders[] = FixtureBuilder::build('roles', array('product_id'=>1, 'maker_id'=>21, 'uid'=>'adfec'));

        //Actions
        $test_json = '"just some valid test JSON"';
        $builders[] = FixtureBuilder::build('actions', array('uid'=>'delete1', 'object_id'=>20,
            'object_type'=>'Maker', 'user_id'=>1001));
        $builders[] = FixtureBuilder::build('actions', array('uid'=>'delete2', 'object2_id'=>20,
            'object2_type'=>'Maker', 'user_id'=>1001));
        $builders[] = FixtureBuilder::build('actions', array('uid'=>'nodel2', 'object2_id'=>21,
            'object2_type'=>'Maker', 'user_id'=>1001, 'metadata'=>$test_json));

        //Connections
        $builders[] = FixtureBuilder::build('connections', array('object_id'=>20, 'object_type'=>'Maker',
            'user_id'=>1001));
        $builders[] = FixtureBuilder::build('connections', array('object_id'=>20, 'object_type'=>'Product',
            'user_id'=>1001));
        $builders[] = FixtureBuilder::build('connections', array('object_id'=>21, 'object_type'=>'Maker',
            'user_id'=>1001));

        //Set admins
        Config::getInstance()->setValue('admins', array('giantairnapadmin'));

        //Log in as admin
        Session::completeLogin('blahad');

        //Set GET and POST params
        // (isset($_GET['object']) && $_GET['object'] == 'product')
        $_GET['object'] = 'maker';
        // && isset($_POST['uid'])
        $_POST['uid'] = 'deleteme';
        // && isset($_POST['delete']) && $_POST['delete'] == 1)
        $_POST['delete'] = '1';
        //Edit IP
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';

        //Assert project exists
        $maker_dao = new MakerMySQLDAO();
        $maker_predelete = $maker_dao->get('deleteme');

        $this->assertNotNull($maker_predelete);
        $this->assertEquals($maker_predelete->slug, 'DeletableMaker');

        $controller = new AdminEditController(true);
        $results = $controller->go();

        //Assert controller redirects to landing page post delete
        $this->assertNull($results);
        $this->assertNotNull($controller->redirect_destination);
        $this->assertEquals($controller->redirect_destination, '/');

        //Assert project has been deleted
        try {
            $maker = $maker_dao->get('deleteme');
            $this->fail('An expected exception has not been raised.');
        } catch (MakerDoesNotExistException $e) {
            $this->assertEquals($e->getMessage(), 'Maker deleteme does not exist.');
        }

        //Assert roles have been deleted
        $role_dao = new RoleMySQLDAO();
        //Don't delete role for the other project
        $role = $role_dao->get('adfec');
        $this->assertNotNull($role);
        $role = null;
        //Two roles should have been deleted
        try {
            $role = $role_dao->get('adfeb');
            $this->fail('An expected exception has not been raised.');
        } catch (RoleDoesNotExistException $e) {
            $this->assertEquals($e->getMessage(), 'Role adfeb does not exist.');
        }
        try {
            $role = $role_dao->get('adfea');
            $this->fail('An expected exception has not been raised.');
        } catch (RoleDoesNotExistException $e) {
            $this->assertEquals($e->getMessage(), 'Role adfea does not exist.');
        }

        //Assert actions got deleted
        $action_dao = new ActionMySQLDAO();
        //Don't delete action for the other project
        $action = $action_dao->get('nodel2');
        $this->assertNotNull($action);

        $action = null;
        try {
            $action_dao->get('delete1');
            $this->fail('An expected exception has not been raised.');
        } catch (ActionDoesNotExistException $e) {
            $this->assertEquals($e->getMessage(), 'Action delete1 does not exist.');
        }
        try {
            $action_dao->get('delete2');
            $this->fail('An expected exception has not been raised.');
        } catch (ActionDoesNotExistException $e) {
            $this->assertEquals($e->getMessage(), 'Action delete2 does not exist.');
        }

        //Assert admin action got inserted
        $admin_action = $action_dao->getLastAdminActivityPerformedOnMaker($maker_predelete);
        $this->assertEquals($admin_action[0]->is_admin, 1);
        $this->assertEquals($admin_action[0]->action_type, 'delete');
        $this->assertEquals($admin_action[0]->user_id, 1001);

        //Assert connections have been deleted
        $connection_dao = new ConnectionMySQLDAO();
        try {
            $connection = $connection_dao->get(1001, 20, 'Maker');
            $this->fail('An expected exception has not been raised.');
        } catch (ConnectionDoesNotExistException $e) {
            $this->assertEquals($e->getMessage(), 'Connection does not exist.');
        }
        $connection = $connection_dao->get(1001, 21, 'Maker');
        $this->assertNotNull($connection);

        //TODO Assert madewiths have been deleted
    }

    //TODO testEditSignedInAsAdminFreezeProduct
    //TODO testEditSignedInAsAdminFreezeMaker
    //TODO testEditSignedInAsAdminFreezeUser
}
