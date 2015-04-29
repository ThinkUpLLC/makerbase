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

$router = new Router();
//In case of caching issues
//Router::$routes = null;
$router->addRoute('index', 'LandingController');
$router->addRoute('m', 'MakerController', array('uid', 'slug'));
$router->addRoute('p', 'ProductController', array('uid', 'slug'));
$router->addRoute('u', 'UserController', array('uid', 'p'));
$router->addRoute('signin', 'SignInController');
$router->addRoute('signout', 'SignOutController');
$router->addRoute('add', 'AddController', array('object', 'method'));
$router->addRoute('edit', 'EditController', array('object'));
$router->addRoute('search', 'SearchController');
$router->addRoute('autocomplete', 'SearchAutoCompleteController');
$router->addRoute('twittersignin', 'TwitterSignInController');
$router->addRoute('about', 'AboutPageController', array('p'));

echo $router->route();
