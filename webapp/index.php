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

//ElasticSearch
require 'vendor/autoload.php';

//FineDiff
require 'extlibs/FineDiff/finediff.php';

//Mandrill
require_once 'extlibs/mandrill/Mandrill.php';

$router = new Router('PageNotFoundController', 'InternalServerErrorController');
//In case of caching issues
//Router::$routes = null;
$router->addRoute('index', 'LandingController');
$router->addRoute('activity', 'LandingController', array('p', 'stream'));
$router->addRoute('m', 'MakerController', array('uid', 'slug', 't', 'p'));
$router->addRoute('p', 'ProductController', array('uid', 'slug', 'p'));
$router->addRoute('u', 'UserController', array('uid', 'p'));
$router->addRoute('settings', 'SettingsController');
$router->addRoute('signin', 'SignInController');
$router->addRoute('signout', 'SignOutController');
$router->addRoute('add', 'AddController', array('object', 'target'));
$router->addRoute('edit', 'EditController', array('object'));
$router->addRoute('search', 'SearchController', array('type'));
$router->addRoute('autocomplete', 'SearchAutoCompleteController');
$router->addRoute('twittersignin', 'TwitterSignInController');
$router->addRoute('about', 'AboutPageController', array('p'));
$router->addRoute('explore', 'ExploreController');
$router->addRoute('s3cr3t', 'AdminDashboardController', array('v', 'p'));
$router->addRoute('aph', 'AdminProductAdderController', array('type'));
$router->addRoute('adminedit', 'AdminEditController', array('object'));
$router->addRoute('follow', 'FollowController', array('object', 'uid'));
$router->addRoute('unfollow', 'UnfollowController', array('object', 'uid'));
$router->addRoute('api', 'APIController', array('endpoint'));

//Event landing pages
$router->addRoute('xoxo2015', 'EventXOXOController');
$router->addRoute('gotoxoxo', 'AttendEventXOXOController');

$router->addRoute('universe', 'EventGitHubUniverseController');
$router->addRoute('gotoghuniverse', 'AttendEventGitHubUniverseController');

echo $router->route();
