<?php


//die("123");

require "./auth.php";

x_session_register("sorted_filter_values_id");
x_session_register("filter_selected_brandids");
x_session_register("filter_prices");
x_session_register("filter_selected_cat");
x_session_register("filter_selected_and_found_brands");
x_session_register("cidev_filters_tree_sorted");
x_session_register("filter_found_fv_ids_count");

#
# Check input data
#
if (!in_array($target, array("show_more"))) {
    func_close_window();
}

#
# Update data
#
if ($REQUEST_METHOD == 'POST') {

    if ($f_mode == 'f_search') {

        # Brands
        if ($f_update == "brands") {
            if ((!empty($b_ids) && is_array($b_ids))) {
                $filter_selected_brandids = array();
                foreach ($b_ids as $k => $v) {
                    $filter_selected_brandids[] = $k;
                }
                $count_filter_selected_brandids = count($filter_selected_brandids);
            } else {
                $filter_selected_brandids = "";
            }

            x_session_save("filter_selected_brandids");
        }

        # Filter attributes
        if ($f_update == "f_values" && !empty($f_id)) {

            if (!empty($fv_ids) && is_array($fv_ids)) {
                $fv_ids = array_keys($fv_ids);
                $fv_ids = array_values($fv_ids);
                $sorted_filter_values_id[$f_id] = $fv_ids;
            } else {
                unset($sorted_filter_values_id[$f_id]);
            }

            x_session_save("sorted_filter_values_id");
        }

    } elseif ($f_mode == 'clear') {

        # Brands
        if ($f_update == "brands") {
            $filter_selected_brandids = "";
            x_session_save("filter_selected_brandids");
        }

        # Filter attributes
        if ($f_update == "f_values" && !empty($f_id)) {
            unset($sorted_filter_values_id[$f_id]);
            x_session_save("sorted_filter_values_id");
        }
    }
    ?>

    <script type="text/javascript">
        <!--
        /* CMD: opener_reload */
        if (window.opener)
            window.opener.location.reload();
        else if (window.parent)
            window.parent.location.reload();
        -->
    </script>

    <?php
    func_close_window();
}

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
