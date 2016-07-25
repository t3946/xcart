<?php
session_start();


require "./top.inc.php";
require "./init.php";

global $xcart_dir, $config;
require_once $xcart_dir . "/include/class/classOrderGroup.php";

$oOrderGroup = new classOrderGroup(['orderid'=>'62712','manufacturerid'=>'12']);

var_dump($oOrderGroup->checkFBAProductsAvailToShipping());





