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

$router = new Router();
//In case of caching issues
//Router::$routes = null;
$router->addRoute('index', 'LandingController');
$router->addRoute('m', 'MakerController', array('slug'));
$router->addRoute('p', 'ProductController', array('slug'));
$router->addRoute('u', 'UserController', array('twitter_user_id'));
$router->addRoute('signin', 'SignInController');
$router->addRoute('signout', 'SignOutController');
$router->addRoute('add', 'AddController', array('object', 'method'));
$router->addRoute('edit', 'EditController', array('object'));
echo $router->route();
