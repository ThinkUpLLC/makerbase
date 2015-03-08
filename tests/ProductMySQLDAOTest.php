<?php
require_once dirname(__FILE__).'/init.tests.php';

class ProductMySQLDAOTest extends MakerbaseUnitTestCase {

    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

    public function testConstructor() {
        $product_dao = new ProductMySQLDAO();
        $this->assertNotNull($product_dao);
        $this->assertInstanceOf('ProductMySQLDAO', $product_dao);
    }

    /**
     * @expectedException DuplicateProductException
     */
    public function testInsert() {
        $product = new Product();
        $product->name = 'Basecamp';
        $product->slug = 'basecamp';
        $product->url = 'http://basecamp.com';
        $product->description = "Basecamp helps you wrangle people with different roles, responsibilities, ".
            "and objectives toward a common goal";
        $product->avatar_url = 'https://pbs.twimg.com/profile_images/431920925563289600/EVCDdTmr.png';

        $product_dao = new ProductMySQLDAO();
        $result = $product_dao->insert($product);
        $this->assertEquals(1, $result);

        $result = $product_dao->insert($product);
        $this->assertEquals(1, $result);
    }
}
