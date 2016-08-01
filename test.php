<?php
/** @var classOrder $oOrderManufacturer */
session_start();

require "./top.inc.php";
require "./init.php";

global $xcart_dir;

require_once $xcart_dir . "/include/class/classProducts.php";
require_once $xcart_dir . "/include/class/classHTMLShot.php";


//$oProduct = new classProduct(275567);

$oHTMLShot = new classHTMLShot(['id'=>2]);
//$oHTMLShot->createHTMLShot($oProduct,0);
$oProduct = unserialize(stripslashes($oHTMLShot->getField('htmlshot')));

var_dump($oProduct);








