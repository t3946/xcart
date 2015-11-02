<?php /* MODIFIED: random:20313 [2010 Mar 16 13:33][Custom development (Speed-up optimization)] */ ?>
<?php /* MODIFIED: random:20460 [2010 Mar 18 13:43][Custom development (Free shipping modifications)] */ ?>
<?php /* MODIFIED: random:18298_18304_18324 [2009 Jun 08 09:50][Custom development (Форма для отправки нотификаций "производителям" (X-Cart's Manufacturers) + Add new "Brands" module + Search URLs feature)] */ ?>
<?php /* MODIFIED: random:1073746882_1073747063 [2008 Dec 24 16:25][Custom development (Shipping Calculation for Several Providers in the USA)] */ ?>
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
# $Id: product.php,v 1.21.2.4 2006/12/07 08:28:02 svowl Exp $
#

define('OFFERS_DONT_SHOW_NEW',1);
require "./auth.php";

if (
    isset($productid)
    && !empty($productid)
    && $config['SEO']['clean_urls_enabled'] == 'Y'
    && !defined('DISPATCHED_REQUEST')
) {
    func_clean_url_permanent_redirect('P', intval($productid));
}

x_load('product');

# START: random:20313 [2010 Mar 16 13:33] 
if (isset($sku)) {
	$sku = trim($sku);
	if ($mode == 'check' || $mode == 'check_all') {
        if ($mode == 'check') {
		    $productid = func_query_first_cell("SELECT $sql_tbl[products].productid FROM $sql_tbl[products]" 
                . " INNER JOIN $sql_tbl[products_sf] ON $sql_tbl[products].productid = $sql_tbl[products_sf].productid" 
                . " WHERE ($sql_tbl[products].productcode LIKE '$sku%') AND ($sql_tbl[products_sf].sfid = $current_storefront)");
        } else {
            $productid = func_query_first_cell("SELECT productid FROM $sql_tbl[products] WHERE productcode LIKE '$sku%'");            
        }
		echo (empty($productid) ? 0 : 1);
		exit; 
	}
	$productid = func_query_first_cell("SELECT productid FROM $sql_tbl[products] WHERE productcode = '$sku'");
}

# END: random:20313 [2010 Mar 16 13:33] 
$smarty->assign("company_state", func_query_first_cell("SELECT $sql_tbl[states].state FROM $sql_tbl[states] WHERE $sql_tbl[states].country_code = '".$config['Company']['location_country']."' AND $sql_tbl[states].code = '".$config['Company']['location_state']."'"));
require $xcart_dir."/include/countries.php";
if(!empty($countries))
	foreach($countries as $country)
	if($country['country_code']==$config['Company']['location_country'])
	$smarty->assign("company_country", $country['country']);

#
# Put all product info into $product array
#

# START: random:20313 [2010 Mar 16 13:33] 
$product_info = func_select_product($productid, @$user_account['membershipid'], !isset($sku));

if (empty($product_info)) {
	func_header_location("search.php?substring=".urlencode($sku)."&by_sku=1&mode=search&from=fast_search");
}

#
##
###
$reverse_sku = func_query_first_cell("SELECT reverse_sku FROM $sql_tbl[manufacturers] WHERE manufacturerid='$product_info[manufacturerid]'");
$remove_dashes = func_query_first_cell("SELECT remove_dashes FROM $sql_tbl[manufacturers] WHERE manufacturerid='$product_info[manufacturerid]'");

if ($remove_dashes == "Y"){
	$product_info["productcode"] = str_replace("-", ".", $product_info["productcode"]);
}

if ($reverse_sku == "Y"){

	$cidev_strlen = strlen($product_info["productcode"]) - 1;

	$new_sku = "";
	for($i=0;$i<strlen($product_info["productcode"]);$i++){
		$new_sku .= substr($product_info["productcode"],$cidev_strlen,1);
		$cidev_strlen--;
	}
	$product_info["productcode"] = $new_sku;
}
###
##
#

