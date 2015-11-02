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
# $Id: order_edit.php, v 1.0.0 2010/03/24 12:08:09 random Exp $
#

if ( !defined("XCART_SESSION_START") ) { header("Location: ../"); die("Access denied"); }

x_load('order_edit','taxes');

include $xcart_dir."/shipping/shipping.php";
include $xcart_dir."/include/countries.php";
include $xcart_dir."/include/states.php";

x_session_register("intershipper_rates");
x_session_register("intershipper_recalc");
x_session_register("current_carrier","UPS");

$all_processors = func_query_hash("SELECT paymentid, payment_method, acc_per_trans, acc_percent FROM $sql_tbl[payment_methods] WHERE acc_proc='Y' ORDER BY orderby","paymentid", false);
$smarty->assign("all_processors", $all_processors);

#
# This flag enables the taxes recalculation if customer profile is modified
#
$real_taxes = "Y";

if ($real_taxes == "Y" && !defined('XAOM'))
	define ("XAOM", 1);

$intershipper_recalc = "Y";

$cart_tmp = $order;
$cart_tmp["orders"][0] = $order_data["order"];
$cart_tmp["total_cost"] = $cart_tmp["total"];
$cart_tmp["giftcerts"] = $order_data["giftcerts"];
$cart_tmp["products"] = $order_data["products"];
$cart_tmp["userinfo"] = $order_data["userinfo"];
$cart_tmp["discount_coupon"] = $order_data["order"]["coupon"];
$cart_tmp["use_discount_alt"] = "Y";
$cart_tmp["discount_alt"] = $order_data["order"]["discount"];

if (empty($cart_tmp['extra']['discount_info'])) {
	$cart_tmp['extra']['discount_info'] = array(
				"discount" => $order_data["order"]["discount"],
				"discount_type" => "absolute"
			);
}

$cart_tmp["use_shipping_costs_alt"] = "Y";
$cart_tmp["shipping_costs_alt"] = array();
$cart_tmp["shipping_cost_alt"] = 0;

foreach ($order["shipping_groups"] as $m_id => $v ) {
	$cart_tmp["shipping_costs_alt"][$m_id] = $v["shipping_cost"]["gross"];
	$cart_tmp["shipping_cost_alt"] += $v["shipping_cost"]["gross"];
}

if (is_array($cart_tmp["products"])) {
	foreach ($cart_tmp["products"] as $k => $v) {
		$cart_tmp["products"][$k]["free_price"] = $v["price"];
		$cart_tmp["products"][$k]["price"] = $v["display_price"];
		if (!empty($v["extra_data"]["taxes"]) && is_array($v["extra_data"]["taxes"])) {
			foreach ($v["extra_data"]["taxes"] as $_tax) {
				if (($_tax["price_includes_tax"] == "Y" || $_tax['display_including_tax'] == 'Y') && $config["Taxes"]["display_taxed_order_totals"] == 'Y')
					$cart_tmp["products"][$k]["price"] -= price_format($_tax["tax_value_precise"]);
			}
		}
		$cart_tmp["products"][$k]["taxed_price"] = $v["display_price"];
		if ($v["product_type"] == "C") {
			$cart_tmp["products"][$k]["options_surcharge"] = $v["price"];
		}
		if (!empty($active_modules["Product_Options"])) {
			$cart_tmp["products"][$k]["keep_options"] = "Y";
		}
	}
}

$customer_membershipid = $cart_tmp["userinfo"]["membershipid"];

if (!empty($debug)) {
func_print_r($cart_tmp);
}

