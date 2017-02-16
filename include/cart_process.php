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
# $Id: cart_process.php,v 1.23.2.5 2006/10/11 08:30:38 max Exp $
#
#
# This script contains the common functions for cart operating
#

if ( !defined('XCART_START') ) { header("Location: ../"); die("Access denied"); }

x_load('cart','product','order');
x_session_register('ajax_error');
x_session_register('ajax_mode');
x_session_register('last_categoryid');

#
# Get the payment methods list
#
function check_payment_methods($membershipid) {
	global $sql_tbl, $config, $cart, $shop_language;

	x_load('tests');

	$condition = "";
	if	(@empty($cart["products"]) && @!empty($cart["giftcerts"]))
		$condition .= " AND pm.paymentid != 14 ";

	$payment_methods = func_query("SELECT pm.*,cc.module_name,cc.processor,cc.type, pm.payment_method as payment_method_orig, IFNULL(l1.value, pm.payment_method) as payment_method, IFNULL(l2.value, pm.payment_details) as payment_details FROM $sql_tbl[payment_methods] as pm LEFT JOIN $sql_tbl[ccprocessors] as cc ON pm.paymentid = cc.paymentid LEFT JOIN $sql_tbl[pmethod_memberships] ON $sql_tbl[pmethod_memberships].paymentid = pm.paymentid LEFT JOIN $sql_tbl[languages_alt] as l1 ON l1.name = CONCAT('payment_method_', pm.paymentid) AND l1.code = '$shop_language' LEFT JOIN $sql_tbl[languages_alt] as l2 ON l2.name = CONCAT('payment_details_', pm.paymentid) AND l2.code = '$shop_language' WHERE pm.active='Y' AND ($sql_tbl[pmethod_memberships].membershipid ='$membershipid' OR $sql_tbl[pmethod_memberships].membershipid IS NULL) $condition ORDER BY pm.is_cod, pm.orderby");

	$payment_methods = test_payment_methods($payment_methods,true);

	return $payment_methods;
}

#
# This function perform actions to normalize cart content
#
function func_cart_normalize(&$cart) {
	global $added_catalogs;

	if (empty($cart['products']))
		return false;

	$hash = array();
	$cart_changed = false;

	$manufacturers_in_cart = array();

	foreach ($cart['products'] as $k => $p) {
		if (!in_array($p['manufacturerid'], $manufacturers_in_cart)) {
			$manufacturers_in_cart[] = $p['manufacturerid'];
		}
		if ($p['hidden'] || !empty($p['pconf_data']))
			continue;

		$po = (!empty($p['options']) && is_array($p['options']) ? serialize($p['options']) : "");
		$key = $p['productid'].$po.$p['free_price'];

		if (isset($p['free_amount'])) {
			# for X-SpecialOffers
			$key .= '-fa'.doubleval($p['free_amount']);
		}

		if (isset($hash[$key])) {
			# Unite several product items
			$cart_changed = true;

			if (empty($p['distribution'])) {
				$cart['products'][$hash[$key]]['amount'] += $p['amount'];
			}
			else {
				$cart['products'][$hash[$key]]['amount'] = 1;
			}

			unset($cart['products'][$k]);
		}
		else {
			$hash[$key] = $k;
		}
	}

	# Enable catalog checkboxes for manufacturers with no products in cart
	if (is_array($added_catalogs)) {
		foreach ($added_catalogs as $mid => $ac) {
			if (!in_array($mid, $manufacturers_in_cart)) {
				unset($added_catalogs[$mid]);
			}
		}
	}

	return $cart_changed;
}

