<?php
require_once dirname(__FILE__).'/init.tests.php';

class MakerMySQLDAOTest extends MakerbaseUnitTestCase {

    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

    protected function buildData() {
        $builders[] = FixtureBuilder::build('makers', array('uid'=>'asdf', 'slug'=>'giantairnap',
            'name'=>'Mary Jane'));
        $builders[] = FixtureBuilder::build('makers', array('uid'=>'asdf1', 'slug'=>'anildash',
            'name'=>'Sweet Mary Jane'));
        return $builders;
    }

    public function testConstructor() {
        $maker_dao = new MakerMySQLDAO();
        $this->assertNotNull($maker_dao);
        $this->assertInstanceOf('MakerMySQLDAO', $maker_dao);
    }

    public function testGetMaker() {
        $builders = $this->buildData();
        //sleep(1000);
        $maker_dao = new MakerMySQLDAO();
        $maker = $maker_dao->get('asdf');
        $this->assertNotNull($maker);
        $this->assertEquals('giantairnap', $maker->slug);
    }

    public function testInsert() {
        $maker = new Maker();
        $maker->name = 'David Carr';
        $maker->slug = 'carr2n';
        $maker->url = 'http://nytimes.com';
        $maker->avatar_url = 'http://example.com/avatar.jpg';

        $maker_dao = new MakerMySQLDAO();
        $result = $maker_dao->insert($maker);
        $this->assertInstanceOf('Maker', $result);
        $this->assertEquals($result->id, 1);
    }
}
