<?php
require_once dirname(__FILE__).'/init.tests.php';

class InspirationMySQLDAOTest extends MakerbaseUnitTestCase {

    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

    public function testConstructor() {
        $dao = new InspirationMySQLDAO();
        $this->assertNotNull($dao);
        $this->assertInstanceOf('InspirationMySQLDAO', $dao);
    }

    public function testInsert() {
        $maker = new Maker();
        $maker->name = 'Gina T';
        $maker->id = 1;

        $inspirer_maker = new Maker();
        $inspirer_maker->name = 'David Carr';
        $inspirer_maker->id = 2;

        $inspiration = new Inspiration();
        $inspiration->maker_id = $maker->id;
        $inspiration->inspirer_maker_id = $inspirer_maker->id;
        $inspiration->description = "Wrote his heart out";

        $dao = new InspirationMySQLDAO();
        $result = $dao->insert($inspiration);
        $this->assertInstanceOf("Inspiration", $result);
        $this->assertEquals($result->id, 1);
    }

    /**
     * @expectedException DuplicateInspirationException
     */
    public function testInsertDuplicate() {
        $builders[] = FixtureBuilder::build('inspirations', array('uid'=>'asdf', 'maker_id'=>10,
            'inspirer_maker_id'=>13));

        $maker = new Maker();
        $maker->id = 10;

        $inspirer = new Maker();
        $inspirer->id = 13;

        $inspiration = new Inspiration();
        $inspiration->maker_id = $maker->id;
        $inspiration->inspirer_maker_id = $inspirer->id;
        $inspiration->description = "HELLO";

        $dao = new InspirationMySQLDAO();
        $result = $dao->insert($inspiration);
        $this->assertInstanceOf("Inspiration", $result);
    }

    public function testEdit() {
        $builders[] = FixtureBuilder::build('inspirations', array('uid'=>'asdf', 'maker_id'=>10,
            'inspirer_maker_id'=>13));

        $dao = new InspirationMySQLDAO();
        $result = $dao->edit('asdf', "Yo yo yo");
        $this->assertEquals($result, true);

        $inspiration = $dao->get('asdf');
        $this->assertEquals('Yo yo yo', $inspiration->description);
    }

    public function testHide() {
        $builders[] = FixtureBuilder::build('inspirations', array('uid'=>'asdf', 'maker_id'=>10,
            'inspirer_maker_id'=>13));

        $dao = new InspirationMySQLDAO();
        $result = $dao->hide('asdf');
        $this->assertEquals($result, true);

        $inspiration = $dao->get('asdf');
        $this->assertEquals($inspiration->is_shown_on_inspirer, 0);
    }

    /**
     * @expectedException InspirationDoesNotExistException
     */
    public function testGetNonexistent() {
        $dao = new InspirationMySQLDAO();
        $result = $dao->get('badfad');
        $this->assertInstanceOf($result, 'Inspiration');
    }

    public function testGet() {
        $builders[] = FixtureBuilder::build('inspirations', array('uid'=>'asdf', 'maker_id'=>10,
            'inspirer_maker_id'=>13));

        $dao = new InspirationMySQLDAO();
        $result = $dao->get('asdf');
        $this->assertInstanceOf('Inspiration', $result);
    }
}
