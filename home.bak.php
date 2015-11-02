<?php /* MODIFIED: random:18298_18304_18324 [2009 Jun 08 09:50][Custom development (Форма для отправки нотификаций "производителям" (X-Cart's Manufacturers) + Add new "Brands" module + Search URLs feature)] */ ?>
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
# $Id: home.php,v 1.10 2006/03/31 06:18:48 max Exp $
#

define('OFFERS_DONT_SHOW_NEW',1);
require "./auth.php";

$cat = isset($cat) ? abs(intval($cat)) : 0;

x_load("category");

#
##
###
x_session_register("e_search_data");
###
##
#

#
##
###
if ($mode == "notify" && !empty($productid) && !empty($notify_email) && !empty($cat)){
        $is_in_table = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[notify_when_in_stock] WHERE email='$notify_email' AND sent='N' AND productid='$productid'");

        if (empty($is_in_table)){

                $notify_when_in_stock[$productid] = "Y";
                x_session_save('notify_when_in_stock');

                db_query("INSERT INTO $sql_tbl[notify_when_in_stock] (productid, email, date) VALUES ('$productid', '$notify_email', '".time()."')");
		$top_message["content"] = 'Thank you! You will be notified when the product is in stock.';
                $top_message["type"] = "I";
        } else {
                $top_message["content"] = 'You already signed up for this notification.';
                $top_message["type"] = "E";
        }

	if (!empty($redirect_to_notify_url)){
		$clean_url_link = $redirect_to_notify_url;
	} else {
	        $clean_url_link = func_query_first_cell("SELECT clean_url FROM $sql_tbl[clean_urls] WHERE resource_type='C' AND resource_id='$cat'");
	}

	if (!empty($page)){
		$clean_url_link .= "&page=".$page;
	}

        func_header_location($clean_url_link);
}
###
##
#

if (
    $cat > 0
    && $config['SEO']['clean_urls_enabled'] == 'Y'
    && !defined('DISPATCHED_REQUEST')
) {
    func_clean_url_permanent_redirect('C', intval($cat));
}

require $xcart_dir."/include/categories.php";

# START: random:18298_18304_18324 [2009 Jun 08 09:50] 
if ($active_modules["Brands"])
    include $xcart_dir."/modules/Brands/customer_brands.php";
else
# END: random:18298_18304_18324 [2009 Jun 08 09:50] 
if ($active_modules["Manufacturers"])
    include $xcart_dir."/modules/Manufacturers/customer_manufacturers.php";


if (!empty($cat))
	include "./products.php";


#
##
###
if ($cidev_dispatched_request_arr[0] == "keyword" && !empty($cidev_dispatched_request_arr[1])){
	$e_search_data["substring"] = $cidev_dispatched_request_arr[1];
	$e_search_data["substring"] = str_replace("-", " ", $e_search_data["substring"]);
}

if ($REQUEST_METHOD == 'POST' && $e_mode == "e_search"){
        $e_search_data["substring"] = htmlspecialchars_decode($e_posted_data["substring"]);
	$e_search_data["substring"] = stripslashes($e_search_data["substring"]);
	$e_search_data["substring"] = trim($e_search_data["substring"]);
	$e_search_data["substring"] = str_replace("&#039;", "'", $e_search_data["substring"]);

//func_print_r($_POST);
//func_print_r($e_search_data["substring"]);
//die();

	x_session_save("e_search_data");

	if (!empty($e_current_url) && !empty($cat)){
		func_header_location($e_current_url);
	} else {
//		func_header_location("home.php");
		func_header_location("/");
	}
}


#
##
###
if (strpos($_SERVER["QUERY_STRING"], "request_uri=") !== false){
        $tmp_QUERY_STRING_arr = explode("request_uri=", $_SERVER["QUERY_STRING"]);
        $action_notify_url = array_pop($tmp_QUERY_STRING_arr);

        if (strpos($action_notify_url, "&") !== false){
                $action_notify_url_arr = explode("&", $action_notify_url);
                $action_notify_url = $action_notify_url_arr[0];
        }

        $smarty->assign("action_notify_url", $action_notify_url);
}
###
##
#

//func_print_r($action_notify_url);

//func_print_r($_POST, $_GET, $e_search_data);
//die();

