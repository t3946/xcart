<?php /* ADDED: random:20341 [2010 Jul 29 14:46][Custom development (Accounting features for X-Cart orders management)] */ ?>
<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2010 Ruslan R. Fazliev <rrf@rrf.ru>                      |
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
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2010           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: func.order_edit.php, v 1.0.0 2010/03/24 14:51:12 random Exp $
#

if ( !defined('XCART_SESSION_START') ) { header("Location: ../../"); die("Access denied"); }

x_load('cart','mail','order','product','taxes');

function func_check_tracking_number($linkid, $tracknum) {
	global $sql_tbl;

	$link = func_query_first_cell("SELECT link FROM $sql_tbl[tracking_links] WHERE linkid='$linkid'");
    
	if (empty($link)) {
		return false;
	}
    
	if (preg_match('/(http|https).*/', $link) && empty($tracknum)) {
		return false;
	}
    
	return true;
}

function func_recalculate_accounting(&$group, $all_processors = array(), $apply_per_trans=false, $refund = false) {
	global $sql_tbl, $price_details_names;

	$acc = $group['accounting'];

	for ($ak=0; $ak<=5; $ak++) {
		if (empty($acc[$ak]) || $ak == 5) {
			$acc[$ak] = array();
			foreach ($price_details_names as $af) {
				$acc[$ak][$af] = 0;
			}
		}
	}

	if (!empty($group['acc_paymentid'])) {
		if (empty($all_processors[$group['acc_paymentid']])) {
			$all_processors[$group['acc_paymentid']] = func_query_first("SELECT acc_percent, acc_per_trans FROM $sql_tbl[payment_methods] WHERE paymentid='$group[acc_paymentid]'");
		}
		$percent = $all_processors[$group['acc_paymentid']]['acc_percent'];
		$per_trans = $all_processors[$group['acc_paymentid']]['acc_per_trans'];
	} else {
		$percent = $per_trans = 0;
	}

	$acc[0]['gst'] = $group['total']['gst'];
	$acc[0]['pst'] = $group['total']['pst'];
	$acc[0]['gross'] = (1 - $percent/100)*$group['total']['gross'];
	if ($apply_per_trans) {
		$acc[0]['gross'] -= $per_trans;
	}

    if ($refund) {
        $ref_values = func_query_first('SELECT total_net, total_gst, total_pst, total_gross'
            . ' FROM ' . $sql_tbl['refund_groups']
            . ' WHERE orderid = "' . $group['orderid'] . '" AND manufacturerid = "' . $group['manufacturerid'] . '"');
        if (!empty($ref_values)) {
            $acc[ACC_REF_TO_CUST]['gross'] = func_calculate_gross_ref_to_cust($group['orderid'], $group['manufacturerid'], $group['acc_paymentid']);
            $acc[ACC_REF_TO_CUST]['gst'] = floatval($ref_values['total_gst']);
            $acc[ACC_REF_TO_CUST]['pst'] = floatval($ref_values['total_pst']);
            $acc[ACC_REF_TO_CUST]['net'] = floatval($acc[ACC_REF_TO_CUST]['gross'] - $acc[ACC_REF_TO_CUST]['gst'] - $acc[ACC_REF_TO_CUST]['pst']);
        } else {
            $acc[ACC_REF_TO_CUST]['net'] = 0;
            $acc[ACC_REF_TO_CUST]['gst'] = 0;
            $acc[ACC_REF_TO_CUST]['pst'] = 0;
            $acc[ACC_REF_TO_CUST]['gross'] = 0; 
        }
    }

	for ($ak=0; $ak<=4; $ak++) {
		$acc[$ak]['net'] = $acc[$ak]['gross'] - $acc[$ak]['pst'] - $acc[$ak]['gst'];
		foreach ($price_details_names as $af) {
			$_sign = ($ak >= 1 && $ak <= 3) ? -1 : 1;
			$acc[5][$af] += floatval($acc[$ak][$af]) * $_sign;
		}
	}

	$group['accounting'] = $acc;
    if ($acc[0]['net'] != 0) {
    	$group['profit_margin'] = price_format(($acc[5]['net'] / $acc[0]['net'] * 100));
    } else {
        $group['profit_margin'] = 0;
    }

    $group['accounting'] = func_set_filled_option($group['accounting']);
	
	return true;

}


function func_sort_taxes_priority($a, $b) {
    if ($a['priority'] == $b['priority']) {
        return 0;
    }
    return ($a['priority'] < $b['priority']) ? 1 : -1;
}

function func_tax_price_details($price, $taxes, $de_tax=false) {
	global $sql_tbl;

	$return =  array('net' => $price, 'gross' => $price, 'pst' => 0, 'gst' => 0 );

	if (empty($taxes)) {
		return $return;
	}

	$tax_array = array();
	
	if ($de_tax) {

		uasort($taxes, "func_sort_taxes_priority");

		foreach ($taxes as $k=>$tax_rate) {
			$_price = $price;
			if ($tax_rate["rate_type"] == "%") {
				$price = $price*100/($tax_rate["rate_value"] + 100);
			}
			else {
				$price -= $tax_rate["rate_value"];
			}
			$tax_array[$k] = $_price - $price;
		}

		$return['net'] = $price;

	} else {

		$tmp = func_tax_price($price, 0, false, NULL, '', $taxes);
		if (!empty($tmp['taxes'])) {
			foreach ($tmp['taxes'] as $tid => $v) {
				foreach ($taxes as $tname => $allv) {
					if ($tid == $allv['taxid']) {
						$tax_array[$tname] = $v;
						break;
					}
				}
				
			}
		}

		$return['net'] = $tmp['net_price'];
		$return['gross'] = $tmp['taxed_price'];

	}

	if (!empty($tax_array)) {
		foreach ($tax_array as $k => $v) {
			if ($k == 'GST' || $k == 'HST') {
				$return['gst'] += $v;
			} elseif ($k == 'PST') {
				$return['pst'] += $v;
			}
		}
	}

	return $return;

}

#
# This function calculates the product's quantity in stock to display it
# on the Edit products dialog
#
function func_oe_get_quantity_in_stock($productid, $cb_status, $dc_status, $options = array(), $order_product = array()) {
	global $sql_tbl, $active_modules;

	$quantity_in_stock = (strpos('PQI', $cb_status) !== false || $dc_status == 'C') ? ((!empty($active_modules['Egoods']) 
        && !empty($order_product['distribution'])) ? 0 : $order_product['amount']) : 0;
	if (!empty($active_modules['Product_Options']) && !empty($options)) {
		$is_equal = false;

		if (!empty($order_product['product_options']) && is_array($order_product['product_options'])) {
			$order_options = array();
			foreach ($order_product['product_options'] as $cid => $o) {
				$order_options[$cid] = $o['optionid'];
			}
			$order_variantid = func_get_variantid($order_options);
			$variantid = func_get_variantid($options);

			$is_equal = ($order_variantid == $variantid);
		}

		$quantity_in_stock += ($is_equal) ? func_query_first_cell("SELECT avail FROM $sql_tbl[variants] WHERE variantid='$variantid'") : 0;

	} else {
		$quantity_in_stock += func_query_first_cell("SELECT avail FROM $sql_tbl[products] WHERE productid='$productid'");
	}

	if (!empty($active_modules["RMA"]))
		$quantity_in_stock -= (int)func_query_first_cell("SELECT SUM(returned_amount) FROM $sql_tbl[returns] WHERE itemid = '$order_product[itemid]'");

	return $quantity_in_stock;
}

