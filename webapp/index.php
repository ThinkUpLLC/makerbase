<?php
require_once 'extlibs/isosceles/libs/model/class.Loader.php';
Loader::register(array(
// dirname(__FILE__).'/libs/',
'libs/model/',
'libs/controller/',
'libs/dao/',
'libs/exceptions/'
));

$router = new Router();
$router->addRoute('index', 'LandingController');
$router->addRoute('m', 'MakerController', array('slug'));
$router->addRoute('p', 'ProductController', array('slug'));
echo $router->route();