//if ($e_mode == "e_search" && is_array($e_search_data) && !empty($e_search_data["substring"])){
if (is_array($e_search_data) && !empty($e_search_data["substring"])){

        $page = isset($page) ? abs(intval($page)) : 1;
        if (empty($page)) $page = 1;

        $e_search_data["products_per_page"] = intval($config["Appearance"]["products_per_page"]);

        if (!empty($current_storefront)){
                $tmp_domain = func_query_first_cell("SELECT domain FROM $sql_tbl[storefronts] WHERE storefrontid='$current_storefront'");
        } else {
                $tmp_domain = "www.artistsupplysource.com";
        }


        if ($page == "1"){
                $from = 0;
        } else {
                $from = $e_search_data["products_per_page"] * ($page - 1);
        }

        $url = $config["ElasticSearch_options"]["es_url"].$tmp_domain."/product/_search?size=".$e_search_data["products_per_page"]."&from=".$from;


        if (!empty($cat) && !empty($search_query)){

//print($search_query);
		$tmp_search_query_arr = explode("ORDER BY", $search_query);
		$tmp_search_query_arr = explode("FROM", $tmp_search_query_arr[0]);

		$new_search_query_productids = "SELECT xcart_products.productid FROM ".$tmp_search_query_arr[1];
		$new_search_query_productids_result = db_query($new_search_query_productids);

		$all_productids_arr = array();
		while ($v = db_fetch_array($new_search_query_productids_result)) {
			$all_productids_arr[] = $v["productid"];
		}
        }

	$e_search_data_substring = preg_replace("/[^0-9a-zA-Z\.\'\-]/S", " ", $e_search_data["substring"]);
	$e_search_data_substring = trim($e_search_data_substring);

        $data_arr["_source"] = "*._id";
        $data_arr["query"]["dis_max"]["queries"][0]["query_string"]["query"] = $e_search_data_substring;
        $data_arr["query"]["dis_max"]["queries"][0]["query_string"]["fields"] = array("productname.productname_original^1.5","sku","upc","brand.brand_original^0.5","description.description_original");
        $data_arr["query"]["dis_max"]["queries"][1]["query_string"]["query"] = $e_search_data_substring;
        $data_arr["query"]["dis_max"]["queries"][1]["query_string"]["analyzer"] = "snowball";
        $data_arr["query"]["dis_max"]["queries"][1]["query_string"]["fields"] = array("productname.productname","sku","upc","brand.brand","description.description");
        $data_arr["query"]["dis_max"]["queries"][1]["query_string"]["fields"] = array("productname.productname^1.5","sku","upc","brand.brand^0.5","description.description");
        $data_arr["query"]["dis_max"]["queries"][2]["match_phrase_prefix"]["sku_original"] = $e_search_data_substring;
        
/*        func_print_r($data_arr);*/

        if (!empty($all_productids_arr) && is_array($all_productids_arr)){
                $data_arr["filter"]["terms"]["_id"] = $all_productids_arr;
        }

        $data_json = json_encode($data_arr);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array ("Accept: application/json"));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result_json = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result_json, true);

	$e_products = array();

        if (!empty($result["hits"]["hits"]) && is_array($result["hits"]["hits"])){

                x_load("product");

                foreach ($result["hits"]["hits"] as $k => $v){
                        $e_products[$k] = func_select_product($v["_id"], @$user_account['membershipid'], false);
			if (!empty($e_products[$k]["clean_url"])){
                                if (substr($e_products[$k]["clean_url"], -1) != "/"){
                                        $e_products[$k]["clean_url"] .= "/";
                                }
			}
                }
        }

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

	if (!defined("IS_ROBOT") && !empty($$XCART_SESSION_NAME)) {
		$is_such_search_phrase = func_query_first_cell($qqq="SELECT id FROM $sql_tbl[search_stats] WHERE search_phrase='".addslashes($e_search_data["substring"])."' AND customer_id='".$$XCART_SESSION_NAME."' AND area_selector='".addslashes($area_selector)."'");

		if (empty($is_such_search_phrase)){

			$source_url = $tmp_domain;

			if (strpos($_SERVER["QUERY_STRING"], "request_uri=") !== false){
	        		$tmp_QUERY_STRING_arr = explode("request_uri=", $_SERVER["QUERY_STRING"]);
		        	$source_url .= array_pop($tmp_QUERY_STRING_arr);
			}

			db_query("INSERT INTO $sql_tbl[search_stats] (search_phrase, area_selector, customer_id, date_time, source_url, request_delay, hits, storefrontid, browser_agent) VALUES ('".addslashes($e_search_data["substring"])."', '".addslashes($area_selector)."', '".$$XCART_SESSION_NAME."', '".time()."', '".addslashes($source_url)."', '".$result["took"]."', '".$result["hits"]["total"]."', '$current_storefront', '".addslashes($HTTP_USER_AGENT)."')");
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
//	                $cidev_script = "/home.php?e_mode=e_search";
	                $cidev_script = "/home.php";
        	        if (!empty($cat)){
//                	        $cidev_script .= "&cat=".$cat;
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

        $smarty->assign("e_products_found", "Y");
	$smarty->assign("e_search_data", $e_search_data);
        $smarty->assign("products", $e_products);


//func_print_r($url, $data_arr, $data_json, $result);
// func_print_r($url, $data_json, $result, $e_search_data);
//func_print_r($e_products, $cat);
 //die();
}
else {
        $e_search_data = "";
###
##
#
	if (empty($products) && empty($keyphrase)) {
        	if (!empty($cat)){
                	include "./featured_products.php";
	        } else {
        	        include "./new_featured_products.php";
	        }
	}
}

#
##
###
x_session_save("e_search_data");
###
##
#


if (!empty($keyphrase)) {
    include $xcart_dir . '/include/search_categories.php';
}

if ($active_modules["Bestsellers"])
	include $xcart_dir."/modules/Bestsellers/bestsellers.php";


if (!empty($current_category) and is_array($current_category["category_location"])) {
	foreach ($current_category["category_location"] as $k => $v) {
//		$v[1] .= '&path='.$k;
		$location[] = $v;
	}
}

if (!empty($current_category) && is_array($location)) {
    $current_category['meta_keywords'] = '';
	foreach ($location as $l) {
		$current_category['meta_keywords'] = $l[0] . ', ' . $current_category['meta_keywords'];
	}
	$current_category['meta_keywords'] = trim(strip_tags(substr($current_category['meta_keywords'], 0, strlen($current_category['meta_keywords']) - 2)));
	$smarty->assign('current_category', $current_category);
}

if (!empty($active_modules["Special_Offers"])) {
	include $xcart_dir."/modules/Special_Offers/category_offers.php";
}

#
##
###
$tmp_count_location = count($location);
if (!empty($current_category) && is_array($location) && (empty($page) || $page == "1") ) {
        $counter_location = 0;
        foreach ($location as $k => $v) {
                $counter_location++;
                if ($counter_location == $tmp_count_location){
                        unset($location[$k][1]);
                }
        }
}
###
##
#

#
##
###
if ((empty($cat) || $cat=="0") && (empty($page) || $page == "1")){
        include './newproducts.php';
}
###
##
#

#
## Filter
###
if (empty($cat) || $cat=="0"){
	x_session_register("sorted_filter_values_id");
	x_session_register("filter_selected_brandids");
	x_session_register("filter_prices");
        $sorted_filter_values_id = "";
        $filter_selected_brandids = "";
        $filter_prices = "";
        x_session_save("filter_prices");
        x_session_save("filter_selected_brandids");
        x_session_save("sorted_filter_values_id");

        $filter_min_price_selected = "";
        $filter_max_price_selected = "";
        x_session_save("filter_min_price_selected");
        x_session_save("filter_max_price_selected");
}
###
##
#

#
## Brands
###
if ($config["Brands"]["enable_advanced_brands_block"] == "Y" && (empty($cat) || $cat=="0")){

	$menu_brands_query = "Select 
        B.brandid, B.brand, COUNT(distinct P.productid) as count
from xcart_orders O
        left join xcart_order_details OD ON OD.orderid = O.orderid
        inner join xcart_products P ON P.productid = OD.productid and P.forsale = 'Y'
        inner join xcart_products_sf PS ON PS.productid = P.productid and PS.sfid = '$current_storefront'
        left join xcart_brands B ON B.brandid = P.brandid
Where FROM_UNIXTIME(O.date) > DATE_ADD(NOW(), INTERVAL -".$config["Brands"]["depth_in_months_to_calculate_brands_powers"]." MONTH)
Group By B.brandid
Order By 3 desc;";

	$menu_brands = func_query($menu_brands_query);

	$count_menu_brands = count($menu_brands);

	if ($count_menu_brands > $config["Brands"]["brands_listed_count"]){
		$show_count_before_see_more = $config["Brands"]["brands_listed_count"];
		$smarty->assign("show_see_more", "Y");
	} else {
		$show_count_before_see_more = $count_menu_brands;
	}

	$smarty->assign("show_count_before_see_more", $show_count_before_see_more);
	$smarty->assign("menu_brands", $menu_brands);
	$smarty->assign("count_menu_brands", $count_menu_brands);

//func_print_r($menu_brands);
}
###
##
#


#
# Assign Smarty variables and show template
#
$smarty->assign("main","catalog");

# Assign the current location line
$smarty->assign("location", $location);

func_display("customer/home.tpl",$smarty);
echo '<meta name="bench" content="2.0" />';
?>