#
# This function validates the price that can be entered e.g. as $15.07
#
function func_oe_validate_price($price) {
	return func_detect_price($price);
}

#
# This function updates products prices with VAT values
#
function func_oe_update_prices($products, $customer_info) {
	global $config, $real_taxes;

	foreach ($products as $k=>$v) {
		$products[$k]["price_deducted_tax"] = "Y";
		if ($real_taxes == "Y")
			$_taxes = func_get_product_taxes($products[$k], $customer_info["login"], false);
		else
			$_taxes = func_get_product_taxes($products[$k], $customer_info["login"], false, $v["extra_data"]["taxes"]);
		$products[$k]["extra_data"]["taxes"] = $products[$k]["taxes"] = $_taxes;
	}
	return $products;
}

#
# This function recalculate order totals
#
function func_oe_recalculate_totals($cart) {
	global $active_modules, $real_taxes, $order_data, $config, $global_store;

	if ($real_taxes == "Y") {
	#
	# Calculate taxes etc depending on the current store settings
	#
		global $current_area, $login, $user_account;
		$_saved_data = compact("current_area", "login", "user_account");
		$current_area = "C";
		$login = $cart["userinfo"]["login"];
		$user_account = $cart["userinfo"];
	}

	$saved_state = false;
	if (!empty($active_modules["Special_Offers"])) {
		$saved_state = true;
		unset($active_modules["Special_Offers"]);
	}

	if ($cart['use_discount_alt'] == 'Y') {
		
		if (!defined('XAOM_WO_DISCOUNT_DATA') && !empty($cart['extra']['discount_info']) && !empty($cart['extra']['discount_info']['discount']))
			define('XAOM_WO_DISCOUNT_DATA', 1);

		$global_store['discounts'] = array(array(
			"__override" => true,
			"discountid" => 999999999,
			"minprice" => 0,
			"discount" => ((!empty($cart['extra']['discount_info']) && !empty($cart['extra']['discount_info']['discount'])) ? $cart['extra']['discount_info']['discount'] : $cart['discount_alt']),
			"discount_type" => ((!empty($cart['extra']['discount_info']) && !empty($cart['extra']['discount_info']['discount_type'])) ? $cart['extra']['discount_info']['discount_type'] : "absolute")
		));
	}

	if (!empty($cart['use_coupon_discount_alt']) && $cart['use_coupon_discount_alt'] == 'Y') {
		$global_store['discount_coupons'] = array(array(
			"__override" => true,
			"coupon" => "Order#".$cart['orderid'],
			"discount" => $cart['coupon_discount_alt'],
			"coupon_type" => "absolute",
			"minimum" => 0,
			"times" => 999999999,
			"times_used" => 0,
			"expire" => time()+30879000,
			"status" => "A",
		));

	} elseif (isset($cart['extra']['discount_coupon_info']) && !empty($cart['extra']['discount_coupon_info']) && $cart['extra']['discount_coupon_info']['coupon'] == $cart['coupon']) {
		$coupon_data = $cart['extra']['discount_coupon_info'];
		$coupon_data['__override'] = true;
		$global_store['discount_coupons'] = array($coupon_data);
	}

	$cart["products"] = func_oe_update_prices($cart["products"], $cart["userinfo"]);

	$cart = func_array_merge($cart, func_calculate($cart, $cart["products"], $cart["userinfo"]["login"], $cart["userinfo"]["usertype"], $cart["paymentid"]));

	$cart["total"] = $cart["total_cost"];

	$cart["applied_taxes"] = $cart["taxes"];

	if (is_array($cart["orders"])) {
		$cart["tax"] = $cart["orders"][0]["tax_cost"];
		$cart["taxes"] = $cart["orders"][0]["taxes"];
	}

	#
	# Correct state, country and county full names (if its modified)
	#
	$uinfo = $cart["userinfo"];

	# Correct the billing address
	if ($uinfo["b_state"].$uinfo["b_country"].$uinfo["b_county"] != $order_data["userinfo"]["b_state"].$order_data["userinfo"]["b_country"].$order_data["userinfo"]["b_county"]) {
		$uinfo["b_statename"] = $uinfo["b_state_text"] = func_get_state($uinfo["b_state"], $uinfo["b_country"]);
		$uinfo["b_countryname"] = $uinfo["b_country_text"] = func_get_country($uinfo["b_country"]);
		if ($config["General"]["use_counties"] == "Y")
			$uinfo["b_countyname"] = $uinfo["b_county_text"] = func_get_county($uinfo["b_county"]);
	}

	# Correct the shipping address
	if ($uinfo["s_state"].$uinfo["s_country"].$uinfo["s_county"] != $order_data["userinfo"]["s_state"].$order_data["userinfo"]["s_country"].$order_data["userinfo"]["s_county"]) {
		$uinfo["s_statename"] = $uinfo["s_state_text"] = func_get_state($uinfo["s_state"], $uinfo["s_country"]);
		$uinfo["s_countryname"] = $uinfo["s_country_text"] = func_get_country($uinfo["s_country"]);
		if ($config["General"]["use_counties"] == "Y")
			$uinfo["s_countyname"] = $uinfo["s_county_text"] = func_get_county($uinfo["s_county"]);
	}

	$cart["userinfo"] = $uinfo;

	if ($saved_state) {
		$active_modules["Special_Offers"] = true;
	}

	if (!empty($_saved_data))
		extract($_saved_data);

	return $cart;

}

