<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2006 Ruslan R. Fazliev <rrf@rrf.ru>                      |
| All rights reserved.                                                        |
+-----------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS ALSO AVAILABLE  |
| AT THE FOLLOWING URL: http://www.x-cart.com/license.php                     |
|                                                                             |
| THIS  AGREEMENT  EXPRESSES  THE  TERMS  AND CONDITIONS ON WHICH YOU MAY USE |
| THIS SOFTWARE   PROGRAM   AND  ASSOCIATED  DOCUMENTATION   THAT  RUSLAN  R. |
| FAZLIEV (hereinafter  referred to as "THE AUTHOR") IS FURNISHING  OR MAKING |
| AVAILABLE TO YOU WITH  THIS  AGREEMENT  (COLLECTIVELY,  THE  "SOFTWARE").   |
| PLEASE   REVIEW   THE  TERMS  AND   CONDITIONS  OF  THIS  LICENSE AGREEMENT |
| CAREFULLY   BEFORE   INSTALLING   OR  USING  THE  SOFTWARE.  BY INSTALLING, |
| COPYING   OR   OTHERWISE   USING   THE   SOFTWARE,  YOU  AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE  ACCEPTING  AND AGREEING  TO  THE TERMS OF THIS |
| LICENSE   AGREEMENT.   IF  YOU    ARE  NOT  WILLING   TO  BE  BOUND BY THIS |
| AGREEMENT, DO  NOT INSTALL OR USE THE SOFTWARE.  VARIOUS   COPYRIGHTS   AND |
| OTHER   INTELLECTUAL   PROPERTY   RIGHTS    PROTECT   THE   SOFTWARE.  THIS |
| AGREEMENT IS A LICENSE AGREEMENT THAT GIVES  YOU  LIMITED  RIGHTS   TO  USE |
| THE  SOFTWARE   AND  NOT  AN  AGREEMENT  FOR SALE OR FOR  TRANSFER OF TITLE.|
| THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY GRANTED BY THIS AGREEMENT.      |
|                                                                             |
| The Initial Developer of the Original Code is Ruslan R. Fazliev             |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2006           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: products.php,v 1.12 2006/01/11 06:55:57 mclap Exp $
#
# Navigation code
#

if ( !defined('XCART_START') ) { header("Location: home.php"); die("Access denied"); }

define('META_DATA_PARAM', 5);

if ($config["General"]["disable_outofstock_products"] == "Y") {
	$avail = ($config["General"]["unlimited_products"] =="N")? " AND $sql_tbl[products].avail>0 " : "";
	$current_category["product_count"] = func_query_first_cell ("SELECT COUNT(*) FROM $sql_tbl[products], $sql_tbl[products_categories] WHERE $sql_tbl[products].productid=$sql_tbl[products_categories].productid AND $sql_tbl[products].forsale='Y' $avail AND $sql_tbl[products_categories].categoryid='$cat'");
	if (is_array($subcategories)) {
		foreach($subcategories as $k=>$v) {
			$subcategories[$k]["product_count"] = func_query_first_cell ("SELECT COUNT(*) FROM $sql_tbl[products], $sql_tbl[products_categories] WHERE $sql_tbl[products].productid=$sql_tbl[products_categories].productid AND $sql_tbl[products].forsale='Y' $avail AND $sql_tbl[products_categories].categoryid='$v[categoryid]'");
		}
		$smarty->assign("subcategories",$subcategories);
	}
}

if ($active_modules["Advanced_Statistics"] && !defined("IS_ROBOT"))
    include $xcart_dir."/modules/Advanced_Statistics/cat_viewed.php";


#
# Get products data for current category and store it into $products array
#

$old_search_data = $search_data["products"];
$old_mode = $mode;

$search_data["products"] = array();
$search_data["products"]["categoryid"] = $cat;
$search_data["products"]["search_in_subcategories"] = "";
$search_data["products"]["category_main"] = "Y";
$search_data["products"]["category_extra"] = "Y";
$search_data["products"]["forsale"] = "Y";


