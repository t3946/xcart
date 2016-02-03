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
# $Id: auth.php,v 1.30.2.4 2006/11/01 12:37:40 twice Exp $
#

define('AREA_TYPE', 'C');

@include_once "./top.inc.php";
@include_once "../top.inc.php";
@include_once "../../top.inc.php";
if (!defined('DIR_CUSTOMER')) die("ERROR: Can not initiate application! Please check configuration.");

include_once $xcart_dir."/init.php";


$current_area="C";

x_load('files');

x_session_register('previous_catid', array());

if (!isset($cat)) {
	$previous_catid = array();
} else {
	if ((basename($PHP_SELF) != 'home.php' && basename($PHP_SELF) != 'product.php') || !isset($cat)) {
		$previous_catid = array();
	} elseif($previous_catid[count($previous_catid) - 1] != $cat) {
		$previous_catid[] = $cat;	
	}
}
x_session_register("logout_user");
x_session_register("session_failed_transaction");
x_session_register("add_to_cart_time");

x_session_register("always_allow_shop");



#
# Clear/update shipping rates cache
#
db_query("DELETE FROM $sql_tbl[shipping_cache] WHERE expiration_date<'".time()."'");
global $XCART_SESSION_EXPIRY;
db_query("UPDATE $sql_tbl[shipping_cache] SET expiration_date='$XCART_SESSION_EXPIRY' WHERE session_id='$XCARTSESSID'");

if (!empty($_GET['shopkey'])) {
    $always_allow_shop = (!empty($config['General']['shop_closed_key']) && $_GET['shopkey'] == $config['General']['shop_closed_key']);
}

if ($config["General"]["shop_closed"] == "Y" && !$always_allow_shop){
	#
	# Close store front
	# Thanks to rubyaryat for the Shop Closed mod
	#
	if (!func_readfile($xcart_dir.DIRECTORY_SEPARATOR.$shop_closed_file, true))
		echo func_get_langvar_by_name("txt_shop_temporarily_unaccessible",false,false,true);
	exit();
}

###
if (empty($Username) || empty($Password))
###
require $xcart_dir."/include/nocookie_warning.php";

if (!defined('HTTPS_CHECK_SKIP')) {
//	@include $xcart_dir.DIR_CUSTOMER."/https.php";
	include_once $xcart_dir.DIR_CUSTOMER."/https.php";
}

if (!empty($active_modules['Users_online'])) {
	x_session_register("current_url_page");
	x_session_register("current_date");
	x_session_register("session_create_date");
	$current_url_page = $php_url['url'].($php_url['query_string']?"?".$php_url['query_string']:"");
	if (empty($session_create_date))
		$session_create_date = time();

	$current_date = time();
}

#
# Display
#
x_session_register("wlid");
if (isset($_GET['wlid']) && $_GET['wlid']) {
	$wlid = $_GET['wlid'];
}

$smarty->assign("wlid", $wlid);

#
# Browser have disabled/enabled javasript switching
#
x_session_register("js_enabled", "Y");


if (!isset($js_enabled)) $js_enabled="Y";

if (isset($_GET['js'])) {
	if ($_GET['js'] == 'y') {
		$js_enabled = "Y";
		$config['Adaptives']['isJS'] = "Y";
		$adaptives['isJS'] = "Y";
	}
	elseif ($_GET['js'] == 'n') {
		$js_enabled = "";
	}
}

if ($js_enabled == "Y") {
	$qry_string = preg_replace("/(&*)js=y/", "", $QUERY_STRING);
	$js_update_link = $PHP_SELF."?".($qry_string?"$qry_string&":"")."js=n";
}
else {
	$qry_string = preg_replace("/(&*)js=n/", "", $QUERY_STRING);
	$js_update_link = $PHP_SELF."?".($qry_string?"$qry_string&":"")."js=y";
}

$smarty->assign("js_update_link", $js_update_link);
$smarty->assign("js_enabled", $js_enabled);

x_session_register("top_message");
if (!empty($top_message)) {
	$smarty->assign("top_message", $top_message);
	if ($config['Adaptives']['is_first_start'] != 'Y')
		$top_message = "";

	x_session_save("top_message");
}