#
##
###
if ($config["Product_Page"]["cidev_show_products_image"] != "Y"){
	$product_info["tmbn_url"] = $product_info["tmbn_url_T"];
	$product_info["image_x"] = $product_info["image_x_T"];
	$product_info["image_y"] = $product_info["image_y_T"];
}
###
##
#

# END: random:20313 [2010 Mar 16 13:33] 
if (intval($cat) == 0) {
	$cat = $product_info["categoryid"];
}

$main = "product";
$smarty->assign("main",$main);

if (!empty($product_info["productid"])) {
	if (empty($product_info['descr'])) {
		$product_info['meta_descr'] = trim(strip_tags(func_get_product_descr($product_info['fulldescr'])));
	} else {
		$product_info['meta_descr'] = trim(strip_tags($product_info['descr']));
	}
    
    if (trim(strtoupper(substr($product_info['meta_descr'], 0, 10))) == 'FEATURES:.') {
        $product_info['meta_descr'] = trim(substr_replace($product_info['meta_descr'], '', 0, 10));
    }

	$product_info['meta_keywords'] = '';
# START: random:20460 [2010 Mar 18 13:43] 
	if ($product_info["free_ship_zone"] < 0) {
		$product_info["free_ship_text"] = "";
	}
# END: random:20460 [2010 Mar 18 13:43] 
}

include $xcart_dir.DIR_CUSTOMER."/send_to_friend.php";

if (!empty($send_to_friend_info)) {
	$smarty->assign("send_to_friend_info", $send_to_friend_info);
	if (!empty($active_modules['Image_Verification'])) {
		$smarty->assign("antibot_err", $send_to_friend_info['antibot_err']);
	}
	x_session_unregister("send_to_friend_info");
}

if (!empty($active_modules["Detailed_Product_Images"]))
	include $xcart_dir."/modules/Detailed_Product_Images/product_images.php";

if (!empty($active_modules["Magnifier"]))
	include $xcart_dir."/modules/Magnifier/product_magnifier.php";

if (!empty($active_modules["Product_Options"]))
	include $xcart_dir."/modules/Product_Options/customer_options.php";

if (!empty($active_modules["Upselling_Products"]))
	include $xcart_dir."/modules/Upselling_Products/related_products.php";

if (!empty($active_modules["Advanced_Statistics"]) && !defined("IS_ROBOT"))
    include $xcart_dir."/modules/Advanced_Statistics/prod_viewed.php";

# START: random:18298_18304_18324 [2009 Jun 08 09:50] 
if ($active_modules["Brands"])
    include $xcart_dir."/modules/Brands/customer_brands.php";
else
# END: random:18298_18304_18324 [2009 Jun 08 09:50] 
if ($active_modules["Manufacturers"])
	include $xcart_dir."/modules/Manufacturers/customer_manufacturers.php";

$product_info["customer_service_email"] = func_query_first_cell("SELECT customer_service_email FROM $sql_tbl[brands] WHERE brandid='$product_info[brandid]'");

if ($product_info["product_type"] != "C") {
	#
	# If this product is not configurable
	#
	if ($config["General"]["disable_outofstock_products"] == "Y" && empty($product_info['distribution'])) {
		$is_avail = true;
		if ($product_info['avail'] <= 0 && empty($variants)) {
			$is_avail = false;
		}
		elseif(!empty($variants)) {
			$is_avail = false;
			foreach($variants as $v) {
				if ($v['avail'] > 0) {
					$is_avail = true;
					break;
				}
			}
		}

		if(!empty($cart['products']) && !$is_avail) {
			foreach($cart['products'] as $v) {
				if($product_info['productid'] == $v['productid']) {
					$is_avail = true;
					break;
				}
			}
		}

		if(!$is_avail) {
			func_header_location("error_message.php?access_denied&id=44");
		}
	}

	if(!empty($active_modules["Extra_Fields"])) {
		$extra_fields_provider=$product_info["provider"];
		include $xcart_dir."/modules/Extra_Fields/extra_fields.php";
	}

	if(!empty($active_modules["Subscriptions"])) {
		$_products = $products;
		$products = array($product_info);
		include_once $xcart_dir."/modules/Subscriptions/subscription.php";
		$products = $_products;
	}

	if(!empty($active_modules["Feature_Comparison"]))
		include $xcart_dir."/modules/Feature_Comparison/product.php";

###
	if ($product_info["new_map_price"] == "0"){

		if (!empty($active_modules["Wholesale_Trading"]) && empty($product_info['variantid']))
			include $xcart_dir."/modules/Wholesale_Trading/product.php";

	}
###


    if (!empty($active_modules['Product_Configurator']) && !empty($_GET['pconf'])) {
		include $xcart_dir."/modules/Product_Configurator/slot_product.php";
    }
		
}

