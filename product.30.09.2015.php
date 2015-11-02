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

x_load("category");

#
##
###

if ($mode == "notify" && !empty($productid) && !empty($notify_email)){
	$is_in_table = func_query_first_cell("SELECT productid FROM $sql_tbl[notify_when_in_stock] WHERE email='$notify_email' AND sent='N' AND productid='$productid'");

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

	$clean_url_link = func_query_first_cell("SELECT clean_url FROM $sql_tbl[clean_urls] WHERE resource_type='P' AND resource_id='$productid'");
	func_header_location($clean_url_link);
}
###
##
#

if (
    isset($productid)
    && !empty($productid)
    && $config['SEO']['clean_urls_enabled'] == 'Y'
    && !defined('DISPATCHED_REQUEST')
) {
    func_clean_url_permanent_redirect('P', intval($productid));
}

x_load('product');

#
##
###
$current_forsale = func_query_first_cell("SELECT forsale FROM $sql_tbl[products] WHERE productid='$productid'");
if ($current_forsale == "N"){

	$categoryid_path = func_query_first_cell("SELECT $sql_tbl[categories].categoryid_path FROM $sql_tbl[categories] LEFT JOIN $sql_tbl[products_categories] ON $sql_tbl[categories].categoryid = $sql_tbl[products_categories].categoryid WHERE $sql_tbl[products_categories].productid='$productid' AND $sql_tbl[products_categories].main='Y' and $sql_tbl[categories].storefrontid = $current_storefront");

	$categoryid_path_arr = explode('/', $categoryid_path);
	krsort($categoryid_path_arr);

	if (!empty($categoryid_path_arr) && is_array($categoryid_path_arr)){
		foreach ($categoryid_path_arr as $k => $categoryid){
			$avail = func_query_first_cell("SELECT avail FROM $sql_tbl[categories] WHERE categoryid='$categoryid'");
			if ($avail == "Y"){
				$redirect_url = func_query_first_cell("SELECT clean_url FROM $sql_tbl[clean_urls] WHERE resource_type='C' AND resource_id='$categoryid'");
				if (!empty($redirect_url)){
					$redirect_url = $xcart_web_dir . "/".$redirect_url."/";
					func_header_location($redirect_url, true, 301);
				}
			}
		}
	}

	$brandid = func_query_first_cell("SELECT brandid FROM $sql_tbl[products] WHERE productid='$productid'");
	if (!empty($brandid)){
		$redirect_url = func_query_first_cell("SELECT clean_url FROM $sql_tbl[clean_urls] WHERE resource_type='M' AND resource_id='$brandid'");
	                if (!empty($redirect_url)){
        	                $redirect_url = $xcart_web_dir . "/".$redirect_url."/";
                        }
	} else {
		$redirect_url = $xcart_web_dir . "/";
	}

	func_header_location($redirect_url, true, 301);
}
###
##
#

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

/*
if (!empty($active_modules["Upselling_Products"]))
	include $xcart_dir."/modules/Upselling_Products/related_products.php";
*/

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


#
##
###
$product_feed_enabled = func_query_first_cell("SELECT d_enable_feed FROM $sql_tbl[manufacturers] WHERE manufacturerid='$product_info[manufacturerid]'");
$smarty->assign("product_feed_enabled", $product_feed_enabled);

if ($product_feed_enabled == "Y" && empty($product_info["is_variants"]) && $product_info["r_avail"] <= 0){

//        $max1 = $product_info["cost_to_us"] + ($product_info["taxed_price"] - $product_info["cost_to_us"])/3;
//        $new_notify_in_stock_price = max($product_info["map_price"], $max1);

	if ($product_info["mult_order_quantity"] == "Y" && $product_info["min_amount"] > 1){
		$product_info["price"] = $product_info["taxed_price"] = func_query_first_cell("SELECT price FROM $sql_tbl[pricing] WHERE productid='$product_info[productid]' AND quantity <= '$product_info[min_amount]' ORDER BY quantity DESC LIMIT 1");
	}

	$new_notify_in_stock_price = func_decreased_price($product_info["cost_to_us"], $product_info["taxed_price"], $product_info["map_price"]);

        $product_info["new_notify_in_stock_price"] = $new_notify_in_stock_price;
//func_print_r($product_info, $max1, $new_price);
}
###
##
#

