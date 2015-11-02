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
/*
		if (!empty($distributors_to_delete) && is_array($distributors_to_delete)){
			foreach ($distributors_to_delete as $k => $v){
				if ($v["delete"] == "Y"){
					db_query("DELETE FROM $sql_tbl[order_groups] WHERE orderid='$orderid' AND manufacturerid='$k'");
					unset($groups[$k]);
					unset($_POST["groups"][$k]);
				}
			}
		}
*/

		$tmp_mnfs = func_get_order_manufacturers($orderid);

                if ($po_update == "1"){
			if (
				$name_of_purchaser != "unknown" &&
				$accounts_payable_full_name != "unknown" &&
                                $purchase_manager_email != "unknown@unknown.com" &&
                                $accounts_payable_email != "unknown@unknown.com" &&
                                $customer_info["email"] != "unknown@unknown.com" &&
                                $purchase_manager_phone != "(000) 000-0000" &&
                                $po_fax != "(000) 000-0000" &&
                                $accounts_payable_phone != "(000) 000-0000" &&
                                $accounts_payable_fax != "(000) 000-0000" &&
                                $customer_info["phone"] != "(000) 000-0000" &&
				$total_shipping_charge_on_orig_po > 0 &&
				$po_issued_to != "A" &&
				$po_issued_to != "" &&
				$orig_po != ""
			){
				$new_cb_status = "O";

				if (!empty($order["shipping_groups"]) && is_array($order["shipping_groups"])){
					foreach ($order["shipping_groups"] as $k_sg => $v_sg) {
						$current_cb_status = $v_sg["cb_status"];
						if ($current_cb_status == "IO"){
							$order["shipping_groups"][$k_sg]["cb_status"] = $new_cb_status;
						}
					}
				}
			}
			else {
                                if (!empty($order["shipping_groups"]) && is_array($order["shipping_groups"])){
					$new_cb_status = "IO";

                                        foreach ($order["shipping_groups"] as $k_sg => $v_sg) {
                                                $current_cb_status = $v_sg["cb_status"];
                                                if ($current_cb_status == "O"){
                                                        $order["shipping_groups"][$k_sg]["cb_status"] = $new_cb_status;
                                                }
                                        }
                                }
			}
                }
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

//func_print_r($ref_values, $cart_tmp['products'][$k]);
//die();

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
                                                . ' WHERE itemid="' . $itemid . '" AND orderid="' . $orderid . '"');

/*
	       		    	        $v['amount'] = func_query_first_cell('SELECT amount FROM ' . $sql_tbl['order_details']
			                        . ' WHERE productid="' . $productid . '" AND orderid="' . $orderid . '"');
*/
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



#
##
###
                                if ($groups[$tmp_manufacturerid]["cb_status"] == "P" || $groups[$tmp_manufacturerid]["cb_status"] == "3" || $groups[$tmp_manufacturerid]["cb_status"] == "V" || $groups[$tmp_manufacturerid]["cb_status"] == "H" || $groups[$tmp_manufacturerid]["cb_status"] == "R"){

                                        if ($v["price"] != $cart_tmp["products"][$k]["price"]){
                                                $v["price"] = $cart_tmp["products"][$k]["price"];

                                                $top_message["content"] = func_get_langvar_by_name("txt_shipping_cost_net_not_saved");
                                                $top_message["type"] = "I";
                                                $section_name_top_message = $top_message;
                                                x_session_save("section_name_top_message");
                                        }

                                        if (strpos($v["amount"], "r") === false && (strpos($v["amount"], "R")) === false && $v["amount"] != $cart_tmp["products"][$k]["amount"]){
                                                $v["amount"] = $cart_tmp["products"][$k]["amount"];

                                                $top_message["content"] = func_get_langvar_by_name("txt_shipping_cost_net_not_saved");
                                                $top_message["type"] = "I";
                                                $section_name_top_message = $top_message;
                                                x_session_save("section_name_top_message");
                                        }
                                }
###
##
#


				# Check if product is out of stock
				$count_product_in_stock = func_oe_get_quantity_in_stock($productid, $order_data['order']['cb_status'], $order_data['order']['dc_status'], $v['product_options'], @$order_data["products"][$k]);
				if ($v["amount"] > 0) {
/*
					if ($config["General"]["unlimited_products"] == "Y"|| $v["amount"] <= $count_product_in_stock) {
						$cart_tmp["products"][$k]["amount"] = $v["amount"];
					} elseif ($cart_tmp["products"][$k]["amount"] > $count_product_in_stock && $count_product_in_stock > 0) {
						$cart_tmp["products"][$k]["amount"] = $count_product_in_stock;
					}
*/
					$cart_tmp["products"][$k]["amount"] = $v["amount"];
				}

				$v["price"] = preg_replace("/[^0-9\.]/S","", $v["price"]);
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