#
# This function updates the order info in the database
#
function func_oe_update_order($cart, $shipping_groups, $old_products="") {
	global $sql_tbl, $config, $active_modules, $xcart_dir, $dhl_ext_country, $all_languages, $price_details_names, $user_account, $login;

	$cart = func_oe_recalculate_totals($cart);

	$userinfo = $cart["userinfo"];
	$products = $cart["products"];
	$giftcerts = $cart["giftcerts"];

	#
	# Update stock level
	#
	if (in_array($cart["status"], array("Q","I","P","C")) && $config["General"]["unlimited_products"] != "Y") {

		$_products = $_old_products = array();		

		if (is_array($products)) {
			foreach($products as $k => $product) {
				if (!empty($active_modules["Egoods"]) && !empty($product['distribution']))
					continue;

				if ($product["deleted"])
					$product["amount"] = 0;

				if ($product["stock_update"] == "Y") {
					$amount_orig = (is_array($old_products) && $old_products[$k]["amount"]) ? $old_products[$k]["amount"] : 0;
					$amount_ret = ($active_modules["RMA"] && $product["returned_to_stock"]) ? $product["returned_to_stock"] : 0;
					$amount_change = $amount_orig - $product["amount"] - $amount_ret;
					
					if ($amount_change) {
						$product["amount"] = abs($amount_change);
						if (@$user_account["flag"] != "FS") {
							func_update_quantity(array($product), $amount_change > 0);
						}
					}
				}
			}
		}
	}

	#
	# Prepare data
	#
	$_extra = $cart["extra"];
	$_extra["tax_info"]["taxed_subtotal"] = $cart["display_subtotal"];
	$_extra["tax_info"]["taxed_discounted_subtotal"] = $cart["display_discounted_subtotal"];
	$_extra["tax_info"]["taxed_shipping"] = $cart["display_shipping_cost"];
	unset($_extra["tax_info"]["product_tax_name"]);
	$_extra['additional_fields'] = $userinfo['additional_fields'];

	$taxes_applied = serialize($cart["taxes"]);

	if (!empty($cart["use_shipping_cost_alt"]))
		$cart["shipping_cost"] = $cart["shipping_cost_alt"];

	$userinfo["b_address"] .= "\n".$userinfo["b_address_2"];
	$userinfo["s_address"] .= "\n".$userinfo["s_address_2"];

#
##
###
	$count_userinfo_additional_fields = count($userinfo["additional_fields"]);
	$count_cart_extra_additional_fields = count($cart["extra"]["additional_fields"]);
	$count_additional_fields = max($count_userinfo_additional_fields, $count_cart_extra_additional_fields);

	$log = "";
	$insert_additional_fields_log = false;

	for ($i = 0; $i < $count_additional_fields; $i++){

		if ($userinfo["additional_fields"][$i]["value"] != $cart["extra"]["additional_fields"][$i]["value"]){

			if (!empty($userinfo["additional_fields"][$i]["title"])){
				$field_title = $userinfo["additional_fields"][$i]["title"];
			} else {
				$field_title = $cart["extra"]["additional_fields"][$i]["title"];
			}

			$log .= $field_title.": ".$cart["extra"]["additional_fields"][$i]["value"]." -> ".$userinfo["additional_fields"][$i]["value"]."<br />";
			$insert_additional_fields_log = true;
		}
	}

	if ($insert_additional_fields_log){
		func_log_order($cart["orderid"], 'X', $log, $login);
	}
###
##
#
	#
	# Update order info
	#
	$memberships = func_get_memberships('C', true);
	$query_data = array(
		"total" => $cart['total'],
		"giftcert_discount" => $cart['giftcert_discount'],
		"giftcert_ids" => $cart['giftcert_ids'],
		"subtotal" => $cart['subtotal'],
		"shipping_cost" => $cart['shipping_cost'],
		"shippingid" => $cart['shippingid'],
		"tax" => $cart['tax'],
		"taxes_applied" => $taxes_applied,
		"discount" => $cart['discount'],
		"coupon" => ($cart["coupon"] ? ((preg_match("/(free_ship|percent|absolute)/S", $cart["coupon_type"])) ? ($cart["coupon_type"]."``".$cart["coupon"]) : $cart["coupon"]) : ""),
		"coupon_discount" => $cart['coupon_discount'],
		"payment_method" => $cart['payment_method'],
		"paymentid" => $cart["paymentid"],
		"payment_surcharge" => $cart["payment_surcharge"],
		"extra" => serialize($_extra),

		"membership" => !empty($memberships[$userinfo["membershipid"]]) ? $memberships[$userinfo["membershipid"]]['membership'] : '',
		"membershipid" => $userinfo["membershipid"],
		"title" => $userinfo["title"],
		"firstname" => $userinfo["firstname"],
		"lastname" => $userinfo["lastname"],
		"company" => $userinfo["company"],
		"tax_number" => $userinfo["tax_number"],
		"tax_exempt" => $userinfo["tax_exempt"],
		"b_title" => $userinfo["b_title"],
		"b_firstname" => $userinfo["b_firstname"],
		"b_lastname" => $userinfo["b_lastname"],
		"b_address" => $userinfo["b_address"],
		"b_city" => $userinfo["b_city"],
		"b_county" => @$userinfo["b_county"],
		"b_state" => $userinfo["b_state"],
		"b_country" => $userinfo["b_country"],
		"b_zipcode" => $userinfo["b_zipcode"],
		"s_title" => $userinfo["s_title"],
		"s_firstname" => $userinfo["s_firstname"],
		"s_lastname" => $userinfo["s_lastname"],
		"s_address" => $userinfo["s_address"],
		"s_city" => $userinfo["s_city"],
		"s_county" => @$userinfo["s_county"],
		"s_state" => $userinfo["s_state"],
		"s_country" => $userinfo["s_country"],
		"s_zipcode" => $userinfo["s_zipcode"],
		"phone" => $userinfo["phone"],
		"phone_ext" => $userinfo["phone_ext"],
		"fax" => $userinfo["fax"],
		"email" => $userinfo["email"],
		"url" => $userinfo["url"]

	);
	$query_data = func_array_map("addslashes", $query_data);

	if (@$user_account["flag"] != "FS") {

		$log = "";

		$log_name = array("membership", "title", "firstname", "lastname", "company", "tax_number", "tax_exempt", "b_title", "b_firstname", "b_lastname", "b_address", "b_city", "b_county", "b_state", "b_country", "b_zipcode", "s_title", "s_firstname", "s_lastname", "s_address", "s_city", "s_county", "s_state", "s_country", "s_zipcode", "phone", "phone_ext", "fax", "email", "url");
	
		$insert_log = false;
		foreach ($log_name as $field_in_db){
			$current = func_query_first_cell("SELECT $field_in_db  FROM $sql_tbl[orders] WHERE orderid='$cart[orderid]'");
			if ($current != $userinfo[$field_in_db]){
				$log .= $field_in_db.": ".$current." -> ".$userinfo[$field_in_db]."<br />";
				$insert_log = true;
			}
		}

		if ($insert_log){
			func_log_order($cart["orderid"], 'X', $log, $login);
		}

		func_array2update("orders", $query_data, "orderid='$cart[orderid]'");
	}

	if (!empty($shipping_groups)) {
		foreach ($shipping_groups as $mid => $v) {
			$shipping_groups[$mid]['products'] = array();
		}
	}
	#
	# Update order details info
	#
    
    // Check for backordered, shipped, backordered/shipped statuses
    // -1 - initial value
    // 0 - no backordered products
    // 1 - some (not all) of the products are backordered
    // 2 - all products are backordered (back == amount for all products)
    $back_products = array();
    $do_refund = array();
    
	if (is_array($products)) {
		$items = array();
		$manufacturers = array();
		foreach ($products as $pk => $product) {
			if ($product["deleted"])
				continue;

			if (!empty($active_modules['Product_Options'])) {

				$options = array();

				if (isset($product["keep_options"]) && $product["keep_options"] == "Y") {

					# Keep original options choice
					$options = $product["extra_data"]["product_options"];
					$options_alt = $product["extra_data"]["product_options_alt"];
					$product["product_options"] = isset($options_alt[$config['default_admin_language']]) ? $options_alt[$config['default_admin_language']] : "";
				} 
				else {

					# Save selected options
					if (is_array($product["product_options"])) {
						foreach ($product["product_options"] as $k=>$v) {
							$options[intval($v["classid"])] = ($v['is_modifier'] == 'T') ? $v["option_name"] : $v["optionid"];
						}
					}

					if ($all_languages && is_array($all_languages) && count($all_languages) > 1 && !empty($active_modules['Product_Options'])) {
						foreach($all_languages as $lng) {
							$options_alt[$lng["code"]] = func_serialize_options($options, false, $lng["code"]);
						}
					}

					$product["product_options"] = func_serialize_options($options);
				}

			} else {

				$product["product_options"] = "";

			}

			$product["extra_data"]["product_options"] = $options;
			$product["extra_data"]["product_options_alt"] = $options_alt;
			$product["extra_data"]["taxes"] = $product["taxes"];
			$product["extra_data"]["display"]["price"] = doubleval($product["display_price"]);
			$product["extra_data"]["display"]["discounted_price"] = doubleval($product["display_discounted_price"]);
			$product["extra_data"]["display"]["subtotal"] = doubleval($product["display_subtotal"]);


#
## https://basecamp.com/2070980/projects/1577907/messages/27385450 
###
if ($shipping_groups[$product['manufacturerid']]["cb_status"] == "P"){
	$product['amount'] = func_query_first_cell("SELECT amount FROM $sql_tbl[order_details] WHERE orderid='$cart[orderid]' AND itemid='$product[itemid]'");
}
###
##
#

			$query_data = array(
				"itemid" => $product['itemid'],
				"orderid" => $cart['orderid'],
				"productid" => $product['productid'],
				"product_options" => $product["product_options"],
				"amount" => $product['amount'],
				'back' => $product['back'],
				"price" => $product['price'],
				"provider" => $product["provider"],
				"extra_data" => serialize($product["extra_data"]),
				"productcode" => $product['productcode'],
				"product" => $product['product']
			);
			$query_data = func_array_map("addslashes", $query_data);

			if (@$user_account["flag"] != "FS") {

		                $log = "";

//		                $log_name = array("product_options", "amount", "back", "price");
		                $log_name = array("amount", "back", "price");

		                $insert_log = false;
		                foreach ($log_name as $field_in_db){
                		        $current = func_query_first_cell("SELECT $field_in_db FROM $sql_tbl[order_details] WHERE itemid='$product[itemid]'");
		                        if ($current != $product[$field_in_db] && $current != ""){
                		                $log .= "<B>".$product['productcode']."</B>: ".$field_in_db.": ".$current." -> ".$product[$field_in_db]."<br />";
                                		$insert_log = true;
		                        }
		                }

		                if ($insert_log){
                		        func_log_order($cart["orderid"], 'X', $log, $login);
		                }

				$items[] = $products[$pk]['itemid'] = func_array2insert("order_details", $query_data, true);
			}

	                if (!isset($back_products[$product['manufacturerid']])) {
        	            $back_products[$product['manufacturerid']] = -1;
	                }
            
        	        if ($product['back'] < 1) {
                		if ($back_products[$product['manufacturerid']] < 1) {
		                    $back_products[$product['manufacturerid']] = 0;
        		        } else {
                		    $back_products[$product['manufacturerid']] = 1;
	                	}
        	        } elseif ($product['back'] == $product['amount']) {
	                	if ($back_products[$product['manufacturerid']] == -1 || $back_products[$product['manufacturerid']] > 1) {
		                    $back_products[$product['manufacturerid']] = 2;
        		        } else {
                		    $back_products[$product['manufacturerid']] = 1;
		                }
        	        } else {
                		$back_products[$product['manufacturerid']] = 1;
	                }

			$mid = func_manufacturerid_for_group($product['shipping_freight'], $product['manufacturerid']);
			$manufacturers[$mid] = true;
			$shipping_groups[$mid]['products'][] = $product;

	                if (isset($product['refund']) && is_array($product['refund'])) {
        	    	    func_refund_product($cart['orderid'], $mid, $product, $userinfo);
	                    $do_refund[$mid] = true;
		            // Do not rewrite these changes with the posted values
                	    unset($_POST['ref_products'][$mid][$product['productid']]);
	                }

	                if (!isset($back_products[$product['manufacturerid']])) {    
		                $back_products[$product['manufacturerid']] = -1;
            		}
		}
		if (@$user_account["flag"] != "FS") {
			db_query("DELETE FROM $sql_tbl[order_details] WHERE orderid='$cart[orderid]' AND itemid NOT IN ('".implode("','", $items)."')");
		}
	}

	if (!empty($shipping_groups)) {
		# Reset order detailed totals
		$_extra['total'] = $_extra['product_total'] = $_extra['shipping_total'] = array('net' => 0, 'gst' => 0, 'pst' => 0, 'gross' => 0);

		$force_send_notification = false;

	        $status_of_all_groups = func_query_column('SELECT type FROM ' . $sql_tbl['order_statuses'] 
        	    . ' GROUP BY type');
	        $status_of_all_groups = array_fill_keys($status_of_all_groups, '');

		$applied_per_trans_payments = array();
        	$last_status_change = '';

		foreach ($shipping_groups as $mid => $v) {

	            $old_statuses = func_query_first('SELECT cb_status, dc_status, bd_status'
        	        . ' FROM ' . $sql_tbl['order_groups'] 
                	. ' WHERE orderid = "' . $cart['orderid'] . '" AND manufacturerid = "' . $mid . '"');

	            if (empty($v['tracking']) && $back_products[$mid] == 2) {
//        	        $v['dc_status'] = 'B';
//        	        $v['dc_status'] = 'M';
	            } elseif (!empty($v['tracking']) && $back_products[$mid] >= 1) {
        	        $v['dc_status'] = 'G';
	            } elseif (!empty($v['tracking']) && $back_products[$mid] == 0) {
        	        $v['dc_status'] = 'S';
	            }
				
        	    foreach ($status_of_all_groups as $type => $sag) {
	                if (empty($sag)) {
        	            $status_of_all_groups[$type] = $v[strtolower($type) . '_status'];
                	} elseif ($sag != $v[strtolower($type) . '_status'] 
	                    && $sag != '-'
        	        ) {
                	    $status_of_all_groups[$type] = '-';
	                }
		    }

		    if ($v['dc_status'] == 'S' && defined('TRACKING_ADDED')) {
				$force_send_notification = true;
		    }

		    $query_data = array();
		    $v['total'] = func_get_group_totals($v['products'], $v['shipping_cost']);
		    if ($apply_per_trans = !in_array($v['acc_paymentid'], $applied_per_trans_payments)) {
				$applied_per_trans_payments[] = $v['acc_paymentid'];
		    }

	            $v['apply_per_trans'] = $apply_per_trans;
        	    $shipping_groups[$mid]['apply_per_trans'] = $apply_per_trans;

			func_recalculate_accounting($v, array(), $apply_per_trans);
			foreach ($v['total'] as $totk => $totv) {
				$_extra['total'][$totk] += $totv;
				$_extra['shipping_total'][$totk] += $v['shipping_cost'][$totk];
				$_extra['product_total'][$totk] += $totv - $v['shipping_cost'][$totk];
			}
			foreach ($price_details_names as $pn) {
				$query_data["total_$pn"] = $v["total"][$pn];
				$query_data["shipping_$pn"] = $v["shipping_cost"][$pn];
			}
			$query_data['accounting'] = addslashes(serialize($v['accounting']));
			$query_data['profit_margin'] = $v['profit_margin'];
			if (!empty($v["new"]) && @$user_account["flag"] != "FS") {
				$query_data['orderid'] = $cart['orderid'];
				$query_data['manufacturerid'] = $mid;
		                $status = (empty($cart['status'])) ? 'Q' : $cart['status'];
                		$status_type = func_get_order_status_type($status);
				$query_data[strtolower($status_type) . '_status'] = $status;
                
		                // Get manufacturer data
                		$manufact_data = func_query_first('SELECT m_city, m_state, m_country FROM ' . $sql_tbl['manufacturers']
		                    . ' WHERE manufacturerid = "' . $mid . '"');
		                if (!empty($manufact_data) && is_array($manufact_data)) {
                		    $query_data['manufacturer_data'] = serialize($manufact_data);
		                }

				func_log_order_groups($query_data, $cart["orderid"], $mid, 'X', $login);

                		func_array2insert('order_groups', $query_data);

		                $last_status_change = $status;
                
			} else {
				if (@$user_account["flag"] != "FS") {
					$query_data['shippingid'] = $v['shippingid'];
					$query_data['shipping'] = $v['shipping'];
				} else {
					$query_data = array();
				}	

		                foreach ($status_of_all_groups as $type => $sag) {
        		            $status_column = strtolower($type) . '_status';
                		    if (!empty($v[$status_column])) {
        				$query_data[$status_column] = $v[$status_column];
                    		    }
	                            if (isset($old_statuses[$status_column]) && $old_statuses[$status_column] != $v[$status_column]) {
        	                	$last_status_change = $v[$status_column];
                	    	    }
	                	}
				
				// Update D2C dispatched time
				if ($old_statuses['dc_status'] != 'C' && $v['dc_status'] == 'C') {
					$query_data['dc_dispatched_time'] = time() - $config["Appearance"]["timezone_offset"];
				}
				
				$query_data['tracking'] = addslashes(serialize($v['tracking']));

				func_log_order_groups($query_data, $cart["orderid"], $mid, 'X', $login);

				func_array2update('order_groups', $query_data, "orderid='$cart[orderid]' AND manufacturerid='$mid'");
                
			}
            
            if (
                (isset($do_refund[$mid]) && $do_refund[$mid] == true)
                || (isset($v['refund']) && !empty($v['refund']))
            ) {
                $v['manufacturerid'] = $mid;
                if (!isset($refund_group_status) || empty($refund_group_status)) {
                    $refund_group_status = func_add_refund_group($v);
                } else {
                    func_add_refund_group($v);
                }
                unset($_POST['ref_groups'][$mid]); // Do not rewrite these changes with the posted values
            }
				
		}
       

#
##
###
	if (!empty($cart["additional_fee"]) && is_array($cart["additional_fee"])){
		foreach ($cart["additional_fee"] as $k => $v){
			$_extra["total"]["net"] += price_format($v["additional_fee_value"]);
			$_extra["total"]["gross"] += price_format($v["additional_fee_value"]);
		}
	}
###
##
#

 
        if (!empty($last_status_change) || $force_send_notification) {
            
            if ($force_send_notification) {
                $last_status_change = 'S';
			}
            func_send_order_status_notification($cart['orderid'], $last_status_change);
		}
		
		# Update order detailed totals
		$query_data = array('extra' => addslashes(serialize($_extra)));
		if (@$user_account["flag"] != "FS") {
			func_array2update("orders", $query_data, "orderid='$cart[orderid]'");
		}
	}
	if (@$user_account["flag"] != "FS") {
		db_query("DELETE FROM $sql_tbl[order_groups] WHERE orderid='$cart[orderid]' AND manufacturerid NOT IN ('".implode("','", array_keys($manufacturers))."')");
	}

    if (
        isset($status_of_all_groups) 
        && is_array($status_of_all_groups) 
        && (empty($refund_group_status) || !isset($refund_group_status))
    ) {
        foreach ($status_of_all_groups as $type => $gas) {
            if (!empty($status_of_all_groups[$type]) && $status_of_all_groups[$type] != '-') {
		# All groups have same status - change it for the whole order
                define('SKIP_NOTIFICATION', true);
                func_change_order_status($cart['orderid'], $gas);
            }
        }
    }

    if (isset($_POST['ref_delete'])) {
        if (is_array($_POST['ref_delete'])) {
            foreach ($_POST['ref_delete'] as $mid => $m) {
                foreach ($m as $pid => $p) {
                    if (isset($_POST['ref_products'][$mid][$pid])) {
                        unset($_POST['ref_products'][$mid][$pid]);
                    }
                    func_delete_refunded_product($pid, $mid, $cart['orderid']);
                }
                if (count($_POST['ref_products'][$mid]) == 0) {
                    unset($_POST['ref_products'][$mid]);
                    func_delete_refund_group($mid, $cart['orderid']);
                }
            }
        }
    }

    if (isset($_POST['ref_products'])) {
        func_update_refunded_products($_POST['ref_products'], $cart['orderid'], $userinfo);
    }

    if (isset($_POST['ref_groups'])) {

#
##
###
	$ref_notify_mode = true;

	if (!empty($_POST['ref_groups']) && is_array($_POST['ref_groups'])){
		foreach ($_POST['ref_groups'] as $kp => $vp){
			if ($vp["delete"] == "Y"){
				$_POST['ref_groups'][$kp]["ref_ship"] = 0;
			}
		}
	}
###
##
#

        func_update_refunded_groups($_POST['ref_groups'], $cart['orderid'], true, $ref_notify_mode);
    }

	$orderid = $cart["orderid"];

    if (!empty($shipping_groups)) {
        // After all refund data is calculated we need to update the ref_to_cust data
        foreach ($shipping_groups as $k => $v) {
            $v['manufacturerid'] = $k;
            func_recalculate_accounting($v, array(), $v['apply_per_trans'], true);
            $accounting = addslashes(serialize($v['accounting']));
            $where = 'orderid = "' . $v['orderid'] . '" AND manufacturerid = "' . $k . '"';
            func_array2update('order_groups', array(
                'accounting'    => $accounting,
                'profit_margin' => $v['profit_margin']
            ), $where);
        }
    }
}