if (!empty($active_modules["Recommended_Products"]))
	include "./recommends.php";

if (!empty($active_modules["SnS_connector"]))
	include $xcart_dir."/modules/SnS_connector/product.php";

include "./vote.php";

require $xcart_dir."/include/categories.php";

if (!empty($current_category) and is_array($current_category["category_location"])) {
	foreach ($current_category["category_location"] as $k=>$v) {
//		$v[1] .= '&path='.$k;
		$location[] = $v;
	}
}

if (!empty($product_info)) {
	$location[] = array($product_info['product'],'');
	if (is_array($location) && !empty($location)) {
		if (is_array($location)) {
			foreach (array_reverse($location) as $l) {
				$product_info['meta_keywords'] .= $l[0] . ', ';
			}
			$product_info['meta_keywords'] = trim(strip_tags(substr($product_info['meta_keywords'], 0, strlen($product_info['meta_keywords']) - 2)));
		}
	}
}

if (!empty($active_modules["Special_Offers"])) {
	include $xcart_dir."/modules/Special_Offers/product_offers.php";
}

$show_dimensions = false;
foreach (array('dim_x','dim_y','dim_z') as $k) {
	$show_dimensions = !empty($product_info[$k]);
	if (!$show_dimensions) {
		break;
	}
}
$smarty->assign('show_dimensions', $show_dimensions);

# START: random:1073746882_1073747063 [2008 Dec 24 16:25] 
if (!empty($product_info['manufacturerid'])) {
	$product_info['manufact_text_displayed'] = func_query_first_cell("SELECT manufact_text_displayed FROM $sql_tbl[manufacturers] WHERE manufacturerid ='".$product_info['manufacturerid']."'");
	$product_info['cart_manufact_text_displayed'] = func_query_first_cell("SELECT cart_manufact_text_displayed FROM $sql_tbl[manufacturers] WHERE manufacturerid ='".$product_info['manufacturerid']."'");
}


#
##
###
$cidev_pos = strpos($product_info["cart_manufact_text_displayed"], "<s3-tab>");
if (!empty($product_info["cart_manufact_text_displayed"]) && $cidev_pos!== false){

        $cidev_cart_manufact_text_displayed_arr = explode("<s3-tab>", $product_info["cart_manufact_text_displayed"]);

        $cidev_make_array_values = false;
        if (!empty($cidev_cart_manufact_text_displayed_arr) && is_array($cidev_cart_manufact_text_displayed_arr)){
                foreach ($cidev_cart_manufact_text_displayed_arr as $k => $v){
                        if (empty($v) || trim($v) == ""){
                                unset($cidev_cart_manufact_text_displayed_arr[$k]);
                                $cidev_make_array_values = true;
                        }
                }
        }

        if ($cidev_make_array_values){
                $cidev_cart_manufact_text_displayed_arr = array_values($cidev_cart_manufact_text_displayed_arr);
        }

        $cart_manufact_text_displayed_tabs = array();
        $cart_manufact_text_displayed_tabs_index = 0;
        if (!empty($cidev_cart_manufact_text_displayed_arr) && is_array($cidev_cart_manufact_text_displayed_arr)){
                foreach ($cidev_cart_manufact_text_displayed_arr as $k => $v){
                        $cidev_pos2 = strpos($v, "</s3-tab>");
                        if ($cidev_pos2 !== false){
                                $cart_manufact_text_displayed_tabs[$cart_manufact_text_displayed_tabs_index] = explode("</s3-tab>", $v);
                                $cart_manufact_text_displayed_tabs_index++;
                        }
                }
        }
}