#
##
###
if (!empty($active_modules['CIDEV_Best_Search_Filter'])) {

$search_data["products"]["search_in_subcategories"] = "Y";

x_session_register("sorted_filter_values_id");
x_session_register("filter_selected_brandids");
x_session_register("filter_prices");
//x_session_register("filter_selected_cat");
x_session_register("filter_selected_and_found_brands");
x_session_register("cidev_filters_tree_sorted");
x_session_register("filter_found_fv_ids_count");

x_session_register("filter_min_price_selected");
x_session_register("filter_max_price_selected");


$filter_selected_and_found_brands = "";
$cidev_filters_tree_sorted = "";
$filter_found_fv_ids_count = "";

/*
if ($filter_selected_cat != $cat){
        $filter_selected_cat = $cat;
        x_session_save("filter_selected_cat");
        $filter_prices = "";
}
*/

if ($f_mode == "clear"){
	$sorted_filter_values_id = "";
	$filter_selected_brandids = "";
	$filter_prices = "";
	$filter_min_price_selected = "";
	$filter_max_price_selected = "";
	x_session_save("filter_min_price_selected");
	x_session_save("filter_max_price_selected");
	x_session_save("filter_prices");
	x_session_save("filter_selected_brandids");
	x_session_save("sorted_filter_values_id");
	func_header_location($xcart_web_dir . "/".$dispatched_request."/");
}
elseif ($f_mode == "f_search"){

	# Filter attributes
	if (!empty($fv_ids) && is_array($fv_ids)){
		$sorted_filter_values_id = array();
		if (!empty($cidev_filters_tree)){
			foreach ($cidev_filters_tree as $k => $v){
				if (!empty($v["filter_values"]) && is_array($v["filter_values"])){
					foreach ($v["filter_values"] as $kk => $filter_value){
						foreach ($fv_ids as $fv_id => $vvv){
							if ($vvv == "Y" && $fv_id == $filter_value["fv_id"] && $filter_value["fv_active"] == "Y"){
								$sorted_filter_values_id[$v["f_id"]][] = $fv_id;
							}
						}
					}
				}
			}
		}

	} else {
		$sorted_filter_values_id = "";
	}

	x_session_save("sorted_filter_values_id");

        # Prices
	if (!empty($filter_prices) && is_array($filter_prices)){

		foreach ($filter_prices as $k => $v){
			unset($filter_prices[$k]["selected"]);
		}

	        if ((!empty($p_ids) && is_array($p_ids))){

		        $filter_min_price_selected = "";
		        $filter_max_price_selected = 0;

			foreach ($filter_prices as $k => $v){
				foreach ($p_ids as $kk => $vv){
					if ($k == $kk && $vv == "Y"){
						$filter_prices[$k]["selected"] = "Y";
						
						if ($filter_min_price_selected == ""){
							$filter_min_price_selected = $v["min_price"];
						}

						if ($v["max_price"] > $filter_max_price_selected){
							$filter_max_price_selected = $v["max_price"];
						}
					}
				}
			}

			if ($filter_max_price_selected == "0"){
			        $filter_min_price_selected = "";
			        $filter_max_price_selected = "";
			}

		} else {
			if (empty($price_ids_range)){
				$filter_prices = "";
                                $filter_min_price_selected = "";
                                $filter_max_price_selected = "";
			} else {

				$filter_prices[0]["min_price"] = $filter_min_price_selected;
				$filter_prices[0]["max_price"] = $filter_max_price_selected;
				$filter_prices[0]["selected"] = "Y";


			}
		}
	}
	x_session_save("filter_prices");
        x_session_save("filter_min_price_selected");
        x_session_save("filter_max_price_selected");

	# Brands
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

	$cidev_redirect_url = $xcart_web_dir ."/".$dispatched_request."/";

        if ($count_filter_selected_brandids == "1" && $cidev_clean_url_type == "C"){
		$b_id = $filter_selected_brandids[0];
        	$brand_clean_url = func_query_first_cell("SELECT clean_url FROM  $sql_tbl[clean_urls] WHERE resource_type='M' AND resource_id='$b_id'");
		$cidev_redirect_url .= $brand_clean_url."/";
        } 

//func_print_r($mode);
//die("asd");

	func_header_location($cidev_redirect_url);
}


//func_print_r($sorted_filter_values_id);


if ($cat_with_one_brand_filter != 'Y'){
	if (!empty($filter_selected_brandids)){
		$count_filter_selected_brandids = count($filter_selected_brandids);
		if ($count_filter_selected_brandids == "1" && $cidev_clean_url_type == "C"){
			$b_id = $filter_selected_brandids[0];
			$brand_clean_url = func_query_first_cell("SELECT clean_url FROM  $sql_tbl[clean_urls] WHERE resource_type='M' AND resource_id='$b_id'");
		        $cidev_redirect_url = $xcart_web_dir ."/".$dispatched_request."/".$brand_clean_url."/";
			func_header_location($cidev_redirect_url);
		}
	}
} else {
	if (empty($filter_selected_brandids) && !empty($brandid_in_url)){
		$filter_selected_brandids[0] = $brandid_in_url;
		x_session_save("filter_selected_brandids");
	}
}


$search_data['products']['sorted_filter_values_id'] = $sorted_filter_values_id;
$search_data['products']['filter_selected_brandids'] = $filter_selected_brandids;
$search_data['products']['filter_prices'] = $filter_prices;


if ($filter_max_price_selected > 0){
	$search_data['products']['price_min'] = $filter_min_price_selected;
	$search_data['products']['price_max'] = $filter_max_price_selected;
} else {
        $search_data['products']['price_min'] = "";
        $search_data['products']['price_max'] = "";
}


//func_print_r($filter_prices);

if (!empty($filter_selected_brandids) && is_array($filter_selected_brandids)){

        $imploded_brandids = implode(",", $filter_selected_brandids);
        $filter_selected_brands = func_query("SELECT brandid, brand FROM $sql_tbl[brands] WHERE brandid IN ($imploded_brandids) ORDER BY brand");

	if (!empty($filter_selected_brands) && is_array($filter_selected_brands)){
		foreach($filter_selected_brands as $k => $v){
			$filter_selected_brands[$k]["selected"] = 'Y';
		}
	}
}

}
###
##
#
//func_print_r($sorted_filter_values_id, $filter_selected_brandids, $filter_selected_brands);


