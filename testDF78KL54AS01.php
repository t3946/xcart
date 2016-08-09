<?php
session_start();


require "./top.inc.php";
require "./init.php";

x_load('froogle');
x_load('backoffice');
x_load('taxes');

global $xcart_dir, $config;

include_once $xcart_dir."/include/class/classProduct.php";
include_once $xcart_dir."/modules/External_Marketplaces/include/classStoreFrontMarketPlace.php";

global $storefrontid;

$aExternalMarketPlaces = classStoreFrontMarketPlace::getMarketPlacesByStoreFront(41);

$storefrontid = 41;

$oProduct = new classProduct(232462);

foreach ($aExternalMarketPlaces as $oExternalMarektPlace)
{
    if ($oExternalMarektPlace instanceof classGMC){
        $oExternalMarektPlace->addProductToBatch($oProduct, 1, 'Y');

    }
}