###
	if ($product_info["new_map_price"] == "0"){

		if (!empty($active_modules["Wholesale_Trading"]) && empty($product_info['variantid'])){
			include $xcart_dir."/modules/Wholesale_Trading/product.php";

			if (!empty($wresult) && is_array($wresult) && $product_info["min_amount"] > 0 && $wresult[0]["quantity"] == $product_info["min_amount"]){
				$product_subtotal_value = $wresult[0]["price"] * $wresult[0]["quantity"];
				$smarty->assign("product_subtotal_value", $product_subtotal_value);
			}
		}
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

if ($show_dimensions){
	$show_dimensions_orderby = array();
	foreach (array('dim_x','dim_y','dim_z') as $k) {
		if (!empty($product_info[$k])){
			$show_dimensions_orderby[] = $product_info[$k];
		}
	}

	if (!empty($show_dimensions_orderby)){
		arsort($show_dimensions_orderby);
		foreach ($show_dimensions_orderby as $k => $v){
			$show_dimensions_orderby[$k] = $v.'"';
		}

		$show_dimensions_orderby_str = implode(" x ", $show_dimensions_orderby);
		$smarty->assign('show_dimensions_orderby_str', $show_dimensions_orderby_str);
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

#
##
###
$product_tabs[0]["title"] = "Product description";
$product_tabs[0]["tpl"] = "_product_description_";
$product_tabs[0]["anchor"] = 0;

if (!empty($brandid_brands_info[$product_info["brandid"]]["descr"])){

	$brand_image = func_image_properties("B", $product_info["brandid"]);
	if (!empty($brand_image["filename"])){
		$smarty->assign("brand_image", $brand_image);
	}

	$product_tabs[1]["title"] = "Brand";
	$product_tabs[1]["tpl"] = "_Brand_";
	$product_tabs[1]["anchor"] = 1;
}
###
##
#

if (!empty($cart_manufact_text_displayed_tabs) && is_array($cart_manufact_text_displayed_tabs)){
	$count_product_tabs = count($product_tabs);
        foreach ($cart_manufact_text_displayed_tabs as $k => $v){
                $product_tabs[$k+$count_product_tabs]["title"] = $v[0];
                $product_tabs[$k+$count_product_tabs]["tpl"] = $v[1];
                $product_tabs[$k+$count_product_tabs]["anchor"] = $k+$count_product_tabs;
        }
}

if ($config['product_question_email']['product_question_enable'] == 'Y') {
	$count_product_tabs = count($product_tabs);
	$product_tabs[$count_product_tabs]["title"] = "Product questions";
	$product_tabs[$count_product_tabs]["tpl"] = "_product_question_tpl_";
	$product_tabs[$count_product_tabs]["anchor"] = $count_product_tabs;
}
/*echo "maintenanced 5<br>";
exit;*/


$chech_domain = "http://www.productqueries.com/";

if ($config['product_queries']['product_queries_enable'] == 'Y' && url_exists($chech_domain)) {
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

	  $curl_err = false;
	  $ch = curl_init();
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	  curl_setopt($ch, CURLOPT_URL, $full_server_url);
	  curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1000);
	  $output = curl_exec($ch);

	  if (curl_errno($ch) != 0 || curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
	          $curl_err = true;
	  }
	  curl_close($ch);

	  if (!$curl_err){


	        $productqueries_page = file_get_contents($full_server_url);
        	$productqueries_page = utf8_encode($productqueries_page);
	        if (!empty($productqueries_page)){

			if (!empty($product_info["clean_url"])){
				$clean_url_arr = explode("/", $product_info["clean_url"]);
				$clean_url_last_part = array_pop($clean_url_arr);
				$clean_url_last_part = ucwords($clean_url_last_part);
			}

        	        $productqueries_page_arr = json_decode($productqueries_page, true);

                	if (!empty($productqueries_page_arr) && is_array($productqueries_page_arr)){

				foreach ($productqueries_page_arr as $k => $v){

					if (!empty($v["name"]))
						$productqueries_page_arr[$k]["name"] = html_entity_decode($v["name"]);

                                        if (!empty($v["content"]))
                                                $productqueries_page_arr[$k]["content"] = html_entity_decode($v["content"]);


					if (!empty($v["answers"]) && is_array($v["answers"])){
						foreach ($v["answers"] as $kk => $vv){

		                                        if (!empty($vv["content"]))
                		                                $productqueries_page_arr[$k]["answers"][$kk]["content"] = html_entity_decode($vv["content"]);

							if (empty($vv["comments"])){
								unset($productqueries_page_arr[$k]["answers"][$kk]["comments"]);
							}
						}
					}

					if (!empty($v["url"]) && !empty($clean_url_last_part)){
						$productqueries_page_arr[$k]["url"] = "http://www.productqueries.com/".$v["tid"]."/".$clean_url_last_part."/";
					}
				}

                        	$smarty->assign("productqueries_page_arr", $productqueries_page_arr);
	                }
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

#
##
###
		$product_image = "";
		$product_image_hidden = "";
		if (!empty($product_info["image_path_P"])){
		        $product_image = str_replace("./", "", $product_info["image_path_P"]);
		} elseif (!empty($product_info["image_path_T"])){
		        $product_image = str_replace("./", "", $product_info["image_path_T"]);
		}
		if (!empty($product_image)){
		        $product_image = $site_url.$product_image;
			$product_image_hidden = '<input type="hidden" name="product_image" value="'.$product_image.'">';
		}
###
##
#
		$full_get_question_forms_url = $get_question_forms_url.$additional_query;


		$curl_err = false;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $full_get_question_forms_url);
		curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1000);
		$output = curl_exec($ch);

		if (curl_errno($ch) != 0 || curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
		        $curl_err = true;
		}
		curl_close($ch);

		if (!$curl_err){


			$get_question_forms_url_info = @file_get_contents($full_get_question_forms_url);
			$get_question_forms_url_info = utf8_encode($get_question_forms_url_info);

			$get_question_forms_url_info_arr = explode("</style>", $get_question_forms_url_info);
			$product_form_info = array_pop($get_question_forms_url_info_arr);

			$product_form_info = str_replace('<input type="submit" value="Submit question">', $product_image_hidden.'<span onclick="javascript: document.form_query.submit();" class="cidev_new_button cidev_new_white">Submit question</span>', $product_form_info);
			$product_form_info = str_replace('<form', '<form name="form_query"', $product_form_info);

			$smarty->assign('product_form_info', $product_form_info);

		}
	}
}


$count_product_tabs = count($product_tabs);
$product_tabs[$count_product_tabs]["title"] = "Product discussions";
$product_tabs[$count_product_tabs]["tpl"] = "_product_discussions_tpl_";
$product_tabs[$count_product_tabs]["anchor"] = $count_product_tabs;

if (!empty($product_tabs) && is_array($product_tabs)) {

	$count_shipping_rates_for_canada = func_query_first_cell("SELECT manufacturerid FROM $sql_tbl[shipping_rates] WHERE manufacturerid='$product_info[manufacturerid]' AND (type='R' OR type='D') AND zoneid='12'");

	if (empty($count_shipping_rates_for_canada)){
		foreach ($product_tabs as $k => $v){
			if ($v["title"] == "Shipping"){
				$product_tabs[$k]["tpl"] .= "<font class='ErrorMessage'>" . func_get_langvar_by_name("lbl_we_dont_ship_to_Canada_product_page") . "</font>"; 
			}
		}
	}

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

/*	$product_info["avail"] = 0;*/
}
###
##
#

if (empty($product_info["lead_time_message"])){
	$lead_time_message = func_query_first_cell("SELECT lead_time_message FROM $sql_tbl[manufacturers] WHERE manufacturerid='$product_info[manufacturerid]'");

	$lead_time_message = str_replace("'","\'",$lead_time_message);
	$lead_time_message = str_replace('"',"\'",$lead_time_message);

	$product_info["lead_time_message"] = $lead_time_message;
}

# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 

$product_info["product_availability"] = func_product_availability(false,false,false,false,false,$product_info);

###
if (!empty($cart["shipping_groups"][$product_info["manufacturerid"]])){
	if (!empty($cart["shipping_groups"][$product_info["manufacturerid"]]["need_add_more"]) && !empty($cart["shipping_groups"][$product_info["manufacturerid"]]["d_minimum_order_amount_in_us"]) && $cart["shipping_groups"][$product_info["manufacturerid"]]["d_minimum_order_amount_in_us"] > $product_info["taxed_price"]){

		$product_info["lbl_minimum_order_amount_message_product"] = "Y";
		$product_info["d_minimum_order_amount_in_us"] = $cart["shipping_groups"][$product_info["manufacturerid"]]["d_minimum_order_amount_in_us"];
	}
}
else {

	$manuf_info_arr = func_query_first("SELECT d_minimum_order_amount_in_us, d_minimum_order_amount, d_for_orders_below_min_order_amount FROM $sql_tbl[manufacturers] WHERE manufacturerid='".$product_info["manufacturerid"]."'");
	$d_minimum_order_amount_in_us = $manuf_info_arr["d_minimum_order_amount_in_us"];
	$d_minimum_order_amount = $manuf_info_arr["d_minimum_order_amount"];
	$d_for_orders_below_min_order_amount = $manuf_info_arr["d_for_orders_below_min_order_amount"];

	if ($d_minimum_order_amount_in_us != "0.00" && $d_minimum_order_amount == "applies_to_all_orders" && $d_for_orders_below_min_order_amount == "are_rejected"){

	    if ($product_info["taxed_price"] < $d_minimum_order_amount_in_us){
	        $product_info["lbl_minimum_order_amount_message_product"] = "Y";
	        $product_info["d_minimum_order_amount_in_us"] = $d_minimum_order_amount_in_us;
	    }
	}
}
###

###
$product_info["product_questions"] = func_query("SELECT * FROM $sql_tbl[product_question] WHERE question_published_on_page='Y' AND productid='$productid' ORDER BY order_by");

if (!empty($product_info["product_questions"]) && is_array($product_info["product_questions"])){

	foreach ($product_info["product_questions"] as $k => $v){

		if (!empty($v["login"])){
			$operator_name = func_query_first_cell("SELECT firstname FROM $sql_tbl[customers] WHERE login='".$v["login"]."'");
			$operator_name = trim($operator_name);
			$operator_first_name_arr = explode(" ", $operator_name);
			$operator_first_name = $operator_first_name_arr[0];
			$product_info["product_questions"][$k]["operator_name"] = $operator_name;
			$product_info["product_questions"][$k]["operator_first_name"] = $operator_first_name;
		}

		if (empty($v["answered_date"])){
			$answered_date = $v["date"];
			$answered_date_str = date("N", $answered_date);
			if ($answered_date_str <= 4 || $answered_date_str == "7"){
		        	$answered_date += 60*60*24;
			}
			elseif ($answered_date_str == "6"){
			        $answered_date += 60*60*24*2;
			}
			$product_info["product_questions"][$k]["answered_date"] = $answered_date;
			db_query("UPDATE $sql_tbl[product_question] SET answered_date='$answered_date' WHERE id='$v[id]'");
		}
	}
}

###

//func_print_r($product_info["product_questions"]);

//func_print_r($product_info, $current_storefront_info["storefrontid"]);

if ($current_storefront_info["storefrontid"] == "50"){

	$br_str = array("<br>", "<br/>", "</br>", "</ br>", "<Br>", "<Br/>", "<Br />", "</Br>", "</ Br>", "<BR>", "<BR/>", "<BR />", "</BR>", "</ BR>");
	$fulldescr = str_replace($br_str, "<br />", $product_info["fulldescr"]);

	$pos_fulldescr_1 = strpos($fulldescr, '*');

	if ($pos_fulldescr_1 !== false){
		$pos_fulldescr_2 = strpos($fulldescr, '<br />', $pos_fulldescr_1);

		if ($pos_fulldescr_2 !== false){

			$fulldescr = substr_replace($fulldescr, '<ul><li>', $pos_fulldescr_1, 1);
			$fulldescr = str_replace("*", "</li><li>", $fulldescr);

			$fulldescr_arr = explode("<br />", $fulldescr);
			$count_fulldescr_arr = count($fulldescr_arr)-1;
			$fulldescr_arr[$count_fulldescr_arr] = $fulldescr_arr[$count_fulldescr_arr] . "</li></ul>";
			$fulldescr = implode("<br />", $fulldescr_arr);

			$product_info["fulldescr"] = $fulldescr;
		}
	}
}

$smarty->assign("product",$product_info);

if ($active_modules["Bestsellers"])
	include $xcart_dir."/modules/Bestsellers/bestsellers.php";


/*
#
## similar products
###
$membershipid = isset($user_account['membershipid']) ? $user_account['membershipid'] : 0;

if (!empty($cat)){

    if (!empty($product_info["similar_productids"])){
	
	$similar_productids_arr = explode(",", $product_info["similar_productids"]);

	if (!empty($similar_productids_arr) && is_array($similar_productids_arr)){
		foreach ($similar_productids_arr as $k => $v){
			$tmp_products_arr[$k]["productid"] = trim($v);
		}
	}
    } else {

        $tmp_products1 = db_query("
                SELECT $sql_tbl[products].productid, $sql_tbl[products].cost_to_us FROM $sql_tbl[products]
                LEFT JOIN $sql_tbl[products_categories]
                        ON $sql_tbl[products_categories].productid = $sql_tbl[products].productid
                WHERE 
                        $sql_tbl[products_categories].categoryid = '$cat' AND $sql_tbl[products_categories].main='Y' AND $sql_tbl[products].cost_to_us > '$product_info[cost_to_us]' AND $sql_tbl[products].productid != '$product_info[productid]' AND $sql_tbl[products].forsale='Y' AND $sql_tbl[products].avail > 0
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
                        $sql_tbl[products_categories].categoryid = '$cat' AND $sql_tbl[products_categories].main='Y' AND $sql_tbl[products].cost_to_us <= '$product_info[cost_to_us]' AND $sql_tbl[products].productid != '$product_info[productid]' AND $sql_tbl[products].forsale='Y' AND $sql_tbl[products].avail > 0
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

		$similar_productids_arr = array();
		foreach ($tmp_products_arr as $k => $v){
			$similar_productids_arr[] = $v["productid"];
                        if ($k == "2")
                                break;
		}
		$similar_productids = implode(",", $similar_productids_arr);
		db_query("UPDATE $sql_tbl[products] SET similar_cron_generated_flag='Y', similar_productids='$similar_productids', similar_time='".time()."' WHERE productid='$product_info[productid]'");
	}
    }

	if (!empty($tmp_products_arr) && is_array($tmp_products_arr)){
		$tmp_sim_prod_counter = 0;
                foreach ($tmp_products_arr as $k => $v){

//                        $similar_products[$k] = func_select_product($v["productid"], @$user_account['membershipid']);

			$similar_product = func_query_first("SELECT productid, product, list_price, map_price, new_map_price FROM $sql_tbl[products] WHERE productid='$v[productid]' AND forsale='Y'");

			if (empty($similar_product)){
				continue;
			}

			$tmp_sim_prod_counter++;

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

//                        if ($k == "2")
                        if ($tmp_sim_prod_counter == "3")
                                break;
                }

		if (!empty($similar_products) && is_array($similar_products)){
			$similar_products = array_values($similar_products);
		}

                $smarty->assign("similar_products", $similar_products);
        }

}
###
##
#
*/

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


#
##
###
if (!empty($product_info["supplier_internal_id_last_parsed_update"])){
	$count_days = (time() - $product_info["supplier_internal_id_last_parsed_update"])/(60*60*24);
}

if ($product_info["manufacturerid"] == "32" && !empty($product_info["supplier_internal_id"]) && !empty($product_info["supplier_internal_option"]) && $count_days > 10){
//if ($product_info["manufacturerid"] == "32" && !empty($product_info["supplier_internal_id"]) && $count_days > 0){

/*
func_print_r($product_info["supplier_internal_id"]);


	$post[] = "form_key=WJrDj5WRAUur1ndq";
	$post[] = "product=".$product_info["supplier_internal_id"];
	$post[] = "real_product=".$product_info["supplier_internal_id"];
	$post[] = "super_attribute%5B1284%5D=11973";
	$post[] = "options%5B449924%5D=1";
	$post[] = "qty=0";
	$post = implode("&", $post);

	list($a1,$data,$a2)=func_http_post_request("www.aajewelry.com","/aajewelry/simpleproduct/loadproduct/",$post);


	$data_arr = json_decode($data, true);

func_print_r($a1, $data, $data_arr);
*/

	$url = "http://www.aajewelry.com/quickshop/product/view/id/".$product_info["supplier_internal_id"]."/?keepThis=true&width=650&height=500&modal=false";
	$error_found = false;
	$make_redirect = false;


	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_TIMEOUT_MS, 2000);
        $output = curl_exec($ch);


        if (curl_errno($ch) != 0 || curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
                $error_found = true;
        }

        if (curl_errno($ch) == 0 && curl_getinfo($ch, CURLINFO_HTTP_CODE) == 404) {
		db_query("UPDATE $sql_tbl[products] SET forsale='N' WHERE productid='$productid'");
		$make_redirect = true;
		$error_found = true;
        }

	if (!$error_found){

		$output = str_replace(array("\n", "\r"), '', $output);

		$new_eta_date_mm_dd_yyyy = $product_info["eta_date_mm_dd_yyyy"];
		$new_r_avail = $product_info["r_avail"];
		$new_cost_to_us = $product_info["cost_to_us"];
		$new_list_price = $product_info["list_price"];
		$new_min_amount = $product_info["min_amount"];
		$new_new_map_price = $product_info["new_map_price"];
		$new_discount_table = $product_info["discount_table"];


		$loadproduct = func_GetAAJ_product_info($product_info["supplier_internal_id"], $product_info["supplier_internal_option"]);

                if (empty($loadproduct["min_amount"]) || $loadproduct["instock"] == "N"){
                        $new_r_avail = 0;
                        $new_eta_date_mm_dd_yyyy_time = time() + 60*60*24*10;
                        $new_eta_date_mm_dd_yyyy = date("m/d/Y", $new_eta_date_mm_dd_yyyy_time);
                }
                elseif ($loadproduct["instock"] == "Y" && $loadproduct["min_amount"]> 0) {
                        $new_r_avail = 10000;
                        $new_min_amount = $loadproduct["min_amount"];
			$new_mult = $loadproduct["mult_order_quantity"];
                        $new_eta_date_mm_dd_yyyy = "";

                        if (!empty($loadproduct["discount_table"])){
                                $new_discount_table = $loadproduct["discount_table"];
                        }

                        if (isset($loadproduct["list_price"])){
                                $new_list_price = $loadproduct["list_price"];
                        }

                        if (isset($loadproduct["cost_to_us"])){
                                $new_cost_to_us = $loadproduct["cost_to_us"];
                        }
                }



/*
###
//	        $parsed_cost_to_us = func_parse_cost_to_us($output);

	        $post[] = "form_key=WJrDj5WRAUur1ndq";
        	$post[] = "product=".$product_info["supplier_internal_id"];
	        $post[] = "real_product=".$product_info["supplier_internal_id"];
        	$post[] = "super_attribute%5B1284%5D=11973";
	        $post[] = "options%5B449924%5D=1";
        	$post[] = "qty=0";
	        $post = implode("&", $post);

        	list($a1,$data,$a2)=func_http_post_request("www.aajewelry.com","/aajewelry/simpleproduct/loadproduct/",$post);

	        $data_arr = json_decode($data, true);

###
		$data_arr_log = $data_arr;
		if (!empty($data_arr_log["product"]["options"])){
			unset($data_arr_log["product"]["options"]);
		}
		if (!empty($data_arr_log["product"]["spec"])){
                        unset($data_arr_log["product"]["spec"]);
                }
                if (!empty($data_arr_log["product"]["shortdesc"])){
                        unset($data_arr_log["product"]["shortdesc"]);
                }
                if (!empty($data_arr_log["product"]["imageurl"])){
                        unset($data_arr_log["product"]["imageurl"]);
                }
                if (!empty($data_arr_log["product"]["gallery"])){
                        unset($data_arr_log["product"]["gallery"]);
                }

		$data_log = json_encode($data_arr_log);
		$data_log = htmlentities($data_log);
		$data_log = str_replace("{","{\n", $data_log);
		$data_log = str_replace("{","{\n", $data_log);
		$data_log = str_replace("','","',\n'", $data_log);
		$data_log = str_replace('",','",'."\n", $data_log);
		func_backprocess_log("aaj_parsing", $data_log);

###

//                $parsed_cost_to_us = strip_tags($data_arr["product"]["options"][0]["unit_price"]);
                $parsed_cost_to_us = strip_tags($data_arr["product"]["msrp"]);
                $parsed_cost_to_us = str_replace(array("$",","), "", $parsed_cost_to_us);
                $parsed_cost_to_us = trim($parsed_cost_to_us);

		if (empty($parsed_cost_to_us) || $parsed_cost_to_us == "0.00"){
	                $parsed_cost_to_us = strip_tags($data_arr["product"]["price"]);
        	        $parsed_cost_to_us = str_replace(array("$",","), "", $parsed_cost_to_us);
                	$parsed_cost_to_us = trim($parsed_cost_to_us);
		}
                else {
                        $new_new_map_price = $parsed_cost_to_us;
                }

//		func_print_r($parsed_cost_to_us);
###

#
                if (isset($data_arr["product"]["options"][0]["qty"]) && isset($data_arr["product"]["options"][0]["price"]) && $data_arr["product"]["options"][0]["qty"]>0){
                        $tmp_parsed_cost_to_us = strip_tags($data_arr["product"]["options"][0]["price"]);
                        $tmp_parsed_cost_to_us = str_replace(array("$",","), "", $tmp_parsed_cost_to_us);
                        $tmp_parsed_cost_to_us = trim($tmp_parsed_cost_to_us);

                        if (empty($tmp_parsed_cost_to_us) || $tmp_parsed_cost_to_us == "0.00"){
                                unset($tmp_parsed_cost_to_us);
                        }
                        else {
                                $parsed_cost_to_us = $tmp_parsed_cost_to_us;
                        }
                } 
#

	        if (!empty($parsed_cost_to_us)){
        	        $new_cost_to_us = $parsed_cost_to_us;
	        }

  		$add_to_cart_button = func_parse_add_to_cart_button($output);

	        if ($add_to_cart_button){
	                $new_r_avail = 1000;
	                $new_eta_date_mm_dd_yyyy = "";
	        }

	        if (!$add_to_cart_button || empty($new_cost_to_us) || $new_cost_to_us == "0.00") {
        	        $new_r_avail = 0;
                	$new_cost_to_us = $product_info["cost_to_us"];

	                $new_eta_date_mm_dd_yyyy_time = time() + 60*60*24*35;
        	        $new_eta_date_mm_dd_yyyy = date("m/d/Y", $new_eta_date_mm_dd_yyyy_time);
	        }

		$new_discount_table = $product_info["discount_table"];

                if (!empty($data_arr["product"]["discount"]) && is_array($data_arr["product"]["discount"])){
                        $new_discount_table_arr = array();

                        foreach ($data_arr["product"]["discount"] as $k_d => $v_d){
                                $new_discount_table_arr[] = $v_d["min_qty"].":0.".$v_d["discount_pct"];
                        }

                        $new_discount_table = implode(",", $new_discount_table_arr);
                }

*/
	        if ($new_mult != $product_info["mult_order_quantity"] ||  $new_eta_date_mm_dd_yyyy != $product_info["eta_date_mm_dd_yyyy"] || $product_info["r_avail"] != $new_r_avail || $product_info["cost_to_us"] != $new_cost_to_us || $new_discount_table != $product_info["discount_table"] || $new_new_map_price != $product_info["new_map_price"] || $new_min_amount != $product_info["min_amount"] || $new_list_price != $product_info["list_price"]){

	                db_query("UPDATE $sql_tbl[products] SET mult_order_quantity ='$new_mult', r_avail='$new_r_avail', eta_date_mm_dd_yyyy='$new_eta_date_mm_dd_yyyy', supplier_internal_id_last_parsed_update='".time()."', supplier_internal_id_last_parsed='".time()."', cost_to_us='$new_cost_to_us', discount_table='$new_discount_table', new_map_price='$new_new_map_price', list_price='$new_list_price', min_amount='$new_min_amount' WHERE productid='$productid'");

			$make_redirect = true;

	                if ($product_info["discount_table"] != $new_discount_table){
        	                func_generate_discounts(array("$productid"));
	                }
	        }
	}

	curl_close($ch);

	if ($make_redirect){
		$url = func_clean_url_get("P", $productid);
	        func_header_location($url);
	}
}
###
##
#

#
##
###
if ($config["Appearance"]["Enable_surf_stats"] == "Y"){
        func_log_cidev_surf("P");
}
###
##
#


# Assign the current location line
$smarty->assign("location", $location);

func_display("customer/home.tpl",$smarty);
?>
