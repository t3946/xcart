<?php
/*
+----------------------------------------------------------------------+
| Best Search Filter Mod                                               |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*/

if ( !defined('XCART_START') ) { header("Location: ../../"); die("Access denied"); }

define("GET_ALL_CATEGORIES", true);
require $xcart_dir."/include/categories.php";

x_session_register("search_data", []);

if ($REQUEST_METHOD == 'POST'){

    if ($mode == 'replace_from_products'){

	if (!empty($productids) && is_array($productids)){
		foreach ($productids as $productid => $v){

			db_query("DELETE FROM $sql_tbl[cidev_filter_products] WHERE productid='$productid'");

			if (!empty($filter_value_id) && is_array($filter_value_id)){
				foreach ($filter_value_id as $kk => $fv_id){
					if ($fv_id > 0) {
						db_query("INSERT INTO $sql_tbl[cidev_filter_products] (productid, fv_id) VALUES ('$productid', '$fv_id')");
					}
				}
			}
		}

		$top_message['content'] = func_get_langvar_by_name('lbl_cidev_f_values_assigned');
	}
    }

    if ($mode == 'add_to_products'){

        if (!empty($productids) && is_array($productids)){
                foreach ($productids as $productid => $v){

                        if (!empty($filter_value_id) && is_array($filter_value_id)){
                                foreach ($filter_value_id as $kk => $fv_id){
                                        if ($fv_id > 0) {
                                                db_query("REPLACE INTO $sql_tbl[cidev_filter_products] (productid, fv_id) VALUES ('$productid', '$fv_id')");
                                        }
                                }
                        }
                }

                $top_message['content'] = func_get_langvar_by_name('lbl_cidev_f_values_assigned');
        }
    }

    if ($mode == 'delete_from_products'){

        if (!empty($productids) && is_array($productids)){

                foreach ($productids as $productid => $v){

                        if (!empty($filter_value_id) && is_array($filter_value_id) && !empty($filter_name_id) && is_array($filter_name_id)){
                                foreach ($filter_value_id as $kk => $fv_id){
                                        if (!empty($fv_id)) {
                                                db_query("DELETE FROM $sql_tbl[cidev_filter_products] WHERE productid='$productid' AND fv_id='$fv_id'");
                                        } else {
						$f_id = $filter_name_id[$kk];

						$all_fv_ids = func_query("SELECT fv_id FROM $sql_tbl[cidev_filter_values] WHERE f_id='$f_id'");
						if (!empty($all_fv_ids) && is_array($all_fv_ids)){
							foreach ($all_fv_ids as $kkk => $vvv){
								db_query("DELETE FROM $sql_tbl[cidev_filter_products] WHERE productid='$productid' AND fv_id='$vvv[fv_id]'");
							}
						}
					}
                                }
			}
                }

                $top_message['content'] = func_get_langvar_by_name('lbl_cidev_f_values_assigned');
        }
    }

    if ($mode == 'delete_from_products' || $mode == 'add_to_products' || $mode == 'replace_from_products'){
	    func_header_location("cidev_admin_add_filter_to_products.php?mode=search&navpage=".$navpage."&sort=".$sort."&sort_direction=".$sort_direction); 
    }
}

# The list of the fields allowed for searching
$allowable_search_fields = array (
        "substring",
        "by_title",
        "by_shortdescr",
        "by_fulldescr",
        "extra_fields",
        "by_keywords",
        "categoryid",
        "category_main",
        "category_extra",
        "search_in_subcategories",
        "price_max",
        "price_min",
        "price_max",
        "avail_min",
        "avail_max",
        "weight_min",
        "weight_max",
        "empty_discount_slope",
        "discount_slope",
        "discount_table",
#
##
###
//	"filter_name_id",
//	"filter_value_id",
###
##
#
        "manufacturers"
);

if ($REQUEST_METHOD == 'GET' && $mode == "search") {
        # Check the variables passed from GET-request
        $get_vars = array();
        foreach ($_GET as $k => $v) {
                if (in_array($k, $allowable_search_fields))
                        $get_vars[$k] = $v;
        }

        # Prepare the search data
        if (!empty($get_vars))
                $search_data["products"] = $get_vars;

        unset($get_vars);
}

if ($mode == 'search'){

	if ($REQUEST_METHOD == "POST") {

		if (!empty($filter_name_id) && is_array($filter_name_id) && !empty($filter_value_id) && is_array($filter_value_id)){
			foreach ($filter_name_id as $k => $v){
				if (empty($v)){
					unset($filter_name_id[$k]);
					unset($filter_value_id[$k]);
				}
			}
		}

		if (!empty($filter_name_id) && is_array($filter_name_id) && !empty($filter_value_id) && is_array($filter_value_id)){
			$search_data['products']['filter_name_id'] = $filter_name_id;
			$search_data['products']['filter_value_id'] = $filter_value_id;
		}
		else {
//			unset($search_data['products']['filter_name_id']);
//			unset($search_data['products']['filter_value_id']);
                        $search_data['products']['filter_name_id'] = "";
                        $search_data['products']['filter_value_id'] = "";
		}


                $all_filter_name_id = array_unique($filter_name_id);
                $all_filter_name_id = array_values($all_filter_name_id);

                $sorted_filter_values_id = array();
                foreach ($filter_value_id as $kid => $fv_id){

	                $f_id = $filter_name_id[$kid];

                        foreach ($all_filter_name_id as $kk_f_id => $vv_f_id){

        	                if ($vv_f_id == $f_id){

                	                if (empty($fv_id)){
                        	                $all_fv_ids = func_query("SELECT fv_id FROM $sql_tbl[cidev_filter_values] WHERE f_id='$f_id'");
                                                if (!empty($all_fv_ids) && is_array($all_fv_ids)){
                                	                foreach ($all_fv_ids as $kkk => $vvv){
                                        	                $sorted_filter_values_id[$f_id][] = $vvv["fv_id"];
                                                        }
                                                }
                                        }
                                        else {
 		                                $sorted_filter_values_id[$f_id][] = $fv_id;
                                        }
                        	}
                	}
                }
		$search_data['products']['sorted_filter_values_id'] = $sorted_filter_values_id;


		$search_data['products']['filter_replace_query'] = $filter_replace_query;
	}


	include $xcart_dir.'/include/search.php';

	if (is_array($products)) {

/*
	    foreach ($products as $k=>$v) {
		$products[$k]["cidev_filter_products"] = func_query("SELECT $sql_tbl[cidev_filter_products].fv_id, $sql_tbl[cidev_filter_values].fv_name, $sql_tbl[cidev_filter_values].f_id FROM $sql_tbl[cidev_filter_products] LEFT JOIN $sql_tbl[cidev_filter_values] ON $sql_tbl[cidev_filter_values].fv_id=$sql_tbl[cidev_filter_products].fv_id WHERE productid='$v[productid]' ORDER BY $sql_tbl[cidev_filter_values].fv_order_by, $sql_tbl[cidev_filter_values].fv_name");
	    }
*/
	    $smarty->assign('navigation_script',"cidev_admin_add_filter_to_products.php?mode=search&sort=$sort&sort_direction=".intval($sort_direction));
	}

	$smarty->assign('products', $products);

}

$cidev_filters_tree = func_cidev_filters_tree();
$smarty->assign('cidev_filters_tree', $cidev_filters_tree);

if (!empty($search_data["products"]))
	$smarty->assign("search_prefilled", $search_data["products"]);

$location[] = array(func_get_langvar_by_name('lbl_cidev_search_by_filter'), '');
$smarty->assign('location', $location);
?>
