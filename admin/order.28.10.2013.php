<?php /* MODIFIED: random:20341 [2010 Jul 29 14:46][Custom development (Accounting features for X-Cart orders management)] */ ?>
<?php /* MODIFIED: random:18591_18598 [2009 Jul 29 10:36][Custom development (Изменения для модуля UPS + Изменения в способ ввода Tracking numbers для заказов)] */ ?>
<?php /* MODIFIED: random:19017 [2009 Sep 14 14:13][Custom development (Add new option to "Order status" selector and "Empty tracking number detection")] */ ?>
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
# $Id: order.php,v 1.60.2.2 2006/12/08 06:33:55 max Exp $
#

define('USE_TRUSTED_POST_VARIABLES',1);
$trusted_post_variables = array('update', 'mnf_body');

require "./auth.php";
require $xcart_dir."/include/security.php";

x_load('mail','order');


//func_print_r($_POST);
//die();

if ($mode == "order_edit_apply" && !empty($items) && is_array($items) && !empty($orderid)){
	foreach ($items as $k => $v){
		if (!empty($v["productid"])){
			db_query("UPDATE $sql_tbl[products] SET eta_date_mm_dd_yyyy='".$v["eta_date_mm_dd_yyyy"]."' WHERE productid='$v[productid]'");

			if (!empty($v["classid_optionid"]) && is_array($v["classid_optionid"])){
				$order_details_product_options = "";
				foreach ($v["classid_optionid"] as $classid => $optionid){
					$class = func_query_first_cell("SELECT class FROM $sql_tbl[classes] WHERE classid='$classid'");
					$option_name = func_query_first_cell("SELECT option_name FROM $sql_tbl[class_options] WHERE classid='$classid' AND optionid='$optionid'");
					$order_details_product_options .= $class.": ".$option_name."\r\n";

				}
				$$order_details_product_options = addslashes($order_details_product_options);

				$extra_data = func_query_first_cell("SELECT extra_data FROM $sql_tbl[order_details] WHERE orderid='$orderid' AND productid='$v[productid]'");
				$extra_data = unserialize($extra_data);
				$extra_data["product_options"] = $v["classid_optionid"];
				$extra_data = addslashes(serialize($extra_data));

				db_query("UPDATE $sql_tbl[order_details] SET product_options='$order_details_product_options', extra_data='$extra_data' WHERE orderid='$orderid' AND productid='$v[productid]'");

			}
		}
	}
}

