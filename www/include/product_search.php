<?php
if (!defined('XCART_SESSION_START')) {
    header("Location: ../");
    die("Access denied");
}

global $xcart_dir;

$oSQL = new Xcart\SQLBuilder();
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

$aProducts = Xcart\Product::model()->find($oSQL);