#
# Analize the string and return the array of the refund values.
# $val  - string: [R|r]{amount}[,{fee}]
# $type - char: 'Q' - qty, 'S' - shipping cost
# return: array(is_refunded, amount, fee)
#

function func_get_refund_values($val, $type = '') {
    
    if (empty($type)) {
        return false;
    }

    if ($type == 'S') {
        $pattern = '/[R|r]([0-9]+\.*[0-9]*)(,([0-9]*))?/';
    } else {
        $pattern = '/[R|r]([0-9]+)(,([0-9]*))?/';
    }

    if (preg_match($pattern, $val, $match)) {
        
        // max fee = 100%
        $match[3] = intval($match[3]);
        if ($match[3] > 100) {
            $match[3] = 100;
        }
        
        $result = array(
            'is_refunded'   => true,
            'amount'        => ($type == 'Q') ? intval($match[1]) : floatval($match[1]),
            'fee'           => ($type == 'Q') ? $match[3] : 0,
        );
        return $result;
    }
    
    return false;
}

function func_adjust_refund_price($price, $fee) {
    if (!empty($fee)) {
        $fee = intval($fee);
        $price = abs($price * (1 - $fee / 100));
    }
    return $price;
}

function func_refund_product($orderid, $mid, &$product, $customer_info) {
    global $sql_tbl, $active_modules;

    // Check parameters
    if (
        !isset($product['refund']) || !$product['refund']['is_refunded']
    ) {
        return;
    }
    
    $product['refund']['amount'] = intval($product['refund']['amount']);
    $product['refund']['price'] = func_convert_number($product['refund']['price']);

    if (!empty($product['refund']['amount'])) {

        $where = 'manufacturerid = "' . $mid . '" AND orderid = "' . $orderid . '"'
            . ' AND productid = "' . $product['productid'] . '"';
            
        $ref_values = func_query_first('SELECT ref_qty, ref_price FROM ' . $sql_tbl['refunded_products'] . ' AS r'
            . ' WHERE ' . $where);

        if (!empty($ref_values)) {
            $product['refund']['amount'] += intval($ref_values['ref_qty']);
        }
        
        if ($product['amount'] < $product['refund']['amount']) {
            $product['refund']['amount'] = $product['amount'];
        }

        $login = func_query_first_cell('SELECT login FROM ' . $sql_tbl['orders']
            . ' WHERE orderid = ' . $orderid);
        
        x_load('taxes');
        
        $_product = $product;
        $_product['amount'] = $product['refund']['amount'];
        $_product['price'] = $product['refund']['price'];

        $product['extra_data']['taxes'] = func_get_product_taxes($_product, $login, false, $product['taxes']);
        
        $_taxes = func_tax_price($product['refund']['price'], 0, false, null, $customer_info, $product['extra_data']['taxes']);
        
        $product['extra_data']['display_subtotal'] = price_format($_taxes['taxed_price'] * $product['refund']['amount']);
        $product['extra_data']['display']['price'] = price_format($_taxes['taxed_price']);
        $product['extra_data']['product'] = $product['product'];
        $product['extra_data']['productcode'] = $product['productcode'];
        $product['extra_data']['price'] = $product['price'];

        if (!empty($ref_values)) {
            $query_data = array(
                'ref_price'     => $product['refund']['price'],
                'ref_qty'       => $product['refund']['amount'],
                'extra_data'    => mysql_real_escape_string(serialize($product['extra_data']))
            );
            func_array2update('refunded_products', $query_data, $where);
        } else {
            $query_data = array(
                'orderid'           => $orderid,
                'manufacturerid'    => $mid,
                'productid'         => $product['productid'],
                'provider'          => $product['provider'],
                'ref_price'         => $product['refund']['price'],
                'ref_qty'           => $product['refund']['amount'],
                'extra_data'        => mysql_real_escape_string(serialize($product['extra_data']))
            );
            func_array2insert('refunded_products', $query_data);
        }
    }
}

