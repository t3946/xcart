<?php
session_start();


require "./top.inc.php";
require "./init.php";

global $xcart_dir, $config;
require_once $xcart_dir . "/include/class/classProduct.php";

$oProduct = new classProduct(124317);
echo $oProduct->getProductURLOnDistributorWebSite();






