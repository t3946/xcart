<?php

require "./auth.php";
require $xcart_dir."/include/security.php";

x_session_register("search_data");

$location[] = array("Shipping quotes log", "");

if ($REQUEST_METHOD == "POST") {

        if ($mode == "update" && !empty($reviewed) && is_array($reviewed)) {
		foreach ($reviewed as $k => $v){
			db_query("UPDATE $sql_tbl[shipping_quote_log] SET reviewed='Y', reviewed_by='$login', reviewed_date='".time()."' WHERE quote_id='$k'");
		}
	}

	func_header_location("shipping_quotes_log.php?mode=search");
}

if ($mode == "search") {

	if (!empty($page) && $search_data["shipping_quotes_log"]["page"] != intval($page)) {
        	# Store the current page number in the session
	        $search_data["shipping_quotes_log"]["page"] = $page;
//        	$flag_save = true;
	} else {
		$search_data["shipping_quotes_log"]["page"] = 1;
	}

//	if ($flag_save)
        	x_session_save("search_data");

	$data['_objects_per_page'] = $config["Appearance"]["users_per_page_admin"];
//	$data['_objects_per_page'] = "3";

	$time_30 = time() - 60*60*24*30;
	$where = "WHERE datetime > $time_30";

	$total_items = func_query_first_cell($q="SELECT COUNT(DISTINCT(quote_id)) FROM $sql_tbl[shipping_quote_log] $where");

	if (!empty($data['_objects_per_page'])) {
	        #
        	# Prepare the page navigation
	        #
        	$page = $search_data["shipping_quotes_log"]["page"];
	        $objects_per_page = $data['_objects_per_page'];
        	$total_nav_pages = ceil($total_items/$objects_per_page)+1;

	        include $xcart_dir."/include/navigation.php";

        	$sort_string .= " LIMIT $first_page, $objects_per_page";
	}

	$distinct_quote_id = func_query("SELECT DISTINCT quote_id FROM $sql_tbl[shipping_quote_log] $where ORDER BY reviewed,DATE(FROM_UNIXTIME( datetime)) desc, HOUR(FROM_UNIXTIME(datetime)) desc, session_id DESC, quote_id DESC".$sort_string);

	if (!empty($distinct_quote_id) && is_array($distinct_quote_id)){
		foreach ($distinct_quote_id as $k => $v){

			$logs = func_query("SELECT * FROM $sql_tbl[shipping_quote_log] WHERE quote_id='$v[quote_id]'");

			if (!empty($logs)){
				foreach ($logs as $kk => $vv){

					if ($vv["ups_ground_quote"] > 0 || $vv["approx_ground_quote"] > 0){
						$check1 = min($vv["ups_ground_quote"], $vv["approx_ground_quote"]) / max($vv["ups_ground_quote"], $vv["approx_ground_quote"]);
						if ($check1 < 0.9 || empty($vv["ups_ground_quote"]) || $vv["ups_ground_quote"] == "0.00" || empty($vv["approx_ground_quote"]) || $vv["approx_ground_quote"] == "0.00"){
							$logs[$kk]["pink_ground_quote"] = "Y";
						}
					} else {
						$logs[$kk]["pink_ground_quote"] = "Y";
					}

					if ($vv["approx_ground_quote"] > 0){
						$check2 = $vv["product_cost"] / $vv["approx_ground_quote"];
						if ($vv["approx_ground_quote"] > 0 && $check2 < 1){
							$logs[$kk]["pink_approx_ground_quote_AND_product_cost"] = "Y";
						}
					} else {
						$logs[$kk]["pink_approx_ground_quote_AND_product_cost"] = "Y";
					}

					$logs[$kk]["orders"] = func_query("SELECT orderid, order_prefix FROM $sql_tbl[orders] WHERE login='$vv[customer_id]'");
					$logs[$kk]["manufacturer"] = func_query_first_cell("SELECT manufacturer FROM $sql_tbl[manufacturers] WHERE manufacturerid='$vv[manufacturerid]'");
					$logs[$kk]["manufacturer_code"] = func_query_first_cell("SELECT code FROM $sql_tbl[manufacturers] WHERE manufacturerid='$vv[manufacturerid]'");
					$products = func_query("SELECT $sql_tbl[products].product, $sql_tbl[products].productid, $sql_tbl[products_sf].sfid, $sql_tbl[shipping_quote_products_log].qty FROM $sql_tbl[products] LEFT JOIN $sql_tbl[products_sf] ON $sql_tbl[products_sf].productid=$sql_tbl[products].productid LEFT JOIN $sql_tbl[shipping_quote_products_log] ON $sql_tbl[shipping_quote_products_log].productid=$sql_tbl[products].productid WHERE $sql_tbl[shipping_quote_products_log].manufacturerid='$vv[manufacturerid]' AND $sql_tbl[shipping_quote_products_log].quote_id='$v[quote_id]'");

					if (!empty($products)){
						foreach ($products as $kkk => $vvv){
							$domain = func_query_first_cell("SELECT domain FROM $sql_tbl[storefronts] WHERE storefrontid='$vvv[sfid]'");
							if (empty($domain)){
								$domain = "www.artistsupplysource.com";
							}
							
							$url = "http://".$domain."/product.php?productid=".$vvv["productid"];

							$products[$kkk]["url"] = $url;
						}
					}

					$logs[$kk]["products"] = $products;

					if (!empty($vv["ab_options"])){
						$ab_options = "";
						$ab_options_arr = explode("<br />", $vv["ab_options"]);

						foreach ($ab_options_arr as $ab_options_str){
							$ab_option_arr = explode("/", $ab_options_str);
							$point_id = $ab_option_arr[0];
							$variant_id = $ab_option_arr[1];

							$point_name = func_query_first_cell("SELECT point_name FROM $sql_tbl[ab_testing_points] WHERE point_id='$point_id'");
							$variant_name = func_query_first_cell("SELECT variant_name FROM $sql_tbl[ab_point_variants] WHERE point_id='$point_id' AND variant_id='$variant_id'");
							if (!empty($ab_options)) $ab_options .= "<br />";
							$ab_options .= $point_name."/".$variant_name;
						}

						$logs[$kk]["ab_options"] = $ab_options;
					}

				}
			}

			$shipping_quote_logs[$k] = $logs;
		}

                # Assign the Smarty variables
                $smarty->assign("navigation_script", "shipping_quotes_log.php?mode=search");
		$smarty->assign("shipping_quote_logs", $shipping_quote_logs);
                $smarty->assign("first_item", $first_page+1);
                $smarty->assign("last_item", min($first_page+$objects_per_page, $total_items));
	}

        $smarty->assign("total_items", $total_items);
        $smarty->assign("mode", $mode);

}

//func_print_r($shipping_quote_logs);

$smarty->assign("main","shipping_quotes_log");

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