$cat = intval(@$cat);
$page = intval(@$page);

if (!empty($active_modules['XAffiliate'])) {
	include $xcart_dir."/include/partner_info.php";
	include $xcart_dir."/include/adv_info.php";
}

if (!empty($active_modules['Mailchimp_Subscription'])) {
    include $xcart_dir . "/include/mailchimp_adv_info.php";
}


include $xcart_dir.DIR_CUSTOMER."/referer.php";

include $xcart_dir."/include/check_useraccount.php";

include $xcart_dir."/include/get_language.php";

x_session_register('perform_autologout');

if ($perform_autologout == 'Y' && !(basename($PHP_SELF) == 'order.php' && $mode == 'invoice' && isset($orderid))) {

    if (!($mode == "order_message" || $mode == "invoice")){
	$perform_autologout = 'N';
	$mode = 'logout';
	$autologout = 'Y';

#
##
###
	x_session_register("shipquote_userinfo");
	$shipquote_userinfo = "";
###
##
#

	include $xcart_dir . '/include/login.php';
    }

}

$lbl_site_name = strip_tags(func_get_langvar_by_name("lbl_site_title", "", false, true));
$location = array();
//$location[] = array((!empty($lbl_site_name) ? $lbl_site_name : $config["Company"]["company_name"]), "home.php");
$location[] = array((!empty($lbl_site_name) ? $lbl_site_name : $config["Company"]["company_name"]), "/");

include $xcart_dir.DIR_CUSTOMER."/minicart.php";

if (!empty($active_modules["Interneka"])) {
	include $xcart_dir."/modules/Interneka/interneka.php";
}

if (!empty($active_modules["Subscriptions"])) {
    if ($login) {
        include $xcart_dir."/modules/Subscriptions/get_subscription_info.php";
        $smarty->assign("user_subscription", is_user_subscribed($login));
    }
}

$pages_menu = func_query("SELECT * FROM $sql_tbl[pages] WHERE language='$store_language' AND active='Y' AND level='E' AND orderby <= '500' ORDER BY orderby, title");

#
##
###
$pages_menu["x1"]["pageid"] = "#";
$pages_menu["x1"]["new_link"] = "/retrieve_orders.php";
$pages_menu["x1"]["title"] = func_get_langvar_by_name("lbl_retrieve_orders");
$pages_menu["x1"]["orderby"] = "-1000";

if (!empty($active_modules["Gift_Certificates"])){
 $pages_menu["x2"]["pageid"] = "#";
 $pages_menu["x2"]["new_link"] = "/giftcert.php";
 $pages_menu["x2"]["title"] = func_get_langvar_by_name("lbl_gift_certificates");
 $pages_menu["x2"]["orderby"] = "1000";
}

/*
$pages_menu["x3"]["pageid"] = "#";
$pages_menu["x3"]["new_link"] = "sitemap.php";
$pages_menu["x3"]["title"] = func_get_langvar_by_name("lbl_sitemap");
$pages_menu["x3"]["orderby"] = "1001";
*/

$pages_menu = func_brand_array_sort($pages_menu, "orderby");
$pages_menu = array_values($pages_menu);
$count_pages_menu = count($pages_menu);

$count_pages_menu_in_cell = ceil($count_pages_menu/4);

$smarty->assign("count_pages_menu", $count_pages_menu);
$smarty->assign("count_pages_menu_in_cell", $count_pages_menu_in_cell);
###
##
#


$smarty->assign("pages_menu", $pages_menu);


$top_pages_menu = func_query("SELECT * FROM $sql_tbl[pages] WHERE language='$store_language' AND active='Y' AND header_pos!='' ORDER BY header_pos, orderby, title");
if (!empty($top_pages_menu) && is_array($top_pages_menu)){
        foreach ($top_pages_menu as $k => $v){
                $top_pages_menu[$k]["image"] = func_query_first("SELECT * FROM $sql_tbl[images_A] WHERE id='$v[pageid]'");
        }
        $smarty->assign("top_pages_menu", $top_pages_menu);
}