function func_add_refund_group($group) {
    global $sql_tbl, $price_details_names;

    if (isset($group['orderid']) && isset($group['manufacturerid'])) {
        
        $query_data = array(
            'orderid'           => $group['orderid'],
            'manufacturerid'    => $group['manufacturerid'],
            'ref_ship'          => $group['refund']['amount'],
        );
        
        $ref_ship = func_query_first_cell('SELECT ref_ship FROM ' . $sql_tbl['refund_groups']
            . ' WHERE orderid = "' . $group['orderid'] . '" AND manufacturerid = "' . $group['manufacturerid'] . '"');

        if ($ref_ship !== false) {
            $query_data['ref_ship'] += floatval($ref_ship);
        }

        if ($query_data['ref_ship'] > $group['shipping_cost_net_orig']) {
            $query_data['ref_ship'] = $group['shipping_cost_net_orig'];
        }

        $group['ref_ship'] = $query_data['ref_ship'];
        
        $query_data = array_merge($query_data, func_manage_refund_group($group));

        //if ($query_data['have_products']) {
            
            unset($query_data['have_products']);
            
            if (isset($query_data['refund_status'])) {
                $return = $query_data['refund_status'];
                unset($query_data['refund_status']);
            } else {
                $return = false;
            }

            $query_data['extra_data']['apply_per_trans'] = $group['apply_per_trans'];
            $query_data['extra_data'] = serialize($query_data['extra_data']);
            
            $query_data['shippingid'] = $group['shippingid'];
            $query_data['shipping'] = $group['shipping'];
            
            if ($ref_ship === false) {
                func_array2insert('refund_groups', $query_data);
            } else {
                $where = 'orderid="' . $query_data['orderid'] . '" AND manufacturerid="' . $query_data['manufacturerid'] . '"';
                func_array2update('refund_groups', $query_data, $where);
            }

            return $return;
        //}
    }

    return false;
}

