<?php
require "./auth.php";
require $xcart_dir."/include/security.php";

x_load('backoffice','files','category');

if ($REQUEST_METHOD == "POST" && $mode == "update" && !empty($google_product_category_arr) && is_array($google_product_category_arr)) {

	foreach ($google_product_category_arr as $categoryid => $google_product_category){
		if (!empty($categoryid)){
			db_query("UPDATE $sql_tbl[categories] SET google_product_category='$google_product_category' WHERE categoryid='$categoryid'");
		}
	}

	func_header_location("category_structure.php");
}


$cat = 0;
require $xcart_dir."/include/categories.php";

if (!empty($all_categories) && is_array($all_categories)){

//	$prev_cat = 0;
//	$prev_google_product_category = "";

	$parentid_google_product_category = array();

	foreach ($all_categories as $k => $v){

		$count_pc_products = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[products] LEFT JOIN $sql_tbl[products_categories] ON $sql_tbl[products_categories].productid=$sql_tbl[products].productid WHERE $sql_tbl[products_categories].categoryid='$v[categoryid]' AND ($sql_tbl[products].pc_classify_status!='ACC' AND $sql_tbl[products].pc_classify_status!='MC') AND $sql_tbl[products].forsale='Y'");

		$all_categories[$k]["count_pc_products"] = $count_pc_products;
                $categoryid_path_arr = func_categoryid_path2category_path($v["categoryid_path"]);
       	        $all_categories[$k]["categoryid_path_arr"] = $categoryid_path_arr;
               	$all_categories[$k]["categoryid_path_arr_count"] = count($categoryid_path_arr);

		if (!empty($v["google_product_category"])){
//			$prev_cat = $v["categoryid"];
//			$prev_google_product_category = $v["google_product_category"];

			$parentid_google_product_category[$v["categoryid"]] = $v["google_product_category"];
		}
//		elseif (!empty($v["parentid"]) && !empty($prev_google_product_category) && $v["parentid"] == $prev_cat){
		elseif (!empty($parentid_google_product_category[$v["parentid"]])){
			$all_categories[$k]["prev_google_product_category"] = $parentid_google_product_category[$v["parentid"]];
		}
	}
}

$smarty->assign("all_categories", $all_categories);

//func_print_r($all_categories);

$location[] = array("Category structure", "");

$smarty->assign("main", "category_structure");
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
