<?php
require_once dirname(__FILE__).'/init.tests.php';

class MadeWithMySQLDAOTest extends MakerbaseUnitTestCase {

    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

    public function testConstructor() {
        $madewith_dao = new MadeWithMySQLDAO();
        $this->assertNotNull($madewith_dao);
        $this->assertInstanceOf('MadeWithMySQLDAO', $madewith_dao);
    }

    public function testInsert() {
        $madewith_dao = new MadeWithMySQLDAO();
        $madewith = new MadeWith();
        $madewith->product_id = 1;
        $madewith->used_product_id = 10;

        $result = $madewith_dao->insert($madewith);
        $this->assertInstanceOf('MadeWith', $result);
        $this->assertEquals($result->id, 1);
    }

    public function testArchiveGetUnarchive() {
        $madewith_dao = new MadeWithMySQLDAO();
        $madewith = new MadeWith();
        $madewith->product_id = 1;
        $madewith->used_product_id = 10;

        $inserted_madewith = $madewith_dao->insert($madewith);
        $this->assertFalse($inserted_madewith->is_archived);

        //Archive
        $madewith_dao->archive($inserted_madewith->uid);
        $archived_madewith = $madewith_dao->get($inserted_madewith->uid);
        $this->assertTrue($archived_madewith->is_archived);

        //Unarchive
        $madewith_dao->unarchive($inserted_madewith->uid);
        $unarchived_madewith = $madewith_dao->get($inserted_madewith->uid);
        $this->assertFalse($unarchived_madewith->is_archived);
    }

    public function testGetByProduct() {
        $builders = array();
        $builders[] = FixtureBuilder::build('products', array('id'=>1, 'uid'=>'asdf', 'slug'=>'user'));
        $builders[] = FixtureBuilder::build('products', array('id'=>2, 'uid'=>'bsdf', 'slug'=>'usee'));
        $builders[] = FixtureBuilder::build('products', array('id'=>3, 'uid'=>'csdf', 'slug'=>'usee'));
        $builders[] = FixtureBuilder::build('products', array('id'=>4, 'uid'=>'dsdf', 'slug'=>'usee'));
        $builders[] = FixtureBuilder::build('products', array('id'=>5, 'uid'=>'esdf', 'slug'=>'usee'));
        $builders[] = FixtureBuilder::build('products', array('id'=>6, 'uid'=>'fsdf', 'slug'=>'usee'));
        $builders[] = FixtureBuilder::build('made_withs', array('id'=>1, 'uid'=>'asdf', 'product_id'=>1,
            'used_product_id'=>2));
        $builders[] = FixtureBuilder::build('made_withs', array('id'=>2, 'uid'=>'bsdf', 'product_id'=>1,
            'used_product_id'=>3));
        $builders[] = FixtureBuilder::build('made_withs', array('id'=>3, 'uid'=>'csdf', 'product_id'=>1,
            'used_product_id'=>4));

        $product_dao = new ProductMySQLDAO();
        $product = $product_dao->get('asdf');

        $madewith_dao = new MadeWithMySQLDAO();
        $madewiths_by_product = $madewith_dao->getByProduct($product);
        $this->assertEquals(count($madewiths_by_product), 3); //Return 3 max for now for v1 sponsor launch
        $this->assertInstanceOf('Product', $madewiths_by_product[0]->product);
        $this->assertInstanceOf('Product', $madewiths_by_product[0]->used_product);

        $this->assertEquals($madewiths_by_product[0]->product->slug, 'user');
        $this->assertEquals($madewiths_by_product[0]->used_product->slug, 'usee');
    }

    public function testGetByProductUsedProductID() {
        $builders = array();
        $builders[] = FixtureBuilder::build('products', array('id'=>1, 'uid'=>'asdf', 'slug'=>'user'));
        $builders[] = FixtureBuilder::build('products', array('id'=>2, 'uid'=>'bsdf', 'slug'=>'usee'));
        $builders[] = FixtureBuilder::build('products', array('id'=>3, 'uid'=>'csdf', 'slug'=>'usee'));
        $builders[] = FixtureBuilder::build('products', array('id'=>4, 'uid'=>'dsdf', 'slug'=>'usee'));
        $builders[] = FixtureBuilder::build('products', array('id'=>5, 'uid'=>'esdf', 'slug'=>'usee'));
        $builders[] = FixtureBuilder::build('products', array('id'=>6, 'uid'=>'fsdf', 'slug'=>'usee'));
        $builders[] = FixtureBuilder::build('made_withs', array('id'=>1, 'uid'=>'asdf', 'product_id'=>1,
            'used_product_id'=>2));
        $builders[] = FixtureBuilder::build('made_withs', array('id'=>2, 'uid'=>'bsdf', 'product_id'=>1,
            'used_product_id'=>3));
        $builders[] = FixtureBuilder::build('made_withs', array('id'=>3, 'uid'=>'csdf', 'product_id'=>1,
            'used_product_id'=>4));

        $madewith_dao = new MadeWithMySQLDAO();
        $madewith = $madewith_dao->getByProductUsedProductID(1,3);
        $this->assertInstanceOf('MadeWith', $madewith);
        $this->assertEquals($madewith->product_id, 1);
        $this->assertEquals($madewith->used_product_id, 3);
    }
}
