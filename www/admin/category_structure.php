<?php
require "./auth.php";
require $xcart_dir."/include/security.php";

x_load('backoffice','files','category');

x_session_register("last_taxonomy_var");

$location[] = array("Category structure", "");

$sAdditionalQuery = '';
if (!empty($ready_to_classify) && $ready_to_classify == 'Y') {
	define('NEED_READY_CLASSIFY', 1);
	$sAdditionalQuery = '?ready_to_classify=Y';
	array_pop($location);
	$location[] = array("Category structure", "category_structure.php");
	$location[] = array("Ready to classification", "");
	$smarty->assign('ready_to_classify',$ready_to_classify);
}

if ($REQUEST_METHOD == "POST" && $mode == "update" && !empty($google_product_category_arr) && is_array($google_product_category_arr)) {

	foreach ($google_product_category_arr as $categoryid => $google_product_category){
		if (!empty($categoryid)){
			db_query("UPDATE $sql_tbl[categories] SET google_product_category='$google_product_category' WHERE categoryid='$categoryid'");
		}
	}

	$last_taxonomy_var = $last_taxonomy;
	x_session_save("last_taxonomy_var");

	func_header_location("category_structure.php".$sAdditionalQuery);
}


$cat = 0;
require $xcart_dir."/include/categories.php";

if (!empty($all_categories) && is_array($all_categories)){

	$parentid_google_product_category = array();

	foreach ($all_categories as $k => $v){

		if ($v['global_product_count'] == 0) {
			unset ($all_categories[$k]);
			continue;
		}

		$count_pc_products = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[products] LEFT JOIN $sql_tbl[products_categories] ON $sql_tbl[products_categories].productid=$sql_tbl[products].productid WHERE $sql_tbl[products_categories].categoryid='$v[categoryid]' AND ($sql_tbl[products].pc_classify_status!='ACC' AND $sql_tbl[products].pc_classify_status!='MC') AND $sql_tbl[products].forsale='Y'");

		$all_categories[$k]["count_pc_products"] = $count_pc_products;
                $categoryid_path_arr = func_categoryid_path2category_path($v["categoryid_path"]);
       	        $all_categories[$k]["categoryid_path_arr"] = $categoryid_path_arr;
               	$all_categories[$k]["categoryid_path_arr_count"] = count($categoryid_path_arr);

		if (!empty($v["google_product_category"])){

			$parentid_google_product_category[$v["categoryid"]] = $v["google_product_category"];
		}
		elseif (!empty($parentid_google_product_category[$v["parentid"]])){
			$all_categories[$k]["prev_google_product_category"] = $parentid_google_product_category[$v["parentid"]];
			$parentid_google_product_category[$v["categoryid"]] = $parentid_google_product_category[$v["parentid"]];
		}
	}
}

//func_print_r($last_taxonomy_var);

$smarty->assign("all_categories", $all_categories);
$smarty->assign("last_taxonomy", $last_taxonomy_var);

//func_print_r($all_categories);



$smarty->assign("main", "category_structure");
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
