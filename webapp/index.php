<?php
require_once 'extlibs/isosceles/libs/model/class.Loader.php';
Loader::register(array(
// dirname(__FILE__).'/libs/',
// dirname(__FILE__).'/libs/model/',
'libs/controller/'
// dirname(__FILE__).'/libs/dao/',
// dirname(__FILE__).'/libs/exceptions/'
));

$router = new Router();
$router->addRoute('index', 'LandingController');
$router->addRoute('m', 'MakerController', array('slug'));
$router->addRoute('p', 'ProductController', array('slug'));
echo $router->route();
