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
# $Id: ajax_add_to_cart.php,v 1.0 2010/11/2 15:48:20 kate Exp $
#

require "./auth.php";

x_load('cart');
x_session_register('cart');
x_session_register('catalog_checkboxes', array());
x_session_register('added_catalogs');
x_session_register('ajax_error');
x_session_register('ajax_redirect');
x_session_register('ajax_mode', 'Y');
require $xcart_dir."/include/cart_process.php";

$result = array();

if (!empty($action)) {
	$url = parse_url($action);
	$base = basename($url['path']);
	if ($base == 'cart.php') {
		$mode = 'add';
	}

	if ($mode == 'add' && !empty($productid)) {

		if (!empty($cart['products'])){
			$q_products_in_cart = count($cart['products']);
		} else {
			$q_products_in_cart = 0;
		}
		
		$catalog_price = func_query_first_cell('SELECT m.catalog_price FROM '.$sql_tbl['manufacturers'].' as m LEFT JOIN ' . $sql_tbl['products'] . ' as p ON m.catalog_sku=p.productcode WHERE p.productid=' . $productid);
		#
		# Add product to the cart
		#
		$add_product = array();
		$add_product['productid'] = abs(intval($productid));
		$add_product['amount'] = abs(intval($amount));
		$add_product['product_options'] = $product_options;
		$add_product['price'] = abs(doubleval($price));
		$add_product['catalog_price'] = ($catalog_price) ? price_format($catalog_price) : null;
	
		#
		# Add to cart
		#
		$result = func_add_to_cart($cart, $add_product);
	
		func_add_catalog_checkbox_to_cart($add_product['productid']);
	
		if (!empty($result['redirect_to'])) {
			$return['redirect'] = $result['redirect_to'];
		}
	
		$intershipper_recalc = 'Y';
	
		# Recalculate cart totals after new item added
		$products = func_products_in_cart($cart, (!empty($user_account['membershipid']) ? $user_account['membershipid'] : ''));
		$cart = func_array_merge($cart, func_calculate($cart, $products, $login, $current_area, 0));
	
		#
		# Redirect
		#
		if ($config['General']['redirect_to_cart'] == 'Y') {
			if (!empty($active_modules['SnS_connector'])) {
				$is_sns_action['AddToCart'][] = $productid;
			}
	
		} else {
			$products = func_products_in_cart($cart, (!empty($user_account['membershipid']) ? $user_account['membershipid'] : ''));
			$cart = func_array_merge($cart, func_calculate($cart, $products, $login, $current_area, 0));
	
			if (!empty($active_modules['SnS_connector'])) {
				func_generate_sns_action('AddToCart', $productid);
			}
	
			func_save_customer_cart($login, $cart);
			if (!empty($HTTP_REFERER)) {
				$tmp = parse_url($HTTP_REFERER);
				if ($config['General']['return_to_dynamic_part'] == 'Y' && $is_hc == 'Y' && (strpos($tmp['path'], '.html') !== false || substr($tmp['path'], -1) == '/')) {
					if (substr($tmp['path'], -1) == '/') {
						$return['redirect'] = 'home.php';
					} elseif (strpos($HTTP_REFERER, '-c-') !== false) {
						$return['redirect'] = "home.php?cat=$cat&page=$page";
					} else {
						$return['redirect'] = 'product.php?productid=' . $add_product['productid'];
					}
				} else {
					$return['redirect'] = $HTTP_REFERER;
				}
			} else {
				$return['redirect'] = "home.php?cat=$cat&page=$page";
			}
		}

		#
		# Update minicart
		#
		include $xcart_dir . '/minicart.php';
		
		$smarty->assign('cart', $cart);
		$display = 'customer/menu_cart.tpl';
		$return['display'] = func_display($display, $smarty, false);
		if ($ajax_error == 'Y') {
			$return['error'] = 'Y';
		}
		if ($ajax_redirect) {
			$return['redirect'] = $ajax_redirect;
		}
		x_session_unregister('ajax_error');
		x_session_unregister('ajax_redirect');
		x_session_unregister('ajax_mode');

		if (!isset($return['error'])) {
			$return['error'] = 'N';
		}
		if (!isset($return['redirect'])) {
			$return['redirect'] = '';
		}
		$ajax_result = func_json_encode($return);
		header('Content-Type: application/json; charset=utf-8');

		echo $ajax_result;
		exit;
	}
}
?>
