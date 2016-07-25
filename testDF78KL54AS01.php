<?php
session_start();


require "./top.inc.php";
require "./init.php";

global $xcart_dir, $config;
require_once $xcart_dir . "/modules/External_Marketplaces/include/classExternalMarketPlace.php";

$aExternalMarketPlace = classStoreFrontMarketPlace::getMarketPlacesByStoreFront(62);

$oExternalMarketPlace = $aExternalMarketPlace[0];
$vvvv = $oExternalMarketPlace->getExternalMarketPlaceEntity();
var_dump($vvvv);
echo $oExternalMarketPlace->getExternalMarketPlaceEntity()->getMarketPlaceStatus();






