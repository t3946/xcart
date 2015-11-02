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
$bench1 = func_microtime();

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
## Mobile
###
if ($top_btn == "Y"){
        $e_search_data["substring"] = "";
        x_session_save("e_search_data");


	if ($mobile_mode == "subcategories"){
		$redirect_url = "home.php?mobile_mode=subcategories";
	}
	elseif ($mobile_mode == "more"){
		$redirect_url = "home.php?mobile_mode=more";
	}
	else {
		$redirect_url = "/";
	}


	func_header_location($redirect_url);

}
###
##
#

#
##
###
if ($mode == "notify" && !empty($productid) && !empty($notify_email) && !empty($cat)){
        $is_in_table = func_query_first_cell("SELECT COUNT(sent) FROM $sql_tbl[notify_when_in_stock] WHERE email='$notify_email' AND sent='N' AND productid='$productid'");

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


if (!empty($cat)){
	include "./products.php";
}
else {
#
##
###
//    if (!$detect->isMobile()){
    if (!$detect_isMobile_was_created) {
	if (strpos($QUERY_STRING, "page=") !== false){
//	    func_header_location("home.php", true, 301);
	    func_header_location("/", true, 301);
	}
    }
###
##
#
}

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
	include $xcart_dir."/elastic_search.php";
}
else {
        $e_search_data = "";

	if (empty($products) && empty($keyphrase)) {
        	if (!empty($cat)){
                	include "./featured_products.php";
	        } else {
        	        include "./new_featured_products.php";
	        }
	}

	x_session_save("e_search_data");
}



if (!empty($keyphrase)) {
    include $xcart_dir . '/include/search_categories.php';
}

/*if ($active_modules["Bestsellers"])
	include $xcart_dir."/modules/Bestsellers/bestsellers.php";
*/

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

/*if (!empty($active_modules["Special_Offers"])) {
	include $xcart_dir."/modules/Special_Offers/category_offers.php";
}*/

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

/*	$menu_brands_query = "Select 
        B.brandid, B.brand, COUNT(distinct P.productid) as count
from xcart_orders O
        left join xcart_order_details OD ON OD.orderid = O.orderid
        inner join xcart_products P ON P.productid = OD.productid and P.forsale = 'Y'
        inner join xcart_products_sf PS ON PS.productid = P.productid and PS.sfid = '$current_storefront'
        left join xcart_brands B ON B.brandid = P.brandid
Where FROM_UNIXTIME(O.date) > DATE_ADD(NOW(), INTERVAL -".$config["Brands"]["depth_in_months_to_calculate_brands_powers"]." MONTH)
Group By B.brandid
HAVING COUNT(distinct P.productid)>0
Order By 3 desc;";
*/
	$menu_brands_query = "Select 
        B.brandid, B.brand, COUNT(distinct P.productid) as count
from xcart_brands B
        inner join xcart_products P ON P.brandid = B.brandid and P.forsale = 'Y'
        inner join xcart_products_sf PS ON PS.productid = P.productid and PS.sfid = '$current_storefront'
Group By B.brandid
/*HAVING COUNT(distinct P.productid)>0*/
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
$bench2 = func_microtime();
$smarty->assign("bench_name", "home.php");
$smarty->assign("bench_time", $bench2-$bench1);

func_display("customer/home.tpl",$smarty);

?>
