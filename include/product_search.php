<?php
if (!defined('XCART_SESSION_START')) {
    header("Location: ../");
    die("Access denied");
}

global $xcart_dir;
require_once $xcart_dir . "/include/class/classSQLBuilder.php";
require_once $xcart_dir . "/include/class/classProduct.php";

$oSQL = new classSQLBuilder();
if (!empty($sort) && isset($sort_fields[$sort])) {
    if (isset($sort_direction)) {
        $sort .= ' ' . $sort_direction;
    }
    $oSQL->addOrderBy($sort);
}

switch ($current_area) {
    case 'C':
        $oSQL->addCondition("forsale='Y'");
        $oSQL->addInnerJoin('products_sf','psf',"main.productid = psf.productid AND sfid=$current_storefront");
        break;
}

$aProducts = classProduct::model()->find($oSQL);
