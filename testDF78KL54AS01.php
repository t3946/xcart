<?php
session_start();


require "./top.inc.php";
require "./init.php";

x_load('froogle');
x_load('backoffice');
x_load('taxes');

global $xcart_dir, $config;

include_once $xcart_dir."/include/class/classProducts.php";

$oclassProducts = new classProducts();
$a = $oclassProducts->getFilterValuesByNameAndFilterType('"English" oval wash',2866);
var_dump($a);


