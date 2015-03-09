<?php
require_once 'extlibs/isosceles/libs/class.Loader.php';
Loader::register(array(
'libs/',
'libs/model/',
'libs/controller/',
'libs/dao/',
'libs/exceptions/',
));
Loader::addSpecialClass('TwitterOAuth', Config::getInstance()->getValue('source_root_path').
    'webapp/extlibs/twitteroauth/twitteroauth.php');

require 'vendor/autoload.php';

if (isset($_GET['auto'])) {
    $controller = new SearchAutoCompleteController();
} else {
    $controller = new SearchController();
}

echo $controller->go();
