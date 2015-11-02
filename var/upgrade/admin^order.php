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
$trusted_post_variables = array('update');

require "./auth.php";
require $xcart_dir."/include/security.php";

x_load('mail','order');

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
	if (isset($HTTP_POST_VARS['details'])) {
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
if ($mode == 'mnf_notify') {
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
	$mail_smarty->assign("message_body",func_eol2br(stripslashes($mnf_body)));
	$mail_smarty->assign("manufacturerid",$mnf_id);
	$mail_smarty->assign("show_shipping",$mnf_shipping);
	$mail_smarty->assign('show_customer_notes', $mnf_customer_notes);
    $mail_smarty->assign('statuses', $statuses);

    if ($submit_to_operator == 'Y') {
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
# START: random:20341 [2010 Jul 29 14:46] 
    db_query("UPDATE $sql_tbl[order_groups] SET notify_sent = 'Y', dc_status='C'"
        . " WHERE orderid = '$orderid' AND manufacturerid='$mnf_id'");
# END: random:20341 [2010 Jul 29 14:46] 

	if ($all_sent) {
        if ($submit_to_operator == 'Y') {
            func_change_order_status($orderid, 'E');
        } else {
		func_change_order_status($orderid, 'C');
	}
	}

	$top_message = array(
		"content" => func_get_langvar_by_name("txt_mnf_notification_sent")
	);
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

# START: random:18298_18304_18324 [2009 Jun 08 09:50] 
if (!empty($active_modules["Manufacturers"])) {
	if (!empty($order['shipping_groups'])) {
		$mnfs = array_keys($order['shipping_groups']);
		if (!empty($mnfs)) {
            $mnfs = func_query_hash('SELECT manufacturerid, manufacturer, mess_body, email, submit_to_operator'
                . " FROM $sql_tbl[manufacturers]"
                . ' WHERE manufacturerid IN ("' . implode('","', $mnfs) . '")', 'manufacturerid', false);
			foreach ($mnfs as $m_id => $mv) {
# START: random:20341 [2010 Jul 29 14:46] 
				$mnfs[$m_id]['notify_sent'] = $order['shipping_groups'][$m_id]['notify_sent'];
# END: random:20341 [2010 Jul 29 14:46] 
			}
			$smarty->assign("order_manufacturers", $mnfs);

		}
	}
}

# END: random:18298_18304_18324 [2009 Jun 08 09:50] 
# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
