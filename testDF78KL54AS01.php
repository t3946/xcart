<?php
session_start();

ini_set('memory_limit', '512M');
set_time_limit(0);

require "./top.inc.php";
require "./init.php";

global $xcart_dir, $config;
include_once $xcart_dir.'/include/class/classSQLBuilder.php';
include_once $xcart_dir.'/include/class/classProduct.php';

//$oSQL = new classSQLBuilder();
//$oSQL->addCondition('productcode LIKE "%ART-KS-1001%"')->setLimit('5, 5');
//$aProducts = classProduct::model()->findAll($oSQL);

var_dump(classProduct::model()->getProductBySKU('ALV-ML420D222'));
