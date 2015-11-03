<?php

class MakerbaseUnitTestCase extends IsoscelesBasicUnitTestCase {
    /**
     * @var IsoscelesTestDatabaseHelper
     */
    var $testdb_helper;
    /**
     * @var str
     */
    var $test_database_name;
    /**
     * @var str
     */
    var $table_prefix;
    /**
     * Create a clean copy of the Isosceles database structure
     */
    public function setUp() {
        parent::setUp();

        Loader::register(array(
        MAKERBASE_PATH.'webapp/libs/',
        MAKERBASE_PATH.'webapp/libs/model/',
        MAKERBASE_PATH.'webapp/libs/controller/',
        MAKERBASE_PATH.'webapp/libs/dao/',
        MAKERBASE_PATH.'webapp/libs/exceptions/',
        ISOSCELES_PATH.'tests/classes/',
        ));
        Loader::addSpecialClass('TwitterOAuth', Config::getInstance()->getValue('source_root_path').
            'webapp/extlibs/twitteroauth/twitteroauth.php');

        require ISOSCELES_PATH.'libs/config.inc.php';
        require 'tests/config.tests.inc.php';
        $this->test_database_name = $TEST_DATABASE;
        $config = Config::getInstance();

        if (!self::ramDiskTestMode() ) {
            //Override default CFG values
            $ISOSCELES_CFG['db_name'] = $this->test_database_name;
            $config->setValue('db_name', $this->test_database_name);
        } else {
            $this->test_database_name = $ISOSCELES_CFG['db_name'];
        }
        $this->testdb_helper = new IsoscelesTestDatabaseHelper();

        $this->testdb_helper->drop($this->test_database_name);

        $this->table_prefix = $config->getValue('table_prefix');
        PDODAO::$prefix = $this->table_prefix;

        $this->testdb_helper->create(MAKERBASE_PATH."/sql/makerbase.sql");
    }

    /**
     * Drop the database and kill the connection
     */
    public function tearDown() {
        if (isset(IsoscelesTestDatabaseHelper::$PDO)) {
            $this->testdb_helper->drop($this->test_database_name);
        }
        parent::tearDown();
    }

    /**
     * Returns an xml/xhtml document element by id
     * @param $doc an xml/xhtml document pobject
     * @param $id element id
     * @return Element
     */
    public function getElementById($doc, $id) {
        $xpath = new DOMXPath($doc);
        return $xpath->query("//*[@id='$id']")->item(0);
    }

    /**
     * Check if we in RAM disk test mode
     * @return bool
     */
    public static function ramDiskTestMode() {
        if (getenv("RD_MODE")=="1") {
            return true;
        }
        return false;
    }
}