$speed_bar = unserialize($config["speed_bar"]);
if (!empty($speed_bar)) {
	$tmp_labels = array();
	foreach ($speed_bar as $k => $v) {
		if (!empty($active_modules['Multiple_Storefronts'])) {
			if ($v['storefrontid'] != $current_storefront) {
				unset($speed_bar[$k]);
			} else {
		$speed_bar[$k] = func_array_map("stripslashes", $v);
		$tmp_labels[] = "speed_bar_".$v['id'];
	}
		} else {
			$speed_bar[$k] = func_array_map("stripslashes", $v);
			$tmp_labels[] = "speed_bar_".$v['id'];
		}
	}

	$tmp = func_get_languages_alt($tmp_labels);
	foreach ($speed_bar as $k => $v) {
		if (isset($tmp['speed_bar_'.$v['id']]))
			$speed_bar[$k]['title'] = $tmp['speed_bar_'.$v['id']];

		$speed_bar[$k]['link'] = str_replace("&", "&amp;", $v['link']);
	}

	$smarty->assign("speed_bar", $speed_bar);
}

unset($speed_bar);

$smarty->assign("redirect","customer");

/* speed optimization turn on when should use
if (!empty($active_modules["News_Management"]))
	include $xcart_dir."/modules/News_Management/news_last.php";
*/
if (!empty($active_modules["Feature_Comparison"]) && $config['Feature_Comparison']['fcomparison_show_product_list'] == 'Y') {
	$comparison_list = func_get_comparison_list();
	$smarty->assign("comparison_list",$comparison_list);
}

if (!empty($active_modules["Survey"])) {
	include_once $xcart_dir."/modules/Survey/surveys_list.php";
}

$smarty->assign("printable", $printable);
$smarty->assign("logout_user", $logout_user);

# Start ------- front page banner rotation starts
$html_banners = array(
"html_banners/banner_1.tpl",
"html_banners/banner_2.tpl",
"html_banners/banner_3.tpl"
);

x_session_register("current_html_banner");
$current_html_banner = intval($current_html_banner);
if ($current_html_banner > (count($html_banners)-1)) $current_html_banner = 0;
$smarty->assign("current_html_banner",$html_banners[$current_html_banner]);
$current_html_banner++;
x_session_save("current_html_banner");
# End ------- front page banner rotation ends

#require_once("DSEFU.php");

$statuses = func_query_hash('SELECT code, name, type FROM ' . $sql_tbl['order_statuses']
    . ' ORDER BY orderby', array('type', 'code'), false, true);
$smarty->assign('statuses', $statuses);


#
##
###
//if ($current_storefront == "12"){
	$smarty->assign('use_schema_org', 'Y');
//}
###
##
#

if (!empty($active_modules['CIDEV_Best_Search_Filter'])) {
	$cidev_filters_tree = func_cidev_filters_tree(true);
	$smarty->assign("cidev_filters_tree", $cidev_filters_tree);

	$filter_price_ranges = array(0, 10, 20, 50, 100, 200, 500, 1000, 2000, 5000, 10000, 20000, 50000, 100000, 200000, 500000, 1000000);
}

#
##
###
x_session_register('notify_when_in_stock', array());
$smarty->assign("notify_when_in_stock", $notify_when_in_stock);
###
##
#

#
##
###
x_session_register('first_order_total_in_current_session');
x_session_register('pointid_ab_testing_arr', array());


x_session_register('variant_id_for_point2');
$variant_id_for_point2 = Get_AB_Variant(2);
x_session_save("variant_id_for_point2");
$smarty->assign("variant_id_for_point2", $variant_id_for_point2);

x_session_register('variant_id_for_point3');
$variant_id_for_point3 = Get_AB_Variant(3);
x_session_save("variant_id_for_point3");
$smarty->assign("variant_id_for_point3", $variant_id_for_point3);

x_session_register('variant_id_for_point5');
$variant_id_for_point5 = Get_AB_Variant(5);
x_session_save("variant_id_for_point5");
$smarty->assign("variant_id_for_point5", $variant_id_for_point5);

