<?php
        if (!empty($page)) {
			$page = abs(intval($page));
		}
		else {
			$page = 1;
		}

		if (isset($_GET['p']) && is_numeric($_GET['p'])) {
			$page = 1;
			$e_search_data["products_per_page"] = intval($_GET['p']) * intval($config["Appearance"]["products_per_page"]);
			$smarty->assign('ajax_navigation_page', intval($_GET['p']));
		}
		else {
			$e_search_data["products_per_page"] = intval($config["Appearance"]["products_per_page"]);
		}

/*
        if (!empty($current_storefront)){
                $site_domain = func_query_first_cell("SELECT domain FROM $sql_tbl[storefronts] WHERE storefrontid='$current_storefront'");
        } else {
                $site_domain = "www.artistsupplysource.com";
        }
*/

    if ($load_all_e_products) {
        $search_query = array("size"=>$e_search_data["total"], "from"=>0);
    }
    else {
        $from = $e_search_data["products_per_page"] * ($page - 1);
        $search_query = array("size"=>$e_search_data["products_per_page"], "from"=>$from);
    }

    $url = $config["ElasticSearch_options"]["es_url"].$site_domain."/product/_search?". http_build_query($search_query);

//    if (!empty($cat) && !empty($search_query)){
//
//        $tmp_search_query_arr = explode("ORDER BY", $search_query);
//        $tmp_search_query_arr = explode("FROM", $tmp_search_query_arr[0]);
//
//        $new_search_query_productids = "SELECT xcart_products.productid FROM ".$tmp_search_query_arr[1];
//        $new_search_query_productids_result = db_query($new_search_query_productids);
//
//        $all_productids_arr = array();
//        while ($v = db_fetch_array($new_search_query_productids_result)) {
//                $all_productids_arr[] = $v["productid"];
//        }
//    }

	if (!$load_all_e_products){
	        $e_search_data_substring = preg_replace("/[^0-9a-zA-Z\.\'\-]/S", " ", $e_search_data["substring"]);
        	$e_search_data_substring = trim($e_search_data_substring);
	}




        $classElastic = new Xcart\ElasticSearch($config["ElasticSearch_options"],$site_domain);
	    $classElastic->setSource("*._id");
		$classElastic->setMinScore($config["ElasticSearch_options"]["search_results_minimum_score_value"]);
		$classElastic->setType('product');
		$classElastic->setQueryParams($e_search_data_substring);
		//$classElastic->setMinScore("0.1");
		if (!empty($all_productids_arr) && is_array($all_productids_arr)){
			//$data_arr["filter"]["terms"]["_id"] = $all_productids_arr;
			$classElastic->setFilterTerms($all_productids_arr);
		}

		$result = $classElastic->query($search_query);

		if ($classElastic->hitsTotal < $config["ElasticSearch_options"]["results_count_if_less_than"] && !$load_all_e_products) {
			$classElastic->setMinScore("0");
			$classElastic->setType('product');
			$aQueryArray = array();
			$classElastic->setQueryParams($e_search_data_substring);

			$result = $classElastic->query($search_query);
			//$result["hits"]["total"] = $config["Appearance"]["products_per_page"];
		}

        $e_products = array();

        $manufacturer_product_feed_enabled = array();

        if (!empty($result["hits"]["hits"]) && is_array($result["hits"]["hits"]))
        {
                x_load("product");

                foreach ($result["hits"]["hits"] as $k => $v) {
                    $founded_ids = array('productid' => $v["_id"], 'categoryid' => array(), 'score' => $v['_score']);

                    if (!$load_all_e_products) {
                        $e_product_info = func_select_product($v["_id"], @$user_account['membershipid'], false);

                        if (!empty($e_product_info)) {

                            $e_products[$k] = $e_product_info;
                            if (!empty($e_products[$k]["clean_url"])) {
                                if (substr($e_products[$k]["clean_url"], -1) != "/") {
                                    $e_products[$k]["clean_url"] .= "/";
                                }
                            }
                        }
                    }

                    if ($load_all_e_products) {
                        $categories = func_query_column("SELECT categoryid FROM $sql_tbl[products_categories] WHERE productid='$v[_id]' ORDER BY FIELD(main, 'Y', 'N')");
                        $e_products[$k]["categoryid"] = (!empty($categories)) ? $categories[0] : '';
                    }
                }
                $e_products = array_values($e_products);
        }

	if (!$load_all_e_products){

	        $e_search_data["page"] = $page;
	        $e_search_data["total"] = $result["hits"]["total"];
	        $e_search_data["total_nav_pages"] = ceil($e_search_data["total"]/$e_search_data["products_per_page"])+1;

	        #
        	if ($cidev_dispatched_request_arr[0] == "keyword" && !empty($cidev_dispatched_request_arr[1])){
                	$area_selector = "keyword";
	        }
        	elseif ($cat == "0"){
	                $area_selector = "All";
        	} else {
                	$area_selector = $current_category["category"];
	        }

	        $smarty->assign("area_selector", $area_selector);

        	if (!defined("IS_ROBOT") && !empty($$XCART_SESSION_NAME)) {

#
##
			if (!empty($e_search_data["orig_substring"])){
				$log_search_phrase = $e_search_data["orig_substring"];
			} else {
				$log_search_phrase = $e_search_data["substring"];
			}

			$log_search_phrase = addslashes($log_search_phrase);

			if ($area_selector == "All"){
				$log_search_phrase = stripslashes($log_search_phrase);
			}
##
#

//	                $is_such_search_phrase = func_query_first_cell($qqq="SELECT id FROM $sql_tbl[search_stats] WHERE search_phrase='".$log_search_phrase."' AND customer_id='".$$XCART_SESSION_NAME."' AND area_selector='".addslashes($area_selector)."'");
	                $is_such_search_phrase = func_query_first_cell($qqq="SELECT id FROM $sql_tbl[search_stats] WHERE search_phrase='".$log_search_phrase."' AND customer_id='".$$XCART_SESSION_NAME."'");

        	        if (empty($is_such_search_phrase)){

/*
                	        $source_url = $site_domain;

                        	if (strpos($_SERVER["QUERY_STRING"], "request_uri=") !== false){
                                	$tmp_QUERY_STRING_arr = explode("request_uri=", $_SERVER["QUERY_STRING"]);
	                                $source_url .= array_pop($tmp_QUERY_STRING_arr);
        	                }
*/
//                	        db_query("INSERT INTO $sql_tbl[search_stats] (search_phrase, area_selector, customer_id, date_time, source_url, request_delay, hits, storefrontid, browser_agent) VALUES ('".$log_search_phrase."', '".addslashes($area_selector)."', '".$$XCART_SESSION_NAME."', '".time()."', '".addslashes($source_url)."', '".$result["took"]."', '".$result["hits"]["total"]."', '$current_storefront', '".addslashes($HTTP_USER_AGENT)."')");
                	        db_query("INSERT INTO $sql_tbl[search_stats] (search_phrase, customer_id, date_time, request_delay, hits, storefrontid) VALUES ('".$log_search_phrase."', '".$$XCART_SESSION_NAME."', '".time()."', '".$result["took"]."', '".$result["hits"]["total"]."', '$current_storefront')");
	                }
        	}
	        #

	        #
        	if (!empty($cidev_orig_dispatched_request)){
	                $cidev_script = $cidev_orig_dispatched_request."/?";
	        } else {

        	        if ($clean_url_data['resource_type'] == "K"){

                	        $cidev_script = $action_notify_url;

	                        if (strpos($cidev_script, "?") !== false){
        	                        $cidev_script_arr = explode("?", $cidev_script);
                	                $cidev_script = $cidev_script_arr[0];
	                        }

        	                if (strpos($cidev_script, "&") !== false){
                	                $cidev_script_arr = explode("&", $cidev_script);
                        	        $cidev_script = $cidev_script_arr[0];
	                        }

        	                $rest = substr($cidev_script, -1);
                	        if ($rest != "/"){
                        	        $cidev_script .= "/";
	                        }
//func_print_r($cidev_script);

        	        } else {
//              	        $cidev_script = "/home.php?e_mode=e_search";
                        	$cidev_script = "/home.php";
	                        if (!empty($cat)){
//      	                        $cidev_script .= "&cat=".$cat;
                	                $cidev_script .= "?cat=".$cat;
                        	}
	                }
	        }

        	$cidev_navigation_script = $cidev_script.($_GET["sort"] ? "&sort=".$sort : "").($sort_direction ? "&sort_direction=".$sort_direction : "");
	        if (strpos($cidev_navigation_script, "?&") !== false) $cidev_navigation_script = str_replace("?&", "?", $cidev_navigation_script);

	        $smarty->assign("navigation_script", $cidev_navigation_script);
	        #

        	#
	        $objects_per_page = $e_search_data["products_per_page"];
        	$total_nav_pages = $e_search_data["total_nav_pages"];
	        $total_items = $e_search_data["total"];
        	$page = $e_search_data["page"];
	        include $xcart_dir."/include/navigation.php";
        	#

			$first_item = $e_search_data["products_per_page"] * ($e_search_data["page"] - 1) + 1;
			$last_item = $e_search_data["products_per_page"] * $e_search_data["page"];
			if ($last_item > $e_search_data["total"]) {
				$last_item = $e_search_data["total"];
			}

		$smarty->assign('first_item', $first_item);
		$smarty->assign('last_item', $last_item);
		$smarty->assign('total_items', $e_search_data["total"]);


#
##
		$e_search_data["current_categoryid"] = $cat;
##
#

	        $smarty->assign("e_products_found", "Y");
        	$smarty->assign("products", $e_products);

	        $smarty->assign("e_search_data", $e_search_data);

#
##
		if (empty($cidev_filter_mode)){
//      	if ($e_search_data["total_nav_pages"] == "1" && $e_search_data["total"] == "0"){
                	$from_script = "elastic_search.php";
                	include $xcart_dir."/cidev_phrase_suggester_json.php";
//      	}
		}
##
#
		$smarty->assign('cidev_filter_mode','load_more_e_products');
		$smarty->assign('e_search_data_substring',$e_search_data_substring);


	}


#
##
###
        $e_search_data["substring"] = "";
        $e_search_data["orig_substring"] = "";
###
##
#

//func_print_r($e_products);

        x_session_save("e_search_data");

//func_print_r($e_search_data);

//func_print_r($url, $data_arr, $data_json, $result);
// func_print_r($url, $data_json, $result, $e_search_data);
//func_print_r($e_products, $cat);
 //die();


?>