if (empty($cart_manufact_text_displayed_tabs) && !empty($product_info["cart_manufact_text_displayed"])){
        $cart_manufact_text_displayed_tabs[0][0] = "Shipping information";
        $cart_manufact_text_displayed_tabs[0][1] = $product_info["cart_manufact_text_displayed"];
}

if (!empty($cart_manufact_text_displayed_tabs) && is_array($cart_manufact_text_displayed_tabs)){
        foreach ($cart_manufact_text_displayed_tabs as $k => $v){
                $product_tabs[$k]["title"] = $v[0];
                $product_tabs[$k]["tpl"] = $v[1];
                $product_tabs[$k]["anchor"] = $k;
        }
}

if ($config['product_question_email']['product_question_enable'] == 'Y') {
	$count_product_tabs = count($product_tabs);
	$product_tabs[$count_product_tabs]["title"] = "Product question";
	$product_tabs[$count_product_tabs]["tpl"] = "_product_question_tpl_";
	$product_tabs[$count_product_tabs]["anchor"] = $count_product_tabs;
}

if ($config['product_queries']['product_queries_enable'] == 'Y') {
        $count_product_tabs = count($product_tabs);
        $product_tabs[$count_product_tabs]["title"] = "Product queries";
        $product_tabs[$count_product_tabs]["tpl"] = "_product_queries_tpl_";
        $product_tabs[$count_product_tabs]["anchor"] = $count_product_tabs;

	$server_url = $config['product_queries']['product_queries_get_content_url'];
	$additional_query = "";
	if (!empty($server_url)){

		if (!empty($product_info["upc"])){
		        $gtin = trim($product_info["upc"]);
		        $gtin = urlencode($gtin);
		        $additional_query = "&gtin=".$gtin;
		} elseif (!empty($product_info["mpn"]) && !empty($product_info["brandid"])){
		        $brand_name = func_query_first_cell("SELECT brand FROM $sql_tbl[brands] WHERE brandid='$product_info[brandid]'");
		        $brand_name = trim($brand_name);
		        $brand_name = urlencode($brand_name);
		        $mpn_urlencode = trim($product_info["mpn"]);
		        $mpn_urlencode = urlencode($mpn_urlencode);
		        $additional_query = "&mpn=".$mpn_urlencode."&brand=".$brand_name;
		}
	}

	if (!empty($additional_query)){
        	$full_server_url = $server_url.$additional_query;

	        $productqueries_page = file_get_contents($full_server_url);
        	$productqueries_page = utf8_encode($productqueries_page);
	        if (!empty($productqueries_page)){
        	        $productqueries_page_arr = json_decode($productqueries_page, true);

                	if (!empty($productqueries_page_arr) && is_array($productqueries_page_arr)){

				foreach ($productqueries_page_arr as $k => $v){
					if (!empty($v["answers"]) && is_array($v["answers"])){
						foreach ($v["answers"] as $kk => $vv){
							if (empty($vv["comments"])){
								unset($productqueries_page_arr[$k]["answers"][$kk]["comments"]);
							}
						}
					}
				}

                        	$smarty->assign("productqueries_page_arr", $productqueries_page_arr);
	                }
        	}
	}

	$get_question_forms_url = $config['product_queries']['product_queries_get_question_forms_url'];
	if (!empty($get_question_forms_url) && !empty($additional_query)){

		$product_name_urlencode = trim($product_info["product"]);
		$product_name_urlencode = urlencode($product_name_urlencode);
		$additional_query .= "&product_name=".$product_name_urlencode;

		$product_sfid = func_query_first_cell("SELECT sfid FROM $sql_tbl[products_sf] WHERE productid='$productid'");

		if ($product_sfid > 0){
			$site_url = func_query_first_cell("SELECT domain FROM $sql_tbl[storefronts] WHERE storefrontid='$product_sfid'");
			$site_url = "http://".$site_url."/";
		} else {
			$site_url = "http://www.artistsupplysource.com/";
		}
		
		$product_url = $site_url.$product_info["clean_url"]."/";
		$product_url_urlencode = urlencode($product_url);
		
		$additional_query .= "&product_url=".$product_url_urlencode;
		$full_get_question_forms_url = $get_question_forms_url.$additional_query;

		$get_question_forms_url_info = file_get_contents($full_get_question_forms_url);
		$get_question_forms_url_info = utf8_encode($get_question_forms_url_info);

		$get_question_forms_url_info_arr = explode("</style>", $get_question_forms_url_info);
		$product_form_info = array_pop($get_question_forms_url_info_arr);

		$product_form_info = str_replace('<input type="submit" value="Submit question">', '<span onclick="javascript: document.form_query.submit();" class="cidev_new_button cidev_new_white">Submit question</span>', $product_form_info);
		$product_form_info = str_replace('<form', '<form name="form_query"', $product_form_info);

		$smarty->assign('product_form_info', $product_form_info);
	}
}

