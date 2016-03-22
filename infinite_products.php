<?php

//print_r($_POST);
//print_r($_GET);

if ($_POST["cidev_filter_mode"] == "load_more_products_SKU"){

/*
	$sku = $_POST["sku"];
	$_GET["sku"] = $sku;
	$mode = "search";
	$_GET["mode"] = $mode;
*/
	require "./top.inc.php";

	$search_all_website = true;
	$current_area = 'C';
	$page_pos = 500;

	require "./init.php";

	$index_sku_search = "Y";
	$smarty->assign('search_all_website', 'Y');

	x_session_register("sfids_of_products");

}
else {
	define('x_session_DO_NOT_save_to_db_var', 'cart');

	require './auth.php';
}


if ($REQUEST_METHOD == 'POST')
 {
	if (!empty($load_next_productids)){
		x_load("product");

		$productids_arr = explode("_", $load_next_productids);

		if (!empty($productids_arr)){
			$products = array();

//func_print_r($sfids_of_products);

			foreach ($productids_arr as $k => $productid){
				if (!empty($productid)){

					if ($cidev_filter_mode == "load_more_products_SKU"){ // This search for www.s3stores.com (!)

	                                        if (!isset($sfids_of_products[$productid])){
							$sfids_of_products[$productid] = array();
							$tmp_sfid = func_query_first_cell("SELECT sfid FROM $sql_tbl[products_sf] WHERE productid='$productid'");
                	                        }
						else {
							$tmp_sfid = func_query_first_cell("SELECT sfid FROM $sql_tbl[products_sf] WHERE productid='$productid' AND sfid NOT IN ('".implode("','",$sfids_of_products[$productid])."')");
						}

						$sfids_of_products[$productid][] = $tmp_sfid;
//func_print_r($sfids_of_products);
						$next_product = func_select_product($productid, 0, false, false, false, false, $tmp_sfid);
						
						if (empty($next_product["domain"])){
						    if (!empty($tmp_sfid)){
							$next_product["domain"] = func_query_first_cell("SELECT domain FROM $sql_tbl[storefronts] WHERE storefrontid='$tmp_sfid'");
						    } else {
							$next_product["domain"] = MAIN_SF_DOMAIN;
						    }
						}

						$next_product["storefrontid"] = $tmp_sfid;

						if (!empty($next_product["clean_url"])){
							$next_product["clean_url"] = "http://".$next_product["domain"]."/".$next_product["clean_url"]."/";
						}
						if (strpos($next_product["tmbn_url"], "cdn") !== false && strpos($next_product["tmbn_url"], "http") === false){
							$next_product["tmbn_url"] = "http://".$next_product["tmbn_url"];
						}

					} //  if ($cidev_filter_mode == "load_more_products_SKU") // This search for www.s3stores.com (!)
					else {
						$next_product = func_select_product($productid, 0, false);
					}

					if (!empty($next_product)){
						$products[] = $next_product;
					}
				}
			}

			$count_products = count($products);
                        $first_item = ($ajax_navigation_page_next - 1)*intval($config["Appearance"]["products_per_page"]) + 1;
                        $last_item = ($ajax_navigation_page_next - 1)*intval($config["Appearance"]["products_per_page"]) + $count_products;
                        $smarty->assign('first_item', $first_item);
                        $smarty->assign("last_item", $last_item);
                        $smarty->assign("total_items", $total_items);

//                        $smarty->assign("cat", $cat);
//                        $smarty->assign("ga_page_name", $ga_page_name);
		}
	}

	if ($cidev_filter_mode == "load_more_products"){

		x_session_save("sfids_of_products");

		x_session_register("search_data");

		$ajax_navigation_page = $ajax_navigation_page_next;
		$search_data["products"]["page"] = $ajax_navigation_page;

		$remember_search_data_products = $search_data["products"];

		$mode = "search";
//		$ajax_load_more_products = "Y";


////
//$smarty->assign('POST_VARS', $_POST);
////


		if (empty($products) || $mode_load_next_productids == "Y"){
			include $xcart_dir."/include/search.php";

                        if ($mode_load_next_productids == "Y"){

                                $next_productids = "";
                                if (!empty($products) && is_array($products)){
                                        $next_productids_arr = array();
                                        foreach($products as $k => $product){
                                                $next_productids_arr[] = $product["productid"];
                                        }
                                        $next_productids = implode("_", $next_productids_arr);
                                }
                                $smarty->assign('next_productids', $next_productids);


//func_print_r($next_productids);

                		$search_data["products"] = $remember_search_data_products;
		                x_session_save("search_data");

                                func_display('customer/main/infinite_products_load_next_productids.tpl', $smarty);
                                die();
                        }
		}
	
		$search_data["products"] = $remember_search_data_products;
		x_session_save("search_data");


		$ajax_load_time = $bench1 - func_microtime();
		$smarty->assign('ajax_load_time', abs($ajax_load_time));


		$smarty->assign('ajax_navigation_page', $ajax_navigation_page);
		$smarty->assign('ajax_search_data', $search_data["products"]);
		$smarty->assign('show_next_products', 'Y');

		$smarty->assign("products",$products);

                if (!empty($ga_page_name)){
                        $smarty->assign("ga_page_name",$ga_page_name);
                }

		func_display('customer/main/infinite_products.tpl', $smarty);
	}
	elseif ($cidev_filter_mode == "load_more_products_SKU"){

                x_session_register("search_data");

                $search_data = array();
                $search_data['products'] = array('by_sku' => 1,
                                     'forsale' => 'Y',
                                     'substring' => trim($sku)
                               );


                $ajax_navigation_page = $ajax_navigation_page_next;
                $search_data["products"]["page"] = $ajax_navigation_page;

                $remember_search_data_products = $search_data["products"];

                $mode = "search";
//                $ajax_load_more_products = "Y";

		if (empty($products) || $mode_load_next_productids == "Y"){
	                include $xcart_dir."/include/search.php";

                        if ($mode_load_next_productids == "Y"){

                                $next_productids = "";
                                if (!empty($products) && is_array($products)){
                                        $next_productids_arr = array();
                                        foreach($products as $k => $product){
                                                $next_productids_arr[] = $product["productid"];
                                        }
                                        $next_productids = implode("_", $next_productids_arr);
                                }
                                $smarty->assign('next_productids', $next_productids);

		                $search_data["products"] = $remember_search_data_products;
                		x_session_save("search_data");

                                func_display('customer/main/infinite_products_load_next_productids.tpl', $smarty);
                                die();
                        }
		}

                $search_data["products"] = $remember_search_data_products;
                x_session_save("search_data");


		$ajax_load_time = $bench1 - func_microtime();
                $smarty->assign('ajax_load_time', abs($ajax_load_time));


                $smarty->assign('ajax_navigation_page', $ajax_navigation_page);
                $smarty->assign('ajax_search_data', $search_data["products"]);
                $smarty->assign('show_next_products', 'Y');
                $smarty->assign("products",$products);

		if (!empty($ga_page_name)){
			$smarty->assign("ga_page_name",$ga_page_name);
		}

                func_display('customer/main/infinite_products.tpl', $smarty);
	}
	elseif ($cidev_filter_mode == "load_more_e_products"){

		x_session_register("e_search_data");
                x_session_register("search_data");

		$e_search_data["substring"] = $e_search_data_substring;

                $ajax_navigation_page = $ajax_navigation_page_next;
                $search_data["products"]["page"] = $ajax_navigation_page;

                $remember_search_data_products = $search_data["products"];

                $mode = "search";
//                $ajax_load_more_products = "Y";

		if (empty($products) || $mode_load_next_productids == "Y"){
	                include $xcart_dir."/include/search.php";
		
	                $search_data["products"] = $remember_search_data_products;
        	        x_session_save("search_data");

			$page = $ajax_navigation_page;
			include $xcart_dir."/elastic_search.php";

			if ($mode_load_next_productids == "Y"){

				$next_productids = "";
				if (!empty($e_products) && is_array($e_products)){
					$next_productids_arr = array();
					foreach($e_products as $k => $product){
						$next_productids_arr[] = $product["productid"];
					}
					$next_productids = implode("_", $next_productids_arr);
				}
				$smarty->assign('next_productids', $next_productids);

				func_display('customer/main/infinite_products_load_next_productids.tpl', $smarty);
				die();
			}
		}
		else {
                        $search_data["products"] = $remember_search_data_products;
                        x_session_save("search_data");

			$smarty->assign("products", $products);
		}


		$ajax_load_time = $bench1 - func_microtime();
                $smarty->assign('ajax_load_time', abs($ajax_load_time));


		$smarty->assign('products_template', $products_template);
		$smarty->assign('ajax_navigation_page', $ajax_navigation_page);
		$smarty->assign('show_next_products', 'Y');

                if (!empty($ga_page_name)){
                        $smarty->assign("ga_page_name",$ga_page_name);
                }

		func_display('customer/main/infinite_products.tpl', $smarty);
	}
}
?>
