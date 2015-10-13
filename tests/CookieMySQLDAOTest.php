<?php
require_once dirname(__FILE__).'/init.tests.php';

class CookieMySQLDAOTest  extends MakerbaseUnitTestCase {
    /**
     * @var CookieMySQLDAO
     */
    protected $dao;

    public function setUp() {
        parent::setUp();
        $this->DAO = new CookieMySQLDAO();
        $this->builders = self::buildData();
        $this->config = Config::getInstance();
    }

    protected function buildData() {
        $builders = array();

        $builders[] = FixtureBuilder::build('cookies', array('user_uid' => 'me@test.com', 'cookie' => 'chocolate'));
        $builders[] = FixtureBuilder::build('cookies', array('user_uid' => 'me@test.com', 'cookie' => 'gingersnap'));
        $builders[] = FixtureBuilder::build('cookies', array('user_uid' => 'you@test.com', 'cookie' => 'oreo'));

        return $builders;
    }

    public function testGetUIDByCookie() {
        $email = $this->DAO->getUIDByCookie('chocolate');
        $this->assertEquals('me@test.com', $email);
        $email = $this->DAO->getUIDByCookie('gingersnap');
        $this->assertEquals('me@test.com', $email);
        $email = $this->DAO->getUIDByCookie('oreo');
        $this->assertEquals('you@test.com', $email);
        $email = $this->DAO->getUIDByCookie('peanutbutter');
        $this->assertNull($email);
    }

    public function testGenerateForUser() {
        $cookie = $this->DAO->generateForUser($em = 'testy@testy.com');
        $this->assertNotNull($cookie);

        $cookie2 = $this->DAO->generateForUser($em = 'testy@testy.com');
        $this->assertNotNull($cookie2);
        $this->assertNotEquals($cookie, $cookie2);

        $email = $this->DAO->getUIDByCookie($cookie);
        $this->assertEquals($em, $email);

        $email = $this->DAO->getUIDByCookie($cookie2);
        $this->assertEquals($em, $email);
    }

    public function testDeleteByCookie() {
        $cookie = $this->DAO->generateForUser($em = 'testy@testy.com');
        $this->assertNotNull($cookie);
        $email = $this->DAO->getUIDByCookie($cookie);
        $this->assertEquals($em, $email);

        $this->DAO->deleteByCookie($cookie);
        $email = $this->DAO->getUIDByCookie($cookie);
        $this->assertNull($email);
    }

    public function testDeleteByUser() {
        $cookie = $this->DAO->generateForUser($em = 'testy@testy.com');
        $this->assertNotNull($cookie);
        $email = $this->DAO->getUIDByCookie($cookie);
        $this->assertEquals($em, $email);

        $this->DAO->deleteByUser($em);
        $email = $this->DAO->getUIDByCookie($cookie);
        $this->assertNull($email);
    }

    public function tearDown() {
        $this->builders = null;
        parent::tearDown();
    }
}