if (!empty($product_tabs)) {
    $smarty->assign('product_tabs', $product_tabs);
}
###
##
#

if (!empty($product_info["brandid"])){
	$product_info["cidev_brand_name"] = func_query_first_cell("SELECT brand FROM $sql_tbl[brands] WHERE brandid='$product_info[brandid]'");
}


#
## https://basecamp.com/2070980/projects/1577907-x-cart/messages/13257251-internal-sf-tasks
###
$cidev_warning_code = 0;

if ($product_info["list_price"] > 0){
        if (($product_info["price"]/$product_info["list_price"]) < 0.1){
                $cidev_warning_code = "101";
        }
}

if ($product_info["cost_to_us"] > $product_info["price"]){
        $cidev_warning_code = "102";
}

if ($cidev_warning_code > 0){
	if ($product_info["warning_code"] != $cidev_warning_code){
	        db_query("UPDATE $sql_tbl[products] SET warning_code='$cidev_warning_code' WHERE productid='$product_info[productid]'");
		$product_info["warning_code"] = $cidev_warning_code;
	}

	$product_info["avail"] = 0;
}
###
##
#

if (empty($product_info["lead_time_message"])){
	$product_info["lead_time_message"] = func_query_first_cell("SELECT lead_time_message FROM $sql_tbl[manufacturers] WHERE manufacturerid='$product_info[manufacturerid]'");
}


# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 
$smarty->assign("product",$product_info);

if ($active_modules["Bestsellers"])
	include $xcart_dir."/modules/Bestsellers/bestsellers.php";


#
## similar products
###
$membershipid = isset($user_account['membershipid']) ? $user_account['membershipid'] : 0;

