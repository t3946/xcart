<?php
/** @var classOrder $oOrderManufacturer */
session_start();

require "./top.inc.php";
require "./init.php";

global $xcart_dir;

require_once $xcart_dir . "/include/class/classProducts.php";
require_once $xcart_dir . "/include/class/classHTMLShot.php";


$oProduct = new classProduct(275567);

$oHTMLShot = new classHTMLShot();
$oHTMLShot->createHTMLShot($oProduct);

$oProduct->getHTMLShot();








