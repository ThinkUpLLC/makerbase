<?php
require_once dirname(__FILE__).'/init.tests.php';

class RoleMySQLDAOTest extends MakerbaseUnitTestCase {

    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

    public function testConstructor() {
        $dao = new RoleMySQLDAO();
        $this->assertNotNull($dao);
        $this->assertInstanceOf('RoleMySQLDAO', $dao);
    }

    //TODO testArchive
    //TODO testUnarchive
    //TODO testGetByMaker
    //TODO testGetByProduct
    //TODO testGet
    //TODO testUpdate
    //TODO testDeleteByProduct
    //TODO testDeleteByMaker
    //TODO testGetFrequentCollaborators
    //TODO testGetCommonProjects
    //TODO testInsert
    //TODO testGetTotal
}