# START: random:18591_18598 [2009 Jul 29 10:36] 
if ($mode == "update" && $user_account["flag"] != "FS") {
# END: random:18591_18598 [2009 Jul 29 10:36] 
	#
	# Update orders info (status)
	#
	if (is_array($order_status) && is_array($order_status_old)) {
		foreach ($order_status as $orderid=>$status) {
			if (is_numeric($orderid) && $status != $order_status_old[$orderid])
				func_change_order_status($orderid, $status);
		}

		func_header_location("orders.php".(empty($qrystring)?"":"?$qrystring"));
	}
}
elseif ($mode == 'prolong_ttl' && $orderid && !empty($active_modules["Egoods"])) {
	#
	# Prolong TTL
	#
	$itemids = func_query("SELECT $sql_tbl[order_details].itemid FROM $sql_tbl[order_details], $sql_tbl[download_keys] WHERE $sql_tbl[order_details].orderid = '$orderid' AND $sql_tbl[order_details].itemid = $sql_tbl[download_keys].itemid");
	if ($itemids) {
		foreach ($itemids as $v)
			db_query("UPDATE $sql_tbl[download_keys] SET expires = '".(time()+$config["Egoods"]["download_key_ttl"]*3600)."' WHERE itemid = '$v[itemid]'");
	}

	$pids = func_query("SELECT $sql_tbl[order_details].itemid, $sql_tbl[order_details].productid, $sql_tbl[products].distribution FROM $sql_tbl[order_details], $sql_tbl[products] WHERE $sql_tbl[order_details].orderid = '$orderid' AND $sql_tbl[order_details].productid = $sql_tbl[products].productid AND $sql_tbl[products].distribution != ''");
	if ($pids) {
		$keys = array();
		foreach ($pids as $v) {
			if (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[download_keys] WHERE itemid = '$v[itemid]'"))
				continue;

			$keys[$v['itemid']]['download_key'] = keygen($v["productid"], $config["Egoods"]["download_key_ttl"], $v['itemid']);
			$keys[$v['itemid']]['distribution_filename'] = basename($v['distribution']);

		}

		if (!empty($keys)) {
			$order = func_order_data($orderid);
			if (!empty($order)) {
				foreach ($order['products'] as $k => $v) {
					if (isset($keys[$v['itemid']])) {
						$order['products'][$k] = func_array_merge($v,$keys[$v['itemid']]);
					}
				}

				$mail_smarty->assign("products", $order['products']);
				$mail_smarty->assign("order", $order['order']);
				$mail_smarty->assign("userinfo", $order['userinfo']);
				func_send_mail($order['userinfo']["email"], "mail/egoods_download_keys_subj.tpl", "mail/egoods_download_keys.tpl", $config["Company"]["orders_department"], false);
			}
		}
	}

	func_header_location("order.php?orderid=".$orderid);
}
elseif ($mode == 'send_ip' && $orderid) {
	#
	# Send customer IP address to Anti Fraud server
	#
	list($a, $result) = func_send_ip_to_af($orderid, $reason);
	if ($result == "1") {
		$top_message["content"] = func_get_langvar_by_name("msg_antifraud_ip_added");
		$top_message["type"] = "I";
	}
	else {
		$top_message["content"] = func_get_langvar_by_name("txt_antifraud_service_generror");
		$top_message["type"] = "E";
	}

	func_header_location("order.php?orderid=".$orderid);
}

$order_ids = explode(",", $orderid);
if (!is_array($order_ids)) $order_ids[] = $orderid;

foreach ($order_ids as $oid) {
	if (!is_numeric($oid))
		func_header_location("error_message.php?access_denied&id=8");
}

$smarty->assign("show_order_details", "Y");

#
# Collect infos about ordered products
#
require $xcart_dir."/include/history_order.php";

if (!empty($active_modules['Google_Checkout']))
	include $xcart_dir."/modules/Google_Checkout/gcheckout_admin.php";

$order = $order_data["order"];
$userinfo = $order_data["userinfo"];
$products = $order_data["products"];
$giftcerts = $order_data["giftcerts"];

#
##
###
if (!empty($products) && is_array($products)){
        $cost_to_us_total = 0;
        foreach ($products as $k => $v){
                $cost_to_us_total += $v["cost_to_us"] * $v["amount"];
        }
        $smarty->assign("cost_to_us_total", $cost_to_us_total);
}
###
##
#


$ids = array();
foreach ($products as $p) {
	$ids[] = $p['productid'];
}
if (!empty($ids)) {
	$cats = func_query_hash('SELECT categoryid, productid FROM ' . $sql_tbl['products_categories'] . ' WHERE productid IN (' . implode(', ', $ids) . ')', 'productid', false, true);
	$smarty->assign('cats', $cats);
}

$smarty->assign("orderid", $orderid);

# START: random:20341 [2010 Jul 29 14:46] 
require $xcart_dir."/include/order_edit.php";

# END: random:20341 [2010 Jul 29 14:46] 
if ($mode == "status_change") {
	#
	# Update order
	#

	$query_data = array (
		"notes" => $notes
	);
	if (isset($_POST['details'])) {
		$query_data['details'] = func_crypt_order_details($details);
	}
# START: random:20341 [2010 Jul 29 14:46] 
	if ($user_account["flag"] != "FS") {
		$query_data["customer_notes"] = $customer_notes;
	}
# END: random:20341 [2010 Jul 29 14:46] 

	func_array2update("orders", $query_data, "orderid = '$orderid'");

	$top_message = array(
		"content" => func_get_langvar_by_name("txt_order_has_been_changed")
	);

	include $xcart_dir . '/include/send_order_email.php';

	func_header_location("order.php?orderid=".$orderid);
}

if ($mode == 'ref_notify') {

    if (!empty($order['refund_groups'][$notify_mid])) {
        $order['refund_groups'][$notify_mid]['notify_status'] = 'S';
    }

    if (func_check_email($userinfo['email'])) {
        
        $order_notification = func_query_first('SELECT * FROM ' . $sql_tbl['order_status_notifications'] 
            . ' WHERE code = "' . $order['shipping_groups'][$notify_mid]['cb_status'] . '"');

        if ($order_notification && $order_notification['enabled'] == 'Y') {
            
            $mail_smarty->assign('order_notification', $order_notification);
        
            $manufacturer_code = func_query_first_cell('SELECT code FROM ' . $sql_tbl['manufacturers'] 
                . ' WHERE manufacturerid = "' . $notify_mid . '"');
            if (!$manufacturer_code) {
                $manufacturer_code = '';
            }

            foreach ($order['refund_groups'][$notify_mid]['products'] as $pk => $product) {
                $order['refund_groups'][$notify_mid]['products'][$pk]['fee'] = func_calculate_fee($product['extra_data']['price'], $product['ref_price']);
            }
            
            $mail_smarty->assign('order', $order);
            $mail_smarty->assign('userinfo', $userinfo);
            $mail_smarty->assign('manufacturerid', $notify_mid);
            $mail_smarty->assign('manufacturer_code', $manufacturer_code);
            $mail_smarty->assign('statuses', $statuses);
            
            func_send_mail($userinfo['email'], 'mail/refund_notification_subj.tpl', 'mail/refund_notification.tpl', $config['Company']['orders_department'], true);
            // Copy to Orders Department
            func_send_mail($config['Company']['orders_department'], 'mail/refund_notification_subj.tpl', 'mail/refund_notification.tpl', $userinfo['email'], true);

            db_query('UPDATE ' . $sql_tbl['refund_groups'] . ' SET notify_status = "S"'
                . ' WHERE orderid = "' . $orderid . '" AND manufacturerid = "' . $notify_mid . '"');

            $top_message = array(
                'content' => func_get_langvar_by_name('txt_ref_notification_sent')
            );
        }
    } else {
        $top_message = array(
            'content'   => func_get_langvar_by_name('txt_ref_notify_wrong_email'),
            'type'      => 'E'
        );
    }
    func_header_location("order.php?orderid=".$orderid);
}
# START: random:18298_18304_18324 [2009 Jun 08 09:50] 
if ($mode == 'mnf_notify' || $mode == "cidev_send_email_to_operator") {
	#
	# Send manufacturer notification and update order's manufacturer notified status
	#

# START: random:20341 [2010 Jul 29 14:46] 
	if (!empty($order['shipping_groups'][$mnf_id])) {
		$order['shipping_groups'][$mnf_id]['notify_sent'] = 'Y';
# END: random:20341 [2010 Jul 29 14:46] 
	}

	$submit_to_operator = func_query_first_cell('SELECT submit_to_operator'
        . ' FROM ' . $sql_tbl['manufacturers'] . ' WHERE manufacturerid = "' . $mnf_id . '"');

	$order_after_refund = $order;

	if (!empty($order['refund_groups'])) {
		foreach ($order['refund_groups'] as $ship_key => $refund_group) {

			$refund_products = $refund_group['products'];
			$order_products = $order_after_refund['shipping_groups'][$ship_key]['products'];

			foreach ($order_products as $pr_key => $order_product) {

				if (!empty($refund_products[$order_product['productid']])) {

					$ref_product = $refund_products[$order_product['productid']];

					if ($ref_product['ref_qty'] == $order_product['amount']) {
						unset($order_after_refund['shipping_groups'][$ship_key]['products'][$pr_key]);
					} else {
						$order_after_refund['shipping_groups'][$ship_key]['products'][$pr_key]['amount'] -= $ref_product['ref_qty'];
					}

				}

			}
		}
	}

	$mail_smarty->assign("products",$products);
	$mail_smarty->assign("giftcerts",$giftcerts);
	$mail_smarty->assign("userinfo",$userinfo);
	$mail_smarty->assign("manufacturerid",$mnf_id);
	$mail_smarty->assign("show_shipping",$mnf_shipping);
	$mail_smarty->assign('show_customer_notes', $mnf_customer_notes);
	$mail_smarty->assign('statuses', $statuses);

	$mail_smarty->assign('d_email_subject_14', $d_email_subject_14);

	if ($set_status_K == "Y"){
		$mail_smarty->assign('cidev_hide_invoice', "Y");
	}

	$mail_smarty->assign('show_s3stores_site_in_invoice', $show_s3stores_site_in_invoice);

	if ($mode == 'mnf_notify'){

		$mnf_body = func_eol2br(stripslashes($mnf_body));

		$mail_smarty->assign("message_body", $mnf_body);

		if ($submit_to_operator == 'through_distributor_website') {
			$mail_smarty->assign('order', $order);
        		$mail_smarty->assign('mnf_operator_notify', 'Y');
		        func_send_mail($mnf_to, 'mail/order_notification_subj.tpl', 'mail/order_notification.tpl', $config['Company']['orders_department'], true);
        		$mail_smarty->assign('mnf_operator_notify', 'N');
		} else {
        		if (empty($order_after_refund['shipping_groups'][$mnf_id]['products'])) {
			        $top_message = array(
                		'content' => func_get_langvar_by_name('msg_full_refunded_nothing_email', array('distributor' => $order_after_refund['shipping_groups'][$mnf_id]['group_name'])),
		                'type'    => 'I'
        		);
	        	func_header_location('order.php?orderid=' . $orderid);
		        } else {
        			$mail_smarty->assign('order', $order_after_refund);

				func_send_mail($mnf_to, "mail/order_notification_subj.tpl", "mail/order_notification_mnf.tpl", $config['Company']['orders_department'], false);
			}
		}

		$all_sent = false;
# START: random:20341 [2010 Jul 29 14:46] 
		foreach ($order['shipping_groups'] as $v) {
			$all_sent = ($v['notify_sent'] == 'Y');
	# END: random:20341 [2010 Jul 29 14:46] 
			if (!$all_sent) {
				break;
			}
		}

                if ($set_status_K == "Y"){
	                db_query("UPDATE $sql_tbl[order_groups] SET notify_sent = 'Y', dc_status='K'"
        	        . " WHERE orderid = '$orderid' AND manufacturerid='$mnf_id'");
		}
		else  {
# START: random:20341 [2010 Jul 29 14:46] 
			db_query("UPDATE $sql_tbl[order_groups] SET notify_sent = 'Y', dc_status='C'"
		        . " WHERE orderid = '$orderid' AND manufacturerid='$mnf_id'");
# END: random:20341 [2010 Jul 29 14:46] 
		}

		if ($all_sent) {
			if ($set_status_K == "Y"){
//				func_change_order_status($orderid, 'K');
			} else {
	        		if ($submit_to_operator == 'through_distributor_website') {
					func_change_order_status($orderid, 'E');
			        } else {
					func_change_order_status($orderid, 'C');
				}
			}
		}

		$top_message = array("content" => func_get_langvar_by_name("txt_mnf_notification_sent"));
	}
	elseif ($mode == 'cidev_send_email_to_operator'){

	        $d_order_entry_operator_email = func_query_first_cell('SELECT d_order_entry_operator_email FROM ' . $sql_tbl['manufacturers'] . ' WHERE manufacturerid = "' . $mnf_id . '"');
	        $d_url_to_login_to_distributor_website = func_query_first_cell('SELECT d_url_to_login_to_distributor_website FROM ' . $sql_tbl['manufacturers'] . ' WHERE manufacturerid = "' . $mnf_id . '"');
	        $d_login = func_query_first_cell('SELECT d_login FROM ' . $sql_tbl['manufacturers'] . ' WHERE manufacturerid = "' . $mnf_id . '"');
	        $d_password = func_query_first_cell('SELECT d_password FROM ' . $sql_tbl['manufacturers'] . ' WHERE manufacturerid = "' . $mnf_id . '"');

//		$message_body = "Please enter order below in distributor system ASAP.\r\n<br />\r\n<br />";
		$message_body .= func_eol2br(stripslashes($mnf_body));
		$message_body .= "--\r\n";
		$message_body .= "Distributor website login credentials are as follows:\r\n";
		$message_body .= "Website: $d_url_to_login_to_distributor_website\r\n";
		$message_body .= "Login/username: $d_login \r\n";
		$message_body .= "Password: $d_password \r\n";

		$mail_smarty->assign('mnf_operator_notify', 'Y');
		$mail_smarty->assign('message_body', $message_body);
		$mail_smarty->assign('order', $order);
		$mail_smarty->assign('order', $order_after_refund);
		func_send_mail($d_order_entry_operator_email, "mail/order_notification_subj.tpl", "mail/order_notification_mnf.tpl", $config['Company']['orders_department'], false);
		$top_message = array("content" => "Sent.");
                db_query("UPDATE $sql_tbl[order_groups] SET dc_status='E' WHERE orderid = '$orderid' AND manufacturerid='$mnf_id'");

	}

	func_header_location("order.php?orderid=".$orderid);
} 
elseif ($mode == 'request_additional_shipping_charge'){

	$mnf_body = func_eol2br(stripslashes($mnf_body));
	$mail_smarty->assign('message_body', $mnf_body);
	$mail_smarty->assign('order', $order);
	$mail_smarty->assign('mnf_operator_notify', 'Y');
	$mail_smarty->assign('cidev_hide_invoice', 'Y');
	$mail_smarty->assign('d_email_subject_14', $d_email_subject_14);

	func_send_mail($mnf_to, "mail/order_notification_subj.tpl", "mail/order_notification_mnf.tpl", $config['Company']['orders_department'], false);
	$top_message = array("content" => "Sent.");
	db_query("UPDATE $sql_tbl[order_groups] SET dc_status='M' WHERE orderid = '$orderid' AND manufacturerid='$mnf_id'");

	func_header_location("order.php?orderid=".$orderid);
}
elseif ($mode == 'backorder_decision_request'){

	$mnf_body = func_eol2br(stripslashes($mnf_body));
        $mail_smarty->assign('message_body', $mnf_body);
        $mail_smarty->assign('order', $order);
        $mail_smarty->assign('mnf_operator_notify', 'Y');
        $mail_smarty->assign('cidev_hide_invoice', 'Y');
        $mail_smarty->assign('d_email_subject_14', $d_email_subject_14);

        func_send_mail($mnf_to, "mail/order_notification_subj.tpl", "mail/order_notification_mnf.tpl", $config['Company']['orders_department'], false);
        $top_message = array("content" => "Sent.");

        func_header_location("order.php?orderid=".$orderid);
}
elseif ($mode == 'waive'){
	db_query("UPDATE $sql_tbl[order_groups] SET actual_shipping_net='0', actual_shipping_gross='0', actual_shipping_gst='0', actual_shipping_pst='0' WHERE orderid='$orderid' AND manufacturerid='$mnf_id'");
	func_header_location("order.php?orderid=".$orderid);
}
elseif ($mode == 'mode_info_request_survey'){

	$current_dc_status = func_query_first_cell("SELECT dc_status FROM $sql_tbl[order_groups] WHERE orderid = '$orderid' AND manufacturerid='$mnf_id'");

	if ($current_dc_status == "K" && !empty($stock_status) && is_array($stock_status)){

            if (!empty($actual_shipping_net)){
	            db_query("UPDATE $sql_tbl[order_groups] SET actual_shipping_net='".addslashes($actual_shipping_net)."', actual_shipping_gross='".addslashes($actual_shipping_net)."' WHERE orderid='$orderid' AND manufacturerid='$mnf_id'");
            }

	    foreach ($stock_status as $ks => $vs){

//		if ($vs == "all_in_stock") continue;

                if (!empty($items_stock) && is_array($items_stock) && !empty($products) && is_array($products)){

                        foreach ($products as $k => $v){
                                $productid = $v["productid"];
                                $amount = $v["amount"];
                                $item_stock = trim($items_stock[$productid]);

                                if ($item_stock != "" && $vs == "some_in_stock"){
                                        $item_stock = abs(intval($item_stock));
                                        $back = $amount - $item_stock;
                                        db_query("UPDATE $sql_tbl[order_details] SET items_stock='$item_stock', back='$back' WHERE orderid='$orderid' AND productid='$productid'");
                                } elseif ($vs == "discontinued" || $vs == "out_of_stock"){
                                        db_query("UPDATE $sql_tbl[order_details] SET items_stock='0', back='$v[amount]' WHERE orderid='$orderid' AND productid='$productid'");
				}
                        }
                }

                if (!empty($eta_date_mm_dd_yyyy) && is_array($eta_date_mm_dd_yyyy) && !empty($products) && is_array($products)){

                        foreach ($products as $k => $v){
                                $productid = $v["productid"];
                                $eta_date = trim($eta_date_mm_dd_yyyy[$productid]);

                                if ($vs == "some_in_stock" || $vs == "out_of_stock"){
                                        db_query("UPDATE $sql_tbl[products] SET eta_date_mm_dd_yyyy='$eta_date' WHERE productid='$productid'");
                                } elseif ($vs == "discontinued"){
					 db_query("UPDATE $sql_tbl[products] SET eta_date_mm_dd_yyyy='', forsale='N', avail='0' WHERE productid='$productid'");
				}
                        }
                }

                if (!empty($cost_to_us) && is_array($cost_to_us)){
                        foreach ($cost_to_us as $k => $v){
                                $v = trim($v);
                                if ($v != ""){
                                        $v = str_replace(",", ".", $v);
                                        $v = str_replace(" ", "", $v);
                                        db_query("UPDATE $sql_tbl[order_details] SET item_cost_to_us='$v' WHERE orderid='$orderid' AND productid='$k'");
                                }
                        }
                }
	    }
	}

	db_query("UPDATE $sql_tbl[order_groups] SET dc_status='M' WHERE orderid = '$orderid' AND manufacturerid='$mnf_id'");

	$top_message = array("content" => "Done.");
	func_header_location("order.php?orderid=".$orderid);
}

# END: random:18298_18304_18324 [2009 Jun 08 09:50] 
#
# Delete order
#
if ($mode == "printable") {
    func_display("provider/order_printable.tpl",$smarty);
	exit;

} elseif ($mode == "delete") {
	func_delete_order($orderid);
	func_header_location("orders.php?".$query_string);
}

$smarty->assign("main","history_order");

if (!empty($active_modules["Advanced_Order_Management"]) && $mode == "edit") {
	include $xcart_dir."/modules/Advanced_Order_Management/order_edit.php";
}
elseif (!empty($active_modules["Anti_Fraud"]) && $mode == "anti_fraud") {
	if ($order['extra']) {
		$userinfo = $order_data["userinfo"];
		$extra = $order['extra'];
		$extras['ip'] = $extra['ip'];
		$extras['proxy_ip'] = $extra['proxy_ip'];
		include $xcart_dir."/modules/Anti_Fraud/anti_fraud.php";
		db_query("UPDATE $sql_tbl[orders] SET extra = '".addslashes(serialize($extra))."' WHERE orderid = '$orderid'");
	}

	func_header_location("order.php?orderid=".$orderid);
}
elseif (!empty($active_modules["Stop_List"]) && $mode == "block_ip") {
	func_add_ip_to_slist($order['extra']['ip']);
	$top_message["content"] = func_get_langvar_by_name("msg_stoplist_ip_added");
	$top_message["type"] = "I";
	func_header_location("order.php?orderid=".$orderid);
}


$mnfs = func_get_order_manufacturers($orderid);
$smarty->assign("order_manufacturers", $mnfs);

#
##
###
if (!empty($mnfs) && is_array($mnfs)){
	$find_one_DC_Pending_availability_check_OR_Pending_order_entry = false;
	foreach ($mnfs as $k => $v){
		if ($v["dc_status"] == "K" || $v["dc_status"] == "E" || $v["dc_status"] == "M"){
			$find_one_DC_Pending_availability_check_OR_Pending_order_entry = true;
			break;
		}
	}
}

if (!empty($products) && is_array($products)){

	$find_back = false;
	foreach ($products as $k => $v){
		if ($v["back"] > 0){
			$find_back = true;
			break;
		}
	}
}

if ($find_one_DC_Pending_availability_check_OR_Pending_order_entry && $find_back){

	$cidev_items_table = "";
	$cidev_items_table .= '<table width="500px" border="1" cellpadding="5" cellspacing="0" bordercolor="#414236" style="font-family: Arial, Helvetica, sans-serif; font-size: 14px; color: #000000; line-height: 18px;">';
	$cidev_items_table .= '<tr><td width="150px" style="text-align: left; font-weight: bold;">Item number</td><td width="250px" style="text-align: left; font-weight: bold;">Item name</td><td style="text-align: right; font-weight: bold;">Quantity</td></tr>';

	$cidev_instock_items_table = '<table width="500px" border="1" cellpadding="5" cellspacing="0" bordercolor="#414236" style="font-family: Arial, Helvetica, sans-serif; font-size: 14px; color: #000000; line-height: 18px;">';
	$cidev_instock_items_table .= '<tr><td width="150px" style="text-align: left; font-weight: bold;">Item number</td><td width="250px" style="text-align: left; font-weight: bold;">Item name</td><td style="text-align: right; font-weight: bold;">Quantity in stock</td></tr>';

	$cidev_outofstock_items_table = '<table width="500px" border="1" cellpadding="5" cellspacing="0" bordercolor="#414236" style="font-family: Arial, Helvetica, sans-serif; font-size: 14px; color: #000000; line-height: 18px;">';
	$cidev_outofstock_items_table .= '<tr><td width="150px" style="text-align: left; font-weight: bold;">Item number</td><td width="250px" style="text-align: left; font-weight: bold;">Item name</td><td style="text-align: right; font-weight: bold;">"Out of stock" quantity</td></tr>';

	foreach ($products as $k => $v){
		$tmp_sku = substr($v["productcode"], 4);

		$instock_items = $v["amount"] - $v["back"];
		$cidev_instock_items_table .= '<tr><td width="150px" style="text-align: left;">'.$tmp_sku.'</td><td width="250px" style="text-align: left;"><a href="'.$v["links"]["customer"].'">'.$v["product"].'</a></td><td style="text-align: right;">'.$instock_items.'</td></tr>';

		$cidev_outofstock_items_table .= '<tr><td width="150px" style="text-align: left;">'.$tmp_sku.'</td><td width="250px" style="text-align: left;"><a href="'.$v["links"]["customer"].'">'.$v["product"].'</a></td><td style="text-align: right;">'.$v["back"].'</td></tr>';
	}

	$cidev_instock_items_table .= "</table>";
	$cidev_outofstock_items_table .= "</table>";

	$backorder_decision_request_subject_line = $config["backorder_decision_request"]["backorder_subject_line"];
	$backorder_decision_request_subject_line = str_replace("{{orderid}}", $order["order_prefix"].$orderid, $backorder_decision_request_subject_line);
	$backorder_decision_request_subject_line = str_replace("{{c-fullname}}", $userinfo["firstname"], $backorder_decision_request_subject_line);

	$backorder_decision_request_message = $config["backorder_decision_request"]["backorder_message_body"];
	$backorder_decision_request_message = str_replace("{{orderid}}", $order["order_prefix"].$orderid, $backorder_decision_request_message);
	$backorder_decision_request_message = str_replace("{{c-fullname}}", $userinfo["firstname"], $backorder_decision_request_message);
	$backorder_decision_request_message = str_replace("{{instock}}", $cidev_instock_items_table, $backorder_decision_request_message);
	$backorder_decision_request_message = str_replace("{{outofstock}}", $cidev_outofstock_items_table, $backorder_decision_request_message);

	$smarty->assign("backorder_decision_request_subject_line", $backorder_decision_request_subject_line);
	$smarty->assign("backorder_decision_request_message", $backorder_decision_request_message);

}
//func_print_r($mnfs, $products);
###
##
#

//func_print_r($mnfs);

if (!empty($userinfo)){

	$google_billing_address = $userinfo["b_address"] . " ". $userinfo["b_city"]. " ". $userinfo["b_state"]. " ". $userinfo["b_zipcode"];
	$google_billing_address = str_replace(" ", "+", $google_billing_address);
	$smarty->assign("google_billing_address", $google_billing_address);

	$google_shipping_address = $userinfo["s_address"] . " ". $userinfo["s_city"]. " ". $userinfo["s_state"]. " ". $userinfo["s_zipcode"];
	$google_shipping_address = str_replace(" ", "+", $google_shipping_address);
	$smarty->assign("google_shipping_address", $google_shipping_address);

	$userinfo_site_arr = explode("@", $userinfo["email"]);
	$userinfo_site = "http://www.".$userinfo_site_arr[1];
	$smarty->assign("userinfo_site", $userinfo_site);
	
	$userinfo_phone = $userinfo["phone"];
	$userinfo_phone = str_replace(" ", "", $userinfo_phone);	
	$userinfo_phone = str_replace("(", "", $userinfo_phone);	
	$userinfo_phone = str_replace(")", "", $userinfo_phone);	
	$userinfo_area_code = substr($userinfo_phone, 0, 3);

	$Telephone_area_codes = func_query_first("SELECT * FROM $sql_tbl[Telephone_area_codes] WHERE area_code='".addslashes($userinfo_area_code)."'");

	if (!empty($Telephone_area_codes)){
		$Telephone_area_code_info = $Telephone_area_codes["area"] . " (".$Telephone_area_codes["state"] . ")";
		$smarty->assign("Telephone_area_code_info", $Telephone_area_code_info);
	}
}
//func_print_r($google_billing_address, $google_shipping_address, $userinfo_site, $userinfo_area_code);

$cidev_order_details = $order["details"];
$cidev_order_details_err = explode("TransID #", $cidev_order_details);
if (!empty($cidev_order_details_err[1])){
	$cidev_order_details_TransID = substr($cidev_order_details_err[1], 0, -1);
	$smarty->assign("cidev_order_details_TransID", $cidev_order_details_TransID);
}


# END: random:18298_18304_18324 [2009 Jun 08 09:50] 
# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
