<?php
session_start();

ini_set('memory_limit', '512M');
set_time_limit(0);

require "./top.inc.php";
require "./init.php";

global $xcart_dir, $config;

$aExternalMarketPlaces = Xcart\External_Marketplaces\StoreFrontMarketPlace::getMarketPlacesByStoreFront(67);
var_dump($aExternalMarketPlaces);