#
# This function is used to add product to the cart
#
function func_add_to_cart(&$cart, $product_data) {
	global $user_account, $current_storefront;
	global $active_modules, $config, $top_message, $xcart_dir, $HTTP_REFERER, $xcart_catalogs, $sql_tbl;
	global $from, $ajax_error, $ajax_mode, $ajax_redirect, $last_categoryid;

	$return = array();

    if (!empty($active_modules['Multiple_Storefronts'])) {
        // for X-Payments and other redirects on checkout
        if (!isset($cart['source_sf'])) {
            $cart['source_sf'] = $current_storefront;
        }
    }

	#
	# Extracts to: $productid, $amount, $product_options, $price
	#
	extract($product_data);
	
	$added_product = func_select_product($productid, (!empty($user_account['membershipid']) ? $user_account['membershipid'] : 0), false, true);

	if ($ajax_mode == 'Y' && !$added_product) {
		$ajax_error = 'Y';
		return false;
	}


#
##
###
	global $variant_id_for_point2;
	$variant_id_for_point2 = Get_AB_Variant(2);
	x_session_save("variant_id_for_point2");
###
##
#

        if ($config["Appearance"]["Enable_surf_stats"] == "Y"){
            Xcart\Surfing\SurfPath::logSurfPath(['resource_type' => Xcart\Surfing\SurfPath::GOAL_TYPE_ADD_TO_CART, 'resource_id' => $added_product["productid"]]);
        }


	if ($added_product["forsale"] == "B") {
		#
		# Bundled product cannot be added to the cart directly (related to X-Configurater)
		#
		$top_message["content"] = func_get_langvar_by_name("txt_pconf_product_is_bundled");
		$top_message["type"] = "W";
		$return["redirect_to"] = $HTTP_REFERER;
		if ($ajax_mode == 'Y') {
			$ajax_error = 'Y';
		}
		return $return;
	}

	if (!empty($active_modules['Egoods']) && !empty($added_product['distribution']))
		$amount = 1;
	else
		$amount = abs(intval($amount));

	if (!empty($active_modules['Subscriptions'])) {
		$subscribed_product = func_query_first_cell("SELECT pay_period_type FROM $sql_tbl[subscriptions] WHERE productid='$productid' LIMIT 1");
		if (!empty($subscribed_product))
			$amount = 1;
	}

	if ($active_modules["Product_Options"]) {
		#
		# Prepare the product options for added products
		#
		if (!empty($product_options)) {
			# Check the received options
			if (!func_check_product_options($productid, $product_options)) {
				if ($ajax_mode == 'Y') {
					$ajax_error = 'Y';
				}
				if (!empty($active_modules["Product_Configurator"]) && $added_product["product_type"] == "C") {
					$return["redirect_to"] = "pconf.php?productid=$productid&err=options";
				}
				else {
					$return["redirect_to"] = "product.php?productid=$productid&err=options";
				}

				return $return;
			}
		}
		else {
			# Get default options
			$product_options = func_get_default_options($productid, $amount, @$user_account['membershipid']);
			if ($product_options === false) {
				if ($ajax_mode == 'Y') {
					$ajax_error = 'Y';
				}
				$return["redirect_to"] = "error_message.php?access_denied&id=30";
				return $return;
			}
			elseif ($product_options === true) {
				$product_options = "";
				unset($product_options);
			}
		}

		# Get the variantid of options
		$variantid = func_get_variantid($product_options, $productid);

		if (!empty($variantid)) {

			# Get the variant amount
			$added_product["avail"] = func_get_options_amount($product_options, $productid);

			if (!empty($cart['products']))  {
				foreach ($cart['products'] as $k => $v) {
					if ($v['productid'] == $productid && $variantid == $v['variantid'])
						$added_product["avail"] -= $v['amount'];
				}
			}
		}
	}

	if ($config["General"]["unlimited_products"] == "N" && $added_product["product_type"] != "C") {
		#
		# Add to cart amount of items that is not much than in stock
		#
		if ($amount > $added_product["avail"])
			$amount = $added_product["avail"];
	}

	if ($from == 'partner' && empty($amount)) {
		$return["redirect_to"] = ($xcart_catalogs['customer']."/product.php?productid=".$productid);
		return $return;
	}

	if ($productid && $amount) {

		if ($amount < $added_product["min_amount"]) {
			if ($ajax_mode == 'Y') {
				$ajax_error = 'Y';
			}
			$return["redirect_to"] =  "error_message.php?access_denied&id=31";
			return $return;
		}

		$found = false;
		if (!empty($cart) && @$cart["products"] && $added_product["product_type"]!="C") {
			foreach ($cart["products"] as $k=>$v) {
				if (($v["productid"] == $productid) && (!$found) && ($v["options"] == $product_options) && empty($v["hidden"])) {
					if (doubleval($v["free_price"]) != $price)
						continue;

					$found = true;
					if (($cart["products"][$k]["amount"] >=1) && (!empty($added_product["distribution"]) || !empty($subscribed_product)))	{
						$cart["products"][$k]["amount"]=1;
						$amount=0;
					}

					$cart["products"][$k]["amount"] += $amount;
				}
			}
		}

		if (!$found) {
			#
			# Add product to the cart
			#
			if (!empty($price)) {
				# price value is defined by customer if admin set it to '0.00'
				$free_price = abs(doubleval($price));
			}

			$cartid = func_generate_cartid($cart["products"]);
			if (empty($cart["products"]))
				$add_to_cart_time = time();

			$cart["products"][] = array(
				"cartid" => $cartid,
				"productid" => $productid,
				"cost_to_us" => $added_product["cost_to_us"],
				"retail_trust_enabled" => $added_product["retail_trust_enabled"],
				"amount" => $amount,
				"options" => $product_options,
				"free_price" => @price_format(@$free_price),
				"distribution" => $added_product["distribution"],
				"variantid" => $variantid,
				'catalog_price' => $catalog_price ? $catalog_price : null);

			if (!empty($active_modules["Product_Configurator"])) {
				$mode = "add";
				include $xcart_dir."/modules/Product_Configurator/pconf_customer_cart.php";
			}
		}

		$catid = func_query_first_cell('SELECT categoryid FROM ' . $sql_tbl['products_categories'] . ' WHERE productid=' . $productid . ' AND main="Y"');
		if (is_numeric($catid)) {
			$last_categoryid = $catid;
		}

	}

	return $return;
}

