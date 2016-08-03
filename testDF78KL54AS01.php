<?php
session_start();


require "./top.inc.php";
require "./init.php";

global $xcart_dir, $config;

include_once $xcart_dir."/include/class/classProduct.php";
include_once $xcart_dir."/include/class/classHTMLShot.php";
$oHTMLShot = new classHTMLShot();
$oProduct = new classProduct(255247);
$oHTMLShot->createHTMLShot($oProduct, 0);