function func_update_refunded_groups(&$groups, $orderid, $can_delete_group = false, $ref_notify_mode = false) {
    global $sql_tbl, $login;

    $operator_login = $login;

    if (!empty($groups) && is_array($groups)) {
        
        foreach ($groups as $mid => $group) {
            
            if ($group['ref_ship'] < 0) {
                $group['ref_ship'] = 0;
            }

            $where = 'orderid = "' . $orderid . '" AND manufacturerid = "' . $mid . '"';
           
            $query_data = func_query_first('SELECT * FROM ' . $sql_tbl['refund_groups'] . ' WHERE ' . $where);

            $max_ship = func_query_first_cell('SELECT shipping_net FROM ' . $sql_tbl['order_groups'] 
                . ' WHERE ' . $where);
                   
            // order group exists
            if ($query_data && $max_ship !== false) {
            
                if ($group['ref_ship'] > $max_ship) {
                    $group['ref_ship'] = $max_ship;
                }

                $query_data['ref_ship'] = $group['ref_ship'];
                $query_data['extra_data'] = unserialize($query_data['extra_data']);
                $query_data['taxes'] = $query_data['extra_data']['taxes'];

                $query_data['tracking'] = unserialize($query_data['tracking']);
                $query_data['accounting'] = unserialize($query_data['accounting']);
                $query_data['shipping_cost_net_orig'] = $max_ship;

                // Recalculate totals
                $query_data = array_merge($query_data, func_manage_refund_group($query_data, $ref_notify_mode));

                if ($can_delete_group && !$query_data['have_products'] && $group['ref_ship'] == 0) {
                    func_delete_refund_group($mid, $orderid, true);
                } else {
                    unset($query_data['have_products']);
                    unset($query_data['shipping_cost_net_orig']);
                    if (isset($query_data['refund_status'])) {
                        unset($query_data['refund_status']);
                    }
                   
                   $lng_adj = func_get_langvar_by_name('lbl_adjustment_to', null, false, true);
                    $query_data['shipping'] = str_replace($lng_adj . ' ', '', $group['shipping']);
                    $query_data['shipping'] = mysql_real_escape_string($query_data['shipping']);

                    $query_data['extra_data'] = serialize($query_data['extra_data']);
                    unset($query_data['taxes']);

		    func_log_order_refunded_groups($query_data, $orderid, $mid, 'X', $operator_login);

                    func_array2update('refund_groups', $query_data, $where);

                }
            }
        }
    }
}

