<?php
session_start();

ini_set('memory_limit', '512M');
set_time_limit(0);

require "./top.inc.php";
require "./init.php";

global $xcart_dir, $config;
include_once $xcart_dir.'/include/class/classSQLBuilder.php';
include_once $xcart_dir.'/include/class/classProduct.php';

$oSQL = new classSQLBuilder();
$aProducts = $oSQL->addSelect('p.productid, p.upc')->addFromTable('products','p')->addInnerJoin('supplier_feeds','f',' f.manufacturerid = p.manufacturerid')->
addCondition("p.forsale='Y'")->addCondition("f.enabled = 'Y'")->addGroupBy('p.productid')->Execute()->getQueryResult();
foreach ($aProducts as $aProduct) {
    $newUPC = classProduct::calculateUPC($aProduct['upc']);
    if ($newUPC != $aProduct['upc']) {
        echo $aProduct['productid']." - ". $aProduct['upc'].'('.strlen($aProduct['upc']).')'.' -> '.$newUPC.'('.strlen($newUPC).')<br>';
        func_array2update('products', ['upc'=>$newUPC], 'productid='.$aProduct['productid']);
    }
}

