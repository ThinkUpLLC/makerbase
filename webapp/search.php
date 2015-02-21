<?php
require_once 'extlibs/isosceles/libs/model/class.Loader.php';
Loader::register(array(
// dirname(__FILE__).'/libs/',
'libs/model/',
'libs/controller/',
'libs/dao/',
'libs/exceptions/'
));

require 'vendor/autoload.php';

$controller = new SearchController();
echo $controller->control();