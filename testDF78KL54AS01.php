<?php
session_start();


require "./top.inc.php";
require "./init.php";

x_load('froogle');
x_load('backoffice');
x_load('taxes');

global $xcart_dir, $config;

include_once $xcart_dir."/include/class/classOrderGroup.php";
$oOrderGroup = new classOrderGroup(['orderid'=>64824, 'manufacturerid'=>36]);
$oOrderGroup->recalculateAccountingAmazon();


