<?php
session_start();

ini_set('memory_limit', '512M');
set_time_limit(0);

require "./top.inc.php";
require "./init.php";


$oProduct = \Xcart\Product::model(['productid'=>16445]);
echo $oProduct->getAmazonQuantity();
echo $oProduct->getAmazonPrice();
echo $oProduct->getManfacturerClass()->getAmazonLeadtimetoship();