function func_manage_refund_group(&$group, $ref_notify_mode = false) {
    global $sql_tbl, $price_details_names, $customer_info;
    
    $query_data = array();
    
    $fields = array(
        'r.orderid', 'r.manufacturerid', 'r.productid', 'r.ref_price', 'r.ref_qty', 'r.provider', 'r.extra_data',
        'r.ref_price AS price', 'r.ref_qty AS amount',       // for funcs()
        'o.amount AS orig_amount, o.price AS orig_price'
    );
    
    $products = func_query('SELECT ' . implode(', ', $fields) . ' FROM ' . $sql_tbl['refunded_products'] . ' AS r'
        . ' LEFT JOIN ' . $sql_tbl['order_details'] . ' AS o ON r.productid = o.productid AND r.orderid = o.orderid'
        . ' WHERE r.orderid = "' . $group['orderid'] . '" AND r.manufacturerid = "' . $group['manufacturerid'] . '"');
    if (!empty($products)) {
        
        if (is_array($products)) {
            
            foreach ($products as $k => $product) {
                
                $products[$k]['extra_data'] = unserialize($product['extra_data']);
                $products[$k]['display_subtotal'] = $products[$k]['extra_data']['display_subtotal'];
            }
        }
        
        $taxes = func_calculate_taxes($products, $customer_info, $group['ref_ship']);
        $query_data['extra_data']['taxes'] = $taxes['taxes'];

        $group['shipping_cost'] = func_tax_price_details($group['ref_ship'], $group['taxes']);
        $group['total'] = func_get_group_totals($products, $group['shipping_cost']);

        $refund_status = func_define_refund_status($group);

//-----------------------------
//func_print_r($refund_status, $group);
//die();
                
        if (!empty($refund_status)) {
            $query_data['refund_status'] = $refund_status;
            if ($refund_status == 'F') {
		if ($ref_notify_mode){
			if ($_POST["mode"] == "ref_notify"){
		                func_change_order_group_status($group['orderid'], $group['manufacturerid'], 'R');
			}
		} else {
			if ($_POST["mode"] != "ref_notify"){
		                func_change_order_group_status($group['orderid'], $group['manufacturerid'], 'V');
			}

		}
            } elseif ($refund_status == 'P') {
		if ($ref_notify_mode){
			if ($_POST["mode"] == "ref_notify"){
		                func_change_order_group_status($group['orderid'], $group['manufacturerid'], 'H');
			}
		} else {
			if ($_POST["mode"] != "ref_notify"){
		                func_change_order_group_status($group['orderid'], $group['manufacturerid'], '3');
			}
		}
            }
        }
        
        func_recalculate_accounting($group, array(), $group['apply_per_trans']);

        foreach ($price_details_names as $pn) {
            $query_data["total_$pn"] = $group['total'][$pn];
            $query_data["shipping_$pn"] = $group['shipping_cost'][$pn];
        }

        $query_data['accounting'] = addslashes(serialize($group['accounting']));

        $query_data['tracking'] = addslashes(serialize($group['tracking']));

        $query_data['have_products'] = true; // flag
    } else {
        $query_data['shipping_net'] = $group['shipping_net'] = $group['ref_ship'];
        $query_data['shipping_gross'] = $group['shipping_gross'] = $group['ref_ship'];
    
        foreach ($price_details_names as $pn) {
            $query_data["total_$pn"] = $group["shipping_$pn"];
        }
        
        $group['total']['gross'] = $group['shipping_gross'];
        $refund_status = func_define_refund_status($group);
                
        if (!empty($refund_status)) {
            $query_data['refund_status'] = $refund_status;
            if ($refund_status == 'F') {
		if ($ref_notify_mode){
			if ($_POST["mode"] == "ref_notify"){
		                func_change_order_group_status($group['orderid'], $group['manufacturerid'], 'R');
			}
		} else {
			if ($_POST["mode"] != "ref_notify"){
	                	func_change_order_group_status($group['orderid'], $group['manufacturerid'], 'V');
			}
		}
            } elseif ($refund_status == 'P') {
		if ($ref_notify_mode){
			if ($_POST["mode"] == "ref_notify"){
		                func_change_order_group_status($group['orderid'], $group['manufacturerid'], 'H');
			}
		} else {
			if ($_POST["mode"] != "ref_notify"){
	                	func_change_order_group_status($group['orderid'], $group['manufacturerid'], '3');
			}
		}
            }
        }
    }
        
    return $query_data;
}

