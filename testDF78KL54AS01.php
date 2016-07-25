<?php
session_start();


require "./top.inc.php";
require "./init.php";

global $xcart_dir, $config;
require_once $xcart_dir . "/modules/External_Marketplaces/include/classExternalMarketPlace.php";
$storefrontid = 3;
$aExternalMarketPlaces = classExternalMarketPlace::getExternalMarketPlaces($storefrontid);
var_dump($aExternalMarketPlaces);






