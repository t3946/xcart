<?php

require "./auth.php";

$rows_in_one_column = 20;


global $current_storefront, $fv_sel, $dispatched_request;

$aFilterSelected = null;
if (!empty($fv_sel))
    $aFilterSelected = explode(',', $fv_sel);

if (!empty($f_id)) {
    $oFilter = \Xcart\Filter::model(['f_id' => $f_id]);
} else $oFilter = \Xcart\Filter::model();

$oFilter->setStoreFront(\Xcart\StoreFront::model(['storefrontid' => $current_storefront]))->
setCategory(\Xcart\Category::model(['categoryid' => $categoryid]));
if (!empty($aFilterSelected)) {
    $oFilter->setFilterValuesSelected(
        \Xcart\FilterValue::model()->findAll(\Xcart\SQLBuilder::getInstance()->addCondition('fv_id IN (' . implode(',', $aFilterSelected) . ')'))
    );
}
if (!empty($p_ids)) {
    $aPriceRange = array_keys($p_ids);
    $oFilter->setPriceRange(reset($aPriceRange));
}

if (!empty($b_ids)) {
    $aBrandSelected = explode(',', $b_ids);
    if (!empty($aBrandSelected)) {
        $oFilter->setBrandSelected(
            \Xcart\Brand::model()->findAll(\Xcart\SQLBuilder::getInstance()->addCondition('brandid IN (' . implode(',', $aBrandSelected) . ')'))
        );
    }
}

$aFilterValues = null;

if ($filter == "brand") {
    $aFilterValues = $oFilter->getMoreBrands();
}

if ($filter == "fvalues") {
    if (!empty($f_id)) {
        $aFilterValues = $oFilter->getMoreFilterValues();
    }
}
$count_f_ids = $oFilter->getFoundValuesCount();
$num_columns = ceil($count_f_ids / $rows_in_one_column);

$smarty->assign("count_f_ids", $count_f_ids);
$smarty->assign("aFilterValues", $aFilterValues);
$smarty->assign("oFilter", $oFilter);

$smarty->assign("rows_in_one_column", $rows_in_one_column);
$smarty->assign("num_columns", $num_columns);
$smarty->assign("target", $target);
$smarty->assign("return", $return);
$smarty->assign("filter", $filter);

$smarty->assign("template_name", "customer/show_more_filters.tpl");
func_display("customer/help/popup_info.tpl", $smarty);
?>
