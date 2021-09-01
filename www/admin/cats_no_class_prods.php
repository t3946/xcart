<?php
require "./auth.php";
require $xcart_dir."/include/security.php";

x_load('backoffice','files','category');

$all_pc_cats = func_query("SELECT $sql_tbl[categories].* FROM $sql_tbl[categories] WHERE $sql_tbl[categories].storefrontid='".$current_storefront_info["storefrontid"]."' AND $sql_tbl[categories].pc_ready_to_classify='Y'");

if (!empty($all_pc_cats) && is_array($all_pc_cats)){
	foreach ($all_pc_cats as $k => $v){

		$count_pc_products = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[products] LEFT JOIN $sql_tbl[products_categories] ON $sql_tbl[products_categories].productid=$sql_tbl[products].productid WHERE $sql_tbl[products_categories].categoryid='$v[categoryid]' AND ($sql_tbl[products].pc_classify_status='ACC' OR $sql_tbl[products].pc_classify_status='MC') AND $sql_tbl[products].forsale='Y'");

		if ($count_pc_products != 0){
			unset($all_pc_cats[$k]);
		} else {
	                $categoryid_path_arr = func_categoryid_path2category_path($v["categoryid_path"]);
        	        $all_pc_cats[$k]["categoryid_path_arr"] = $categoryid_path_arr;
                	$all_pc_cats[$k]["categoryid_path_arr_count"] = count($categoryid_path_arr);
		}
	}
}

$count_all_not_pc_cats = count($all_pc_cats);

$smarty->assign("all_not_pc_cats", $all_pc_cats);

//func_print_r($all_pc_cats);

$location[] = array("Categories containing no classified products", "");

$smarty->assign("main", "cats_no_class_prods");
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