//func_print_r($v);
//die();

                                if (!empty($v["additional_shipping_status"])){

					$current_additional_shipping_status = $order['shipping_groups'][$m_id]['additional_shipping_status'];
					$new_additional_shipping_status = $v["additional_shipping_status"];

	                                if ((!empty($v["additional_vt_paymentid"]) || $v["cb_status"] == "O") && $new_additional_shipping_status == "P"){

						$save_additional_vt = true;

						if (empty($v["additional_transaction_id_link"])){
	        	                                $m_id_payment_transaction_id_link = func_query_first_cell("SELECT transaction_id_link FROM $sql_tbl[payment_methods] WHERE paymentid='$v[additional_vt_paymentid]'");
        	        	                        if (!empty($m_id_payment_transaction_id_link)){
                	        	                        $new_additional_shipping_status = $current_additional_shipping_status;
								$save_additional_vt = false;
                        	        	        }
						}

						if ($save_additional_vt || $v["cb_status"] == "O"){


#
## https://basecamp.com/2070980/projects/1577907/messages/31184034
###
							if ($log != "") $log .= "<br />";
//							$additional_ship_charge = $v["actual_shipping_cost_net"] * $config["Additional_shipping_charge"]["required_shipping_charge_k"] - $v["shipping_cost_net"];
							$log .= "<B>".$code.": </B>Additional shipping charge: ".$tmp_mnfs[$m_id]["additional_shipping_charge"];
//							$log .= "<B>".$code.": </B>Additional shipping charge: ".$additional_ship_charge;

                                                        $order["shipping_groups"][$m_id]["total"]["net"] += $tmp_mnfs[$m_id]["additional_shipping_charge"];
                                                        $order["shipping_groups"][$m_id]["total"]["gross"] += $tmp_mnfs[$m_id]["additional_shipping_charge"];
                                                        $groups[$m_id]["total"]["net"] += $tmp_mnfs[$m_id]["additional_shipping_charge"];
                                                        $groups[$m_id]["total"]["gross"] += $tmp_mnfs[$m_id]["additional_shipping_charge"];

                                                        $order["shipping_groups"][$m_id]["accounting"][0]["net"] += $tmp_mnfs[$m_id]["additional_shipping_charge"];
                                                        $order["shipping_groups"][$m_id]["accounting"][0]["gross"] += $tmp_mnfs[$m_id]["additional_shipping_charge"];

                                                        $order["shipping_groups"][$m_id]["accounting"][5]["net"] += $tmp_mnfs[$m_id]["additional_shipping_charge"];
                                                        $order["shipping_groups"][$m_id]["accounting"][5]["gross"] += $tmp_mnfs[$m_id]["additional_shipping_charge"];

                                                        $groups[$m_id]["accounting"][0]["net"] += $tmp_mnfs[$m_id]["additional_shipping_charge"];
                                                        $groups[$m_id]["accounting"][0]["gross"] += $tmp_mnfs[$m_id]["additional_shipping_charge"];

                                                        $groups[$m_id]["accounting"][5]["net"] += $tmp_mnfs[$m_id]["additional_shipping_charge"];
                                                        $groups[$m_id]["accounting"][5]["gross"] += $tmp_mnfs[$m_id]["additional_shipping_charge"];

###
##
#



//							if ($v["shipping_value_selectbox"] == "actual_shipping_cost"){

								$v["shipping_cost_net"] += $tmp_mnfs[$m_id]["additional_shipping_charge"];
								$groups[$m_id]["shipping_cost_net"] = $v["shipping_cost_net"];
								$order["shipping_groups"][$m_id]["shipping_cost_net"] = $v["shipping_cost_net"];
/*
								$v["actual_shipping_cost_net"] = 0;
								$groups[$m_id]["actual_shipping_cost_net"] = $v["actual_shipping_cost_net"];
								$order["shipping_groups"][$m_id]["actual_shipping_cost_net"] = $v["actual_shipping_cost_net"];
*/

//							}



                                                        if ($v["additional_transaction_id_link"] != $order['shipping_groups'][$m_id]['additional_transaction_id_link']){
                                                                $payment_transaction_id_link = func_query_first_cell("SELECT transaction_id_link FROM $sql_tbl[payment_methods] WHERE paymentid='".$v["additional_vt_paymentid"]."'");
                                                                if (!empty($payment_transaction_id_link)){
                                                                        $payment_transaction_id_link = str_replace("{{trans-id}}", $v["additional_transaction_id_link"], $payment_transaction_id_link);
									if ($log != "") $log .= "<br />";
                                                                        $log .= "<a href='".$payment_transaction_id_link."' target='_blank' style='color: #1411FF;'>Link to ".$vt_paymentid_name." virtual terminal transaction</a>";
                                                                }

                                                        }

                                                        if ($v["additional_vt_paymentid"] != $order['shipping_groups'][$m_id]['additional_vt_paymentid']){

                                                                $current_vt_paymentid_name = func_query_first_cell("SELECT payment_method FROM $sql_tbl[payment_methods] WHERE paymentid='".$order['shipping_groups'][$m_id]['additional_vt_paymentid']."'");
                                                                $vt_paymentid_name = func_query_first_cell("SELECT payment_method FROM $sql_tbl[payment_methods] WHERE paymentid='$v[additional_vt_paymentid]'");
								if ($log != "") $log .= "<br />";
                                                                $log .= "<B>".$code.": </B>"."Shipping payment method: " . $current_vt_paymentid_name . " -> " . $vt_paymentid_name;
                                                        }

							if ($v["additional_transaction_id_link"] != $order['shipping_groups'][$m_id]['additional_transaction_id_link']){
								if ($log != "") $log .= "<br />";
								$log .= "<B>".$code.": </B>"."Shipping transaction ID:".$order['shipping_groups'][$m_id]['additional_transaction_id_link']. " -> ".$v["additional_transaction_id_link"];
							}

                                                        if ($v["additional_avs_code"] != $order['shipping_groups'][$m_id]['additional_avs_code']){
								if ($log != "") $log .= "<br />";

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

### https://basecamp.com/2070980/projects/1577907/messages/33784697

				if ($v["cb_status"] == "P" || $v["cb_status"] == "3" || $v["cb_status"] == "V" || $v["cb_status"] == "H" || $v["cb_status"] == "R"){
					if (
						($order["shipping_groups"][$m_id]["shipping_cost"]["net"] != $v["shipping_cost_net"]) 
						&& ((strpos($v["shipping_cost_net"], "r")) === false 
						&& (strpos($v["shipping_cost_net"], "R")) === false)
						&& empty($v["additional_shipping_status"])
					){
						$v["shipping_cost_net"] = $order["shipping_groups"][$m_id]["shipping_cost"]["net"];

	        	                        $top_message["content"] = func_get_langvar_by_name("txt_shipping_cost_net_not_saved");
        	        	                $top_message["type"] = "I";
						$section_name_top_message = $top_message;
						x_session_save("section_name_top_message");
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

					if ($save_additional_vt){
						$set_type = "S";
					} else {
						$set_type = "X";
					}

                                        func_log_order($orderid, $set_type, $log, $login);
                                }
                                ### LOG: END

				$v["actual_shipping_cost_net"] = preg_replace("/[^0-9\.]/S","", $v["actual_shipping_cost_net"]);


				$actual_shipping_gross = $v["actual_shipping_cost_net"];

				if ($order['shipping_groups'][$m_id]['all_distributor_info']['d_drop_ship_fee_select'] == "applies_to_all_orders"){
					if (!empty($order['shipping_groups'][$m_id]['all_distributor_info']['d_drop_ship_fee_in_us'])){
						$actual_shipping_gross += $order['shipping_groups'][$m_id]['all_distributor_info']['d_drop_ship_fee_in_us'];
					}
				}
				elseif ($order['shipping_groups'][$m_id]['all_distributor_info']['d_drop_ship_fee_select'] == "applies_to_orders_below_minimum_order_amount_only"){
					if (!empty($order['shipping_groups'][$m_id]['all_distributor_info']['d_drop_ship_fee_in_us'])){

						$sum_cost_to_us = 0;

						if (!empty($order['shipping_groups'][$m_id]['products']) && is_array($order['shipping_groups'][$m_id]['products'])){
							foreach ($order['shipping_groups'][$m_id]['products'] as $v_pr){
								$sum_cost_to_us += $v_pr["cost_to_us"];
							}
						}

						if ($sum_cost_to_us < $order['shipping_groups'][$m_id]['all_distributor_info']['d_minimum_order_amount_in_us'] && $order['shipping_groups'][$m_id]['all_distributor_info']['d_minimum_order_amount_in_us'] > 0){
							$actual_shipping_gross += $order['shipping_groups'][$m_id]['all_distributor_info']['d_drop_ship_fee_in_us'];
						}
					}
				}

				db_query("UPDATE $sql_tbl[order_groups] SET actual_shipping_net='$v[actual_shipping_cost_net]', actual_shipping_gross='$actual_shipping_gross', shipping_value_selectbox='$v[shipping_value_selectbox]' WHERE orderid='$orderid' AND manufacturerid='$m_id'");
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
				$order['shipping_groups'][$m_id]['po_status'] = $v['po_status'];

				if (empty($order["shipping_groups"][$m_id]["tracking"])) {
					$tracking = array();
				} else {
					$tracking = $order["shipping_groups"][$m_id]["tracking"];
				}
				if (!is_array($tracking)) {
					$tracking = array();
				}

				$add_tracking_log = false;
				$log = "<B>Tracking numbers:</B><br /><B>Added:</B><br />";
//				foreach ($v["tracking_shipper"] as $_k => $sh) {
				foreach ($v["tracking_carrier"] as $_k => $sh) {
					if (!empty($v["tracking_carrier"][$_k])) {

                                                if (!isset($v["tracking_shipper"][$_k])){
	                                                $linkid = 0;
                                                } else {
                                                        $linkid = $v["tracking_shipper"][$_k];
                                                }

						$tracking[] = array('linkid' => $linkid, 'tracknum' => trim($v["tracking_number"][$_k]), 'ship_date' => trim($v["tracking_ship_date"][$_k]), 'carrier_id' => $sh);
						$order['shipping_groups'][$m_id]['dc_status'] = 'S';
						define('TRACKING_ADDED', 1);
				
						if (!empty($linkid)){
							$shipping_link = func_query_first_cell("SELECT shipping FROM $sql_tbl[tracking_links] WHERE linkid='$linkid'");
						} else {
							$shipping_link = "";
						}

						$carrier = func_query_first_cell("SELECT carrier FROM $sql_tbl[tracking_links_carrier] WHERE carrier_id='$sh'");

						$log .= $carrier. " " .$shipping_link.": ".trim($v["tracking_number"][$_k])."<br />";
						$add_tracking_log = true;
					}
				}
				$order["shipping_groups"][$m_id]['tracking'] = $tracking;

				if ($add_tracking_log){
					func_log_order($orderid, 'X', $log, $login);
				}
				$log = "";

###
                                if ($v['dc_status'] == "C" || $v['dc_status'] == "L"){
					$current_dc_status = func_query_first_cell("SELECT dc_status FROM $sql_tbl[order_groups] WHERE manufacturerid='$m_id' AND orderid='$orderid'");
					if ($current_dc_status != $v['dc_status']){

						if ($v['dc_status'] == "C"){

							$addition_column = "";
						
							$current_dc_dispatched_time = func_query_first_cell("SELECT dc_dispatched_time FROM $sql_tbl[order_groups] WHERE manufacturerid='$m_id' AND orderid='$orderid'");
							if (empty($current_dc_dispatched_time)){
								 $addition_column = ", dc_dispatched_time='".time()."'";
							}

							$time_to_dispatch = time() - $order["date"];

		                                        db_query("UPDATE $sql_tbl[order_groups] SET time_to_dispatch='$time_to_dispatch' $addition_column WHERE manufacturerid='$m_id' AND orderid='$orderid'");

						}

//						if ($current_dc_status == "E" && $v['dc_status'] == "L" && $cart_tmp["shipping_groups"][$m_id]["all_distributor_info"]["submit_to_operator"] == "through_distributor_website")
						if ($v['dc_status'] == "L"){
							$current_dc_received_by_distributor_time = func_query_first_cell("SELECT dc_received_by_distributor_time FROM $sql_tbl[order_groups] WHERE manufacturerid='$m_id' AND orderid='$orderid'");

							if (empty($current_dc_received_by_distributor_time)){
								db_query("UPDATE $sql_tbl[order_groups] SET dc_received_by_distributor_time='".time()."' WHERE manufacturerid='$m_id' AND orderid='$orderid'");
							}
						}
					}
                                }
###
			}

#
## 11.04.2014
###
			if (!empty($order["shipping_groups"]) && is_array($order["shipping_groups"])){
				$tracking_in_all_distrs = true;
				foreach ($order["shipping_groups"] as $ko => $vo){
					if (empty($vo["tracking"]) || !is_array($tracking)){
						$tracking_in_all_distrs = false;
						break;
					}
				}

				$current_tracking_all_filled = func_query_first_cell("SELECT tracking_all_filled FROM $sql_tbl[orders] WHERE orderid='$orderid'");

				if ($tracking_in_all_distrs){
					if ($current_tracking_all_filled != "Y"){
						db_query("UPDATE $sql_tbl[orders] SET tracking_all_filled='Y', tracking_fill_time='".time()."' WHERE orderid='$orderid'");
					}
				} else {
					if ($current_tracking_all_filled == "Y"){
						db_query("UPDATE $sql_tbl[orders] SET tracking_all_filled='N' WHERE orderid='$orderid'");
					}
				}
			}
###
##
#

		}


//func_print_r($_POST, $groups, $order);
//die();

		$operator_login = $login;

#
##
###
		if (!empty($add_productcode) && is_array($add_productcode)) {
			foreach ($add_productcode as $kkk => $sku) {
				if (empty($sku)){
					unset($add_productcode[$kkk]);
				}
			}
		}
###
##
#

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


                                global $add_from_order_edit;
                                $add_from_order_edit = true;

				$prd = func_select_product($newproductid, $customer_membershipid, false, false, true);

###
				if (!empty($order["shipping_groups"][$prd["manufacturerid"]]["cb_status"]) && ($order["shipping_groups"][$prd["manufacturerid"]]["cb_status"] == "P" || $order["shipping_groups"][$prd["manufacturerid"]]["cb_status"] == "3" || $order["shipping_groups"][$prd["manufacturerid"]]["cb_status"] == "V" || $order["shipping_groups"][$prd["manufacturerid"]]["cb_status"] == "H" || $order["shipping_groups"][$prd["manufacturerid"]]["cb_status"] == "R")){
                                        $top_message["content"] = func_get_langvar_by_name("txt_product_was_not_added");
                                        $top_message["type"] = "I";
                                        $section_name_top_message = $top_message;
                                        x_session_save("section_name_top_message");

					continue;
				}

###
				if (!empty($prd)) {

#
##
###
	                                $log = "<B>Add product:</B> ".$sku." x ".$amount;
        	                        func_log_order($orderid, 'X', $log, $operator_login);
###
##
#


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

						$skip_product = true;

						if (!empty($prd["eta_date_mm_dd_yyyy"]) && $prd["eta_date_in_future"] == "Y"){
							$skip_product = false;
						}

						if ($skip_product){
							continue;
						}
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

###
                               $top_message["content"] = func_get_langvar_by_name("txt_do_not_forget_re_calculate_shipping");
                               $top_message["type"] = "I";
                               $section_name_top_message = $top_message;
                               x_session_save("section_name_top_message");

###
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

		if (!empty($add_additional_fee_name) && is_array($add_additional_fee_name) && !empty($add_additional_fee_value) && is_array($add_additional_fee_value) && !empty($order["shipping_groups"]) && is_array($order["shipping_groups"])){

			$allow_to_add_fee = false;
			foreach ($order["shipping_groups"] as $k_manufacturerid => $v_m_info){

				if (!empty($v_m_info["cb_status"])){
					if (!($v_m_info["cb_status"] == "P" || $v_m_info["cb_status"] == "3" || $v_m_info["cb_status"] == "V" || $v_m_info["cb_status"] == "H" || $v_m_info["cb_status"] == "R")){
						$allow_to_add_fee = true;
						break;
					}
				}
			}

			if ($allow_to_add_fee){
			 foreach ($add_additional_fee_name as $k => $v){
			    $v = trim($v);
			    if (!empty($v)){
				$add_price = price_format($add_additional_fee_value[$k]);
				$additional_fee_row["additional_fee_name"] = $v;
				$additional_fee_row["additional_fee_value"] = $add_price;
				$additional_fee[] = $additional_fee_row;
				$log .= $v . " $".$add_price. " - Added <br />";

//				db_query("INSERT INTO $sql_tbl[order_additional_fee] (orderid, additional_fee_name, additional_fee_value) VALUES ('$orderid', '".addslashes($v)."', '$add_price')");
				db_query("INSERT INTO $sql_tbl[order_additional_fee] (orderid, additional_fee_name, additional_fee_value) VALUES ('$orderid', '".$v."', '$add_price')");
			    }
			 }
			} else {

				foreach ($add_additional_fee_name as $k => $v){
					$v = trim($v);
					if (!empty($v)){
		                                $top_message["content"] = func_get_langvar_by_name("txt_product_was_not_added");
                		                $top_message["type"] = "I";
                                		$section_name_top_message = $top_message;
		                                x_session_save("section_name_top_message");
						break;
					}
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

//				db_query("UPDATE $sql_tbl[order_additional_fee] SET additional_fee_name='".addslashes($v["additional_fee_name"])."', additional_fee_value='$add_price' WHERE id='$k'");
				db_query("UPDATE $sql_tbl[order_additional_fee] SET additional_fee_name='".$v["additional_fee_name"]."', additional_fee_value='$add_price' WHERE id='$k'");
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

#
##
###
				$purchase_manager_phone_ext_flag_found = false;
				foreach ($data as $i => $line) {
					if (($a = strpos($line, "purchase manager phone ext:")) !== false) {
						$purchase_manager_phone_ext_flag_found = true;
						break;
					}
				}

                                $accounts_payable_phone_ext_flag_found = false;
                                foreach ($data as $i => $line) {
                                        if (($a = strpos($line, "accounts payable phone ext:")) !== false) {
                                                $accounts_payable_phone_ext_flag_found = true;
                                                break;
                                        }
                                }

				if (!$purchase_manager_phone_ext_flag_found){
					$data[] = "purchase manager phone ext:";
				}

                                if (!$accounts_payable_phone_ext_flag_found){
                                        $data[] = "accounts payable phone ext:";
                                }
###
##
#

				$po_fields = array("PO Number" => $po_number, "Company name" => $po_company_name, "Name of purchaser" => $name_of_purchaser, "Position" => $po_position, "po fax" => $po_fax, "accounts payable full name" => $accounts_payable_full_name, "accounts payable phone" => $accounts_payable_phone, "accounts payable fax" => $accounts_payable_fax, "accounts payable email" => $accounts_payable_email, "purchase manager phone" => $purchase_manager_phone, "purchase manager email" => $purchase_manager_email, "accounts payable phone ext" => $accounts_payable_phone_ext, "purchase manager phone ext" => $purchase_manager_phone_ext);


//func_print_r($data, $po_fields);
//die();

				$order["po_details"] = array();
				foreach ($data as $i => $line) {
					if (empty($po_fields)) {
						break;
					}
					foreach ($po_fields as $k => $po_text) {
						if (($a = strpos($line, $k.":")) !== false) {
							$data[$i] = "$k: $po_text"; 
							break;
						}
					}
				}
//func_print_r($data);
//die();

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
//                if ($all_groups_cb_status_eq_P && $groups_cb_status_eq_P_found && !empty($groups) && is_array($groups)) {
                if (!empty($groups) && is_array($groups)) {


			$new_groups = array();

			foreach ($groups as $m_id => $v){

				if (!empty($vt_paymentid)){
					$new_groups[$m_id]["paymentid"] = $vt_paymentid;
				}
/*
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
*/
				$new_groups[$m_id]["acc"] = $order['shipping_groups'][$m_id]['accounting'];

			}

			$groups = $new_groups;


//func_print_r($groups[7], $order['shipping_groups'][7]["accounting"]);
//die("==");

                        $applied_per_trans_payments = array();
                        foreach ($groups as $m_id => $v) {

				if (isset($v['paymentid'])){
	                                $order['shipping_groups'][$m_id]['acc_paymentid'] = $v['paymentid'];
				}

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

###
				if (!isset($v['paymentid']) && !isset($v['acc_paymentid']) && isset($order['shipping_groups'][$m_id]["acc_paymentid"])){
                                        if ($apply_per_trans = !in_array($order['shipping_groups'][$m_id]["acc_paymentid"], $applied_per_trans_payments)) {
                                                $applied_per_trans_payments[] = $order['shipping_groups'][$m_id]["acc_paymentid"];
                                        }
				} else {
###
	                                if ($apply_per_trans = !in_array($v['paymentid'], $applied_per_trans_payments)) {
        	                                $applied_per_trans_payments[] = $v['paymentid'];
                	                }
				}

//func_print_r($order['shipping_groups'][7]["accounting"]);
//die();

                                func_recalculate_accounting($order['shipping_groups'][$m_id], $all_processors, $apply_per_trans, true);
/*
if ($m_id == "12"){
func_print_r($order['shipping_groups'][$m_id], $apply_per_trans, $applied_per_trans_payments, $v['acc_paymentid'], $v);
die();
}
*/


                                $update = array();


//                                $update['accounting'] = addslashes(serialize($order['shipping_groups'][$m_id]['accounting']));
###
				$update = func_add_accounting_fields($update, '', '', '', "order_groups", $order['shipping_groups'][$m_id]['accounting']);
###
                                $update['profit_margin'] = $order['shipping_groups'][$m_id]['profit_margin'];

				if (isset($v['paymentid'])){
	                                $update['acc_paymentid'] = $v['paymentid'];
				}

                                func_log_order_groups($update, $orderid, $m_id, 'X', $login);


//func_print_r($update);
//die();

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

			$send_email_flag = false;

			$current_cb_dc_statuses = func_query("SELECT cb_status, dc_status FROM $sql_tbl[order_groups] WHERE orderid='$orderid'");

			if (!empty($current_cb_dc_statuses) && is_array($current_cb_dc_statuses)){
				foreach ($current_cb_dc_statuses as $kc => $vc){

					$order_notification_enabled = func_query_first_cell("SELECT enabled FROM $sql_tbl[order_status_notifications] WHERE code='$vc[cb_status]'");

					if ($order_notification_enabled == "Y"){
						$send_email_flag = true;
						break;
					} else {
	                                        $order_notification_enabled = func_query_first_cell("SELECT enabled FROM $sql_tbl[order_status_notifications] WHERE code='$vc[dc_status]'");
                        	                if ($order_notification_enabled == "Y"){
                	                                $send_email_flag = true;
        	                                        break;
	                                        }
					}
				}
			}

			if ($send_email_flag){
	                        include $xcart_dir . '/include/send_order_email.php';
			}
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


                        $order = func_order_data($orderid);
                        $mail_smarty->assign("products", $order['products']);
                        $mail_smarty->assign("order", $order['order']);
                        $mail_smarty->assign("userinfo", $order['userinfo']);

                        $attach_pdf_invoice = "Y";
                        $mail_smarty->assign("attach_pdf_invoice", $attach_pdf_invoice);

###
		        $signature = func_get_signature(false, $order['products']);
		        $po_instructions = str_replace("{{signature}}", $signature, $po_instructions);

		        $firstname = trim($order['userinfo']['firstname']);
		        $c_firstname_arr = explode(" ", $firstname);
		        $c_firstname = array_pop($c_firstname_arr);
//		        $po_instructions = str_replace("{{userfirstname}}", $c_firstname, $po_instructions);
		        $po_instructions = str_replace("{{userfirstname}}", $userfirstname, $po_instructions);
		        $po_instructions = str_replace("{{userfullname}}", $userfullname, $po_instructions);


                        $po_instructions_subject_line = str_replace("{{signature}}", $signature, $po_instructions_subject_line);
//                        $po_instructions_subject_line = str_replace("{{userfirstname}}", $c_firstname, $po_instructions_subject_line);
                        $po_instructions_subject_line = str_replace("{{userfirstname}}", $userfirstname, $po_instructions_subject_line);
                        $po_instructions_subject_line = str_replace("{{userfullname}}", $userfullname, $po_instructions_subject_line);
###


                        $mail_smarty->assign("po_instructions_subject_line", $po_instructions_subject_line);
                        $mail_smarty->assign("po_instructions", $po_instructions);

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
	elseif ( (($mode == "accounting_apply" && $user_account["flag"] != "FS") || $mode == "table_accounting_apply") && !empty($certain_mid) ) {

#
##
###
/*
	    if ($mode == "accounting_apply" && $user_account["flag"] != "FS"){
		if (!empty($invoice_field) && is_array($invoice_field)){
			foreach ($invoice_field as $itemid => $v){

		
				foreach ($order["shipping_groups"] as $m_id => $g_v)	{
					if (!empty($g_v["products"]) && is_array($g_v["products"])){
						foreach ($g_v["products"] as $k_p => $v_p) {

							if ($v_p["manufacturerid"] != $certain_mid){
								continue;
							}

							if ($v_p["itemid"] == $itemid && ($v["qty_inv"] != $v_p["qty_inv"] || $v["unit_cost"] != $v_p[unit_cost])){

								if ($v["qty_inv"] != $v_p["qty_inv"]){
					                                $log = "<B>".$g_v["code"].":</B> QTY INVOICED: ".$v_p["qty_inv"]." -> ".$v["qty_inv"];
        	        				                func_log_order($orderid, 'X', $log, $login);
								}

                                                                if ($v["unit_cost"] != $v_p["unit_cost"]){
                                                                        $log = "<B>".$g_v["code"].":</B> Unit cost: ".$v_p["unit_cost"]." -> ".$v["unit_cost"];
                                                                        func_log_order($orderid, 'X', $log, $login);
                                                                }

								db_query("UPDATE $sql_tbl[order_details] SET qty_inv='$v[qty_inv]', unit_cost='$v[unit_cost]' WHERE itemid='$itemid'");
							}

							if ($v_p["itemid"] == $itemid){
								if (!isset($all_product_total_extended1_arr[$v_p["manufacturerid"]])){
									$all_product_total_extended1_arr[$v_p["manufacturerid"]] = 0.00;
								}
								$all_product_total_extended1_arr[$v_p["manufacturerid"]] += $v["qty_inv"]*$v["unit_cost"];
							}
						}
					}

				}
			}
		}
	    }
*/
###
##
#



//func_print_r($groups);
//die();


		if (!empty($groups)) {
			$tracking_in_all_distrs = true;
			$applied_per_trans_payments = array();
			foreach ($groups as $m_id => $v) {

			    if ($m_id != $certain_mid){
				continue;
			    }
#
##
###
			    if ($mode == "accounting_apply" && $user_account["flag"] != "FS"){

				if (!empty($tracknums[$m_id]) && is_array($tracknums[$m_id])){

					$tmp_tracknums = array();
					$tmp_tracknums_counter = 0;
					
					foreach ($tracknums[$m_id] as $invoice_number => $v_tracknums){
						if (!empty($v_tracknums) && is_array($v_tracknums)){
							foreach ($v_tracknums as $row_conter => $vv_tracknums){

								$tmp_tracknums[$tmp_tracknums_counter] = $vv_tracknums;
								$tmp_tracknums[$tmp_tracknums_counter]["invoice_number"] = $invoice_number;

								$tmp_tracknums_counter++;
							}
						}
					}

//					$tmp_tracknums_counter = count($tracknums[$m_id]);
//					$tracknums[$m_id] = array_values($tracknums[$m_id]);
					$tracknums[$m_id] = $tmp_tracknums;
				}
				else {
					$tmp_tracknums_counter = 0;
				}


				$Tracking_number_Added_flag = false;

//func_print_r($v["tracking_shipper"], $v["tracking_number"], $v["tracking_carrier"]);
//die();

//				if (!empty($v["tracking_shipper"]) && is_array($v["tracking_shipper"])){
				if (!empty($v["tracking_carrier"]) && is_array($v["tracking_carrier"])){

//				 foreach ($v["tracking_shipper"] as $invoice_number => $v_tracking_shipper){
				 foreach ($v["tracking_carrier"] as $invoice_number => $v_tracking_carrier){
//				  if (!empty($v_tracking_shipper) && is_array($v_tracking_shipper)){
				  if (!empty($v_tracking_carrier) && is_array($v_tracking_carrier)){

					$add_tracking_log = false;
	                                $log = "<B>Tracking numbers:</B><br /><B>Added:</B><br />";
//        	                        foreach ($v_tracking_shipper as $_k => $sh) {
        	                        foreach ($v_tracking_carrier as $_k => $sh) {
//                	                        if (!empty($sh) && func_check_tracking_number($sh, trim($v["tracking_number"][$invoice_number][$_k]))) {
                	                        if (!empty($sh)) {

							if (!isset($v["tracking_shipper"][$invoice_number][$_k])){
								$linkid = 0;
							} else {
								$linkid = $v["tracking_shipper"][$invoice_number][$_k];
							}

                        	                        $tracknums[$m_id][$tmp_tracknums_counter] = array('linkid' => $linkid, 'tracknum' => trim($v["tracking_number"][$invoice_number][$_k]), 'invoice_number' => $invoice_number, 'ship_date' => trim($v["tracking_ship_date"][$invoice_number][$_k]), 'carrier_id' => $sh);
							$tmp_tracknums_counter++;
//                                	                $order['shipping_groups'][$m_id]['dc_status'] = 'S';
//                                        	        define('TRACKING_ADDED', 1);

							if (!empty($linkid)){
		                                                $shipping_link = func_query_first_cell("SELECT shipping FROM $sql_tbl[tracking_links] WHERE linkid='$linkid'");
							}
							else {
								$shipping_link = "";
							}

							$carrier = func_query_first_cell("SELECT carrier FROM $sql_tbl[tracking_links_carrier] WHERE carrier_id='$sh'");
        	                                        $log .= "invoice_number_".$invoice_number. ": " . $carrier . " " . $shipping_link.": ".trim($v["tracking_number"][$invoice_number][$_k])."<br />";
                	                                $add_tracking_log = true;
							$Tracking_number_Added_flag = true;
                        	                }
                                	}

//func_print_r($v["tracking_shipper"], $v["tracking_number"], $tracknums);
//die();


	                                if ($add_tracking_log){

                                        	$current_dc_status = func_query_first_cell("SELECT dc_status FROM $sql_tbl[order_groups] WHERE manufacturerid='$m_id' AND orderid='$orderid'");
                                        	if ($current_dc_status != "S"){

							$current_dc_status_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='$current_dc_status'");
			                                $new_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='S'");
                        			        $log .= "<B>".$code.":</B> dc_status: ". $current_dc_status_value . " -> ". $new_value;

							db_query("UPDATE $sql_tbl[order_groups] SET dc_status='S' WHERE orderid = '$orderid' AND manufacturerid='$m_id'");
						}


        	                                func_log_order($orderid, 'X', $log, $login);
                	                }
                        	        $log = "";
				  }
				 }
				}


	                        $current_trackings = func_query_first_cell("SELECT tracking FROM $sql_tbl[order_groups] WHERE manufacturerid='$m_id' AND orderid='$orderid'");
	                        $current_trackings = unserialize($current_trackings);
	                        if (empty($current_trackings) || !is_array($current_trackings))
        	                        $current_trackings = array();

	                        $log = "<B>Tracking numbers:</B><br />";

        	                $current_trackings_for_diff = array();
	                        if (!empty($current_trackings) && is_array($current_trackings)){
                	                $log .= "<B>Before:</B><br />";
        	                        foreach ($current_trackings as $kk => $vv){
						$invoice_number = $vv["invoice_number"];
						if (empty($invoice_number)){
							$invoice_number = 1;
						}
/*
	                                        $shipping_link_info = func_query_first("SELECT shipping, carrier_id FROM $sql_tbl[tracking_links] WHERE linkid='$vv[linkid]'");
	                                        $shipping_link = $shipping_link_info["shipping"];
						$carrier_id = $shipping_link_info["carrier_id"];
*/
						$shipping_link = $vv["shipping"];
						$carrier_id = $vv["carrier_id"];
						$carrier = func_query_first_cell("SELECT carrier FROM $sql_tbl[tracking_links_carrier] WHERE carrier_id='$carrier_id'");

                                        	$current_tracking_str = "invoice_number_".$invoice_number. ": " . $carrier . " " . $shipping_link.": ".$vv["tracknum"];
                                	        $log .= $current_tracking_str."<br />";
                        	                $current_trackings_for_diff[] = $current_tracking_str;
                	                }
        	                }

	                        db_query("UPDATE $sql_tbl[order_groups] SET tracking='' WHERE manufacturerid='$m_id' AND orderid='$orderid'");

                	        $tracknums_to_db = array();
        	                $trackings_for_diff = array();
	                        if (!empty($tracknums[$m_id]) && is_array($tracknums[$m_id])){
                	                $tracknums_to_db_index = 0;
        	                        $log .= "<B>Now:</B><br />";
	                                foreach ($tracknums[$m_id] as $kk => $vv){
                        	                if (!empty($vv["carrier_id"])){
                	                                $tracknums_to_db[$tracknums_to_db_index]["linkid"] = $vv["linkid"];
        	                                        $tracknums_to_db[$tracknums_to_db_index]["tracknum"] = $vv["tracknum"];
        	                                        $tracknums_to_db[$tracknums_to_db_index]["invoice_number"] = $vv["invoice_number"];
        	                                        $tracknums_to_db[$tracknums_to_db_index]["ship_date"] = $vv["ship_date"];
        	                                        $tracknums_to_db[$tracknums_to_db_index]["carrier_id"] = $vv["carrier_id"];
	                                                $tracknums_to_db_index++;

/*
	                                                $shipping_link_info = func_query_first("SELECT shipping, carrier_id FROM $sql_tbl[tracking_links] WHERE linkid='$vv[linkid]'");
        	                                        $shipping_link = $shipping_link_info["shipping"];
                	                                $carrier_id = $shipping_link_info["carrier_id"];
*/
	                                                $shipping_link = $vv["shipping"];
        	                                        $carrier_id = $vv["carrier_id"];

                        	                        $carrier = func_query_first_cell("SELECT carrier FROM $sql_tbl[tracking_links_carrier] WHERE carrier_id='$carrier_id'");

	                                                $tracking_str = "invoice_number_".$vv["invoice_number"]. ": " . $carrier . " " . $shipping_link.": ".$vv["tracknum"];
        	                                        $log .= $tracking_str."<br />";
	                                                $trackings_for_diff[] = $tracking_str;
                	                        }
        	                        }
	                        }
				else {
					$tracking_in_all_distrs = false;
				}

        	                $trackings_diff = array_diff($current_trackings_for_diff, $trackings_for_diff);

	                        if (!empty($trackings_diff)){
        	                        func_log_order($orderid, 'X', $log, $login);
	                        }

        	                $tracknums_to_db = addslashes(serialize($tracknums_to_db));
	                        db_query("UPDATE $sql_tbl[order_groups] SET tracking='$tracknums_to_db' WHERE manufacturerid='$m_id' AND orderid='$orderid'");
	                        unset($tracknums_to_db);

                                if ($Tracking_number_Added_flag){
                                 // SEND mail
//                                      func_change_order_status($orderid, "S"); //Shipped
//                                      func_change_order_group_status($orderid, $m_id, "S");

					$old_v = $v;
                                        include $xcart_dir . '/include/send_order_email.php';
					$v = $old_v;

                                }

			    } //if ($mode == "accounting_apply" && $user_account["flag"] != "FS")
###
##
#


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
/*
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
*/
###
				if ($order['shipping_groups'][$m_id]['ru_status'] == "RR"){
					$v["ru_status"] = "RR";
				} 
				else {
//					if ($new_Ref_to_us_gross > 0)
					if ($v["acc"][4]["gross"] > 0){
						$v["ru_status"] = "RP";
					} else {
						$v["ru_status"] = "";
					}
				}
/*				
                                if ($v["ru_status"] != $order['shipping_groups'][$m_id]['ru_status']){
                                        $current_ru_status_name = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='".$order['shipping_groups'][$m_id]['ru_status']."'");
                                        $new_ru_status_name = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='$v[ru_status]'");
					$log .= "REF TO US status: ".$current_ru_status_name." -> ".$new_ru_status_name."<br />";
                                }
*/

###
/*
				if (!empty($log)){
					$log = "<B>".$code.":</B> ".$log;
					func_log_order($orderid, 'X', $log, $login);
				}
*/
###
##
#




				$order['shipping_groups'][$m_id]['acc_paymentid'] = $v['paymentid'];
				$order['shipping_groups'][$m_id]['manufacturerid'] = $m_id;


		    	    if ($mode == "table_accounting_apply"){


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

			    } //if ($mode == "table_accounting_apply")


				if ($apply_per_trans = !in_array($v['paymentid'], $applied_per_trans_payments)) {
					$applied_per_trans_payments[] = $v['paymentid'];
				}

// moved below				func_recalculate_accounting($order['shipping_groups'][$m_id], $all_processors, $apply_per_trans, true);
		


				$update = array();

//				$update['accounting'] = addslashes(serialize($order['shipping_groups'][$m_id]['accounting']));
// ,oved below				$update = func_add_accounting_fields($update, '', '', '', "order_groups", $order['shipping_groups'][$m_id]['accounting']);

//func_print_r($update);
//die();

//				$update['profit_margin'] = $order['shipping_groups'][$m_id]['profit_margin'];

//func_print_r($groups[$certain_mid]);

				if ($mode == "table_accounting_apply"){
					$update['acc_paymentid'] = $v['paymentid'];
					$update['ru_status'] = $v['ru_status'];
				}



                                if ($mode == "accounting_apply" && $user_account["flag"] != "FS" && !empty($manufacturer_memos_data[$certain_mid]) && is_array($manufacturer_memos_data[$certain_mid])){
                                        $SUM_ref_to_us = 0;
                                        $SUM_ref_to_us_HST = 0; 
                                        $SUM_ref_to_us_total = 0;

                                        
                                        $log = "<B>".$order["shipping_groups"][$certain_mid]["code"]."</B>:";

/*
if ($certain_mid == "12"){
func_print_r($manufacturer_memos_data);
}
*/
                                        
                                        foreach ($manufacturer_memos_data[$certain_mid] as $memo_number => $memo_data){
                                                
                                                $update_memos_table_flag = false;
                                                $group_memos = array();
                                                
                                                $ref_to_us_HST = $memo_data["ref_to_us_HST"];
                                                $SUM_ref_to_us_HST += $ref_to_us_HST;
                                                
                                                if ($ref_to_us_HST != $order["shipping_groups"][$certain_mid]["memos"][$memo_number]["ref_to_us_HST"]){
                                                        $group_memos["ref_to_us_HST"] = $ref_to_us_HST;
                                                        $log .= "<br />memo_number-".$memo_number.": ref_to_us_HST: ".$order["shipping_groups"][$certain_mid]["memos"][$memo_number]["ref_to_us_HST"]." -> ".$ref_to_us_HST;
                                                        $update_memos_table_flag = true;
                                                }
                                                
                                                $ref_to_us_total = $memo_data["ref_to_us_total"];
                                                $SUM_ref_to_us_total += $ref_to_us_total;
                                                
                                                if ($ref_to_us_total != $order["shipping_groups"][$certain_mid]["memos"][$memo_number]["ref_to_us_total"]){
                                                        $group_memos["ref_to_us_total"] = $ref_to_us_total;
                                                        $log .= "<br />memo_number-".$memo_number.": ref_to_us_total: ".$order["shipping_groups"][$certain_mid]["memos"][$memo_number]["ref_to_us_total"]." -> ".$ref_to_us_total;
                                                        $update_memos_table_flag = true;
                                                }

                                                $ref_to_us = $ref_to_us_total - $ref_to_us_HST;
                                                $group_memos["ref_to_us"] = $ref_to_us;
                                                $SUM_ref_to_us += $ref_to_us;

                                                $memo_descr = $memo_data["memo_descr"];
                                                if ($memo_descr != $order["shipping_groups"][$certain_mid]["memos"][$memo_number]["memo_descr"]){
                                                        $log .= "<br />memo_number-".$memo_number.": memo_descr: ".$order["shipping_groups"][$certain_mid]["memos"][$memo_number]["memo_descr"]." -> ".$memo_descr;
							$group_memos["memo_descr"] = $memo_descr;
                                                        $update_memos_table_flag = true;
                                                }

                                                if ($update_memos_table_flag){
                                                        func_array2update("order_group_memos", $group_memos, "orderid='$orderid' AND manufacturerid='$certain_mid' AND memo_number='$memo_number'");
                                                }

                                        } // foreach ($manufacturer_memos_data[$certain_mid] as $memo_number => $memo_data)

                                        if ($log != "<B>".$order["shipping_groups"][$certain_mid]["code"]."</B>:"){
                                                func_log_order($orderid, 'X', $log, $login);
                                        }

                                        if ($order['shipping_groups'][$m_id]['accounting'][4]["net"] != price_format($SUM_ref_to_us)){
                                                $order['shipping_groups'][$m_id]['accounting'][4]["net"] = price_format($SUM_ref_to_us);
                                        }

                                        if ($order['shipping_groups'][$m_id]['accounting'][4]["gst"] != price_format($SUM_ref_to_us_HST)){
                                                $order['shipping_groups'][$m_id]['accounting'][4]["gst"] = price_format($SUM_ref_to_us_HST);
                                        }

                                        if ($order['shipping_groups'][$m_id]['accounting'][4]["gross"] != price_format($SUM_ref_to_us_total)){
                                                $order['shipping_groups'][$m_id]['accounting'][4]["gross"] = price_format($SUM_ref_to_us_total);
                                        }
				}

				$log = "";

				if ($mode == "accounting_apply" && $user_account["flag"] != "FS" && !empty($manufacturer_invoices_data[$certain_mid]) && is_array($manufacturer_invoices_data[$certain_mid])){

/*
					$update['tax_charged_except_HST'] = $v["tax_charged_except_HST"];
					$update['shipping_charged'] = $v["shipping_charged"];
					$update['drop_ship_fee_charged'] = $v["drop_ship_fee_charged"];
					$update['HST_charged'] = $v["HST_charged"];
*/

                                        $SUM_HST_charged = 0;
                                        $SUM_tax_charged_except_HST = 0;
                                        $SUM_cost_to_us_for_products_charged = 0;
                                        $SUM_shipping_charged = 0;
                                        $SUM_drop_ship_fee_charged = 0;


					$log = "<B>".$order["shipping_groups"][$certain_mid]["code"]."</B>:";

					foreach ($manufacturer_invoices_data[$certain_mid] as $invoice_number => $invoice_data){

	                                        $update_invoices_table_flag = false;
	                                        $cost_to_us_for_products_charged = 0;

//func_print_r($order['shipping_groups'][$m_id]);
//func_print_r($invoice_data, $manufacturer_invoices_data[$certain_mid], $order["shipping_groups"][$m_id]["products"]);
//func_print_r($manufacturer_invoices_data[$certain_mid]);
//die();

						if (!empty($invoice_data["unit_cost"]) && is_array($invoice_data["unit_cost"])){
							foreach ($invoice_data["unit_cost"] as $itemid => $unit_cost){

								$invoices_products = array();

								$qty_inv = $invoice_data["qty_inv"][$itemid];
								$unit_cost_total = price_format($qty_inv*$unit_cost);

								if ($unit_cost != $order["shipping_groups"][$certain_mid]["invoices"][$invoice_number]["products"][$itemid]["unit_cost"]){
									$invoices_products["unit_cost"] = $unit_cost;

									$update_invoices_table_flag = true;

									$log .= "<br />invoice_number-".$invoice_number.": unit_cost: ".$order["shipping_groups"][$certain_mid]["invoices"][$invoice_number]["products"][$itemid]["unit_cost"]." -> ".$unit_cost;


#
##
									if ($unit_cost < $order['shipping_groups'][$m_id]["products"][$itemid]["cost_to_us"] && !empty($config["Attention_tags_invoices"]["tag_for_Unit_cost_LT_Cost_to_us"])){
										$status_id = func_query_first_cell("SELECT status_id FROM $sql_tbl[orders_additional_tags] WHERE orderid='$orderid' AND status_id='".$config["Attention_tags_invoices"]["tag_for_Unit_cost_LT_Cost_to_us"]."'");
										if (empty($status_id)){
											db_query("INSERT INTO $sql_tbl[orders_additional_tags] (orderid, status_id) VALUES ('$orderid', '".$config["Attention_tags_invoices"]["tag_for_Unit_cost_LT_Cost_to_us"]."')");

											$log .= "<br />Attention tag added: ".$attention_tags_values[$config["Attention_tags_invoices"]["tag_for_Unit_cost_LT_Cost_to_us"]]["status"];

										}
									}

                                                                        if ($unit_cost > $order['shipping_groups'][$m_id]["products"][$itemid]["cost_to_us"] && !empty($config["Attention_tags_invoices"]["tag_for_Unit_cost_GT_Cost_to_us"])){
                                                                                $status_id = func_query_first_cell("SELECT status_id FROM $sql_tbl[orders_additional_tags] WHERE orderid='$orderid' AND status_id='".$config["Attention_tags_invoices"]["tag_for_Unit_cost_GT_Cost_to_us"]."'");
                                                                                if (empty($status_id)){
                                                                                        db_query("INSERT INTO $sql_tbl[orders_additional_tags] (orderid, status_id) VALUES ('$orderid', '".$config["Attention_tags_invoices"]["tag_for_Unit_cost_GT_Cost_to_us"]."')");

                                                                                        $log .= "<br />Attention tag added: ".$attention_tags_values[$config["Attention_tags_invoices"]["tag_for_Unit_cost_GT_Cost_to_us"]]["status"];
                                                                                }
                                                                        }

##
#


								}

                                                                if ($qty_inv != $order["shipping_groups"][$certain_mid]["invoices"][$invoice_number]["products"][$itemid]["qty_inv"]){
                                                                        $invoices_products["qty_inv"] = $qty_inv;

                                                                        $update_invoices_table_flag = true;                                                                     
                                                                        $log .= "<br />invoice_number-".$invoice_number.": qty_inv: ".$order["shipping_groups"][$certain_mid]["invoices"][$invoice_number]["products"][$itemid]["qty_inv"]." -> ".$qty_inv;
#
##
									if (!empty($config["Attention_tags_invoices"]["tag_for_Qty_invoiced_NOT_EQ_Qty_dispatched"])){
										$ref_qty = 0;
										if (!empty($order["refund_groups"][$m_id]["products"][$itemid]["ref_qty"])){
											$ref_qty = $order["refund_groups"][$m_id]["products"][$itemid]["ref_qty"];
										}
	
										$qty_disp = $order["shipping_groups"][$m_id]["products"][$itemid]["amount"] - $ref_qty;

										$sum_qty_inv_for_certain_product = 0;
										foreach ($manufacturer_invoices_data[$certain_mid] as $k_tmp => $v_tmp){
											$sum_qty_inv_for_certain_product += $v_tmp["qty_inv"][$itemid];
										}

										if ($qty_disp != $sum_qty_inv_for_certain_product){
	                                                                                $status_id = func_query_first_cell("SELECT status_id FROM $sql_tbl[orders_additional_tags] WHERE orderid='$orderid' AND status_id='".$config["Attention_tags_invoices"]["tag_for_Qty_invoiced_NOT_EQ_Qty_dispatched"]."'");
        	                                                                        if (empty($status_id)){
                	                                                                        db_query("INSERT INTO $sql_tbl[orders_additional_tags] (orderid, status_id) VALUES ('$orderid', '".$config["Attention_tags_invoices"]["tag_for_Qty_invoiced_NOT_EQ_Qty_dispatched"]."')");
	
        	                                                                                $log .= "<br />Attention tag added: ".$attention_tags_values[$config["Attention_tags_invoices"]["tag_for_Qty_invoiced_NOT_EQ_Qty_dispatched"]]["status"];
        	                                                                        }
										}
									}
##
#
								}

								if ($update_invoices_table_flag){
									$invoices_products["unit_cost_total"] = $unit_cost_total;
								}

								if ($update_invoices_table_flag){
									func_array2update("order_group_invoices_products", $invoices_products, "orderid='$orderid' AND manufacturerid='$certain_mid' AND invoice_number='$invoice_number' AND itemid='$itemid'");
								}

								$cost_to_us_for_products_charged += $unit_cost_total;
							}
						}

						$group_invoices = array();

						$cost_to_us_for_products_charged = price_format($cost_to_us_for_products_charged);
						$SUM_cost_to_us_for_products_charged += $cost_to_us_for_products_charged;
						$group_invoices["cost_to_us_for_products_charged"] = $cost_to_us_for_products_charged;

						$tax_charged_except_HST = $invoice_data["tax_charged_except_HST"];
						$SUM_tax_charged_except_HST += $tax_charged_except_HST;

						if ($tax_charged_except_HST != $order["shipping_groups"][$certain_mid]["invoices"][$invoice_number]["tax_charged_except_HST"]){
							$group_invoices["tax_charged_except_HST"] = $tax_charged_except_HST;
							$log .= "<br />invoice_number-".$invoice_number.": tax_charged_except_HST: ".$order["shipping_groups"][$certain_mid]["invoices"][$invoice_number]["tax_charged_except_HST"]." -> ".$tax_charged_except_HST;
							$update_invoices_table_flag = true;

#
##					
                                                        if ($tax_charged_except_HST > 0 && !empty($config["Attention_tags_invoices"]["tag_for_Tax_charged_except_HST_GT_0"])){
                                                                                $status_id = func_query_first_cell("SELECT status_id FROM $sql_tbl[orders_additional_tags] WHERE orderid='$orderid' AND status_id='".$config["Attention_tags_invoices"]["tag_for_Tax_charged_except_HST_GT_0"]."'");
                                                                                if (empty($status_id)){
                                                                                        db_query("INSERT INTO $sql_tbl[orders_additional_tags] (orderid, status_id) VALUES ('$orderid', '".$config["Attention_tags_invoices"]["tag_for_Tax_charged_except_HST_GT_0"]."')");

                                                                                        $log .= "<br />Attention tag added: ".$attention_tags_values[$config["Attention_tags_invoices"]["tag_for_Tax_charged_except_HST_GT_0"]]["status"];

                                                                                }
                                                                        }
##
#
						}

#
##
						if ($invoice_data["extra_items_on_invoice"] != $order["shipping_groups"][$certain_mid]["invoices"][$invoice_number]["extra_items_on_invoice"]){
							$group_invoices["extra_items_on_invoice"] = $invoice_data["extra_items_on_invoice"];

                                                        $log .= "<br />invoice_number-".$invoice_number.": extra_items_on_invoice: ".$order["shipping_groups"][$certain_mid]["invoices"][$invoice_number]["extra_items_on_invoice"]." -> ".$invoice_data["extra_items_on_invoice"];
                                                        $update_invoices_table_flag = true;

                                                        if ($invoice_data["extra_items_on_invoice"] == "Y" && !empty($config["Attention_tags_invoices"]["tag_for_extra_items_on_invoice"])){
                                                                                $status_id = func_query_first_cell("SELECT status_id FROM $sql_tbl[orders_additional_tags] WHERE orderid='$orderid' AND status_id='".$config["Attention_tags_invoices"]["tag_for_extra_items_on_invoice"]."'");
                                                                                if (empty($status_id)){
                                                                                        db_query("INSERT INTO $sql_tbl[orders_additional_tags] (orderid, status_id) VALUES ('$orderid', '".$config["Attention_tags_invoices"]["tag_for_extra_items_on_invoice"]."')");

                                                                                        $log .= "<br />Attention tag added: ".$attention_tags_values[$config["Attention_tags_invoices"]["tag_for_extra_items_on_invoice"]]["status"];

                                                                                }
                                                         }
						}

                                                if ($invoice_data["items_shipped_to_wrong_address"] != $order["shipping_groups"][$certain_mid]["invoices"][$invoice_number]["items_shipped_to_wrong_address"]){                                       
                                                        $group_invoices["items_shipped_to_wrong_address"] = $invoice_data["items_shipped_to_wrong_address"];

                                                        $log .= "<br />invoice_number-".$invoice_number.": items_shipped_to_wrong_address: ".$order["shipping_groups"][$certain_mid]["invoices"][$invoice_number]["items_shipped_to_wrong_address"]." -> ".$invoice_data["items_shipped_to_wrong_address"];
                                                        $update_invoices_table_flag = true;
                                                                                
                                                        if ($invoice_data["items_shipped_to_wrong_address"] == "Y" && !empty($config["Attention_tags_invoices"]["tag_for_items_shipped_to_wrong_address"])){
                                                                                $status_id = func_query_first_cell("SELECT status_id FROM $sql_tbl[orders_additional_tags] WHERE orderid='$orderid' AND status_id='".$config["Attention_tags_invoices"]["tag_for_items_shipped_to_wrong_address"]."'");
                                                                                if (empty($status_id)){
                                                                                        db_query("INSERT INTO $sql_tbl[orders_additional_tags] (orderid, status_id) VALUES ('$orderid', '".$config["Attention_tags_invoices"]["tag_for_items_shipped_to_wrong_address"]."')");

                                                                                        $log .= "<br />Attention tag added: ".$attention_tags_values[$config["Attention_tags_invoices"]["tag_for_items_shipped_to_wrong_address"]]["status"];

                                                                                }
                                                         }
                                                }
##
#

						$products_total = price_format($cost_to_us_for_products_charged + $tax_charged_except_HST);
						$group_invoices["products_total"] = $products_total;

						$shipping_charged = $invoice_data["shipping_charged"];
						$SUM_shipping_charged += $shipping_charged;

                                                if ($shipping_charged != $order["shipping_groups"][$certain_mid]["invoices"][$invoice_number]["shipping_charged"]){
							$group_invoices["shipping_charged"] = $shipping_charged;
                                                        $log .= "<br />invoice_number-".$invoice_number.": shipping_charged: ".$order["shipping_groups"][$certain_mid]["invoices"][$invoice_number]["shipping_charged"]." -> ".$shipping_charged;
                                                        $update_invoices_table_flag = true;

#
##
                                                                        if ($shipping_charged > $order['shipping_groups'][$m_id]["actual_shipping_cost"]["net"] && !empty($config["Attention_tags_invoices"]["tag_for_Shipping_charged_GT_Shipping_quoted_by_distr"]) && $order['shipping_groups'][$m_id]["actual_shipping_cost"]["net"] > 0){
                                                                                $status_id = func_query_first_cell("SELECT status_id FROM $sql_tbl[orders_additional_tags] WHERE orderid='$orderid' AND status_id='".$config["Attention_tags_invoices"]["tag_for_Shipping_charged_GT_Shipping_quoted_by_distr"]."'");
                                                                                if (empty($status_id)){
                                                                                        db_query("INSERT INTO $sql_tbl[orders_additional_tags] (orderid, status_id) VALUES ('$orderid', '".$config["Attention_tags_invoices"]["tag_for_Shipping_charged_GT_Shipping_quoted_by_distr"]."')");

                                                                                        $log .= "<br />Attention tag added: ".$attention_tags_values[$config["Attention_tags_invoices"]["tag_for_Shipping_charged_GT_Shipping_quoted_by_distr"]]["status"];

                                                                                }
                                                                        }

                                                                        if ($shipping_charged == 0 && !empty($config["Attention_tags_invoices"]["tag_for_Shipping_charged_EQ_0"])){
                                                                                $status_id = func_query_first_cell("SELECT status_id FROM $sql_tbl[orders_additional_tags] WHERE orderid='$orderid' AND status_id='".$config["Attention_tags_invoices"]["tag_for_Shipping_charged_EQ_0"]."'");
                                                                                if (empty($status_id)){
                                                                                        db_query("INSERT INTO $sql_tbl[orders_additional_tags] (orderid, status_id) VALUES ('$orderid', '".$config["Attention_tags_invoices"]["tag_for_Shipping_charged_EQ_0"]."')");

                                                                                        $log .= "<br />Attention tag added: ".$attention_tags_values[$config["Attention_tags_invoices"]["tag_for_Shipping_charged_EQ_0"]]["status"];

                                                                                }
                                                                        }
##
#
                                                }

						$drop_ship_fee_charged = $invoice_data["drop_ship_fee_charged"];
						$SUM_drop_ship_fee_charged += $drop_ship_fee_charged;

                                                if ($drop_ship_fee_charged != $order["shipping_groups"][$certain_mid]["invoices"][$invoice_number]["drop_ship_fee_charged"]){
							$group_invoices["drop_ship_fee_charged"] = $drop_ship_fee_charged;
                                                        $log .= "<br />invoice_number-".$invoice_number.": drop_ship_fee_charged: ".$order["shipping_groups"][$certain_mid]["invoices"][$invoice_number]["drop_ship_fee_charged"]." -> ".$drop_ship_fee_charged;
                                                        $update_invoices_table_flag = true;

#
##
                                                                        if ($drop_ship_fee_charged > $order['shipping_groups'][$m_id]["all_distributor_info"]["d_drop_ship_fee_in_us"] && !empty($config["Attention_tags_invoices"]["tag_for_Drop_ship_fee_charged_GT_Drop_ship_fee_in_xcart"])){
                                                                                $status_id = func_query_first_cell("SELECT status_id FROM $sql_tbl[orders_additional_tags] WHERE orderid='$orderid' AND status_id='".$config["Attention_tags_invoices"]["tag_for_Drop_ship_fee_charged_GT_Drop_ship_fee_in_xcart"]."'");
                                                                                if (empty($status_id)){
                                                                                        db_query("INSERT INTO $sql_tbl[orders_additional_tags] (orderid, status_id) VALUES ('$orderid', '".$config["Attention_tags_invoices"]["tag_for_Drop_ship_fee_charged_GT_Drop_ship_fee_in_xcart"]."')");

                                                                                        $log .= "<br />Attention tag added: ".$attention_tags_values[$config["Attention_tags_invoices"]["tag_for_Drop_ship_fee_charged_GT_Drop_ship_fee_in_xcart"]]["status"];
                                                                                }
                                                                        }
##
#
                                                }

						$shipping_total = price_format($shipping_charged + $drop_ship_fee_charged);
						$group_invoices["shipping_total"] = $shipping_total;

						$HST_charged = $invoice_data["HST_charged"];
						$SUM_HST_charged += $HST_charged;

                                                if ($HST_charged != $order["shipping_groups"][$certain_mid]["invoices"][$invoice_number]["HST_charged"]){
							$group_invoices["HST_charged"] = $HST_charged;
                                                        $log .= "<br />invoice_number-".$invoice_number.": HST_charged: ".$order["shipping_groups"][$certain_mid]["invoices"][$invoice_number]["HST_charged"]." -> ".$HST_charged;
                                                        $update_invoices_table_flag = true;

#
##                                      
                                                                        if ($HST_charged > 0 && !empty($config["Attention_tags_invoices"]["tag_for_HST_charged_GT_0"])){
                                                                                $status_id = func_query_first_cell("SELECT status_id FROM $sql_tbl[orders_additional_tags] WHERE orderid='$orderid' AND status_id='".$config["Attention_tags_invoices"]["tag_for_HST_charged_GT_0"]."'");
                                                                                if (empty($status_id)){
                                                                                        db_query("INSERT INTO $sql_tbl[orders_additional_tags] (orderid, status_id) VALUES ('$orderid', '".$config["Attention_tags_invoices"]["tag_for_HST_charged_GT_0"]."')");

                                                                                        $log .= "<br />Attention tag added: ".$attention_tags_values[$config["Attention_tags_invoices"]["tag_for_HST_charged_GT_0"]]["status"];

                                                                                }
                                                                        }
##
#

                                                }

						$invoice_total = price_format($products_total + $shipping_total + $HST_charged);
						$group_invoices["invoice_total"] = $invoice_total;

						if ($update_invoices_table_flag){
							func_array2update("order_group_invoices", $group_invoices, "orderid='$orderid' AND manufacturerid='$certain_mid' AND invoice_number='$invoice_number'");
						}

					} // foreach ($manufacturer_invoices_data[$certain_mid] as $invoice_number => $invoice_data)

// Moved below
//					if ($log != "<B>".$order["shipping_groups"][$certain_mid]["code"]."</B>:"){
//						func_log_order($orderid, 'X', $log, $login);
//					}


//func_print_r($manufacturer_invoices_data[$certain_mid], $_POST, $order);
//die();




		                        if ($order['shipping_groups'][$m_id]['accounting'][1]["gst"] != price_format($SUM_HST_charged)){
						$order['shipping_groups'][$m_id]['accounting'][1]["gst"] = price_format($SUM_HST_charged);
                		        }

					$SUM_products_total = $SUM_cost_to_us_for_products_charged + $SUM_tax_charged_except_HST;

		                        if ($order['shipping_groups'][$m_id]['accounting'][1]["gross"] != price_format($SUM_HST_charged + $SUM_products_total)){
                		                $order['shipping_groups'][$m_id]['accounting'][1]["gross"] = price_format($SUM_HST_charged + $SUM_products_total);
		                        }

                		        if ($order['shipping_groups'][$m_id]['accounting'][2]["gross"] != price_format($SUM_shipping_charged + $SUM_drop_ship_fee_charged)){
        		                        $order['shipping_groups'][$m_id]['accounting'][2]["gross"] = price_format($SUM_shipping_charged + $SUM_drop_ship_fee_charged);
		                        }
				}

###

				func_recalculate_accounting($order['shipping_groups'][$m_id], $all_processors, $apply_per_trans, true);

				$update['profit_margin'] = $order['shipping_groups'][$m_id]['profit_margin'];

				$update = func_add_accounting_fields($update, '', '', '', "order_groups", $order['shipping_groups'][$m_id]['accounting']);

#
##
###
				if ($update["accounting_gross_5_profit"] < 0 && $update["accounting_gross_5_profit"] != $order["shipping_groups"][$m_id]["accounting_gross_5_profit"]){
					$tmp_use_profit_for_tag = $update["accounting_gross_5_profit"];
//					if ($order["shipping_groups"][$m_id]["cb_status"] == "O")
					if (empty($update["accounting_net_0"]) || $update["accounting_net_0"] == "0.00"){
						$tmp_use_profit_for_tag += $order["shipping_groups"][$m_id]["total"]["net"];
					}

					if ($tmp_use_profit_for_tag < 0){
		                                $status_id = func_query_first_cell("SELECT status_id FROM $sql_tbl[orders_additional_tags] WHERE orderid='$orderid' AND status_id='".$config["Attention_tags_invoices"]["tag_for_PROFIT_LT_0"]."'");
        	                                if (empty($status_id)){
        		                                db_query("INSERT INTO $sql_tbl[orders_additional_tags] (orderid, status_id) VALUES ('$orderid', '".$config["Attention_tags_invoices"]["tag_for_PROFIT_LT_0"]."')");
                        	                        $log .= "<br />Attention tag added: ".$attention_tags_values[$config["Attention_tags_invoices"]["tag_for_PROFIT_LT_0"]]["status"];
                                	        }
					}
				}
###
##
#
                                if ($log != "<B>".$order["shipping_groups"][$certain_mid]["code"]."</B>:"){
                                        func_log_order($orderid, 'X', $log, $login);
                                }


				func_log_order_groups($update, $orderid, $m_id, 'X', $login);

				func_array2update("order_groups", $update ,"orderid='$orderid' AND manufacturerid='$m_id'");


			    if ($mode == "table_accounting_apply"){
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


#
## 11.04.2014
###
			if ($mode == "accounting_apply" && $user_account["flag"] != "FS"){

				$current_tracking_all_filled = func_query_first_cell("SELECT tracking_all_filled FROM $sql_tbl[orders] WHERE orderid='$orderid'");

				if ($tracking_in_all_distrs){
					if ($current_tracking_all_filled != "Y"){
						db_query("UPDATE $sql_tbl[orders] SET tracking_all_filled='Y', tracking_fill_time='".time()."' WHERE orderid='$orderid'");
					}
				} else {
					if ($current_tracking_all_filled == "Y"){
						db_query("UPDATE $sql_tbl[orders] SET tracking_all_filled='N' WHERE orderid='$orderid'");
					}
				}
			}
###
##
#


		}	
		func_header_location("order.php?orderid=$orderid");
	}

}

#$all_shippings = func_query("SELECT shippingid, shipping FROM $sql_tbl[shipping] WHERE active='Y' ORDER BY orderby");
#$smarty->assign("all_shippings", $all_shippings);

if (empty($order["refund_groups"]) && is_array($order["refund_groups"])){
	unset($order["refund_groups"]);
}

if (!empty($order["refund_groups"]) && is_array($order["refund_groups"])){
	foreach ($order["refund_groups"] as $m_id => $r_v){
		if (!empty($r_v["products"]) && is_array($r_v["products"])){
			foreach ($r_v["products"] as $itemid => $r_p){
				if (!empty($order["shipping_groups"][$m_id]["products"]) && is_array($order["shipping_groups"][$m_id]["products"])){
					foreach ($order["shipping_groups"][$m_id]["products"] as $k => $v){
//						if ($v["itemid"] == $itemid && $v["back"] == $r_p["ref_qty"]){
						if ($v["itemid"] == $itemid){
							$order["shipping_groups"][$m_id]["products"][$k]["dropped"] = "Y";
						}
					}
				}
			}
		}
	}
}

if (!empty($order["shipping_groups"]) && is_array($order["shipping_groups"])){
	foreach ($order["shipping_groups"] as $k => $v){

###
/*
		if ($v["dc_status"] == "DP" && $v["all_distributor_info"]["allow_dispatch_off_working_hours"] == "Y" && !empty($order["attention_tags"]) && is_array($order["attention_tags"])){
			foreach ($order["attention_tags"] as $kk => $vv){
				if (strtoupper(trim($vv["status"])) == "OTRS: NEW MESSAGE") { // OTRS: New message
					$order["shipping_groups"][$k]["allow_dispatch_off_working_hours_functionality_enabled"] = "Y";
					$order["allow_dispatch_off_working_hours_functionality_enabled_found"] = "Y";
					break;
				}
			}
		}
*/
                if ($v["dc_status"] == "DP" && $v["all_distributor_info"]["allow_dispatch_off_working_hours"] == "Y"){
                                        $order["shipping_groups"][$k]["allow_dispatch_off_working_hours_functionality_enabled"] = "Y";
                                        $order["allow_dispatch_off_working_hours_functionality_enabled_found"] = "Y";
		}
###

		if ($v["products"] && is_array($v["products"])){
			foreach ($v["products"] as $kk => $vv){
				if (!empty($vv["eta_date_mm_dd_yyyy"])){
                                        $current_eta_date_mm_dd_yyyy_arr = explode("/", $vv["eta_date_mm_dd_yyyy"]);
                                        $current_eta_date_mm_dd_yyyy_time = mktime(0, 0, 0, $current_eta_date_mm_dd_yyyy_arr[0], $current_eta_date_mm_dd_yyyy_arr[1], $current_eta_date_mm_dd_yyyy_arr[2]);

					if (time() > $current_eta_date_mm_dd_yyyy_time){
						$order["shipping_groups"][$k]["products"][$kk]["eta_date_mm_dd_yyyy"] = "";
					}
				}





/*

				if ($vv["qty_inv"] == ""){
					$qty_inv = $vv["amount"];
				} else {
					$qty_inv = $vv["qty_inv"];
				}

                                if ($vv["unit_cost"] == ""){
                                        $unit_cost = $vv["cost_to_us"];
                                } else {
                                        $unit_cost = $vv["unit_cost"];
                                }

				$extended1 = $qty_inv * $unit_cost;
				$extended2 = $qty_inv * $vv["cost_to_us"];

				$order["shipping_groups"][$k]["products"][$kk]["product_total_extended1"] = $extended1;
				$order["shipping_groups"][$k]["products"][$kk]["product_total_extended2"] = $extended2;

				if (!isset($order["shipping_groups"][$k]["all_product_total_extended1"])){
					$order["shipping_groups"][$k]["all_product_total_extended1"] = 0;
				}
				$order["shipping_groups"][$k]["all_product_total_extended1"] += $extended1;

                                if (!isset($order["shipping_groups"][$k]["all_product_total_extended2"])){
                                        $order["shipping_groups"][$k]["all_product_total_extended2"] = 0;
                                }
                                $order["shipping_groups"][$k]["all_product_total_extended2"] += $extended2;
*/
			}

/*
			if ($order["shipping_groups"][$k]["tax_charged_except_HST"] == ""){
				$order["shipping_groups"][$k]["tax_charged_except_HST"] = "0.00";
			}
			$order["shipping_groups"][$k]["all_product_total_extended1_tax"] = $order["shipping_groups"][$k]["all_product_total_extended1"] + $order["shipping_groups"][$k]["tax_charged_except_HST"];


			if ($order["shipping_groups"][$k]["shipping_charged"] == ""){
//				$order["shipping_groups"][$k]["shipping_charged"] = $order["shipping_groups"][$k]["actual_shipping_cost"]["net"];
				$order["shipping_groups"][$k]["shipping_charged"] = "0.00";
			}
			if ($order["shipping_groups"][$k]["drop_ship_fee_charged"] == ""){
				$order["shipping_groups"][$k]["drop_ship_fee_charged"] = $order["shipping_groups"][$k]["all_distributor_info"]["d_drop_ship_fee_in_us"];
			}
//			$order["shipping_groups"][$k]["shipping_total"] = $order["shipping_groups"][$k]["all_product_total_extended1_tax"] + $order["shipping_groups"][$k]["shipping_charged"] + $order["shipping_groups"][$k]["drop_ship_fee_charged"];
			$order["shipping_groups"][$k]["shipping_total"] = $order["shipping_groups"][$k]["shipping_charged"] + $order["shipping_groups"][$k]["drop_ship_fee_charged"];


			if ($order["shipping_groups"][$k]["HST_charged"] == ""){
				$order["shipping_groups"][$k]["HST_charged"] = "0.00";
			}
//			$order["shipping_groups"][$k]["invoice_total"] = $order["shipping_groups"][$k]["shipping_total"] + $order["shipping_groups"][$k]["HST_charged"];
			$order["shipping_groups"][$k]["invoice_total"] = $order["shipping_groups"][$k]["all_product_total_extended1_tax"] + $order["shipping_groups"][$k]["shipping_total"] + $order["shipping_groups"][$k]["HST_charged"];
*/

/*

			$new_update = array();
			$log = "";

			if ($v["accounting"][1]["gst"] != $order["shipping_groups"][$k]["HST_charged"]){
                                $log .= "Cost_to_us_gst: ".$v["accounting"][1]["gst"]." -> ".$order["shipping_groups"][$k]["HST_charged"]."<br />";

				$order["shipping_groups"][$k]["accounting"][1]["gst"] = $order["shipping_groups"][$k]["HST_charged"];
				$new_update["accounting_gst_1_cost_to_us"] = $order["shipping_groups"][$k]["accounting"][1]["gst"];
			}

                        if ($v["accounting"][1]["gross"] != ($order["shipping_groups"][$k]["HST_charged"] + $order["shipping_groups"][$k]["all_product_total_extended1_tax"])){
				$log .= "Cost_to_us_gross: ".$v["accounting"][1]["gross"]." -> ".($order["shipping_groups"][$k]["HST_charged"] + $order["shipping_groups"][$k]["all_product_total_extended1_tax"])."<br />";

				$order["shipping_groups"][$k]["accounting"][1]["gross"] = $order["shipping_groups"][$k]["HST_charged"] + $order["shipping_groups"][$k]["all_product_total_extended1_tax"];
				$new_update["accounting_gross_1_cost_to_us"] = $order["shipping_groups"][$k]["accounting"][1]["gross"];
			}

			if ($v["accounting"][2]["gross"] != $order["shipping_groups"][$k]["shipping_total"]){
				$log .= "Shipping_gross: ".$v["accounting"][2]["gross"]." -> ".$order["shipping_groups"][$k]["shipping_total"]."<br />";

				$order["shipping_groups"][$k]["accounting"][2]["gross"] = $order["shipping_groups"][$k]["shipping_total"];
				$order["shipping_groups"][$k]["accounting"][2]["net"] = $order["shipping_groups"][$k]["accounting"][2]["gross"];

				$new_update["accounting_gross_2_shipping"] = $order["shipping_groups"][$k]["accounting"][2]["gross"];
			}

			if (!empty($new_update)){
				func_array2update("order_groups", $new_update, "orderid = '$orderid' AND manufacturerid='$k'");
				unset($new_update);

                                $log = "<B>".$v["code"].":</B> ".$log;
                                func_log_order($orderid, 'X', $log, $login);
			}
*/

		}
	}
}

//func_print_r($order);

$smarty->assign("order", $order);

?>
