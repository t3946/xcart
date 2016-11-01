<?php
global $xcart_dir, $current_storefront;
require "./auth.php";
require $xcart_dir . "/include/security.php";

$location[] = [func_get_langvar_by_name("lbl_match_amazon_FBA_missing_SKU"), ""];
$smarty->assign("location", $location);

$smarty->assign("main", "match_amazon_missing_sku");

$smarty->assign('aMatchedProducts',
    \Xcart\FbaMissingSku::model()->findAll(\Xcart\SQLBuilder::getInstance()->
    addCondition('main.productid != 0')->
    addInnerJoin('products_sf', 'psf', 'psf.productid = main.productid AND psf.sfid=' . $current_storefront)->
    addOrderBy('missing_productcode')));

$smarty->assign('aNotMatchedProducts',
    \Xcart\FbaMissingSku::model()->findAll(\Xcart\SQLBuilder::getInstance()->
    addCondition('main.productid = 0')->
    addOrderBy('missing_productcode')));

@include $xcart_dir . "/modules/gold_display.php";
func_display("admin/home.tpl", $smarty);
