<?php
require_once dirname(__FILE__).'/init.tests.php';

class SentTweetMySQLDAOTest extends MakerbaseUnitTestCase {

    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

    public function testConstructor() {
        $dao = new SentTweetMySQLDAO();
        $this->assertNotNull($dao);
        $this->assertInstanceOf('SentTweetMySQLDAO', $dao);
    }

    public function testInsert() {
        $dao = new SentTweetMySQLDAO();
        $result = $dao->insert(100, '100');
        $this->assertEquals($result, 1);

        $result1 = $dao->hasBeenSent(100);
        $this->assertTrue($result1);

        $result2 = $dao->hasBeenSent(101);
        $this->assertFalse($result2);

        $result3 = $dao->insert(100, '100');
        $this->assertFalse($result3);
    }
}
