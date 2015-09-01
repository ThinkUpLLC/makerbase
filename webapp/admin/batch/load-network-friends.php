<?php
// cd into the admin/batch/ folder to run this
chdir('../..');
date_default_timezone_set('America/New_York');

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

$controller = new LoadNetworkFriendsController();
echo $controller->control();
