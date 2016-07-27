<?php
session_start();


require "./top.inc.php";
require "./init.php";

global $xcart_dir, $config;
require_once $xcart_dir . "/include/class/classOrderGroup.php";

$oOrderGroup = new classOrderGroup(['orderid'=>'62713','manufacturerid'=>'12']);
//$oOrderGroup->shipOrderGroupByAmazon();