if(!isset($sort))
	$sort = $config["Appearance"]["products_order"];
if(!isset($sort_direction))
    $sort_direction = 0;

$mode = "search";

include $xcart_dir."/include/search.php";

#
##
###
if (!empty($active_modules['CIDEV_Best_Search_Filter'])) {

$search_query_count_NEW = str_replace("  ", " ", $search_query_count_NEW);

if (!empty($cidev_filters_tree) && is_array($cidev_filters_tree)){
	foreach ($cidev_filters_tree as $k => $v){
		if (!empty($v["filter_values"]) && is_array($v["filter_values"])){
			foreach ($v["filter_values"] as $kk => $tree_filter_values) {

				if (!empty($sorted_filter_values_id) && is_array($sorted_filter_values_id)){
					foreach ($sorted_filter_values_id as $k_sorted => $fv_ids){
						if (!empty($fv_ids) && is_array($fv_ids)){
							foreach ($fv_ids as $fv_id){
								if ($fv_id == $tree_filter_values["fv_id"]){
									$cidev_filters_tree[$k]["filter_values"][$kk]["selected"] = 'Y';
								}
							}
						}
					}
				}

				if (!empty($filter_found_fv_ids) && is_array($filter_found_fv_ids)){
					foreach ($filter_found_fv_ids as $fv_id){
						if ($fv_id == $tree_filter_values["fv_id"]){
							$cidev_filters_tree[$k]["filter_values"][$kk]["found"] = 'Y';

							if ($cidev_filters_tree[$k]["filter_values"][$kk]["selected"] == 'Y'){
								$cidev_filters_tree[$k]["filter_values"][$kk]["selected_and_found"] = 'Y';
							}
						}
					}
				}
			}
		}
	}


###
/*
        $seach_query_fv_count = $search_query_count_NEW;
        $seach_query_fv_count_arr = explode("GROUP BY", $seach_query_fv_count);
//print($seach_query_fv_count);
        foreach ($cidev_filters_tree as $k => $v){
                if (!empty($v["filter_values"]) && is_array($v["filter_values"])){
                        foreach ($v["filter_values"] as $kk => $tree_filter_values) {
				if ($tree_filter_values["found"] == "Y" || $tree_filter_values["selected"] == "Y"){

                $filter_fv_sub_query = "$sql_tbl[cidev_filter_products].fv_id='".$tree_filter_values["fv_id"]."'";
//                $seach_query_fv_count = $seach_query_fv_count_arr[0] . " AND " . $filter_fv_sub_query . " GROUP BY " . $seach_query_fv_count_arr[1];
		$seach_query_fv_count = $seach_query_fv_count_arr[0] . " AND " . $filter_fv_sub_query . " GROUP BY $sql_tbl[products].productid";
//print("<br /><br />");
//print($seach_query_fv_count);

                $seach_query_fv_count_products = db_query($seach_query_fv_count);
                $filter_count_fv = db_num_rows($seach_query_fv_count_products);
                db_free_result($seach_query_fv_count_products);
                $cidev_filters_tree[$k]["filter_values"][$kk]["count_products"] = $filter_count_fv;


				}
                        }
                }
	}
*/
###


        foreach ($cidev_filters_tree as $k => $v){
                if (!empty($v["filter_values"]) && is_array($v["filter_values"])){

			$tmp_selected_fv_arr = array();
			$tmp_without_selected_fv_arr = $v["filter_values"];

                        foreach ($v["filter_values"] as $kk => $tree_filter_values) {
				if ($tree_filter_values["selected"] == "Y"){
					$tmp_selected_fv_arr[] = $tree_filter_values;
					unset($tmp_without_selected_fv_arr[$kk]);
				}
                        }

			$show_N_fvalues = 5;
			if (!empty($tmp_selected_fv_arr) && is_array($tmp_selected_fv_arr)){

				$count_tmp_selected_fv_arr = count($tmp_selected_fv_arr);
				if ($count_tmp_selected_fv_arr > $show_N_fvalues){
					$show_N_fvalues = $count_tmp_selected_fv_arr;
				}

				$new_filter_values = array();

				foreach ($tmp_selected_fv_arr as $kn => $vn){
					$new_filter_values[] = $vn;
				}
		
				if (!empty($tmp_without_selected_fv_arr) && is_array($tmp_without_selected_fv_arr)){
					foreach ($tmp_without_selected_fv_arr as $kn => $vn){
						$new_filter_values[] = $vn;
					}
				}

				$cidev_filters_tree[$k]["filter_values"] = $new_filter_values;
			}

//$show_N_fvalues = 1;

			$cidev_filters_tree[$k]["show_N_fvalues"] = $show_N_fvalues;
                }
        }

	$cidev_filters_tree_sorted = $cidev_filters_tree;
	$smarty->assign("cidev_filters_tree_sorted", $cidev_filters_tree_sorted);
}
x_session_save("cidev_filters_tree_sorted");
x_session_save("filter_found_fv_ids_count");

//func_print_r($sorted_filter_values_id);
//func_print_r($cidev_filters_tree_sorted);


if (!empty($filter_found_brands) && is_array($filter_found_brands)){

	$filter_selected_and_found_brands = $filter_found_brands;

	if (!empty($filter_selected_brands) && is_array($filter_selected_brands)){
		foreach($filter_selected_brands as $k => $v){
			$the_same_brand_is_found = false;
			foreach ($filter_selected_and_found_brands as $kk => $vv){
				if ($v["brandid"] == $vv["brandid"]){
					$the_same_brand_is_found = true;
					$filter_selected_and_found_brands[$kk]["selected_and_found"] = 'Y';
					$filter_selected_and_found_brands[$kk]["selected"] = 'Y';
				}
			}

			if (!$the_same_brand_is_found){
				$filter_selected_and_found_brands[] = $v;
			}
		}
	}

} else {
        if (!empty($filter_selected_brands) && is_array($filter_selected_brands)){
		$filter_selected_and_found_brands = $filter_selected_brands;
	}
}

if (!empty($filter_selected_and_found_brands) && is_array($filter_selected_and_found_brands)){

	$seach_query_brands_count = $search_query_count_NEW;

//print($seach_query_brands_count);

	$seach_query_brands_count_arr = explode("GROUP BY", $seach_query_brands_count);

	$count_selected_brands = 0;
	foreach ($filter_selected_and_found_brands as $k => $v){
		$filter_brand_sub_query = "$sql_tbl[products].brandid='".$v["brandid"]."'";
		$seach_query_brands_count = $seach_query_brands_count_arr[0] . " AND " . $filter_brand_sub_query . " GROUP BY " . $seach_query_brands_count_arr[1];
                $seach_query_brands_count_products = db_query($seach_query_brands_count);
                $filter_count_brands = db_num_rows($seach_query_brands_count_products);
                db_free_result($seach_query_brands_count_products);
                $filter_selected_and_found_brands[$k]["count_products"] = $filter_count_brands;

		if ($v["selected"] == "Y"){
			$count_selected_brands++;
		}
	}

//func_print_r($filter_selected_and_found_brands);

	$filter_selected_and_found_brands = my_array_sort($filter_selected_and_found_brands, 'brand');

/*
        if ($count_selected_brands > 0){
		$tmp_selected_brands_arr = array();

                foreach ($filter_selected_and_found_brands as $k => $v){
			if ($v["selected"] == "Y"){
				$tmp_selected_brands_arr[] = $v;
				unset($filter_selected_and_found_brands[$k]);
			}
                }

		$new_filter_selected_and_found_brands = array();
		foreach ($tmp_selected_brands_arr as $k => $v){
			$new_filter_selected_and_found_brands[] = $v;
		}

                foreach ($filter_selected_and_found_brands as $k => $v){
			$new_filter_selected_and_found_brands[] = $v;
                }

		$filter_selected_and_found_brands = $new_filter_selected_and_found_brands;
        }
*/


	$smarty->assign("filter_selected_and_found_brands", $filter_selected_and_found_brands);

	$show_N_brands = 5;
	if ($count_selected_brands > 5){
		$show_N_brands = $count_selected_brands;
	}

//$show_N_brands = 1;
	$smarty->assign("show_N_brands", $show_N_brands);

}
x_session_save("filter_selected_and_found_brands");
###
##
#


//if (!empty($filter_prices)){
	$filter_prices_old = $filter_prices;
	$filter_prices = "";
//}


//if (empty($filter_prices)){


	if ($filter_max_price_selected > 0){
		$filter_min_price = $filter_min_price_selected;
		$filter_max_price = $filter_max_price_selected;
	} else {
		$search_query_prices = $search_query_count_NEW;
		$search_query_prices = preg_replace('/SELECT(.*?)FROM/is', "SELECT xcart_pricing.price FROM", $search_query_prices);
		$search_query_max_price = $search_query_prices . " ORDER BY xcart_pricing.price DESC LIMIT 1";
		$filter_max_price = func_query_first_cell($search_query_max_price);
		$filter_min_price = 0;
	}

	if ($filter_max_price > 0){
		$filter_range_price = ($filter_max_price - $filter_min_price)/5;

		$start_price_flag = false;
		foreach ($filter_price_ranges as $k => $v){
			$next_k = $k + 1;
			if ($filter_range_price > $v){
				$filter_range_step_price = $filter_price_ranges[$next_k];
			}
		}

		for ($i = 0; $i < 5; $i++) {
			$min_price = $i * $filter_range_step_price + $filter_min_price;
			$max_price = $min_price + $filter_range_step_price;

			$filter_prices[$i]["min_price"] = $min_price;
			$filter_prices[$i]["max_price"] = $max_price;

			if (!empty($filter_prices_old) && is_array($filter_prices_old)){
	       		        if ($filter_prices_old[$i]["min_price"] == $min_price && $filter_prices_old[$i]["max_price"] == $max_price){
					$filter_prices[$i]["selected"] = $filter_prices_old[$i]["selected"];
	        	        }
			}

			if ($max_price > $filter_max_price){
				break;
			}
		}
	}
//}
x_session_save("filter_prices");



if (!empty($filter_prices)){

//print($search_query_count_NEW . "<br /><br />");

	if (strpos($search_query_count_NEW, '((xcart_pricing.price >=')!==false){
		$filter_price_is_checked = true;
	} else {
		$search_query_prices_range_arr = explode("GROUP BY", $search_query_count_NEW);
		$filter_price_is_checked = false;
	}

	foreach ($filter_prices as $k => $v){

		$filter_price_sub_query = "((xcart_pricing.price >='".$v["min_price"]."' AND xcart_pricing.price <='".$v["max_price"]."'))";

		if ($filter_price_is_checked){
			$search_query_prices_range = $search_query_count_NEW;
			$search_query_prices_range = preg_replace('/\(\(xcart_pricing.price >=(.*?)\)\)/is', $filter_price_sub_query, $search_query_prices_range);
		} else {
			$search_query_prices_range = $search_query_prices_range_arr[0] . " AND " . $filter_price_sub_query . " GROUP BY " . $search_query_prices_range_arr[1];
		}

		$search_query_prices_range_products = db_query($search_query_prices_range);
		$count_search_query_prices_range_products = db_num_rows($search_query_prices_range_products);
		db_free_result($search_query_prices_range_products);
		$filter_prices[$k]["count_products"] = $count_search_query_prices_range_products;
	}

	$smarty->assign("filter_prices", $filter_prices);

	$smarty->assign("filter_min_price_selected", $filter_min_price_selected);
	$smarty->assign("filter_max_price_selected", $filter_max_price_selected);

//func_print_r($filter_prices, $filter_min_price_selected, $filter_max_price_selected);

}



if (!empty($subcategories) && is_array($subcategories)){

	$search_query_count_NEW_SUB_CAT = $search_query_count_NEW;
	$search_query_count_NEW_SUB_CAT = preg_replace('/SELECT(.*?)FROM/is', "SELECT COUNT(*) FROM", $search_query_count_NEW_SUB_CAT);
	$search_query_count_NEW_SUB_CAT = preg_replace('/xcart_products_categories.categoryid IN(.*?)\)/is', "xcart_products_categories.categoryid IN (____XXXX____)", $search_query_count_NEW_SUB_CAT);

	$cidev_subcategories_products_count = array();
	foreach ($subcategories as $k => $v){

		$tmp_categoryid_path = addslashes(func_query_first_cell("SELECT categoryid_path FROM $sql_tbl[categories] WHERE categoryid='".$v["categoryid"]."'"));
                $tmp_categoryids = func_query_column("SELECT categoryid FROM $sql_tbl[categories] WHERE categoryid='".$v["categoryid"]."' OR categoryid_path LIKE '$tmp_categoryid_path/%'");
		$tmp_categoryids_imploded = implode(",", $tmp_categoryids);
		$search_query_count_NEW_SUB_CAT_query = str_replace("____XXXX____", $tmp_categoryids_imploded, $search_query_count_NEW_SUB_CAT);
		$subcategories_count_products = db_query($search_query_count_NEW_SUB_CAT_query);
		$COUNT_products_in_subcat = db_num_rows($subcategories_count_products);
		db_free_result($subcategories_count_products);

		$cidev_subcategories_products_count[$k]["categoryid"] = $v["categoryid"];
		$cidev_subcategories_products_count[$k]["count_products"] = $COUNT_products_in_subcat;
	}

	$smarty->assign("cidev_subcategories_products_count", $cidev_subcategories_products_count);
}


}
###
##
#

