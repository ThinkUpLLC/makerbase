<?php
require_once dirname(__FILE__).'/init.tests.php';

class ConnectionMySQLDAOTest extends MakerbaseUnitTestCase {

    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

    public function testConstructor() {
        $connection_dao = new ConnectionMySQLDAO();
        $this->assertNotNull($connection_dao);
        $this->assertInstanceOf('ConnectionMySQLDAO', $connection_dao);
    }

    public function testInsert() {
        $connection_dao = new ConnectionMySQLDAO();

        $user = new User();
        $user->id = 10;

        $product = new Product();
        $product->id = 432;

        $result = $connection_dao->insert($user, $product);
        $this->assertTrue($result);

        $result = $connection_dao->insert($user, $product);
        $this->assertFalse($result);

        //@TODO Use raw SQL to select the new connection and assert its values
    }
}
