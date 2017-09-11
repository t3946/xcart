<?php

require "./auth.php";

x_session_register('cart');
x_session_register('catalog_checkboxes', array());
x_session_register('added_catalogs');
x_session_register('ajax_error');
x_session_register('ajax_redirect');
x_session_register('ajax_mode', 'Y');
require_once $xcart_dir."/include/cart_process.php";

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
		
		$tmp_info_product = func_query_first("SELECT p.min_amount, p.mult_order_quantity, m.catalog_price FROM $sql_tbl[products] as p LEFT JOIN  $sql_tbl[manufacturers] as m ON m.catalog_sku=p.productcode WHERE p.productid='$productid'");

		$min_amount = $tmp_info_product["min_amount"];
		$mult_order_quantity = $tmp_info_product["mult_order_quantity"];
		$catalog_price = $tmp_info_product["catalog_price"];

		#
		# Add product to the cart
		#
		$add_product = array();
		$add_product['productid'] = abs(intval($productid));
		$add_product['amount'] = abs(intval($amount));
		$add_product['product_options'] = $product_options;
		$add_product['price'] = abs(doubleval($price));
		$add_product['catalog_price'] = ($catalog_price) ? price_format($catalog_price) : null;

###
		if ($mult_order_quantity == "Y" && $min_amount > 1){
			$ceil_amount = $add_product['amount'] / $min_amount;
			$ceil_amount = ceil($ceil_amount);
			$add_product['amount'] = $ceil_amount*$min_amount;
		}
###

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

		if (!isset($is_group)) {
            $ajax_result = func_json_encode($return);
            header('Content-Type: application/json; charset=utf-8');

            echo $ajax_result;
            exit;
        }
	}
}