//func_print_r($cidev_subcategories_products_count);
	
if (is_array($current_category)) {
	if (is_array($products)) {
		$fp_num = (count($products) >= META_DATA_PARAM) ? META_DATA_PARAM : count($products);
		$first_products = array_slice($products, 0, $fp_num);
		$q_first_products = count($first_products);
	} else {
		$first_products = array();
		$q_first_products = 0;
	}

	if ($q_first_products < META_DATA_PARAM && is_array($subcategories)) {
		$rest = META_DATA_PARAM - $q_first_products;
		$fs_num = (count($subcategories) >= $rest) ? $rest : count($subcategories);
		$first_subcats = array_slice($subcategories, 0, $fs_num);
	}
		
	if (!isset($first_subcats) || !is_array($first_subcats)) {
		$first_subcats = array();
	}

	$meta_descr = array();

	foreach ($first_products as $fp) {
		$meta_descr[] = $fp['product'];
	}
	foreach ($first_subcats as $fs) {
		$meta_descr[] = $fs['category'];
	}
	$meta_descr = implode(', ', $meta_descr);
		
	$current_category['meta_descr'] = trim(strip_tags($current_category['category'] . ': ' . $meta_descr));
}

$search_data["products"] = $old_search_data;
$mode = $old_mode;

if (!empty($active_modules["Subscriptions"])) {
    include $xcart_dir."/modules/Subscriptions/subscription.php";
}

$smarty->assign("products",$products);
//$smarty->assign("navigation_script","home.php?cat=$cat&sort=$sort&sort_direction=$sort_direction");

#
##
###
if (!empty($cidev_orig_dispatched_request)){
        $cidev_script = $cidev_orig_dispatched_request."/?";
} else {
        $cidev_script = "home.php?";
        if (!empty($cat)){
                $cidev_script .= "cat=".$cat;
        }
}

//func_print_r($cidev_script);

$cidev_navigation_script = $cidev_script.($_GET["sort"] ? "&sort=".$sort : "").($sort_direction ? "&sort_direction=".$sort_direction : "");
//if (substr($cidev_navigation_script, -1) == "?") $cidev_navigation_script = substr($cidev_navigation_script, 0, -1);
if (strpos($cidev_navigation_script, "?&") !== false) $cidev_navigation_script = str_replace("?&", "?", $cidev_navigation_script);

//func_print_r($cidev_navigation_script);

$smarty->assign("navigation_script", $cidev_navigation_script);
###
##
#
?>