function func_update_refunded_products($products, $orderid) {
    global $sql_tbl, $active_modules, $login;

    $operator_login = $login;

    if (is_array($products) && !empty($products)) {
        
        foreach ($products as $mid => $mproducts) {
            if (is_array($mproducts)) {
                foreach ($mproducts as $pid => $product) {
                    
                    if (intval($product['ref_qty']) == 0) {
                        func_delete_refunded_product($pid, $mid, $orderid);
                        continue;
                    }
                    
                    $where = 'manufacturerid = "' . $mid . '" AND orderid = "' . $orderid . '"'
                        . ' AND productid = "' . $pid . '"';

                    $query_data = func_query_first('SELECT * FROM ' . $sql_tbl['refunded_products']
                        . ' WHERE ' . $where);

                    $max_values = func_query_first('SELECT price, amount FROM ' . $sql_tbl['order_details']
                        . ' WHERE orderid = "' . $orderid . '" AND productid = "' . $pid . '"');

                    if (isset($product['ref_qty'])) {
                        $product['ref_qty'] = intval($product['ref_qty']);
                        if (isset($max_values['amount']) && $product['ref_qty'] > $max_values['amount']) {
                            $product['ref_qty'] = $max_values['amount'];
                        }
                    } else {
                        $product['ref_qty'] = func_query_first_cell('SELECT ref_qty FROM ' . $sql_tbl['refunded_products']
                            . ' WHERE ' . $where);
                    }

                    $product['ref_price'] = func_convert_number($product['ref_price']);
                    
                    if (isset($max_values['price']) && $product['ref_price'] > $max_values['price']) {
                        $product['ref_price'] = $max_values['price'];
                    }

                    if ($query_data && !empty($product['ref_qty'])) {

                        $c_login = func_query_first_cell('SELECT login FROM ' . $sql_tbl['orders']
                            . ' WHERE orderid = ' . $orderid);
                        
                        x_load('taxes');

                        $query_data['extra_data'] = unserialize($query_data['extra_data']);
                        
                        $_product = $query_data;
                        $_product['amount'] = $product['ref_qty'];
                        $_product['price'] = $product['ref_price'];

                        $query_data['extra_data']['taxes'] = func_get_product_taxes($_product, $c_login, false, $query_data['extra_data']['taxes']);
                                
                        $_taxes = func_tax_price($product['ref_price'], 0, false, null, $customer_info, $query_data['extra_data']['taxes']);
                        
                        if (empty($query_data['extra_data']['taxes'])) {
                            $query_data['extra_data']['display']['price'] = $product['ref_price'];
                        }
                        
                        $query_data['extra_data']['display_subtotal'] = price_format($_taxes['taxed_price'] * $product['ref_qty']);

                        $query_data['ref_price'] = $product['ref_price'];
                        $query_data['ref_qty'] = $product['ref_qty'];
                        $query_data['extra_data'] = mysql_real_escape_string(serialize($query_data['extra_data']));
      
	                $log = "";
        	        $log_name = array("ref_price", "ref_qty");

                	$insert_log = false;
	                foreach ($log_name as $field_in_db){
        	                $current = func_query_first_cell("SELECT $field_in_db FROM $sql_tbl[refunded_products] WHERE $where");
                	        if ($current != $query_data[$field_in_db]){
					$product_code = func_query_first_cell("SELECT productcode FROM $sql_tbl[products] WHERE productid='$pid'");
                        	        $log .= "<B>".$product_code.":</B> ".$field_in_db.": ".$current." -> ".$query_data[$field_in_db]."<br />";
                                	$insert_log = true;
	                        }
        	        }

	                if ($insert_log){
        	                func_log_order($orderid, 'X', $log, $operator_login);
                	}
                 
                        func_array2update('refunded_products', $query_data, $where);
                    }
                }
            }
        }
    }
}

function func_delete_refunded_product($pid, $mid, $orderid) {
    global $sql_tbl;

    db_query('DELETE FROM ' . $sql_tbl['refunded_products'] 
        . ' WHERE productid = "' . $pid . '" AND manufacturerid = "' . $mid . '" AND orderid = "' . $orderid. '"');
}

function func_delete_refund_group($mid, $orderid, $full = false) {
    global $sql_tbl;

    db_query('DELETE FROM ' . $sql_tbl['refunded_products'] 
        . ' WHERE orderid = "' . $orderid . '" AND manufacturerid = "' . $mid . '"');

    if ($full) {
    db_query('DELETE FROM ' . $sql_tbl['refund_groups']
        . ' WHERE orderid = "' . $orderid . '" AND manufacturerid = "' . $mid . '"');

        $current_cb_status = func_query_first_cell("SELECT cb_status FROM $sql_tbl[order_groups] WHERE orderid = '$orderid' AND manufacturerid='$mid'");
        $current_cb_status_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='$current_cb_status'");
        $code = func_query_first_cell("SELECT code FROM $sql_tbl[manufacturers] WHERE manufacturerid='$mid'");

        if ($current_cb_status != "P"){
                $new_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='P'");
                $log = "<B>".$code.":</B> cb_status: ". $current_cb_status_value . " -> ". $new_value;
		global $login;
                func_log_order($orderid, 'X', $log, $login);
        }

        db_query("UPDATE $sql_tbl[order_groups] SET cb_status='P' WHERE orderid='$orderid' AND manufacturerid='$mid'");
    } else {
        $data = func_query_first("SELECT shipping, ref_ship FROM $sql_tbl[refund_groups] WHERE orderid='$orderid' AND manufacturerid='$mid'");
        $groups = array($mid => $data);
        func_update_refunded_groups($groups, $orderid);
    }
}

function func_calculate_gross_ref_to_cust($orderid, $mid, $paymentid) {
    global $sql_tbl;

    if (is_numeric($paymentid) && !empty($paymentid)) {
        $ref_values = func_query_first('SELECT percent_ref, per_ref FROM ' . $sql_tbl['payment_methods']
            . ' WHERE paymentid = "' . intval($paymentid) . '"'); 

        if ($ref_values) {
            
            $refund_sum_products = func_query_first_cell('SELECT total_gross FROM ' . $sql_tbl['refund_groups']
                . ' WHERE orderid = "' . $orderid . '" AND manufacturerid = "' . $mid . '"');

            $refund_sum_products = floatval($refund_sum_products);

            $gross_to_cust = (1 - $ref_values['percent_ref'] / 100) * $refund_sum_products + $ref_values['per_ref'];

            return $gross_to_cust;
        }
    }

    return 0;
}

function func_define_refund_status(&$group) {
    global $sql_tbl;
    
    // Gather refund info
    // F - fully refunded
    // P - partitially refunded
    // '' - not refunded

    $refund_status = '';
    
    if (isset($group['manufacturerid'])) {
        if (!empty($group['total']['gross'])) {
            $order_group_gross = func_query_first_cell('SELECT total_gross FROM ' . $sql_tbl['order_groups'] 
                . ' WHERE orderid = "' . $group['orderid'] . '" AND manufacturerid = "' . $group['manufacturerid'] . '"');

            $order_group_gross = round(floatval($order_group_gross), 2);

            if (round($group['total']['gross'], 2) >= $order_group_gross) {
                $refund_status = 'F';
            } else {
                $refund_status = 'P';
            }
        }
    }

    return $refund_status;
}
?>
