<?php
session_start();

ini_set('memory_limit', '512M');
set_time_limit(0);

require "./top.inc.php";
require "./init.php";

global $xcart_dir, $config;
include_once $xcart_dir.'/include/class/classSQLBuilder.php';
include_once $xcart_dir.'/include/class/classOrderAmazonDetails.php';


$oOrder = classOrderAmazonDetails::model();

var_dump($oOrder->getOrderAmazonDetails(['orderid'=>65022, 'manufacturerid'=>12]));
