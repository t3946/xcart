<?php
session_start();

ini_set('memory_limit', '512M');
set_time_limit(0);

require "./top.inc.php";
require "./init.php";

global $xcart_dir, $config;
include_once $xcart_dir.'/include/class/classSQLBuilder.php';
include_once $xcart_dir.'/include/class/classProduct.php';

$oProduct = new classProduct(['productid'=>16133]);


$currentDate = new DateTime("now");
$iDaysInterval = $currentDate->diff($oProduct->getProductLastVerifyDate())->days;
echo $iDaysInterval;
if ($iDaysInterval <= 20) {
    echo 1;
}
