<?php
require_once dirname(__FILE__).'/init.tests.php';

class ActionMySQLDAOTest extends MakerbaseUnitTestCase {

    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

    public function testConstructor() {
        $action_dao = new ActionMySQLDAO();
        $this->assertNotNull($action_dao);
        $this->assertInstanceOf('ActionMySQLDAO', $action_dao);
    }

    public function testInsert() {
        $action_dao = new ActionMySQLDAO();
        $action = new Action();
        $action->ip_address = '192.168.2.1';
        $action->action_type = 'create';
        $action->object_id = 10;
        $action->object_type = 'product';
        $action->severity = Action::SEVERITY_NORMAL;
        $action->user_id = 100;
        $action->is_admin = true;

        $inserted_action = $action_dao->insert($action);
        $this->assertInstanceOf('Action', $inserted_action);
        $this->assertEquals($inserted_action->id, 1);
        $this->assertNotNull($inserted_action->uid);
        $this->assertTrue($inserted_action->is_admin);
    }
}