x_session_register('variant_id_for_point6');
$variant_id_for_point6 = Get_AB_Variant(6);
x_session_save("variant_id_for_point6");
$smarty->assign("variant_id_for_point6", $variant_id_for_point6);

//$smarty->assign("pointid_ab_testing_arr", $pointid_ab_testing_arr); // try to move to func_display
###
##
#

###
if ($HTTPS){
	$smarty->assign("HTTPS_url", "Y");
}
else {
	$smarty->assign("HTTPS_url", "N");
}
###


#
##
###
if (!empty($config["Appearance"]["Google_Trusted_Store_ID"])){

//	if ($_GET["mode"] != "order_message") {

		$GTS_badge_code = str_replace('GTS_STORE_ID', $config["Appearance"]["Google_Trusted_Store_ID"], $config["Google_trusted_stores_options"]["GTS_badge_code"]);

		$ITEM_GOOGLE_SHOPPING_ACCOUNT_ID = func_query_first_cell("SELECT MerchantID FROM $sql_tbl[froogle_options] WHERE storefrontid='$current_storefront'");
		$GTS_badge_code = str_replace('ITEM_GOOGLE_SHOPPING_ACCOUNT_ID', $ITEM_GOOGLE_SHOPPING_ACCOUNT_ID, $GTS_badge_code);

		$ITEM_GOOGLE_SHOPPING_ID = "";
		if (strpos($_SERVER["REQUEST_URI"], "request_uri=/product/")!==false && !empty($productid)){
			$ITEM_GOOGLE_SHOPPING_ID = $productid;
		}
		$GTS_badge_code = str_replace('ITEM_GOOGLE_SHOPPING_ID', $ITEM_GOOGLE_SHOPPING_ID, $GTS_badge_code);

		$smarty->assign("GTS_badge_code", $GTS_badge_code);
//	}
}
###
##
#


//if (empty($login) && $mode=="checkout" && empty($userinfo["s_country"])){
  if (!empty($CLIENT_IP)){
        $CLIENT_IP_arr = explode(".", $CLIENT_IP);
        if (!empty($CLIENT_IP_arr) && is_array($CLIENT_IP_arr)){
                $CLIENT_IP_INTEGER = $CLIENT_IP_arr[0]*16777216 + $CLIENT_IP_arr[1]*65536 + $CLIENT_IP_arr[2]*256 + $CLIENT_IP_arr[3];
        }

        if (!empty($CLIENT_IP_INTEGER)){
                $locId = func_query_first_cell("SELECT locId FROM $sql_tbl[geo_litecity_blocks] WHERE $CLIENT_IP_INTEGER BETWEEN startIpNum AND endIpNum LIMIT 1");

                if ($geo_litecity_location_test == "Y"){

                        if (!empty($geo_litecity_location_test_locId)){
                                $locId = addslashes($geo_litecity_location_test_locId);
//                              $locId = "1087";  // New York
                        }
                }

                if (!empty($locId)){
                        $geo_litecity_location = func_query_first("SELECT * FROM $sql_tbl[geo_litecity_location] WHERE locId='".addslashes($locId)."'");

                        if (!empty($geo_litecity_location)){

				if (!empty($geo_litecity_location["country"]) && !empty($geo_litecity_location["region"])){
					$geo_litecity_location["phone"] = func_query_first_cell("SELECT phone FROM $sql_tbl[states] WHERE country_code='$geo_litecity_location[country]' AND code='$geo_litecity_location[region]'");
				}

                                $smarty->assign('geo_litecity_location', $geo_litecity_location);

                                if ($geo_litecity_location_debug == "Y"){
                                        x_load("debug");
                                        func_print_r($geo_litecity_location);
                                }
                        }
                }
        }
  }

//}

func_detect_working_hours();

# https://basecamp.com/2070980/projects/1577907/messages/53989896
##  Auto pop up in 30 seconds
###
x_session_register("viralmarketingbomb_shown");
$smarty->assign('viralmarketingbomb_shown', $viralmarketingbomb_shown);
###
##
#

?>