#
# Process and update orders data
#
if ($REQUEST_METHOD == "POST") {

	if ($mode == "order_edit_apply") {

#
##
###
		$tmp_mnfs = func_get_order_manufacturers($orderid);
###
##
#

                $all_groups_cb_status_eq_P = true;
                $groups_cb_status_eq_P_found = false;

		$cart_tmp["flag_change"] = true;

		if (!empty($items)) {
			foreach ($items as $itemid => $v) {
				$k = -1;
				foreach ($cart_tmp["products"] as $kp => $vp) {
					if ($vp["itemid"] == $itemid) {
						$k = $kp;
						break;
					}
				}
				if ($k == -1) {
					continue;
				}

				$productid = $cart_tmp['products'][$k]['productid'];

		                $ref_values = func_get_refund_values($v['amount'], 'Q');
#
##
###
				$product_code = func_query_first_cell("SELECT productcode FROM $sql_tbl[products] WHERE productid='$v[productid]'");
				if ($ref_values["is_refunded"] == "1"){
					$log = "<B>".$product_code."</B>: amount: ".$v["amount"];
					func_log_order($orderid, 'X', $log, $login);
				}
###
##
#

            
		                if (!empty($ref_values) && $ref_values['is_refunded'] || !is_numeric($v['amount'])) {
                		    $v['amount'] = func_query_first_cell('SELECT amount FROM ' . $sql_tbl['order_details']
		                        . ' WHERE productid="' . $productid . '" AND orderid="' . $orderid . '"');
		                }
                
		                if (!empty($ref_values) && $ref_values['is_refunded']) {
                		    if ($v['amount'] < $ref_values['amount']) {
		                        $ref_values['amount'] = $v['amount'];
                		    }
		                    $ref_values['price'] = func_adjust_refund_price($v['price'], $ref_values['fee']);
                		    if ($ref_values['price'] > $v['price']) {
		                        $ref_values['price'] = $v['price'];
                		    }
		                }
                		$cart_tmp['products'][$k]['refund'] = $ref_values;

				$v["amount"] = intval($v["amount"]);
				$v['back'] = intval($v['back']);
                
		                // back can't be more than amount
                		if ($v['back'] > $v['amount']) {
		                    $v['back'] = $v['amount'];
		                }


#
##
###
                                $current_back = $cart_tmp['products'][$k]['back'];
				$new_back = $v['back'];

				$current_eta_date_mm_dd_yyyy = func_query_first_cell("SELECT eta_date_mm_dd_yyyy FROM $sql_tbl[products] WHERE productid='$productid'");
				$new_eta_date_mm_dd_yyyy = $v['eta_date_mm_dd_yyyy'];

				if (!empty($current_eta_date_mm_dd_yyyy)){
	                                $current_eta_date_mm_dd_yyyy_arr = explode("/", $current_eta_date_mm_dd_yyyy);
        	                        $current_eta_date_mm_dd_yyyy_time = mktime(0, 0, 0, $current_eta_date_mm_dd_yyyy_arr[0], $current_eta_date_mm_dd_yyyy_arr[1], $current_eta_date_mm_dd_yyyy_arr[2]);
				} else {
					$current_eta_date_mm_dd_yyyy_time = 0;
				}

                                if (!empty($new_eta_date_mm_dd_yyyy)){
                                        $new_eta_date_mm_dd_yyyy_arr = explode("/", $new_eta_date_mm_dd_yyyy);
                                        $new_eta_date_mm_dd_yyyy_time = mktime(0, 0, 0, $new_eta_date_mm_dd_yyyy_arr[0], $new_eta_date_mm_dd_yyyy_arr[1], $new_eta_date_mm_dd_yyyy_arr[2]);
                                } else {
                                        $new_eta_date_mm_dd_yyyy_time = 0;
                                }

				$tmp_manufacturerid = $cart_tmp['products'][$k]["manufacturerid"];

				if ( 
					( $current_back == "0" || ($current_eta_date_mm_dd_yyyy_time < time() || empty($current_eta_date_mm_dd_yyyy)) )
					&&
					( $new_back > 0 && $new_eta_date_mm_dd_yyyy_time > time() )
				){

					$order['shipping_groups'][$tmp_manufacturerid]['dc_status'] = "M";

					if (!empty($groups[$tmp_manufacturerid]['dc_status'])){
						$groups[$tmp_manufacturerid]['dc_status'] = "M";
					}
				}


#
##
###
//func_print_r($v, $groups[$tmp_manufacturerid]["cb_status"], $transaction_id_link, $vt_paymentid, $order['shipping_groups'][$tmp_manufacturerid]['cb_status']);
//die();
				if (!empty($vt_paymentid) && empty($transaction_id_link)){
					$payment_transaction_id_link = func_query_first_cell("SELECT transaction_id_link FROM $sql_tbl[payment_methods] WHERE paymentid='$vt_paymentid'");
					if (!empty($payment_transaction_id_link)){
						$groups[$tmp_manufacturerid]["cb_status"] = $order['shipping_groups'][$tmp_manufacturerid]['cb_status'];
					}
				}

				if (!empty($vt_paymentid)){

					if ($groups[$tmp_manufacturerid]["cb_status"] != "P"){
						$all_groups_cb_status_eq_P = false;
					} else {
						$groups_cb_status_eq_P_found = true;
					}

				}
###
##
#

                                ### LOG: START
//                                $current_eta_date_mm_dd_yyyy = func_query_first_cell("SELECT eta_date_mm_dd_yyyy FROM $sql_tbl[products] WHERE productid='$productid'");
                                if ($current_eta_date_mm_dd_yyyy != $v["eta_date_mm_dd_yyyy"]){
                                        $product_code = func_query_first_cell("SELECT productcode FROM $sql_tbl[products] WHERE productid='$productid'");

                                        $log = "<B>".$product_code."</B> ETA date: " . $current_eta_date_mm_dd_yyyy . " -> " . $v["eta_date_mm_dd_yyyy"];
                                        func_log_order($orderid, 'X', $log, $login);
                                }
                                ### LOG: END

                                db_query("UPDATE $sql_tbl[products] SET eta_date_mm_dd_yyyy='".$v["eta_date_mm_dd_yyyy"]."' WHERE productid='$v[productid]'");

###
##
#


                		$cart_tmp['products'][$k]['back'] = $v['back'];

				if (!empty($v["delete"]) || $v["amount"] == 0) {
					$cart_tmp["products"][$k]["deleted"] = true;

#
##
###
//					$product_code = func_query_first_cell("SELECT productcode FROM $sql_tbl[products] WHERE productid='$v[productid]'");
					$log = "<B>Deleted:</B> ".$product_code;
					func_log_order($orderid, 'X', $log, $login);
###
##
#

					continue;
				}

				# Check if product is out of stock
				$count_product_in_stock = func_oe_get_quantity_in_stock($productid, $order_data['order']['cb_status'], $order_data['order']['dc_status'], $v['product_options'], @$order_data["products"][$k]);
				if ($v["amount"] > 0) {
					if ($config["General"]["unlimited_products"] == "Y"|| $v["amount"] <= $count_product_in_stock) {
						$cart_tmp["products"][$k]["amount"] = $v["amount"];
					} elseif ($cart_tmp["products"][$k]["amount"] > $count_product_in_stock && $count_product_in_stock > 0) {
						$cart_tmp["products"][$k]["amount"] = $count_product_in_stock;
					}
				}

#
##
###
				$v["price"] = preg_replace("/[^0-9\.]/S","", $v["price"]);
###
##
#

				$v["price"] = func_oe_validate_price($v["price"]);

				$cart_tmp["products"][$k]["price"] = $v["price"];
				$cart_tmp["products"][$k]["free_price"] = $v["price"];
				$cart_tmp["products"][$k]["stock_update"] = "N";
			}
		}


//func_print_r($order);
//die();

		if (!empty($groups)) {
			$cart_tmp["shipping_cost_alt"] = 0;

			foreach ($groups as $m_id => $v) {

#
##
###
                                ### LOG: START
                                $current_actual_shipping_net = func_query_first_cell("SELECT actual_shipping_net FROM $sql_tbl[order_groups] WHERE orderid='$orderid' AND manufacturerid='$m_id'");

                                $log = "";
				$code = func_query_first_cell("SELECT code FROM $sql_tbl[manufacturers] WHERE manufacturerid='$m_id'");

                                $current_shipping_value_selectbox = func_query_first_cell("SELECT shipping_value_selectbox FROM $sql_tbl[order_groups] WHERE orderid='$orderid' AND manufacturerid='$m_id'");

                                if ($current_shipping_value_selectbox != $v['shipping_value_selectbox']){
                                        if ($log != "") $log .= "<br />";
                                        $log .= "<B>".$code.": </B>"."shipping_value_selectbox: ". $current_shipping_value_selectbox. " -> ". $v['shipping_value_selectbox'];
                                }

###
                                if (!empty($v["additional_shipping_status"])){

					$current_additional_shipping_status = $order['shipping_groups'][$m_id]['additional_shipping_status'];
					$new_additional_shipping_status = $v["additional_shipping_status"];

	                                if (!empty($v["additional_vt_paymentid"]) && $new_additional_shipping_status == "P"){

						$save_additional_vt = true;

						if (empty($v["additional_transaction_id_link"])){
	        	                                $m_id_payment_transaction_id_link = func_query_first_cell("SELECT transaction_id_link FROM $sql_tbl[payment_methods] WHERE paymentid='$v[additional_vt_paymentid]'");
        	        	                        if (!empty($m_id_payment_transaction_id_link)){
                	        	                        $new_additional_shipping_status = $current_additional_shipping_status;
								$save_additional_vt = false;
                        	        	        }
						}

						if ($save_additional_vt){

//func_print_r($v, $tmp_mnfs);
//die();

//							if ($v["shipping_value_selectbox"] == "actual_shipping_cost"){

								$v["shipping_cost_net"] += $tmp_mnfs[$m_id]["additional_shipping_charge"];
								$groups[$m_id]["shipping_cost_net"] = $v["shipping_cost_net"];

								$v["actual_shipping_cost_net"] = 0;
								$groups[$m_id]["actual_shipping_cost_net"] = $v["actual_shipping_cost_net"];
//							}


							if ($log != "") $log .= "<br />";
							if ($v["additional_vt_paymentid"] != $order['shipping_groups'][$m_id]['additional_vt_paymentid']){

								$current_vt_paymentid_name = func_query_first_cell("SELECT payment_method FROM $sql_tbl[payment_methods] WHERE paymentid='".$order['shipping_groups'][$m_id]['additional_vt_paymentid']."'");
								$vt_paymentid_name = func_query_first_cell("SELECT payment_method FROM $sql_tbl[payment_methods] WHERE paymentid='$v[additional_vt_paymentid]'");
								$log .= "<B>".$code.": </B>"."Shipping payment method: " . $current_vt_paymentid_name . " -> " . $vt_paymentid_name;
							}

							if ($log != "") $log .= "<br />";
                                                        if ($v["additional_transaction_id_link"] != $order['shipping_groups'][$m_id]['additional_transaction_id_link']){
                                                                $log .= "<B>".$code.": </B>"."Shipping transaction ID:".$order['shipping_groups'][$m_id]['additional_transaction_id_link']. " -> ".$v["additional_transaction_id_link"];
					                        $payment_transaction_id_link = func_query_first_cell("SELECT transaction_id_link FROM $sql_tbl[payment_methods] WHERE paymentid='".$v["additional_vt_paymentid"]."'");
					                        if (!empty($payment_transaction_id_link)){
					                                $payment_transaction_id_link = str_replace("{{trans-id}}", $v["additional_transaction_id_link"], $payment_transaction_id_link);
					                                $log .= "<br /><a href='".$payment_transaction_id_link."' target='_blank' style='color: #1411FF;'>Link to ".$vt_paymentid_name." virtual terminal transaction</a>";
					                        }
                                                        }


							if ($log != "") $log .= "<br />";
                                                        if ($v["additional_avs_code"] != $order['shipping_groups'][$m_id]['additional_avs_code']){

								$additional_avs_code_description = func_query_first_cell("SELECT description FROM $sql_tbl[avs_codes] WHERE code='".addslashes($v["additional_avs_code"])."'");
								if (!empty($additional_avs_code_description)){
									$additional_avs_code_description_txt = "(".$additional_avs_code_description.")";
								} else $additional_avs_code_description_txt = "";

                                                                $log .= "<B>".$code.": </B>"."Shipping AVS code:".$order['shipping_groups'][$m_id]['additional_avs_code']. " -> ".$v["additional_avs_code"]. " ".$additional_avs_code_description_txt;
                                                        }

							db_query("UPDATE $sql_tbl[order_groups] SET additional_vt_paymentid='$v[additional_vt_paymentid]', additional_transaction_id_link='$v[additional_transaction_id_link]', additional_avs_code='$v[additional_avs_code]' WHERE orderid='$orderid' AND manufacturerid='$m_id'");
						}
	                                }


                                        if ($current_additional_shipping_status != $new_additional_shipping_status){
                                                db_query("UPDATE $sql_tbl[order_groups] SET additional_shipping_status='$new_additional_shipping_status' WHERE orderid='$orderid' AND manufacturerid='$m_id'");

                                                if ($log != "") $log .= "<br />";
                                                $log .= "<B>".$code.": </B>"."additional_shipping_status: ". $additional_shipping_statuses[$current_additional_shipping_status] . " -> ". $additional_shipping_statuses[$new_additional_shipping_status];
                                        }
                                }
###


                                if ($current_actual_shipping_net != $v["actual_shipping_cost_net"]){
                                        $tmp_actual_shipping_cost_net = $v["actual_shipping_cost_net"];
                                        if (empty($tmp_actual_shipping_cost_net)){
                                                $tmp_actual_shipping_cost_net = 0;
                                        }

					if (!empty($log)) {
						$log .= "<br />"; 
					} else {
						$log = "";
					}

                                        $log .= "<B>".$code.": </B>"."Actual shipping cost net: " . $current_actual_shipping_net . " -> " . $tmp_actual_shipping_cost_net;
                                }


                                if ($log != ""){
                                        func_log_order($orderid, 'X', $log, $login);
                                }
                                ### LOG: END

				$v["actual_shipping_cost_net"] = preg_replace("/[^0-9\.]/S","", $v["actual_shipping_cost_net"]);

				db_query("UPDATE $sql_tbl[order_groups] SET actual_shipping_net='$v[actual_shipping_cost_net]', actual_shipping_gross='$v[actual_shipping_cost_net]', shipping_value_selectbox='$v[shipping_value_selectbox]' WHERE orderid='$orderid' AND manufacturerid='$m_id'");
###
##
#                
		                $ref_values = func_get_refund_values($v['shipping_cost_net'], 'S');

#
##
###
                                if ($ref_values["is_refunded"] == "1"){
                                        $log = "<B>".$code."</B>: shipping_cost_net: ".$v["shipping_cost_net"];
                                        func_log_order($orderid, 'X', $log, $login);
                                }
###
##
#

		                if (!empty($ref_values) && $ref_values['is_refunded']) {
                		    $v['shipping_cost_net'] = $v['shipping_cost_net_orig'];
		                }
                		$order['shipping_groups'][$m_id]['refund'] = $ref_values;

				$v["shipping_cost_net"] = preg_replace("/[^0-9\.]/S","", $v["shipping_cost_net"]);
				$v["shipping_cost_net_orig"] = preg_replace("/[^0-9\.]/S","", $v["shipping_cost_net_orig"]);

		                $order['shipping_groups'][$m_id]['shipping_cost_net_orig'] = $v['shipping_cost_net_orig'];
					
		                $order["shipping_groups"][$m_id]["shipping_cost"] = func_tax_price_details($v["shipping_cost_net"], $order["shipping_groups"][$m_id]["taxes"]);
				$cart_tmp["shipping_costs_alt"][$m_id] = $order["shipping_groups"][$m_id]["shipping_cost"]["gross"];
				$cart_tmp["shipping_cost_alt"] += $cart_tmp["shipping_costs_alt"][$m_id];
#				$order["shipping_groups"][$m_id]["shippingid"] = $v["shippingid"];
				$order["shipping_groups"][$m_id]["shipping"] = $v["shipping"];
				if (!in_array($v['dc_status'], array('C','S')) && $user_account['flag'] == 'FS') {
					$v['dc_status'] = 'C';
				}
				$order['shipping_groups'][$m_id]['cb_status'] = $v['cb_status'];
				$order['shipping_groups'][$m_id]['dc_status'] = $v['dc_status'];
				$order['shipping_groups'][$m_id]['bd_status'] = $v['bd_status'];

				if (empty($order["shipping_groups"][$m_id]["tracking"])) {
					$tracking = array();
				} else {
					$tracking = $order["shipping_groups"][$m_id]["tracking"];
				}
				if (!is_array($tracking)) {
					$tracking = array();
				}

				$log = "<B>Tracking numbers:</B><br /><B>Added:</B><br />";
				foreach ($v["tracking_shipper"] as $_k => $sh) {
					if (!empty($v["tracking_shipper"][$_k]) && func_check_tracking_number($sh, trim($v["tracking_number"][$_k]))) {
						$tracking[] = array('linkid' => $sh, 'tracknum' => trim($v["tracking_number"][$_k]));
						$order['shipping_groups'][$m_id]['dc_status'] = 'S';
						define('TRACKING_ADDED', 1);
				
						$shipping_link = func_query_first_cell("SELECT shipping FROM $sql_tbl[tracking_links] WHERE linkid='$sh'");
						$log .= $shipping_link.": ".trim($v["tracking_number"][$_k])."<br />";
						$add_tracking_log = true;
					}
				}
				$order["shipping_groups"][$m_id]['tracking'] = $tracking;

				if ($add_tracking_log){
					func_log_order($orderid, 'X', $log, $login);
				}
				$log = "";
			}
		}


//func_print_r($_POST);
//die();

		$operator_login = $login;

		if (!empty($add_productcode)) {
			$saved_data = compact("login", "login_type", "current_area");
			$login = $cart_tmp["userinfo"]["login"];
			$login_type = "C";
			$current_area = "C";
			foreach ($add_productcode as $kkk => $sku) {
				$amount = intval($add_amount[$kkk]);
				if (empty($amount)) {
					continue;
				}
				$newproductid = func_query_first_cell("SELECT productid FROM $sql_tbl[products] WHERE productcode='".trim($sku)."'");
				if (empty($newproductid)) {
					$_tmp = func_query_first("SELECT productid, variantid FROM $sql_tbl[variants] WHERE productcode='".trim($sku)."'");
					if (!empty($_tmp)) {
						$newproductid = $_tmp["productid"];
						$newvariantid = $_tmp["variantid"];
					}
					if (empty($newproductid)) {
						continue;
					}
				}

#
##
###
				$log = "<B>Add product:</B> ".$sku." x ".$amount;
				func_log_order($orderid, 'X', $log, $operator_login);
###
##
#
				global $add_from_order_edit;
				$add_from_order_edit = true;
				if ($prd = func_select_product($newproductid, $customer_membershipid, false, false, true)) {
					$prd["provider"] = (!empty($config['General']['default_provider_name'])) ? $config['General']['default_provider_name'] : $prd['provider'];

/* provider check removed - random, 2011-01-27 - see msgid: 538231346 
					if (!$single_mode && is_array($cart_tmp["products"])) {
						$_providers = array();
						foreach ($cart_tmp["products"] as $_product) {
							$_providers[$_product["provider"]] = 1;
						}
						if (!in_array($prd["provider"], array_keys($_providers))) {
							continue;
						}
					}
*/

					if ($prd["avail"] <= 0 && $config["General"]["unlimited_products"] == "N") {
						continue;
					}

					# Update wholesale price
		                        $prd["price"] = func_query_first_cell("SELECT MIN($sql_tbl[pricing].price) FROM $sql_tbl[pricing] WHERE $sql_tbl[pricing].productid='$newproductid' AND $sql_tbl[pricing].quantity<='$amount' AND $sql_tbl[pricing].variantid = '$newvariantid'");

#
##
###
					$prd["new_map_price"] = func_query_first_cell("SELECT new_map_price FROM $sql_tbl[products] WHERE productid='$newproductid'");
					$prd["price"] = max($prd["price"], $prd["new_map_price"]);
###
##
#

					$prd["catalog_price"] = $prd["price"];

					if ($active_modules["Product_Options"]) {
						if ($newvariantid && $vars = func_get_product_variants($newproductid, $customer_membershipid, 'C')) {
							$variant = $vars[$newvariantid];
							$variant["variantid"] = $newvariantid;
							$product_options_result = $variant["options"];
							$prd['extra_data']["product_options"] = array();
							if ($product_options_result) {
								foreach ($product_options_result as $opt) {
									$prd['extra_data']["product_options"][$opt["classid"]] = $opt["optionid"];
								}
							}
						} else {
							$prd['extra_data']["product_options"] = func_get_default_options($newproductid, $amount, $customer_membershipid);
							list($variant, $product_options_result) = func_get_product_options_data($newproductid, $prd['extra_data']["product_options"], $customer_membershipid);
						}
						$surcharge = 0;
						$prd['product_options'] = $product_options_result;
						if($product_options_result) {
							foreach($product_options_result as $key=>$o)
								$surcharge += ($o['modifier_type'] == '%'?($prd['price']*$o['price_modifier']/100):$o['price_modifier']);
						}
						if (!empty($variant) && !empty($variant["productcode"]) && $variant["productid"] == $cart_tmp["products"][$k]["productid"]) {
							$cart_tmp["products"][$k]["productcode"] = $variant["productcode"];
							$cart_tmp["products"][$k]["variantid"] = $variant["variantid"];
							$cart_tmp["products"][$k]["catalog_price"] = $prd["price"] = $variant["price"];
						}

						$prd["price"] = price_format($prd["price"] + $surcharge);
					}
					$prd["amount"] = $amount;
					$prd["new"] = true;

					$cart_tmp["products"][] = $prd;
					if (!array_key_exists($prd["manufacturerid"], $order["shipping_groups"])) {
						$order["shipping_groups"][$prd["manufacturerid"]] = array("new" => true);
					}
					unset($prd);
				}
			}
			extract($saved_data);
		}

#
##
###
		$log = "";
		$additional_fee = array();

		if (!empty($delete_additional_fee) && is_array($delete_additional_fee) && !empty($edit_additional_fee_name) && is_array($edit_additional_fee_name)){
			foreach ($delete_additional_fee as $k => $v){
				if ($v == "Y"){
					$log .= $edit_additional_fee_name[$k]["additional_fee_name"]. " $".$edit_additional_fee_name[$k]["additional_fee_value"]. " - Deleted <br />";
					unset($edit_additional_fee_name[$k]);
					db_query("DELETE FROM $sql_tbl[order_additional_fee] WHERE id='$k'");
				}
			}
		}

		if (!empty($add_additional_fee_name) && is_array($add_additional_fee_name) && !empty($add_additional_fee_value) && is_array($add_additional_fee_value)){
			foreach ($add_additional_fee_name as $k => $v){
			    $v = trim($v);
			    if (!empty($v)){
				$add_price = price_format($add_additional_fee_value[$k]);
				$additional_fee_row["additional_fee_name"] = $v;
				$additional_fee_row["additional_fee_value"] = $add_price;
				$additional_fee[] = $additional_fee_row;
				$log .= $v . " $".$add_price. " - Added <br />";

				db_query("INSERT INTO $sql_tbl[order_additional_fee] (orderid, additional_fee_name, additional_fee_value) VALUES ('$orderid', '".addslashes($v)."', '$add_price')");
			    }
			}
		}

		if (!empty($edit_additional_fee_name) && is_array($edit_additional_fee_name)){
			foreach ($edit_additional_fee_name as $k => $v){

				$add_price = price_format($v["additional_fee_value"]);
				$additional_fee_row["additional_fee_name"] = $v["additional_fee_name"];
				$additional_fee_row["additional_fee_value"] = $add_price;
				$additional_fee[] = $additional_fee_row;

				$current_fee_info = func_query_first("SELECT additional_fee_name, additional_fee_value FROM $sql_tbl[order_additional_fee] WHERE id='$k'");
				if ($current_fee_info["additional_fee_name"] != $v["additional_fee_name"] || $current_fee_info["additional_fee_value"] != $add_price) {
					$log .= $current_fee_info["additional_fee_name"]. " $".$current_fee_info["additional_fee_value"] . " -> ".$v["additional_fee_name"] . " $".$add_price . "<br />";
				}

				db_query("UPDATE $sql_tbl[order_additional_fee] SET additional_fee_name='".addslashes($v["additional_fee_name"])."', additional_fee_value='$add_price' WHERE id='$k'");
			}
		}

		$cart_tmp["additional_fee"] = $additional_fee;

		if (!empty($log)){
			func_log_order($orderid, 'X', $log, $login);
		}
###
##
#

#
##
###
		$login = $operator_login;
###
##
#

		if (!empty($customer_info)) {
			$cart_tmp["userinfo"] = func_array_merge($cart_tmp["userinfo"], func_array_map("stripslashes", $customer_info));
		}
		if (!empty($additional_fields) && !empty($cart_tmp["userinfo"]["additional_fields"])) {
			foreach ($additional_fields as $ak => $av) {
				foreach ($cart_tmp["userinfo"]["additional_fields"] as $_uk => $_uv) {
					if ($_uv["fieldid"] == $ak) {
						$cart_tmp["userinfo"]["additional_fields"][$_uk]["value"] = stripslashes($av);
						break;
					}
				
				}
			}
		}

		if ($order["paymentid"] == PAYMENT_PURCHASE_ID) {
			# Get PO data from order details text
			$data = explode("\n",$order["details"]);
	
			$data_current = $data;

			if ($data) {
				$po_fields = array("PO Number" => $po_number, "Company name" => $po_company_name, "Name of purchaser" => $name_of_purchaser, "Position" => $po_position, "po fax" => $po_fax);
				$order["po_details"] = array();
				foreach ($data as $i => $line) {
					if (empty($po_fields)) {
						break;
					}
					foreach ($po_fields as $k => $po_text) {
						if (($a = strpos($line, $k)) !== false) {
							$data[$i] = "$k: $po_text"; 
							break;
						}
					}
				}
                
				if ($po_update) {


#
##
###
				        $count_data_current = count($data_current);
				        $count_data = count($data);
				        $count_po_fields = max($count_data_current, $count_data);

				        $log = "<B>Order details:</B><br />";
				        $insert_po_log = false;

				        for ($i = 0; $i < $count_po_fields; $i++){

				                if ($data_current[$i] != $data[$i]){
				                        $log .= $data_current[$i]." -> ".$data[$i]."<br />";
				                        $insert_po_log = true;
				                }
				        }

				        if ($insert_po_log){
				                func_log_order($order["orderid"], 'C', $log, $login);
				        }
###
##
#


		        	        $order['details'] = implode("\n", $data);
			                db_query("UPDATE $sql_tbl[orders] SET details='" . addslashes(text_crypt($order['details'])) . "', po_number='".addslashes($po_number)."' WHERE orderid=$order[orderid]");
				}
			}
		}

#
##
###
	        if (!empty($orderid) && !empty($groups) && is_array($groups)){

        	        $all_cb_status_eq_O = true;
                	$all_dc_status_eq_S = true;

	                foreach ($groups as $k => $v){
        	                if ($v["cb_status"] != "O"){
                	                $all_cb_status_eq_O = false;
                        	}

	                        if ($v["dc_status"] != "S"){
        	                        $all_dc_status_eq_S = false;
                	        }
	                }

	                if ($all_cb_status_eq_O && $all_dc_status_eq_S){

        	                $current_dc_statuses = func_query("SELECT dc_status FROM $sql_tbl[order_groups] WHERE orderid='$orderid'");
                	        if (!empty($current_dc_statuses) && is_array($current_dc_statuses)){
                        	        $all_current_dc_status_NOT_eq_S = false;
                                	foreach ($current_dc_statuses as $k => $v){
                                        	if ($v["dc_status"] != "S"){
                                                	$all_current_dc_status_NOT_eq_S = true;
                                        	        break;
                                	        }
                        	        }
                	        }
        	        }
	        }
###
##
#

                func_oe_update_order($cart_tmp, $order["shipping_groups"], $order_data["products"]);


#
##
###
                if ($all_groups_cb_status_eq_P && $groups_cb_status_eq_P_found && !empty($groups) && is_array($groups)) {

			$new_groups = array();

			foreach ($groups as $k => $v){
				$new_groups[$k]["paymentid"] = $vt_paymentid;

				$new_groups[$k]["acc"][1]["gst"] = $order['shipping_groups'][$m_id]['accounting'][1]["gst"];
				$new_groups[$k]["acc"][2]["gst"] = $order['shipping_groups'][$m_id]['accounting'][2]["gst"];
				$new_groups[$k]["acc"][3]["gst"] = $order['shipping_groups'][$m_id]['accounting'][3]["gst"];
				$new_groups[$k]["acc"][4]["gst"] = $order['shipping_groups'][$m_id]['accounting'][4]["gst"];

                                $new_groups[$k]["acc"][1]["pst"] = $order['shipping_groups'][$m_id]['accounting'][1]["pst"];
                                $new_groups[$k]["acc"][2]["pst"] = $order['shipping_groups'][$m_id]['accounting'][2]["pst"];
                                $new_groups[$k]["acc"][3]["pst"] = $order['shipping_groups'][$m_id]['accounting'][3]["pst"];
                                $new_groups[$k]["acc"][4]["pst"] = $order['shipping_groups'][$m_id]['accounting'][4]["pst"];

                                $new_groups[$k]["acc"][1]["gross"] = $order['shipping_groups'][$m_id]['accounting'][1]["gross"];
                                $new_groups[$k]["acc"][2]["gross"] = $order['shipping_groups'][$m_id]['accounting'][2]["gross"];
                                $new_groups[$k]["acc"][3]["gross"] = $order['shipping_groups'][$m_id]['accounting'][3]["gross"];
                                $new_groups[$k]["acc"][4]["gross"] = $order['shipping_groups'][$m_id]['accounting'][4]["gross"];
			}

			$groups = $new_groups;


                        $applied_per_trans_payments = array();
                        foreach ($groups as $m_id => $v) {

                                $order['shipping_groups'][$m_id]['acc_paymentid'] = $v['paymentid'];
                                $order['shipping_groups'][$m_id]['manufacturerid'] = $m_id;

                                if (is_array($order['shipping_groups'][$m_id]['accounting']) && !empty($order['shipping_groups'][$m_id]['accounting'])) {
                                    $acc_zero_data = array(
                                        ACC_COST_TO_US  => true,
                                        ACC_REF_TO_CUST => true,
                                        ACC_REF_TO_US   => true,
                                    );
                                    $acc_new_data = array(
                                        ACC_COST_TO_US  => false,
                                        ACC_REF_TO_CUST => false,
                                        ACC_REF_TO_US   => false,
                                    );

                                    foreach ($order['shipping_groups'][$m_id]['accounting'] as $col => $sga) {
                                        foreach ($sga as $pdn => $pdv) {
                                            if (
                                                in_array($col, array_keys($acc_zero_data))
                                                && !in_array($pdn, array('filled', 'net'))
                                            ) {
                                                $pdv = intval($pdv);

                                                if (!empty($pdv)) {
                                                    $acc_zero_data[$col] = false;
                                                }

                                                if (isset($v['acc'][$col][$pdn])) {
                                                    $_pdv = intval($v['acc'][$col][$pdn]);
                                                    if (!empty($_pdv)) {
                                                        $acc_new_data[$col] = true;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }

/*
                                if (
                                    in_array($order['shipping_groups'][$m_id]['cb_status'], array('P','R','H')) 
                                    || in_array($order['shipping_groups'][$m_id]['dc_status'], array('C','S')) 
                                ) {
*/
                                        for ($ak=1; $ak<=4; $ak++) {
                                                if ($ak == ACC_REF_TO_CUST) {
                                                    $refund_group = func_query_first('SELECT total_net, total_gst, total_pst, total_gross'
                                                        . ' FROM ' . $sql_tbl['refund_groups']
                                                        . ' WHERE orderid = "' . $order['shipping_groups'][$m_id]['orderid'] . '"'
                                                        . ' AND manufacturerid = "' . $m_id .'"');
                                                }

                                                $order['shipping_groups'][$m_id]['accounting'][$ak] = array();

                                                foreach ($price_details_names as $af) {
                                                    if ($ak == ACC_REF_TO_CUST) {
                                                        if (!empty($refund_group)) {
                                                            $v['acc'][$ak][$af] = $refund_group['total_' . $af];
                                                        } else {
                                                            $v['acc'][$ak][$af] = 0;
                                                        }
                                                    }

                                                    $order['shipping_groups'][$m_id]['accounting'][$ak][$af] = $v['acc'][$ak][$af];
                                                }
                                        }
//                              }

                                if ($apply_per_trans = !in_array($v['paymentid'], $applied_per_trans_payments)) {
                                        $applied_per_trans_payments[] = $v['paymentid'];
                                }

                                func_recalculate_accounting($order['shipping_groups'][$m_id], $all_processors, $apply_per_trans, true);

                                $update = array();
                                $update['accounting'] = addslashes(serialize($order['shipping_groups'][$m_id]['accounting']));
                                $update['profit_margin'] = $order['shipping_groups'][$m_id]['profit_margin'];
                                $update['acc_paymentid'] = $v['paymentid'];

                                func_log_order_groups($update, $orderid, $m_id, 'X', $login);

                                func_array2update("order_groups", $update ,"orderid='$orderid' AND manufacturerid='$m_id'");

                                // Change the order group status

                                if (
                                    $acc_zero_data[ACC_REF_TO_US]
                                    && $acc_new_data[ACC_REF_TO_US]
                                ) {
                                    func_change_order_group_status($orderid, $m_id, 'Z');
                                } elseif ($acc_zero_data[ACC_COST_TO_US]
                                    && $acc_new_data[ACC_COST_TO_US]
                                ) {
                                    func_change_order_group_status($orderid, $m_id, 'X');
                                }

                                if (
                                    $acc_zero_data[ACC_REF_TO_CUST]
                                    && $acc_new_data[ACC_REF_TO_CUST]
                                ) {
                                    func_change_order_group_status($orderid, $m_id, 'R');
                                }
                        }



//func_print_r($_POST, $order['shipping_groups'][$m_id]['accounting'], $new_groups);
//die();


		}
###
##
#


                if ($send_email == 'Y') {
                        include $xcart_dir . '/include/send_order_email.php';
                }


#
##
###
		if ($all_current_dc_status_NOT_eq_S){
        	        #send email PO instructions

                        # N 1

                        $send_to_email = func_query_first_cell("SELECT email FROM $sql_tbl[orders] WHERE orderid='$orderid'");
                        $send_to_email .= ",custserv@s3stores.com";

                        $po_instructions_subject_line = $config['Purchase_Order']['po_instructions_subject_line'];
                        $po_instructions_subject_line = str_replace("{{orderid}}", $orderid, $po_instructions_subject_line);

                        $po_instructions = $config['Purchase_Order']['po_instructions'];
                        $po_instructions = str_replace("{{orderid}}", $orderid, $po_instructions);

                        $mail_smarty->assign("po_instructions_subject_line", $po_instructions_subject_line);
                        $mail_smarty->assign("po_instructions", $po_instructions);

                        $order = func_order_data($orderid);
                        $mail_smarty->assign("products", $order['products']);
                        $mail_smarty->assign("order", $order['order']);
                        $mail_smarty->assign("userinfo", $order['userinfo']);

                        $attach_pdf_invoice = "Y";
                        $mail_smarty->assign("attach_pdf_invoice", $attach_pdf_invoice);

                        func_send_mail($send_to_email, 'mail/purchase_order_shipped_subj.tpl', 'mail/purchase_order_shipped.tpl', $config['Company']['orders_department'], true, false, false, true);


                        # N 2
                        $tmp_order_details = func_query_first_cell("SELECT details FROM $sql_tbl[orders] WHERE orderid='$orderid'");
                        $tmp_order_details = text_decrypt($tmp_order_details);
                        $tmp_order_details_arr = explode("\n",$tmp_order_details);
                        if (!empty($tmp_order_details_arr) && is_array($tmp_order_details_arr)){
  	                      foreach ($tmp_order_details_arr as $k => $v){
        	                      if ((strpos($v, "po fax:")) !== false) {
                	                      $po_fax_num_arr = explode("po fax:", $v);
                                              $po_fax_num = $po_fax_num_arr[1];
                                              $po_fax_num = preg_replace("/[^0-9]/S","", $po_fax_num);
                                      }
                              }
                        }

                        $po_faxage_operator_email = $config['Purchase_Order']['po_faxage_operator_email'];

                        if (!empty($po_fax_num) && !empty($po_faxage_operator_email)){

                                $mail_smarty->assign("po_instructions_subject_line", $po_instructions_subject_line);
                                $mail_smarty->assign("po_instructions", $po_instructions);

                                $attach_pdf_invoice = "Y";
                                $mail_smarty->assign("attach_pdf_invoice", $attach_pdf_invoice);

                                $attach_pdf_po_instructions = "Y";
                                $mail_smarty->assign("attach_pdf_po_instructions", $attach_pdf_po_instructions);

                                $send_to_email = $po_fax_num.$po_faxage_operator_email;
                                $send_to_email .= ",custserv@s3stores.com";
//                                $send_to_email .= ",xcartmaster@gmail.com";

                        	func_send_mail($send_to_email, 'mail/purchase_order_shipped_subj.tpl', 'mail/purchase_order_shipped.tpl', $config['Company']['orders_department'], true, false, false, true);

                	}
		}
//func_print_r($_POST);
//die("123");
###
##
#

		func_header_location("order.php?orderid=$orderid");
	}
	elseif ($mode == "accounting_apply" && $user_account["flag"] != "FS") {


//func_print_r($_POST, $order['shipping_groups'][$m_id]['accounting']);
//die();


		if (!empty($groups)) {
			$applied_per_trans_payments = array();
			foreach ($groups as $m_id => $v) {

				$v["acc"][1]["gst"] = preg_replace("/[^0-9\.]/S","", $v["acc"][1]["gst"]);
				$v["acc"][1]["pst"] = preg_replace("/[^0-9\.]/S","", $v["acc"][1]["pst"]);
				$v["acc"][1]["gross"] = preg_replace("/[^0-9\.]/S","", $v["acc"][1]["gross"]);

                                $v["acc"][2]["gst"] = preg_replace("/[^0-9\.]/S","", $v["acc"][2]["gst"]);
                                $v["acc"][2]["pst"] = preg_replace("/[^0-9\.]/S","", $v["acc"][2]["pst"]);
                                $v["acc"][2]["gross"] = preg_replace("/[^0-9\.]/S","", $v["acc"][2]["gross"]);

                                $v["acc"][3]["gst"] = preg_replace("/[^0-9\.]/S","", $v["acc"][3]["gst"]);
                                $v["acc"][3]["pst"] = preg_replace("/[^0-9\.]/S","", $v["acc"][3]["pst"]);
                                $v["acc"][3]["gross"] = preg_replace("/[^0-9\.]/S","", $v["acc"][3]["gross"]);

                                $v["acc"][4]["gst"] = preg_replace("/[^0-9\.]/S","", $v["acc"][4]["gst"]);
                                $v["acc"][4]["pst"] = preg_replace("/[^0-9\.]/S","", $v["acc"][4]["pst"]);
                                $v["acc"][4]["gross"] = preg_replace("/[^0-9\.]/S","", $v["acc"][4]["gross"]);
		

#
##
###
				$code = func_query_first_cell("SELECT code FROM $sql_tbl[manufacturers] WHERE manufacturerid='$m_id'");

				$log = "";

				$current_Cost_to_us_gst = price_format($order['shipping_groups'][$m_id]['accounting'][1]["gst"]);
				$current_Cost_to_us_pst = price_format($order['shipping_groups'][$m_id]['accounting'][1]["pst"]);
				$current_Cost_to_us_gross = price_format($order['shipping_groups'][$m_id]['accounting'][1]["gross"]);

				$new_Cost_to_us_gst = $v["acc"][1]["gst"];
				$new_Cost_to_us_pst = $v["acc"][1]["pst"];
				$new_Cost_to_us_gross = $v["acc"][1]["gross"];

				if ($current_Cost_to_us_gst != $new_Cost_to_us_gst){
					$log .= "Cost_to_us_gst: ".$current_Cost_to_us_gst." -> ".$new_Cost_to_us_gst."<br />";
				}
				if ($current_Cost_to_us_pst != $new_Cost_to_us_pst){
				        $log .= "Cost_to_us_pst: ".$current_Cost_to_us_pst." -> ".$new_Cost_to_us_pst."<br />";
				}
				if ($current_Cost_to_us_gross != $new_Cost_to_us_gross){
				        $log .= "Cost_to_us_gross: ".$current_Cost_to_us_gross." -> ".$new_Cost_to_us_gross."<br />";
				}

				$current_Shipping_gst = price_format($order['shipping_groups'][$m_id]['accounting'][2]["gst"]);
				$current_Shipping_pst = price_format($order['shipping_groups'][$m_id]['accounting'][2]["pst"]);
				$current_Shipping_gross = price_format($order['shipping_groups'][$m_id]['accounting'][2]["gross"]);

				$new_Shipping_gst = $v["acc"][2]["gst"];
				$new_Shipping_pst = $v["acc"][2]["pst"];
				$new_Shipping_gross = $v["acc"][2]["gross"];

				if ($current_Shipping_gst != $new_Shipping_gst){
				        $log .= "Shipping_gst: ".$current_Shipping_gst." -> ".$new_Shipping_gst."<br />";
				}
				if ($current_Shipping_pst != $new_Shipping_pst){
				        $log .= "Shipping_pst: ".$current_Shipping_pst." -> ".$new_Shipping_pst."<br />";
				}
				if ($current_Shipping_gross != $new_Shipping_gross){
				        $log .= "Shipping_gross: ".$current_Shipping_gross." -> ".$new_Shipping_gross."<br />";
				}
	
				$current_Ref_to_cust_gst = price_format($order['shipping_groups'][$m_id]['accounting'][3]["gst"]);
				$current_Ref_to_cust_pst = price_format($order['shipping_groups'][$m_id]['accounting'][3]["pst"]);
				$current_Ref_to_cust_gross = price_format($order['shipping_groups'][$m_id]['accounting'][3]["gross"]);
				
				$new_Ref_to_cust_gst = $v["acc"][3]["gst"];
				$new_Ref_to_cust_pst = $v["acc"][3]["pst"];
				$new_Ref_to_cust_gross = $v["acc"][3]["gross"];

				if ($current_Ref_to_cust_gst != $new_Ref_to_cust_gst){
				        $log .= "Ref_to_cust_gst: ".$current_Ref_to_cust_gst." -> ".$new_Ref_to_cust_gst."<br />";
				}
				if ($current_Ref_to_cust_pst != $new_Ref_to_cust_pst){
				        $log .= "Ref_to_cust_pst: ".$current_Ref_to_cust_pst." -> ".$new_Ref_to_cust_pst."<br />";
				}
				if ($current_Ref_to_cust_gross != $new_Ref_to_cust_gross){
				        $log .= "Ref_to_cust_gross: ".$current_Ref_to_cust_gross." -> ".$new_Ref_to_cust_gross."<br />";
				}

				$current_Ref_to_us_gst = price_format($order['shipping_groups'][$m_id]['accounting'][4]["gst"]);
				$current_Ref_to_us_pst = price_format($order['shipping_groups'][$m_id]['accounting'][4]["pst"]);
				$current_Ref_to_us_gross = price_format($order['shipping_groups'][$m_id]['accounting'][4]["gross"]);

				$new_Ref_to_us_gst = $v["acc"][4]["gst"];
				$new_Ref_to_us_pst = $v["acc"][4]["pst"];
				$new_Ref_to_us_gross = $v["acc"][4]["gross"];

				if ($current_Ref_to_us_gst != $new_Ref_to_us_gst){
				        $log .= "Ref_to_us_gst: ".$current_Ref_to_us_gst." -> ".$new_Ref_to_us_gst."<br />";
				}
				if ($current_Ref_to_us_pst != $new_Ref_to_us_pst){
				        $log .= "Ref_to_us_pst: ".$current_Ref_to_us_pst." -> ".$new_Ref_to_us_pst."<br />";
				}
				if ($current_Ref_to_us_gross != $new_Ref_to_us_gross){
				        $log .= "Ref_to_us_gross: ".$current_Ref_to_us_gross." -> ".$new_Ref_to_us_gross."<br />";
				}				

				if (!empty($log)){
					$log = "<B>".$code.":</B><br />".$log;
					func_log_order($orderid, 'X', $log, $login);
				}
###
##
#




				$order['shipping_groups'][$m_id]['acc_paymentid'] = $v['paymentid'];
				$order['shipping_groups'][$m_id]['manufacturerid'] = $m_id;

		                if (is_array($order['shipping_groups'][$m_id]['accounting']) && !empty($order['shipping_groups'][$m_id]['accounting'])) {
		                    $acc_zero_data = array(
                		        ACC_COST_TO_US  => true,
		                        ACC_REF_TO_CUST => true,
		                        ACC_REF_TO_US   => true,
                		    );
		                    $acc_new_data = array(
                		        ACC_COST_TO_US  => false,
		                        ACC_REF_TO_CUST => false,
                		        ACC_REF_TO_US   => false,
		                    );

		                    foreach ($order['shipping_groups'][$m_id]['accounting'] as $col => $sga) {
                		        foreach ($sga as $pdn => $pdv) {
		                            if (
                		                in_array($col, array_keys($acc_zero_data)) 
		                                && !in_array($pdn, array('filled', 'net'))
                		            ) {
                                		$pdv = intval($pdv);
                                
		                                if (!empty($pdv)) {
                		                    $acc_zero_data[$col] = false;
                                		}
                                
		                                if (isset($v['acc'][$col][$pdn])) {
                		                    $_pdv = intval($v['acc'][$col][$pdn]);
                                		    if (!empty($_pdv)) {
		                                        $acc_new_data[$col] = true;
		                                    }
                		                }
		                            }
                		        }
		                    }
		                }

/*
				if (
		                    in_array($order['shipping_groups'][$m_id]['cb_status'], array('P','R','H')) 
                		    || in_array($order['shipping_groups'][$m_id]['dc_status'], array('C','S')) 
		                ) {
*/
					for ($ak=1; $ak<=4; $ak++) {
	                        		if ($ak == ACC_REF_TO_CUST) {
		        	                    $refund_group = func_query_first('SELECT total_net, total_gst, total_pst, total_gross'
                			                . ' FROM ' . $sql_tbl['refund_groups']
                        			        . ' WHERE orderid = "' . $order['shipping_groups'][$m_id]['orderid'] . '"'
		                                	. ' AND manufacturerid = "' . $m_id .'"');
	        		                }
					
						$order['shipping_groups'][$m_id]['accounting'][$ak] = array();
								
		        	                foreach ($price_details_names as $af) {
                			            if ($ak == ACC_REF_TO_CUST) {
                        			        if (!empty($refund_group)) {
		                                	    $v['acc'][$ak][$af] = $refund_group['total_' . $af];
	        		                        } else {
        	                		            $v['acc'][$ak][$af] = 0;
		                	                }
                		        	    }
							
						    $order['shipping_groups'][$m_id]['accounting'][$ak][$af] = $v['acc'][$ak][$af];
						}
					}
//				}

				if ($apply_per_trans = !in_array($v['paymentid'], $applied_per_trans_payments)) {
					$applied_per_trans_payments[] = $v['paymentid'];
				}

				func_recalculate_accounting($order['shipping_groups'][$m_id], $all_processors, $apply_per_trans, true);
		
				$update = array();
				$update['accounting'] = addslashes(serialize($order['shipping_groups'][$m_id]['accounting']));
				$update['profit_margin'] = $order['shipping_groups'][$m_id]['profit_margin'];
				$update['acc_paymentid'] = $v['paymentid'];

				func_log_order_groups($update, $orderid, $m_id, 'X', $login);

				func_array2update("order_groups", $update ,"orderid='$orderid' AND manufacturerid='$m_id'");

		                // Change the order group status
				
		                if (
                		    $acc_zero_data[ACC_REF_TO_US]
		                    && $acc_new_data[ACC_REF_TO_US]
                		) {
		                    func_change_order_group_status($orderid, $m_id, 'Z');
                		} elseif ($acc_zero_data[ACC_COST_TO_US]
		                    && $acc_new_data[ACC_COST_TO_US]
		                ) {
                		    func_change_order_group_status($orderid, $m_id, 'X');
		                }
                
		                if (
                		    $acc_zero_data[ACC_REF_TO_CUST]
		                    && $acc_new_data[ACC_REF_TO_CUST]
		                ) {
		                    func_change_order_group_status($orderid, $m_id, 'R');
                		}
			}
		}	
		func_header_location("order.php?orderid=$orderid");
	}

}

#$all_shippings = func_query("SELECT shippingid, shipping FROM $sql_tbl[shipping] WHERE active='Y' ORDER BY orderby");
#$smarty->assign("all_shippings", $all_shippings);

if (empty($order["refund_groups"]) && is_array($order["refund_groups"])){
	unset($order["refund_groups"]);
}

$smarty->assign("order", $order);

?>