#
# This function is used to delete product from the cart
#
function func_delete_from_cart(&$cart, $productindex) {
	global $active_modules, $config, $xcart_dir, $sql_tbl, $added_catalogs;

	$mode = 'delete';

	if (!empty($active_modules["Product_Configurator"]))
		include $xcart_dir."/modules/Product_Configurator/pconf_customer_cart.php";

	$productid = 0;

	foreach ($cart["products"] as $k=>$v) {
		if ($v["cartid"] == $productindex) {
			$productid = $v["productid"];
			if (!empty($active_modules["Advanced_Statistics"]))
				@include $xcart_dir."/modules/Advanced_Statistics/prod_del.php";

			# Check if the current product is a catalog
			$productcode = $cart['products'][$k]['productcode'];

			array_splice($cart["products"],$k,1);
			break;
		}
	}

	if (empty($cart['products'])) {
		$added_catalogs = array();
	}

	return $productid;
}

#
# This function updates the quantity of products in the cart
#
function func_update_quantity_in_cart(&$cart, $productindexes) {
	global $active_modules, $config, $xcart_dir, $sql_tbl;
	
	if (empty($cart["products"]))
		return;
	
	$action = "update";
	foreach ($productindexes as $_cartid=>$new_quantity) {
		foreach ($cart["products"] as $k=>$v) {
			if ($v["cartid"] == $_cartid) {
				$productindexes_tmp[$k] = $new_quantity;
				break;
			}
		}
	}

	$productindexes = $productindexes_tmp;
	unset($productindexes_tmp);

	if (!empty($active_modules["Product_Configurator"]))
		include $xcart_dir."/modules/Product_Configurator/pconf_customer_cart.php";

	$min_amount_warns = array();
	$mult_amount_warns = array();
	foreach ($cart["products"] as $k=>$v) {
		$tot = 0;
		$tot_amount = 0;
		$product_info = func_query_first("SELECT min_amount, mult_order_quantity FROM $sql_tbl[products] WHERE productid = '$v[productid]'");
		$min_amount = $product_info['min_amount'];
		foreach ($productindexes as $productindex=>$new_quantity) {
			if (!is_numeric($new_quantity))
				continue;

			if ($cart["products"][$productindex]["productid"] == $v["productid"] && $cart["products"][$productindex]["variantid"] == $v["variantid"]) {
				if ($product_info['mult_order_quantity'] == 'Y' && $new_quantity % $min_amount != 0) {
					$mult_amount_warns[$v['cartid']] = array('old' => $new_quantity);
					$new_quantity = ceil($new_quantity / $min_amount) * $min_amount;
					$productindexes[$productindex] = $new_quantity;
                    
					$mult_amount_warns[$v['cartid']]['new'] = $new_quantity;
				}
            
				if ($new_quantity < $min_amount && $new_quantity > 0) {
					$mult_amount_warns[$v['cartid']] = array('old' => $new_quantity);
					$productindexes[$productindex] = $new_quantity = $min_amount;
					$min_amount_warns[$v['cartid']] = $min_amount;
                    
					$mult_amount_warns[$v['cartid']]['new'] = $new_quantity;
				}
				$tot += floor($new_quantity);
			}
		}

		foreach ($cart["products"] as $k2=>$v2) {
			if ($v["productid"] == $v2["productid"] && $v2["variantid"] == $v["variantid"])
				$tot_amount += $v2['amount'];
		}

		$updates_array[$k] = array("quantity"=>$v["amount"], "total_quantity"=>$tot, "total_amount" => $tot_amount);
	}

	#
	# Create hash array with variants
	#
	$hash = array();
	if (!empty($active_modules["Product_Options"])) {
		foreach ($productindexes as $productindex => $new_quantity) {
			if (!empty($cart["products"][$productindex]["options"])) {
				$variantid = $cart["products"][$productindex]["variantid"];
				if ($variantid) {
					if (!isset($hash[$variantid])) {
						$hash[$variantid]["avail"] = func_get_options_amount($cart["products"][$productindex]["options"], $cart["products"][$productindex]["productid"]);
					}

					$hash[$variantid]["old"] += $cart["products"][$productindex]["amount"];
					$hash[$variantid]["new"] += $new_quantity;
					$hash[$variantid]["ids"][] = $cart["products"][$productindex]["productid"];
					$cart["products"][$productindex]["variantid"] = $variantid;
				}
			}
		}
	}

	#
	# Update the quantities
	#
	foreach ($productindexes as $productindex => $new_quantity) {

		if (!is_numeric($new_quantity) || empty($cart["products"][$productindex]))
			continue;

		$new_quantity = floor($new_quantity);
		$productid = $cart["products"][$productindex]["productid"];
		$total_quantity = $updates_array[$productindex]["total_quantity"];
		$total_amount = $updates_array[$productindex]["total_amount"];
		if ($config["General"]["unlimited_products"] == "N" && $cart["products"][$productindex]["product_type"] != "C") {
			if (!empty($cart["products"][$productindex]["variantid"])) {
				$amount_max = $hash[$cart["products"][$productindex]["variantid"]]["avail"];
				$total_quantity = $hash[$cart["products"][$productindex]["variantid"]]["old"];
			}
			else {
				$amount_max = func_query_first_cell("SELECT avail FROM $sql_tbl[products] WHERE productid='$productid'");
			}
		}
		else {
			$amount_max = $total_quantity + 1;
		}

		$amount_min = func_query_first_cell("SELECT min_amount FROM $sql_tbl[products] WHERE productid='$productid'");

		#
		# Do not change
		#
		if ($config["General"]["unlimited_products"] == "Y") {
			$cart["products"][$productindex]["amount"] = $new_quantity;
			continue;
		}

		if (($new_quantity >= $amount_min ) && ($cart["products"][$productindex]["distribution"])) {
			$cart["products"][$productindex]["amount"] = 1;
		}
		elseif (($new_quantity >= $amount_min) && ($new_quantity <= ($amount_max - $total_amount + $cart["products"][$productindex]["amount"]))) {
			$cart["products"][$productindex]["amount"] = $new_quantity;
			if(!empty($cart["products"][$productindex]["variantid"])) {
				$hash[$cart["products"][$productindex]["variantid"]]["old"] += ($new_quantity - $cart["products"][$productindex]["amount"]);
			}
			else {
				$updates_array[$productindex]["total_amount"] += ($new_quantity-$cart["products"][$productindex]["amount"]);
			}
		}
		elseif ($new_quantity >= $amount_min) {
			$old_amount = $cart["products"][$productindex]["amount"];
			$cart["products"][$productindex]["amount"] = ($amount_max - $total_amount + $cart["products"][$productindex]["amount"]);
			if (!empty($cart["products"][$productindex]["variantid"])) {
				$hash[$cart["products"][$productindex]["variantid"]]["old"] += ($amount_max - $total_amount + $cart["products"][$productindex]["amount"] - $old_amount);
			}
			else {
				$updates_array[$productindex]["total_amount"] += ($amount_max - $total_amount + $cart["products"][$productindex]["amount"] - $old_amount);
			}
		}
		else {
			$cart["products"][$productindex]["amount"] = 0;
		}

		if ($cart["products"][$productindex]["amount"] < 0)
			$cart["products"][$productindex]["amount"] = 0;
	}

	if (!empty($active_modules["Product_Configurator"])) {
		$pconf_update = "post_update";
		include $xcart_dir."/modules/Product_Configurator/pconf_customer_cart.php";
	}

	$products = array();
	foreach ($cart["products"] as $product) {
		if ($product["amount"] > 0)
			$products[] = $product;
	}
	$cart["products"] = $products;

	return array($min_amount_warns, $mult_amount_warns);
}

#
# This function counts the total quantity of products in the cart
#
function func_cart_count_items(&$cart) {
	if (empty($cart) || empty($cart['products'])) return 0;

	$count = 0;
	foreach ($cart['products'] as $product) {
		$count += $product['amount'];
	}

	return $count;
}

#
# This function saves the cart details to the xcart_customers table
#
function func_save_customer_cart($login, $cart) {
	global $sql_tbl;

	if (!empty($login)){

#
## https://basecamp.com/2070980/projects/1577907/messages/46647624
### Start: Cart number feature

		if (!empty($cart["cart_number"])){
			$update_field = ", cart_number='".$cart["cart_number"]."' ";
		} else {
			$update_field = "";
		}
###
## End: Cart number feature
#
		db_query("UPDATE $sql_tbl[customers] SET cart='".addslashes(serialize($cart))."' $update_field WHERE login='$login'");
	}
}

?>
