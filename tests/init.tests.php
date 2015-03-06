<?php

putenv("MODE=TESTS");

if ( !defined('ISOSCELES_PATH') ) {
    define('ISOSCELES_PATH', dirname(dirname(__FILE__)).'/webapp/extlibs/isosceles/');
}

if ( !defined('MAKERBASE_PATH') ) {
    define('MAKERBASE_PATH', dirname(dirname(__FILE__)).'/');
}

if ( !defined('TESTS_RUNNING') ) {
    define('TESTS_RUNNING', true);
}

require_once 'config.tests.inc.php';

require_once ISOSCELES_PATH.'libs/class.Loader.php';

Loader::register(array(
ISOSCELES_PATH.'tests/classes/',
));

require_once 'class.MakerbaseUnitTestCase.php';