if ((!empty($cat)) && (1!=1)){

/*
        $tmp_products1 = func_query("
                SELECT $sql_tbl[products].productid, $sql_tbl[products].cost_to_us FROM $sql_tbl[products]
                LEFT JOIN $sql_tbl[products_categories]
	                ON $sql_tbl[products_categories].productid = $sql_tbl[products].productid
                WHERE 
                	$sql_tbl[products_categories].categoryid = '$cat' AND $sql_tbl[products_categories].main='Y' AND $sql_tbl[products].cost_to_us > '$product_info[cost_to_us]' AND $sql_tbl[products].productid != '$product_info[productid]' AND $sql_tbl[products].forsale='Y'
                ORDER BY $sql_tbl[products].cost_to_us ASC LIMIT 3");

        $tmp_products2 = func_query("
                SELECT $sql_tbl[products].productid, $sql_tbl[products].cost_to_us FROM $sql_tbl[products]
                LEFT JOIN $sql_tbl[products_categories]
        	        ON $sql_tbl[products_categories].productid = $sql_tbl[products].productid
                WHERE 
	                $sql_tbl[products_categories].categoryid = '$cat' AND $sql_tbl[products_categories].main='Y' AND $sql_tbl[products].cost_to_us <= '$product_info[cost_to_us]' AND $sql_tbl[products].productid != '$product_info[productid]' AND $sql_tbl[products].forsale='Y'
                ORDER BY $sql_tbl[products].cost_to_us DESC LIMIT 3");
*/

        $tmp_products1 = db_query("
                SELECT $sql_tbl[products].productid, $sql_tbl[products].cost_to_us FROM $sql_tbl[products]
                LEFT JOIN $sql_tbl[products_categories]
                        ON $sql_tbl[products_categories].productid = $sql_tbl[products].productid
                WHERE 
                        $sql_tbl[products_categories].categoryid = '$cat' AND $sql_tbl[products_categories].main='Y' AND $sql_tbl[products].cost_to_us > '$product_info[cost_to_us]' AND $sql_tbl[products].productid != '$product_info[productid]' AND $sql_tbl[products].forsale='Y'
                ORDER BY $sql_tbl[products].cost_to_us ASC LIMIT 3");

	if ($tmp_products1){
		$tmp_products1_1 = array();
		$tmp_counter = 0;
		while($p = db_fetch_array($tmp_products1)) {
			$tmp_products1_1[$tmp_counter]["productid"] = $p["productid"];
			$tmp_products1_1[$tmp_counter]["cost_to_us"] = $p["cost_to_us"];
			$tmp_counter++;
		}
		db_free_result($tmp_products1);
		unset($tmp_products1);
		$tmp_products1 = $tmp_products1_1;
	}


        $tmp_products2 = db_query("
                SELECT $sql_tbl[products].productid, $sql_tbl[products].cost_to_us FROM $sql_tbl[products]
                LEFT JOIN $sql_tbl[products_categories]
                        ON $sql_tbl[products_categories].productid = $sql_tbl[products].productid
                WHERE 
                        $sql_tbl[products_categories].categoryid = '$cat' AND $sql_tbl[products_categories].main='Y' AND $sql_tbl[products].cost_to_us <= '$product_info[cost_to_us]' AND $sql_tbl[products].productid != '$product_info[productid]' AND $sql_tbl[products].forsale='Y'
                ORDER BY $sql_tbl[products].cost_to_us DESC LIMIT 3");

        if ($tmp_products2){
                $tmp_products2_1 = array();
                $tmp_counter = 0;
                while($p = db_fetch_array($tmp_products2)) {
                        $tmp_products2_1[$tmp_counter]["productid"] = $p["productid"];
                        $tmp_products2_1[$tmp_counter]["cost_to_us"] = $p["cost_to_us"];
                        $tmp_counter++;
                }
                db_free_result($tmp_products2);
                unset($tmp_products2);
                $tmp_products2 = $tmp_products2_1;
        }



        $tmp_products_arr = array();

        if (!empty($tmp_products1) && is_array($tmp_products1)){
                foreach ($tmp_products1 as $k => $v){
                        $tmp_products_arr[$k]["productid"] = $v["productid"];
                        $tmp_products_arr[$k]["cost_to_us"] = $v["cost_to_us"];

                        if ($v["cost_to_us"] > 0)
                                $kf = $product_info["cost_to_us"]/$v["cost_to_us"];

                        $tmp_products_arr[$k]["rate"] =  1 - $kf;
                }
        }

        $count_tmp_products = count($tmp_products_arr);

        if (!empty($tmp_products2) && is_array($tmp_products2)){
                foreach ($tmp_products2 as $k => $v){
                        $tmp_products_arr[$count_tmp_products]["productid"] = $v["productid"];
                        $tmp_products_arr[$count_tmp_products]["cost_to_us"] = $v["cost_to_us"];

                        if ($product_info["cost_to_us"] > 0)
                                $kf = $v["cost_to_us"]/$product_info["cost_to_us"];

                        $tmp_products_arr[$count_tmp_products]["rate"] = 1- $kf;
                        $count_tmp_products++;
                }
        }

        if (!empty($tmp_products_arr) && is_array($tmp_products_arr)){

                $tmp_products_arr = array_values(my_array_sort($tmp_products_arr, 'rate'));

                foreach ($tmp_products_arr as $k => $v){

//                        $similar_products[$k] = func_select_product($v["productid"], @$user_account['membershipid']);

			$similar_product = func_query_first("SELECT productid, product, list_price, map_price, new_map_price FROM $sql_tbl[products] WHERE productid='$v[productid]'");
			$similar_product["price"] = func_query_first_cell("SELECT MIN($sql_tbl[pricing].price) as price FROM $sql_tbl[pricing] WHERE $sql_tbl[pricing].quantity = 1 AND $sql_tbl[pricing].variantid = 0 AND $sql_tbl[pricing].productid='$v[productid]'");

		        if ($similar_product["new_map_price"]>0 && $similar_product["new_map_price"] > $similar_product["price"]){
       	        	        $similar_product["price"] = $similar_product["new_map_price"];
			}

			$similar_product["taxed_price"] = $similar_product["price"];

		        $tmp = func_query_first("SELECT image_path as image_path_T, image_x as image_x_T, image_y as image_y_T FROM $sql_tbl[images_T] WHERE id = '$v[productid]'");
		        if (!empty($tmp)) {
		                $similar_product = func_array_merge($similar_product, $tmp);
		                $similar_product['is_thumbnail'] = true;
				$similar_product['tmbn_url'] = func_get_image_url($similar_product["productid"], "T", $similar_product['image_path_T']);
		        } else {
				$tmp = func_query_first("SELECT image_path as image_path_P, image_x as image_x_P, image_y as image_y_P FROM $sql_tbl[images_P] WHERE id = '$v[productid]'");
				$similar_product['is_image'] = true;
				$similar_product['tmbn_url'] = func_get_image_url($similar_product["productid"], "P", $similar_product['image_path_P']);
			}

		        if (!$similar_product['is_image'] && !$similar_product['is_thumbnail']) {
		                $similar_product["tmbn_url"] = func_get_default_image("P");
		        }

                        $similar_products[$k] = $similar_product;
			unset($similar_product);

                        if ($k == "2")
                                break;
                }
/*
                if (!empty($similar_products) && is_array($similar_products)){
                        foreach ($similar_products as $k => $v){
                                if (!empty($v["tmbn_url_P"]) && !empty($v["tmbn_url_T"])){
                                        $similar_products[$k]["tmbn_url"] = $v["tmbn_url_T"];
                                }
                        }
                }
*/

                $smarty->assign("similar_products", $similar_products);
        }

}
###
##
#

$pos = strpos($product_info['productcode'], '-');
$mpn = '';

if ($pos && is_numeric($pos) && $pos + 1 != strlen($product_info['productcode'])) {
	$mpn = substr($product_info['productcode'], $pos + 1);
	$smarty->assign("cidev_mpn", $mpn);
}

if (!empty($location) && is_array($location)){
	$tmp_count_location = count($location);
	$cat_for_itemscope1 = $tmp_count_location - 2;
	$cat_for_itemscope2 = $tmp_count_location - 3;

	if (!empty($location[$cat_for_itemscope1])){
		$cat_for_itemscope[$cat_for_itemscope1] = $location[$cat_for_itemscope1][0];
		$smarty->assign("cat_name_for_itemprop", $location[$cat_for_itemscope1][0]);
	}

        if (!empty($location[$cat_for_itemscope2])){
                $cat_for_itemscope[$cat_for_itemscope2] = $location[$cat_for_itemscope2][0];
        }

	if (!empty($cat_for_itemscope)){
		$smarty->assign("cat_for_itemscope", $cat_for_itemscope);
	}
}

# Assign the current location line
$smarty->assign("location", $location);

func_display("customer/home.tpl",$smarty);
?>
