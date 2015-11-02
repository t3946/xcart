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
if (!in_array($target,array("show_more"))) {
	func_close_window();
}

#
# Update data
#
if ($REQUEST_METHOD == 'POST'){

    if ($f_mode == 'f_search') {

        # Brands
	if ($f_update == "brands"){
	        if ((!empty($b_ids) && is_array($b_ids))){
        	        $filter_selected_brandids = array();
                	foreach ($b_ids as $k => $v){
                        	$filter_selected_brandids[] = $k;
	                }
        	        $count_filter_selected_brandids = count($filter_selected_brandids);
	        } else {
        	        $filter_selected_brandids = "";
	        }

        	x_session_save("filter_selected_brandids");
	}

        # Filter attributes
	if ($f_update == "f_values" && !empty($f_id)){

		if (!empty($fv_ids) && is_array($fv_ids)){
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
        if ($f_update == "brands"){
                $filter_selected_brandids = "";
                x_session_save("filter_selected_brandids");
        }

	# Filter attributes
	if ($f_update == "f_values" && !empty($f_id)){
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

if ($filter == "brand"){
	$smarty->assign("filter_selected_and_found_brands", $filter_selected_and_found_brands);
	$count_brands = count($filter_selected_and_found_brands);
	$num_columns = ceil($count_brands/$rows_in_one_column);
}

if ($filter == "fvalues" && !empty($cidev_filters_tree_sorted) && is_array($cidev_filters_tree_sorted)){

	if (!empty($f_id)){

		foreach ($cidev_filters_tree_sorted as $k => $v){
			if ($v["f_id"] == $f_id){
				$count_f_ids =count($v["filter_values"]);
			}
		}

		$num_columns = ceil($count_f_ids/$rows_in_one_column);

		$smarty->assign("count_f_ids", $count_f_ids);
		$smarty->assign("cidev_filters_tree_sorted", $cidev_filters_tree_sorted);
		$smarty->assign("f_id", $f_id);
		$smarty->assign("filter_found_fv_ids_count", $filter_found_fv_ids_count);
	}	
}

$smarty->assign("rows_in_one_column", $rows_in_one_column);
$smarty->assign("num_columns", $num_columns);
$smarty->assign("target", $target);
$smarty->assign("filter", $filter);

$smarty->assign("template_name", "customer/show_more_filters.tpl");
func_display("customer/help/popup_info.tpl", $smarty);
?>
