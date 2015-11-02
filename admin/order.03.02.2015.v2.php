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

x_session_register("order_search_condition");

$po_issued_to_arr = array(
		"S"	=> "S3 Stores, Inc.",
		"A"	=> "Another name"
	);

$smarty->assign("po_issued_to_arr", $po_issued_to_arr);

#
##
###
if ($REQUEST_METHOD == "GET") {

	if ($mode == "clone_order" && !empty($orderid)){

		$order_table = func_query_first("SELECT * FROM $sql_tbl[orders] WHERE orderid='$orderid'");
		
		$details = '';

	        if ($order_table["paymentid"] == 2) {

			$o_details = text_decrypt($order_table["details"]);
			$o_details = stripslashes($o_details);

        	        # Get PO data from order details text
                	$tmp = explode("\n",$o_details);

	                if ($tmp) {
        	                $po_fields = array("po_number" => "PO Number", "company_name" => "Company name",        "name_of_purchaser" => "Name of purchaser",     "position" => "Position", "po_fax" => "po fax", "accounts_payable_full_name" => "accounts payable full name", "accounts_payable_phone" => "accounts payable phone", "accounts_payable_fax" => "accounts payable fax", "accounts_payable_email" => "accounts payable email", "purchase_manager_phone" => "purchase manager phone", "purchase_manager_email" => "purchase manager email", "purchase_manager_phone_ext" => "purchase manager phone ext", "accounts_payable_phone_ext" => "accounts payable phone ext");

                	        $po_details = array();
	                        foreach ($tmp as $line) {

        	                        if (empty($po_fields)) {
                	                        break;
                        	        }
                                	foreach ($po_fields as $k => $po_text) {
                                        	if (($a = strpos($line, $po_text.":")) !== false) {
	                                                $po_details[] = $line;
        	                                        unset($po_fields[$k]);
                	                                break;
                        	                }
                                	}
	                        }

				$details = implode("\n",$po_details);
				$details = text_crypt($details);
        	        }
	        }

                $insert_data = array (
 			'order_prefix' => $order_table['order_prefix'],
 			'login' => $order_table['login'],
 			'membership' => $order_table['membership'],
 			'total' => $order_table['total'],
 			'giftcert_discount' => $order_table['giftcert_discount'],
 			'giftcert_ids' => $order_table['giftcert_ids'],
 			'subtotal' => $order_table['subtotal'],
 			'discount' => $order_table['discount'],
 			'coupon' => $order_table['coupon'],
 			'coupon_discount' => $order_table['coupon_discount'],
 			'shippingid' => $order_table['shippingid'],
 			'tracking' => $order_table['tracking'],
 			'shipping_cost' => $order_table['shipping_cost'],
 			'shipping_costs' => $order_table['shipping_costs'],
 			'tax' => $order_table['tax'],
 			'taxes_applied' => $order_table['taxes_applied'],
 			'date' => time(),
 			'cb_status' => 'Q',
 			'dc_status' => 'T',
 			'bd_status' => 'W',
 			'payment_method' => $order_table['payment_method'],
 			'flag' => $order_table['flag'],
 			'notes' => $order_table['notes'],
// 			'details' => $order_table['details'],
 			'details' => $details,
// 			'customer_notes' => $order_table['customer_notes'],
 			'customer_notes' => '',
 			'customer' => $order_table['customer'],
 			'title' => $order_table['title'],
 			'firstname' => $order_table['firstname'],
 			'lastname' => $order_table['lastname'],
 			'company' => $order_table['company'],
 			'b_title' => $order_table['b_title'],
 			'b_firstname' => $order_table['b_firstname'],
 			'b_lastname' => $order_table['b_lastname'],
 			'b_address' => $order_table['b_address'],
 			'b_city' => $order_table['b_city'],
 			'b_county' => $order_table['b_county'],
 			'b_state' => $order_table['b_state'],
 			'b_country' => $order_table['b_country'],
 			'b_zipcode' => $order_table['b_zipcode'],
 			's_title' => $order_table['s_title'],
 			's_firstname' => $order_table['s_firstname'],
 			's_lastname' => $order_table['s_lastname'],
 			's_address' => $order_table['s_address'],
 			's_city' => $order_table['s_city'],
 			's_county' => $order_table['s_county'],
 			's_state' => $order_table['s_state'],
 			's_country' => $order_table['s_country'],
 			's_zipcode' => $order_table['s_zipcode'],
 			'phone' => $order_table['phone'],
 			'fax' => $order_table['fax'],
 			'url' => $order_table['url'],
 			'email' => $order_table['email'],
 			'language' => $order_table['language'],
 			'clickid' => $order_table['clickid'],
 			'extra' => $order_table['extra'],
 			'membershipid' => $order_table['membershipid'],
 			'paymentid' => $order_table['paymentid'],
 			'payment_surcharge' => $order_table['payment_surcharge'],
 			'tax_number' => $order_table['tax_number'],
 			'tax_exempt' => $order_table['tax_exempt'],
 			'shipping_groups' => $order_table['shipping_groups'],
 			'storefrontid' => $order_table['storefrontid'],
 			'phone_ext' => $order_table['phone_ext'],
 			'orig_po' => $order_table['orig_po'],
 			'total_shipping_charge_on_orig_po' => $order_table['total_shipping_charge_on_orig_po'],
 			'po_issued_to' => $order_table['po_issued_to'],
// 			'ca_status' => '',
 			'po_number' => $order_table['po_number'],
 			'fraud_status' => (!empty($order_table['fraud_status']) ? $order_table['fraud_status'] : 'N'),
 			'overall_fraud_score' => $order_table['overall_fraud_score'],
 			'vt_paymentid' => $order_table['vt_paymentid'],
 			'transaction_id_link' => $order_table['transaction_id_link'],
 			'avs_code' => $order_table['avs_code'],
 			'otrs_ticket' => $order_table['otrs_ticket'],
// 			'tracking_all_filled' => $order_table['tracking_all_filled'],
 			'tracking_all_filled' => '',
// 			'tracking_fill_time' => $order_table['tracking_fill_time'],
 			'tracking_fill_time' => '0',
 			'note_is_taken_care_of' => $order_table['note_is_taken_care_of'],
 			'cloned_from' => $order_table['orderid'],
 			'cloned_by' => $login
		);

		$new_orderid = func_array2insert('orders', $insert_data);

                $order_groups_table = func_query("SELECT * FROM $sql_tbl[order_groups] WHERE orderid='$orderid'");

		if (!empty($order_groups_table) && is_array($order_groups_table)){
			foreach($order_groups_table as $k => $v){
	                	$insert_data2 = array (
        	                	'orderid' => $new_orderid,
        	                	'manufacturerid' => $v['manufacturerid'],
        	                	'shippingid' => $v['shippingid'],
        	                	'shipping' => $v['shipping'],
        	                	'cb_status' => 'Q',
        	                	'dc_status' => 'T',
        	                	'bd_status' => 'W',
//        	                	'tracking' => $v['tracking'],
        	                	'tracking' => '',
        	                	'total_net' => $v['total_net'],
        	                	'total_gst' => $v['total_gst'],
        	                	'total_pst' => $v['total_pst'],
        	                	'total_gross' => $v['total_gross'],
        	                	'shipping_net' => $v['shipping_net'],
        	                	'shipping_gst' => $v['shipping_gst'],
        	                	'shipping_pst' => $v['shipping_pst'],
        	                	'shipping_gross' => $v['shipping_gross'],
        	                	'accounting' => $v['accounting'], // <------ delete orderid
        	                	'profit_margin' => $v['profit_margin'],
//        	                	'acc_paymentid' => $v['acc_paymentid'],
        	                	'acc_paymentid' => '0',
        	                	'manufacturer_data' => $v['manufacturer_data'],
//        	                	'dc_dispatched_time' => $v['dc_dispatched_time'],
        	                	'dc_dispatched_time' => 0,
        	                	'actual_shipping_net' => $v['actual_shipping_net'],
        	                	'actual_shipping_gst' => $v['actual_shipping_gst'],
        	                	'actual_shipping_pst' => $v['actual_shipping_pst'],
        	                	'actual_shipping_gross' => $v['actual_shipping_gross'],
        	                	'shipping_value_selectbox' => $v['shipping_value_selectbox'],
        	                	'additional_shipping_status' => $v['additional_shipping_status'],
        	                	'additional_vt_paymentid' => $v['additional_vt_paymentid'],
        	                	'additional_transaction_id_link' => $v['additional_transaction_id_link'],
        	                	'additional_avs_code' => $v['additional_avs_code'],
//        	                	'part_of_total_transaction_in_amount_of' => $v['part_of_total_transaction_in_amount_of'],
        	                	'part_of_total_transaction_in_amount_of' => '0',
        	                	'ref_to_us_part_of_transaction' => $v['ref_to_us_part_of_transaction'],
        	                	'ru_status' => $v['ru_status'],
        	                	'po_status' => $v['po_status']
	                	);
	
				func_array2insert('order_groups', $insert_data2);	
				unset($insert_data2);
			}
		}


                $order_details_table = func_query("SELECT * FROM $sql_tbl[order_details] WHERE orderid='$orderid'");

                if (!empty($order_details_table) && is_array($order_details_table)){
                        foreach($order_details_table as $k => $v){
                                $insert_data3 = array (
                                        'orderid' => $new_orderid,
                                        'productid' => $v['productid'],
                                        'price' => $v['price'],
                                        'amount' => $v['amount'],
                                        'back' => $v['back'],
                                        'provider' => $v['provider'],
                                        'product_options' => $v['product_options'],
                                        'extra_data' => $v['extra_data'],
                                        'productcode' => $v['productcode'],
                                        'product' => addslashes($v['product']),
                                        'original_provider' => $v['original_provider'],
                                        'items_stock' => $v['items_stock']
                                );

				if (!empty($v['item_cost_to_us'])){
					$insert_data3['item_cost_to_us'] = $v['item_cost_to_us'];
				}

                                func_array2insert('order_details', $insert_data3);
                                unset($insert_data3);
                        }
                }

//		$order_fraud_checks_manual = func_query("SELECT * FROM $sql_tbl[order_fraud_checks] WHERE orderid='$orderid' AND manual_action!=''");
		$order_fraud_checks = func_query("SELECT * FROM $sql_tbl[order_fraud_checks] WHERE orderid='$orderid'");

		if (!empty($order_fraud_checks)){
			foreach ($order_fraud_checks as $k => $v) {
				if (!empty($v) && is_array($v)){

					$fields_arr = array();
					$values_arr = array();

					foreach ($v as $field => $val){
						if ($field != "id" && $field != 'orderid'){
							$fields_arr[] = $field;
							$values_arr[] = "'".addslashes($val)."'";
						}
					}

					if (!empty($fields_arr)){
						$fields_arr[] = "orderid";
						$values_arr[] = "'".$new_orderid."'";

						$query = "INSERT INTO xcart_order_fraud_checks (".implode(",",$fields_arr).") VALUES (".implode(",",$values_arr).")";
						db_query($query);
					}

					unset($fields_arr);
					unset($values_arr);
				}
			}
		}


                $log = 'This order has been cloned from <a style="color: #1411FF;" href="order.php?orderid='.$orderid.'" target="_blank">'.$order_table['order_prefix'].$orderid.'</a>';
                func_log_order($new_orderid, 'S', $log, $login);



//func_print_r($order_fraud_checks_manual);

//die("asd");


		func_header_location("order.php?orderid=".(!empty($new_orderid) ? $new_orderid : $orderid));
	}
}

if ($REQUEST_METHOD == "POST") {

        if ($mode == "order_edit_apply") {

                if (!empty($distributors_to_delete) && is_array($distributors_to_delete)){
                        foreach ($distributors_to_delete as $k => $v){
                                if ($v["delete"] == "Y"){

					$shipping_gross = func_query_first_cell("SELECT shipping_gross FROM $sql_tbl[order_groups] WHERE orderid='$orderid' AND manufacturerid='$k'");
					$shipping_cost = func_query_first_cell("SELECT shipping_cost FROM $sql_tbl[orders] WHERE orderid='$orderid'");
					$new_shipping_cost = $shipping_cost - $shipping_gross;
					if ($new_shipping_cost < 0){
						$new_shipping_cost = 0;
					}
					db_query("UPDATE  $sql_tbl[orders] SET shipping_cost='$new_shipping_cost' WHERE orderid='$orderid'");

                                        db_query("DELETE FROM $sql_tbl[order_groups] WHERE orderid='$orderid' AND manufacturerid='$k'");
                                        unset($groups[$k]);
                                        unset($_POST["groups"][$k]);
                                }
                        }
                }
	}
}

if ($REQUEST_METHOD == "POST" && $mode == "note_is_taken_care_of") {

	$log = "'Customer notes' removed<br /><B>Customer notes:</B> ";
	$log .= func_query_first_cell("SELECT customer_notes FROM $sql_tbl[orders] WHERE orderid='$orderid'");
	func_log_order($orderid, 'X', $log, $login);

	db_query("UPDATE $sql_tbl[orders] SET note_is_taken_care_of='Y' WHERE orderid='$orderid'");
	func_header_location("order.php?orderid=".$orderid."&tab=y#main_order_tabs-");
}

if ($REQUEST_METHOD == "POST" && $mode == "alt_items_add" && !empty($alt_items_add)) {

	$alt_items = func_query_first_cell("SELECT alt_items FROM $sql_tbl[orders] WHERE orderid='$orderid'");

	if (!empty($alt_items)){
		$alt_items_add .= ",".$alt_items;
	}

        db_query("UPDATE $sql_tbl[orders] SET alt_items='$alt_items_add' WHERE orderid='$orderid'");
        func_header_location("order.php?orderid=".$orderid."&tab=y#main_order_tabs-alt_items");
}

if ($REQUEST_METHOD == "POST" && $mode == "alt_items_update" && !empty($alt_items_del) && is_array($alt_items_del) && !empty($all_alt_items) && is_array($all_alt_items)){

	foreach ($alt_items_del as $sku => $v){
		if ($v == "Y"){
			unset($all_alt_items[$sku]);
		}
	}

	$alt_items = implode(",", $all_alt_items);

        db_query("UPDATE $sql_tbl[orders] SET alt_items='$alt_items' WHERE orderid='$orderid'");
        func_header_location("order.php?orderid=".$orderid."&tab=y#main_order_tabs-alt_items");
}

if ($REQUEST_METHOD == "POST" && $mode == "unlock_order") {

	db_query("UPDATE $sql_tbl[orders] SET time_last_opened_or_saved='0' WHERE orderid='".addslashes($orderid)."'");

	$unlock_message = "Order unlocked.";
	$smarty->assign("order_unlocked", "Y");
	$smarty->assign("unlock_message", $unlock_message);
} elseif ($REQUEST_METHOD == "POST" && $mode == "unlock_orders") {

        db_query("UPDATE $sql_tbl[orders] SET time_last_opened_or_saved='0' WHERE login='".addslashes($login)."'");

        $unlock_message = "All orders unlocked.";
        $smarty->assign("order_unlocked", "Y");
        $smarty->assign("unlock_message", $unlock_message);

} else {
	$time_for_order_in_mins = 10; //Setting: operators can be on this mage during this time.
	$current_time = time();

	$last_opened_or_saved = func_query_first("SELECT login_last_opened_or_saved, time_last_opened_or_saved FROM $sql_tbl[orders] WHERE orderid='".addslashes($orderid)."'");
	$login_last_opened_or_saved = $last_opened_or_saved["login_last_opened_or_saved"];
	$time_last_opened_or_saved = $last_opened_or_saved["time_last_opened_or_saved"];

	$diff_time_in_mins = ($current_time - $time_last_opened_or_saved)/60;

	$you_have_right_to_change_order = true;

	if ($login_last_opened_or_saved == $login){
		db_query("UPDATE $sql_tbl[orders] SET time_last_opened_or_saved='$current_time' WHERE orderid='".addslashes($orderid)."'");
		$time_last_opened_or_saved = $current_time;
	} else {
		if ($diff_time_in_mins > $time_for_order_in_mins){
			db_query("UPDATE $sql_tbl[orders] SET login_last_opened_or_saved='".addslashes($login)."', time_last_opened_or_saved='$current_time' WHERE orderid='".addslashes($orderid)."'");
			$time_last_opened_or_saved = $current_time;
		} else {
			$you_have_right_to_change_order = false;
		}
	}

	$time_unlock = $time_last_opened_or_saved + $time_for_order_in_mins*60 + 60*60;

	if (!$you_have_right_to_change_order){

		if ($REQUEST_METHOD == "POST"){
			$top_message["content"] = 'Order not saved!';
			$top_message["type"] = "E";
			func_header_location("order.php?orderid=$orderid");
		}

		$operator_on_order = func_query_first("SELECT firstname, s_firstname, b_firstname FROM $sql_tbl[customers] WHERE login='".addslashes($login_last_opened_or_saved)."'");
		$operator_firstname = "";
		if (!empty($operator_on_order["firstname"])){
			$operator_firstname = $operator_on_order["firstname"];
		} elseif (!empty($operator_on_order["s_firstname"])) {
			$operator_firstname = $operator_on_order["s_firstname"];
		} else {
			$operator_firstname = $operator_on_order["b_firstname"];
		}

//		$warning_message = $operator_firstname."(".$login_last_opened_or_saved.") is working in this order. You will not be able to modify this order untill he complete his work with it or unlock it.";

		$warning_message = "This order is locked by $operator_firstname ($login_last_opened_or_saved) until ".date("G:i", $time_unlock).".
If you need to make urgent changes to the order, ask $operator_firstname to unlock it.";

		$smarty->assign("warning_message", $warning_message);
		$smarty->assign("you_cannot_modify_order", "Y");
	} else {
//		$lock_message = "Order locked to you from ".date("G:i", $time_last_opened_or_saved)." for ".$time_for_order_in_mins." minutes";
		$lock_message = 'You locked this order. Nobody can make any changes to it. The order will be unlocked at '.date("G:i", $time_unlock).'. You can also ';

		$smarty->assign("lock_message", $lock_message);

#
##
		$tmp_diff_time = time() - 60*$time_for_order_in_mins;
		$count_locked_orders = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[orders] WHERE login_last_opened_or_saved='".addslashes($login)."' AND time_last_opened_or_saved > '$tmp_diff_time'");

		$smarty->assign("count_locked_orders", $count_locked_orders);
##
#
	}
}
###
##
#
//func_print_r($time_last_opened_or_saved);


x_session_register("section_name");
x_session_register("section_name_top_message");

if (!empty($_GET["orderid"]) && !empty($section_name)){
	$redirect_url = "order.php?orderid=".$_GET["orderid"]."&tab=y#".$section_name;
	$section_name = "";
	x_session_save("section_name");

	if (!empty($section_name_top_message)){
		$top_message = $section_name_top_message;
		$section_name_top_message = "";
		x_session_save("section_name_top_message");
	}

	func_header_location($redirect_url);
}

#
##
###
$url = "http://helpdesk.s3stores.com/otrs/index.pl";
$curl_err = false;
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1000);
$output = curl_exec($ch);

if (curl_errno($ch) != 0 || curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
	$curl_err = true;
}
curl_close($ch);

if (!$curl_err){
	require "./gi-find.php";
}
###
##
#

if ($REQUEST_METHOD == "POST") {

//func_print_r($_POST);
//die();


if ($mode == "submit_message" && !empty($notes) && !empty($orderid)) {

        $section_name = "main_order_tabs-logs";
        x_session_save("section_name");

	$order_prefix = func_query_first_cell("SELECT order_prefix FROM $sql_tbl[orders] WHERE orderid='$orderid'");
        $notes_arr = explode("\n", $notes);
	$first_line = trim($notes_arr[0]);
        $subj = $order_prefix.$orderid.": ".$first_line;

	$notes_length = strlen($notes);

	if ($notes_length > 260){
		$log1 = "'Post to OTRS only' at 'Important messages'";
		$date_sent = date("j-M-Y_H-i-s");
//		$log2 = "<a href='https://mail.google.com/mail/u/0/?rld=1#search/".$order_prefix.$orderid."+AND+".$date_sent."' target='_blank' style='color: #1411FF;'>Link to Gmail message:".$date_sent."</a>";

		if (empty($ticket_resolver_link)){
			$ticket_resolver_link = func_query_first_cell("SELECT otrs_ticket FROM $sql_tbl[orders] WHERE orderid='$orderid'");
		}

		if (!empty($ticket_resolver_link)){
			$log2 = "Message was posted to <a href='".$ticket_resolver_link."' target='_blank' style='color: #1411FF;'>OTRS ticket system</a>";
		} else {
			$log2 = "Message was posted to OTRS ticket system";
		}

		$subj .= " (posted on ".$date_sent.")";
	} else {
		$log1 = "'Post message' at 'Important messages'";
        	$log2 = $notes;
	}

       	func_log_order($orderid, 'X', $log1, $login);
        func_log_order($orderid, 'S', $log2, $login);

	$body = $notes."\n\nposted by ".$cidev_firstname." (".$login.")";
	$from = $cidev_firstname."<helpdesk@s3stores.com>";
	$to = "orders@s3stores.com";
//	$to = "xcartmaster@gmail.com";

	func_send_simple_mail($to, $subj, $body, $from);

        func_header_location("order.php?orderid=".$orderid);
}

if ($mode == "order_edit_apply") {

	if (!empty($customer_info) && is_array($customer_info)){
                $section_name = "main_order_tabs-customer_info";

                if ($send_email == "Y"){
                        $log = "'Apply changes and Send emails' at 'Customer info'";
                } else {
                        $log = "'Apply changes' at 'Customer info'";
                }
	} else {
		$section_name = "main_order_tabs-order_details";

		if ($send_email == "Y"){
			$log = "'Apply changes and Send emails' at 'Order info'";
		} else {
			$log = "'Apply changes' at 'Order info'";
		}
	}

        x_session_save("section_name");
        func_log_order($orderid, 'X', $log, $login);

#
##
###
        if (!empty($orderid)){

		$log = "";
        	$current_vt_paymentid = func_query_first_cell("SELECT vt_paymentid FROM $sql_tbl[orders] WHERE orderid='$orderid'");
		if ($current_vt_paymentid != $vt_paymentid && !empty($vt_paymentid)){

			$current_vt_paymentid_name = func_query_first_cell("SELECT payment_method FROM $sql_tbl[payment_methods] WHERE paymentid='$current_vt_paymentid'");
			$vt_paymentid_name = func_query_first_cell("SELECT payment_method FROM $sql_tbl[payment_methods] WHERE paymentid='$vt_paymentid'");

	                $log .= "Payment method: " . $current_vt_paymentid_name . " -> " . $vt_paymentid_name;
		}

		$current_transaction_id_link = func_query_first_cell("SELECT transaction_id_link FROM $sql_tbl[orders] WHERE orderid='$orderid'");
                if ($current_transaction_id_link != $transaction_id_link){
                        $log .= "<br />Transaction ID: " . $current_transaction_id_link . " -> " . $transaction_id_link;

			$payment_transaction_id_link = func_query_first_cell("SELECT transaction_id_link FROM $sql_tbl[payment_methods] WHERE paymentid='$vt_paymentid'");
			if (!empty($payment_transaction_id_link)){
				$payment_transaction_id_link = str_replace("{{trans-id}}", $transaction_id_link, $payment_transaction_id_link);
				$log .= "<br /><a href='".$payment_transaction_id_link."' target='_blank' style='color: #1411FF;'>Link to ".$vt_paymentid_name." virtual terminal transaction</a>";
			}


                }

		$current_avs_code = func_query_first_cell("SELECT avs_code FROM $sql_tbl[orders] WHERE orderid='$orderid'");
                if ($current_avs_code != $avs_code){

			$additional_avs_code_description = func_query_first_cell("SELECT description FROM $sql_tbl[avs_codes] WHERE code='".addslashes($avs_code)."'");
			if (!empty($additional_avs_code_description)){
				$additional_avs_code_description_txt = "(".$additional_avs_code_description.")";
			} else $additional_avs_code_description_txt = "";

                        $log .= "<br />AVS code: " . $current_avs_code . " -> " . $avs_code. " " . $additional_avs_code_description_txt;
                }

		if ($log != ""){
//			db_query("UPDATE $sql_tbl[orders] SET vt_paymentid='$vt_paymentid', transaction_id_link='".addslashes($transaction_id_link)."', avs_code='".addslashes($avs_code)."' WHERE orderid='$orderid'");
			db_query("UPDATE $sql_tbl[orders] SET vt_paymentid='$vt_paymentid', transaction_id_link='".$transaction_id_link."', avs_code='".$avs_code."' WHERE orderid='$orderid'");
			func_log_order($orderid, 'S', $log, $login);
		}
	}
###
##
#

#
##
###
	if (!empty($orderid) && !empty($groups) && is_array($groups)){

		foreach ($groups as $k => $v){

			$current_trackings = func_query_first_cell("SELECT tracking FROM $sql_tbl[order_groups] WHERE manufacturerid='$k' AND orderid='$orderid'");
			$current_trackings = unserialize($current_trackings);
			if (empty($current_trackings) || !is_array($current_trackings))
				$current_trackings = array();

			$log = "<B>Tracking numbers:</B><br />";

			$current_trackings_for_diff = array();
                        if (!empty($current_trackings) && is_array($current_trackings)){
				$log .= "<B>Before:</B><br />";
				foreach ($current_trackings as $kk => $vv){
					$shipping_link = func_query_first_cell("SELECT shipping FROM $sql_tbl[tracking_links] WHERE linkid='$vv[linkid]'");
					$current_tracking_str = $shipping_link.": ".$vv["tracknum"];
					$log .= $current_tracking_str."<br />";
					$current_trackings_for_diff[] = $current_tracking_str;
				}
                        }

			db_query("UPDATE $sql_tbl[order_groups] SET tracking='' WHERE manufacturerid='$k' AND orderid='$orderid'");

			$tracknums_to_db = array();
			$trackings_for_diff = array();
			if (!empty($tracknums[$k]) && is_array($tracknums[$k])){
				$tracknums_to_db_index = 0;
				$log .= "<B>Now:</B><br />";
				foreach ($tracknums[$k] as $kk => $vv){
					if (!empty($vv["tracknum"])){
						$tracknums_to_db[$tracknums_to_db_index]["linkid"] = $vv["linkid"];
						$tracknums_to_db[$tracknums_to_db_index]["tracknum"] = $vv["tracknum"];
						$tracknums_to_db_index++;

						$shipping_link = func_query_first_cell("SELECT shipping FROM $sql_tbl[tracking_links] WHERE linkid='$vv[linkid]'");
						$tracking_str = $shipping_link.": ".$vv["tracknum"];
						$log .= $tracking_str."<br />";
						$trackings_for_diff[] = $tracking_str;
					}
				}
			}

			$trackings_diff = array_diff($current_trackings_for_diff, $trackings_for_diff);

                        if (!empty($trackings_diff)){
				func_log_order($orderid, 'X', $log, $login);
                        }

			$tracknums_to_db = addslashes(serialize($tracknums_to_db));
			db_query("UPDATE $sql_tbl[order_groups] SET tracking='$tracknums_to_db' WHERE manufacturerid='$k' AND orderid='$orderid'");
			unset($tracknums_to_db);

/*
#
##
			if ($v["cb_status"] == "O" && !empty($v["actual_shipping_cost_net"]) && $v["actual_shipping_cost_net"] > 0){
				$groups[$k]["shipping_cost_net_orig"] = $v["actual_shipping_cost_net"];
				$groups[$k]["shipping_cost_net"] = $v["actual_shipping_cost_net"];

			        ### LOG: START
			        $current_shipping_cost_net = func_query_first_cell("SELECT shipping_net FROM $sql_tbl[order_groups] WHERE orderid='$orderid' AND manufacturerid='$k'");
			        if ($current_shipping_cost_net != $v["actual_shipping_cost_net"]){
					$tmp_actual_shipping_cost_net = $v["actual_shipping_cost_net"];
					if (empty($tmp_actual_shipping_cost_net)){
						$tmp_actual_shipping_cost_net = 0;
					}
		                        $log = "Shipping cost net: " . $current_shipping_cost_net . " -> " . $tmp_actual_shipping_cost_net;
			                func_log_order($orderid, 'X', $log, $login);
			        }
			        ### LOG: END

				db_query("UPDATE $sql_tbl[order_groups] SET shipping_net='$v[actual_shipping_cost_net]', shipping_gst='$v[actual_shipping_cost_net]', shipping_pst='$v[actual_shipping_cost_net]', shipping_gross='$v[actual_shipping_cost_net]' WHERE manufacturerid='$k' AND orderid='$orderid'");
			}
##
#
*/
		}
	}
###
##
#


	if (!empty($items) && is_array($items) && !empty($orderid)){
		foreach ($items as $k => $v){
			if (!empty($v["productid"])){

				$product_code = func_query_first_cell("SELECT productcode FROM $sql_tbl[products] WHERE productid='$v[productid]'");

/*
                                ### LOG: START
                                $current_eta_date_mm_dd_yyyy = func_query_first_cell("SELECT eta_date_mm_dd_yyyy FROM $sql_tbl[products] WHERE productid='$v[productid]'");
                                if ($current_eta_date_mm_dd_yyyy != $v["eta_date_mm_dd_yyyy"]){
					$product_code = func_query_first_cell("SELECT productcode FROM $sql_tbl[products] WHERE productid='$v[productid]'");

                                        $log = "<B>".$product_code."</B> ETA date: " . $current_eta_date_mm_dd_yyyy . " -> " . $v["eta_date_mm_dd_yyyy"];
                                        func_log_order($orderid, 'X', $log, $login);
                                }
                                ### LOG: END

				db_query("UPDATE $sql_tbl[products] SET eta_date_mm_dd_yyyy='".$v["eta_date_mm_dd_yyyy"]."' WHERE productid='$v[productid]'");
*/

				if (!empty($v["classid_optionid"]) && is_array($v["classid_optionid"])){

					$log = "";
					$options_for_diff = array();
					$order_details_product_options = "";
					foreach ($v["classid_optionid"] as $classid => $optionid){
						$class = func_query_first_cell("SELECT class FROM $sql_tbl[classes] WHERE classid='$classid'");
						$option_name = func_query_first_cell("SELECT option_name FROM $sql_tbl[class_options] WHERE classid='$classid' AND optionid='$optionid'");
						$option_line = $class.": ".$option_name;
						$order_details_product_options .= $option_line."\r\n";
						$options_for_diff[] = $option_line;
						$log .= $option_line."<br />";
					}
					$order_details_product_options = addslashes($order_details_product_options);

					$extra_data = func_query_first_cell("SELECT extra_data FROM $sql_tbl[order_details] WHERE orderid='$orderid' AND productid='$v[productid]'");
					$extra_data = unserialize($extra_data);

					$current_options_for_diff = array();
					if (!empty($extra_data["product_options"]) && is_array($extra_data["product_options"])){
						$log = "<B>".$product_code."</B><br /><B>Before:</B><br />".$log."<B>Now:</B><br />";
						foreach ($extra_data["product_options"] as $classid => $optionid){
	                                                $class = func_query_first_cell("SELECT class FROM $sql_tbl[classes] WHERE classid='$classid'");
        	                                        $option_name = func_query_first_cell("SELECT option_name FROM $sql_tbl[class_options] WHERE classid='$classid' AND optionid='$optionid'");
                	                                $option_line = $class.": ".$option_name;
                        	                        $log .= $option_line."\r\n";
                                	                $current_options_for_diff[] = $option_line;
						}
					}

		                        $options_diff = array_diff($options_for_diff, $current_options_for_diff);

                		        if (!empty($options_diff)){
		                                func_log_order($orderid, 'X', $log, $login);
                		        }

					$extra_data["product_options"] = $v["classid_optionid"];

					$extra_data = addslashes(serialize($extra_data));

//					db_query("UPDATE $sql_tbl[order_details] SET product_options='$order_details_product_options', extra_data='$extra_data' WHERE orderid='$orderid' AND productid='$v[productid]'");
					db_query("UPDATE $sql_tbl[order_details] SET product_options='$order_details_product_options', extra_data='$extra_data' WHERE orderid='$orderid' AND productid='$v[productid]' AND itemid='$k'");
				}
			}
		}
	}

	if (isset($orig_po)){

		$current_orig_po = func_query_first_cell("SELECT orig_po FROM $sql_tbl[orders] WHERE orderid='$orderid'");

		if ($current_orig_po != $orig_po){
			$log = "orig_po: " . $current_orig_po . " -> " . $orig_po;
			func_log_order($orderid, 'X', $log, $login);

			db_query("UPDATE $sql_tbl[orders] SET orig_po='".addslashes($orig_po)."' WHERE orderid='$orderid'");
		}
	}

        if (isset($total_shipping_charge_on_orig_po)){

                $current_total_shipping_charge_on_orig_po = func_query_first_cell("SELECT total_shipping_charge_on_orig_po FROM $sql_tbl[orders] WHERE orderid='$orderid'");

                if ($current_total_shipping_charge_on_orig_po != $total_shipping_charge_on_orig_po){
                        $log = "total_shipping_charge_on_orig_po: " . $current_total_shipping_charge_on_orig_po . " -> " . $total_shipping_charge_on_orig_po;
                        func_log_order($orderid, 'X', $log, $login);

                        db_query("UPDATE $sql_tbl[orders] SET total_shipping_charge_on_orig_po='".addslashes($total_shipping_charge_on_orig_po)."' WHERE orderid='$orderid'");
                }
        }

        if (isset($po_issued_to)){

                $current_po_issued_to = func_query_first_cell("SELECT po_issued_to FROM $sql_tbl[orders] WHERE orderid='$orderid'");
                
                if ($current_po_issued_to != $po_issued_to){
                        $log = "po_issued_to: " . $po_issued_to_arr[$current_po_issued_to] . " -> " . $po_issued_to_arr[$po_issued_to];
                        func_log_order($orderid, 'X', $log, $login);
                        
                        db_query("UPDATE $sql_tbl[orders] SET po_issued_to='".addslashes($po_issued_to)."' WHERE orderid='$orderid'");
                }
        }

}
elseif ($mode == "accounting_apply") {

	$log = "'Update' at 'Accounting' pressed";
	func_log_order($orderid, 'X', $log, $login);

	$section_name = "main_order_tabs-accounting";
	x_session_save("section_name");

	if (!empty($links_to_distributor_invoices) && is_array($links_to_distributor_invoices)){
		foreach ($links_to_distributor_invoices as $manufacturerid => $v){

			$log = "";
			$code = func_query_first_cell("SELECT code FROM $sql_tbl[manufacturers] WHERE manufacturerid='$manufacturerid'");

			$current_links_to_distributor_invoices = func_query("SELECT * FROM $sql_tbl[links_to_distributor_invoices] WHERE orderid='$orderid' AND manufacturerid='$manufacturerid'");
                        $current_links_for_diff = array();
                        if (!empty($current_links_to_distributor_invoices) && is_array($current_links_to_distributor_invoices)){
				foreach ($current_links_to_distributor_invoices as $kk => $vv){
					$current_links_str = "Link: ".$vv["link_to_distributor_invoice"];
					$log .= $current_links_str."<br />";
					$current_links_for_diff[] = $current_links_str;
				}
                        }

			db_query("DELETE FROM $sql_tbl[links_to_distributor_invoices] WHERE orderid='$orderid' AND manufacturerid='$manufacturerid'");

			$new_links_for_diff = array();
			if (!empty($v) && is_array($v)){
				$log = "<B>Before:</B><br />" . $log."<B>Now:</B><br />";
				foreach ($v as $kk => $vv){
					$link_to_distributor_invoice = $vv["link_to_distributor_invoice"];
					if (!empty($link_to_distributor_invoice)){
						$new_links_str = "Link: ".$link_to_distributor_invoice;
						$log .= $new_links_str."<br />";
						$new_links_for_diff[] = $new_links_str;
						db_query("INSERT INTO $sql_tbl[links_to_distributor_invoices] (orderid, link_to_distributor_invoice, manufacturerid) VALUES ('$orderid', '".addslashes($link_to_distributor_invoice)."', '$manufacturerid')");
					}
				}
			}

                        $links_diff = array_diff($current_links_for_diff, $new_links_for_diff);

                        if (!empty($links_diff)){
				$log = "<B>".$code.":</B><br />".$log;
 	                	func_log_order($orderid, 'X', $log, $login);
                        }

			unset($current_links_for_diff);
			unset($new_links_for_diff);
		}
	}

	if (!empty($part_of_total_transaction_in_amount_of) && is_array($part_of_total_transaction_in_amount_of)){
                $log = "";
		foreach ($part_of_total_transaction_in_amount_of as $manufacturerid => $v){
                        $code = func_query_first_cell("SELECT code FROM $sql_tbl[manufacturers] WHERE manufacturerid='$manufacturerid'");

			$new_part_of_total_transaction_in_amount_of = price_format($v);
                        $current_part_of_total_transaction_in_amount_of = func_query_first_cell("SELECT part_of_total_transaction_in_amount_of FROM $sql_tbl[order_groups] WHERE orderid='$orderid' AND manufacturerid='$manufacturerid'");
			if ($current_part_of_total_transaction_in_amount_of != $new_part_of_total_transaction_in_amount_of){
				if (empty($log)) $log = "This invoice is a part of the total transaction in the amount of: <br />";
				$log .= "<B>".$code.":</B> " . $current_part_of_total_transaction_in_amount_of . " -> " . $new_part_of_total_transaction_in_amount_of . "<br />";
			}

			db_query("UPDATE $sql_tbl[order_groups] SET part_of_total_transaction_in_amount_of='$v' WHERE orderid='$orderid' AND manufacturerid='$manufacturerid'");
		}
		
		if (!empty($log)){
			 func_log_order($orderid, 'X', $log, $login);
		}
	}

        if (!empty($ref_to_us_part_of_transaction) && is_array($ref_to_us_part_of_transaction)){
                $log = "";
                foreach ($ref_to_us_part_of_transaction as $manufacturerid => $v){
                        $code = func_query_first_cell("SELECT code FROM $sql_tbl[manufacturers] WHERE manufacturerid='$manufacturerid'");

                        $new_ref_to_us_part_of_transaction = price_format($v);
                        $current_ref_to_us_part_of_transaction = func_query_first_cell("SELECT ref_to_us_part_of_transaction FROM $sql_tbl[order_groups] WHERE orderid='$orderid' AND manufacturerid='$manufacturerid'");
                        if ($current_ref_to_us_part_of_transaction != $new_ref_to_us_part_of_transaction){
                                if (empty($log)) $log = "REF TO US is a part of the total transaction in the amount of: <br />";
                                $log .= "<B>".$code.":</B> " . $current_ref_to_us_part_of_transaction . " -> " . $new_ref_to_us_part_of_transaction . "<br />";
                        }

                        db_query("UPDATE $sql_tbl[order_groups] SET ref_to_us_part_of_transaction='$v' WHERE orderid='$orderid' AND manufacturerid='$manufacturerid'");
                }

                if (!empty($log)){
                         func_log_order($orderid, 'X', $log, $login);
                }
        }


/*
        ### LOG: START
        $current_links_to_distributor_invoices = func_query("SELECT * FROM $sql_tbl[links_to_distributor_invoices] WHERE orderid='$orderid'");
        if (!empty($current_links_to_distributor_invoices) && is_array($current_links_to_distributor_invoices)){
                $log = "Deleted: <br />";
                foreach ($current_links_to_distributor_invoices as $k => $v){
                        $log .= "manufacturerid: " . $v["manufacturerid"] . "; Link: " . addslashes($v["link_to_distributor_invoice"]) . "<br />";
                }
                func_log_order($orderid, 'X', $log, $login);
        }
        ### LOG: END

	$log = "";
	$current_links_to_distributor_invoices = func_query("SELECT * FROM $sql_tbl[links_to_distributor_invoices] WHERE orderid='$orderid'");
        $current_links_for_diff = array();
        if (!empty($current_links_to_distributor_invoices) && is_array($current_links_to_distributor_invoices)){
	        $log .= "<B>Before:</B><br />";
                foreach ($current_links_to_distributor_invoices as $kk => $vv){
	                $shipping_link = func_query_first_cell("SELECT shipping FROM $sql_tbl[tracking_links] WHERE linkid='$vv[linkid]'");
                                        $current_tracking_str = $shipping_link.": ".$vv["tracknum"];
                                        $log .= $current_tracking_str."<br />";
                                        $current_trackings_for_diff[] = $current_tracking_str;
                                }
                        }



	db_query("DELETE FROM $sql_tbl[links_to_distributor_invoices] WHERE orderid='$orderid'");

	if (!empty($links_to_distributor_invoices) && is_array($links_to_distributor_invoices)){

		$log = "Inserted: <br />";
		$write_log = false;
		foreach ($links_to_distributor_invoices as $k => $v){
			if (!empty($v) && is_array($v)){
				foreach ($v as $kk => $vv){
					if (!empty($vv["link_to_distributor_invoice"])){
						db_query("INSERT INTO $sql_tbl[links_to_distributor_invoices] (orderid, link_to_distributor_invoice, manufacturerid) VALUES ('$orderid', '".addslashes($vv["link_to_distributor_invoice"])."', '$k')");
						$log .= "manufacturerid: " . $k . "; Link: " . addslashes($vv["link_to_distributor_invoice"]) . "<br />";
						$write_log = true;
					}
				}
			}
		}

		if ($write_log){
	                func_log_order($orderid, 'X', $log, $login);
		}
	}
*/
}
/*
elseif ($mode == "change_ca_status" && isset($ca_status)) {

	$current_ca_status = func_query_first_cell("SELECT ca_status FROM $sql_tbl[orders] WHERE orderid='$orderid'");

	if ($current_ca_status != $ca_status){

		db_query("UPDATE $sql_tbl[orders] SET ca_status='$ca_status' WHERE orderid='$orderid'");
		
		### LOG: START
		$current_ca_status_name = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='$current_ca_status'");
		$ca_status_name = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='$ca_status'");
		$log = "CA: ".$current_ca_status_name . " -> ".$ca_status_name;
		func_log_order($orderid, 'X', $log, $login);
		### LOG: END

	        $top_message["content"] = "Done.";
        	$top_message["type"] = "I";
		func_header_location("order.php?orderid=$orderid");
	}
}
*/
elseif ($mode == "add_additional_tag" && isset($additional_tag_status)) {

	$status_name = func_query_first_cell("SELECT status FROM $sql_tbl[attention_tags_values] WHERE status_id='$additional_tag_status'");

	$allowed_logins = func_query("SELECT login FROM $sql_tbl[attention_tags_values_logins] WHERE status_id='$additional_tag_status' AND action='set'");

	$allowed_to_set_flag = false;
	if (!empty($allowed_logins) && is_array($allowed_logins)){
		foreach ($allowed_logins as $k => $v){
			if ($v["login"] == $login || $v["login"] == "_ANY_"){
				$allowed_to_set_flag = true;
				break;
			}
		}
	}

	if (!$allowed_to_set_flag){
                $top_message["content"] = "You cannot add the '".$status_name."' tag.";
                $top_message["type"] = "W";
                func_header_location("order.php?orderid=".$orderid."#main_order_tabs-order_details");
	}

	$is_such_additional_tag_status = func_query_first_cell("SELECT status_id FROM $sql_tbl[orders_additional_tags] WHERE orderid='$orderid' AND status_id='$additional_tag_status'");

        if (empty($is_such_additional_tag_status) && $allowed_to_set_flag){

                db_query("INSERT INTO $sql_tbl[orders_additional_tags] (status_id, orderid) VALUES('$additional_tag_status', '$orderid')");

                ### LOG: START
//                $log = "Add additional tag: ".$status_name;
                $log = "'".$status_name."' attention tag added";
                func_log_order($orderid, 'X', $log, $login);
                ### LOG: END

                $top_message["content"] = "Done.";
                $top_message["type"] = "I";
		func_header_location("order.php?orderid=".$orderid."#main_order_tabs-order_details");
        }
}
elseif ($mode == "del_additional_tag" && !empty($del_status_id)) {

        $status_name = func_query_first_cell("SELECT status FROM $sql_tbl[attention_tags_values] WHERE status_id='$del_status_id'");

        $allowed_logins = func_query("SELECT login FROM $sql_tbl[attention_tags_values_logins] WHERE status_id='$del_status_id' AND action='unset'");
        
        $allowed_to_unset_flag = false;
        if (!empty($allowed_logins) && is_array($allowed_logins)){
                foreach ($allowed_logins as $k => $v){
                        if ($v["login"] == $login || $v["login"] == "_ANY_"){
                                $allowed_to_unset_flag = true;
                                break;
                        }
                }
        }

        if (!$allowed_to_unset_flag){
                $top_message["content"] = "You cannot remove the '".$status_name."' tag.";
                $top_message["type"] = "W";
		func_header_location("order.php?orderid=".$orderid."#main_order_tabs-order_details");
        }

        $is_such_additional_tag_status = func_query_first_cell("SELECT status_id FROM $sql_tbl[orders_additional_tags] WHERE orderid='$orderid' AND status_id='$del_status_id'");

        if (!empty($is_such_additional_tag_status) && $allowed_to_unset_flag){

                db_query("DELETE FROM $sql_tbl[orders_additional_tags] WHERE status_id='$del_status_id' AND orderid='$orderid'");

                ### LOG: START
//                $log = "Remove additional tag: ".$status_name;
                $log = "'".$status_name."' attention tag removed";
                func_log_order($orderid, 'X', $log, $login);
                ### LOG: END

                $top_message["content"] = "Done.";
                $top_message["type"] = "I";
		func_header_location("order.php?orderid=".$orderid."#main_order_tabs-order_details");
        }
}

} //if ($REQUEST_METHOD == "POST") 

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

/*
# END: random:20341 [2010 Jul 29 14:46] 
if ($mode == "status_change") {
	#
	# Update order
	#

	$query_data = array (
		"notes" => $notes
	);

        $current_notes = func_query_first_cell("SELECT notes FROM $sql_tbl[orders] WHERE orderid = '$orderid'");
        if ($current_notes != $notes){
        	$log = "<B>Customer Service notes:</B><br />Before:<br />".$current_notes. "<br />Now:<br />". $notes;
                        func_log_order($orderid, 'S', $log, $login);
                }

                $query_data["customer_notes"] = $customer_notes;

	if (isset($_POST['details'])) {
		$query_data['details'] = func_crypt_order_details($details);
	}
# START: random:20341 [2010 Jul 29 14:46] 
	if ($user_account["flag"] != "FS") {

		$current_customer_notes = func_query_first_cell("SELECT customer_notes FROM $sql_tbl[orders] WHERE orderid = '$orderid'");
		if ($current_customer_notes != $customer_notes){
			$log = "<B>customer_notes:</B><br />Before:<br />".$current_customer_notes. "<br />Now:<br />". $customer_notes;
			func_log_order($orderid, 'C', $log, $login);
		}

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

*/

if ($mode == 'ref_notify') {

/*    if ($ref_notify_do_not_send_email == "Y"){ */
    if ($ref_notify_button_clicked == "Update_C2B_status"){
	$log = "'Update C2B status' at 'Refund'";
    } else {
	$log = "'Update C2B status and Send refund notification' at 'Refund'";
    }

    $section_name = "main_order_tabs-order_details";
    x_session_save("section_name");
    func_log_order($orderid, 'X', $log, $login);

    if (!empty($order['refund_groups'][$notify_mid])) {
        $order['refund_groups'][$notify_mid]['notify_status'] = 'S';
    }

    if (func_check_email($userinfo['email'])) {

#
##
###
	if (!empty($order['refund_groups']) && is_array($order['refund_groups']) && !empty($notify_mid)){
		foreach ($order['refund_groups'] as $k => $v){
			if ($k != $notify_mid){
				unset($order['refund_groups'][$k]);
			}
		}
	}
###
##
#

        foreach ($order['refund_groups'][$notify_mid]['products'] as $pk => $product) {
                $order['refund_groups'][$notify_mid]['products'][$pk]['fee'] = func_calculate_fee($product['extra_data']['price'], $product['ref_price']);
        }


#
##
###
        func_update_refunded_groups($order['refund_groups'], $orderid, true, true);
	$tmp_cb_status = func_query_first_cell("SELECT cb_status FROM $sql_tbl[order_groups] WHERE orderid='$orderid' AND manufacturerid='$notify_mid'");
###
##
#
        
        $order_notification = func_query_first('SELECT * FROM ' . $sql_tbl['order_status_notifications'] 
            . ' WHERE code = "' . $tmp_cb_status . '"');


        if ($order_notification /* && $order_notification['enabled'] == 'Y' */) {

	    $order_notification["email_body"] = func_eol2br(stripslashes($order_notification["email_body"]));

            $mail_smarty->assign('order_notification', $order_notification);
        
            $manufacturer_code = func_query_first_cell('SELECT code FROM ' . $sql_tbl['manufacturers'] 
                . ' WHERE manufacturerid = "' . $notify_mid . '"');
            if (!$manufacturer_code) {
                $manufacturer_code = '';
            }

/*
            foreach ($order['refund_groups'][$notify_mid]['products'] as $pk => $product) {
                $order['refund_groups'][$notify_mid]['products'][$pk]['fee'] = func_calculate_fee($product['extra_data']['price'], $product['ref_price']);
            }
*/            
            $mail_smarty->assign('order', $order);
            $mail_smarty->assign('userinfo', $userinfo);
            $mail_smarty->assign('manufacturerid', $notify_mid);
            $mail_smarty->assign('manufacturer_code', $manufacturer_code);
            $mail_smarty->assign('statuses', $statuses);


	    if ($ref_notify_button_clicked == "Update_C2B_status"){
		    func_send_mail($config['Company']['orders_department'], 'mail/refund_notification_subj.tpl', 'mail/refund_notification.tpl', $userinfo['email'], true);
	    } elseif ($ref_notify_button_clicked == "Update_C2B_status_and_Send_refund_notification"){

/*	    if ($ref_notify_do_not_send_email != "Y"){ */
	            func_send_mail($userinfo['email'], 'mail/refund_notification_subj.tpl', 'mail/refund_notification.tpl', $config['Company']['orders_department'], true);
            // Copy to Orders Department
	            func_send_mail($config['Company']['orders_department'], 'mail/refund_notification_subj.tpl', 'mail/refund_notification.tpl', $userinfo['email'], true);


	            db_query('UPDATE ' . $sql_tbl['refund_groups'] . ' SET notify_status = "S"'
        	        . ' WHERE orderid = "' . $orderid . '" AND manufacturerid = "' . $notify_mid . '"');

	
	            $top_message = array(
			'content' => func_get_langvar_by_name('txt_ref_notification_sent')
	            );
	    }
	    else {
	            $top_message = array(
			'content' => 'Done.'
	            );
	    }
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

        $section_name = "main_order_tabs-email_communications";
        x_session_save("section_name");

	$code = func_query_first_cell("SELECT code FROM $sql_tbl[manufacturers] WHERE manufacturerid='$mnf_id'");
	$manufacturer_name = func_query_first_cell("SELECT manufacturer FROM $sql_tbl[manufacturers] WHERE manufacturerid='$mnf_id'");

	if ($mode == "cidev_send_email_to_operator") {
        	$log = "'Submit to order entry operator' at '".$manufacturer_name.": Order entry'";
	        func_log_order($orderid, 'X', $log, $login);
	}

        if ($mode == "mnf_notify" && $set_status_K == "Y") {
                $log = "'Send (Request availability)' at '".$manufacturer_name.": Request availability'";
                func_log_order($orderid, 'X', $log, $login);
        }

        if ($mode == "mnf_notify" && $set_status_K != "Y") {

		if ($bad_time_do_not_send_email == "Y"){
			$log = "'Send (Off-hours dispatch to distributor)' at '".$manufacturer_name.": Dispatch to distributor'";
                        $current_dc_status = func_query_first_cell("SELECT dc_status FROM $sql_tbl[order_groups] WHERE orderid = '$orderid' AND manufacturerid='$mnf_id'");
                        $current_dc_status_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='$current_dc_status'");

                        if ($current_dc_status != "DP"){
                                $new_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='DP'");
                                $log .= "<B>".$code.":</B> dc_status: ". $current_dc_status_value . " -> ". $new_value;

                        	db_query("UPDATE $sql_tbl[order_groups] SET dc_status='DP' WHERE orderid = '$orderid' AND manufacturerid='$mnf_id'");
                        }


                        func_log_order($orderid, 'X', $log, $login);
			$top_message = array("content" => func_get_langvar_by_name("lbl_offhours_dispatch_message"));
                        $section_name_top_message = $top_message;
                        x_session_save("section_name_top_message");

			func_header_location("order.php?orderid=".$orderid);
		}
		else {
	                $log = "'Send (Dispatch to distributor)' at '".$manufacturer_name.": Dispatch to distributor'";
	                func_log_order($orderid, 'X', $log, $login);
		}
        }

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

//				if (!empty($refund_products[$order_product['productid']])) {
				if (!empty($refund_products[$order_product['itemid']])) {

//					$ref_product = $refund_products[$order_product['productid']];
					$ref_product = $refund_products[$order_product['itemid']];

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

	if ($set_status_K == "Y" || $cidev_hide_invoice == "Y"){
		$mail_smarty->assign('cidev_hide_invoice', "Y");
	}

	$mail_smarty->assign('show_s3stores_site_in_invoice', $show_s3stores_site_in_invoice);

	if ($mode == 'mnf_notify'){

		if (!empty($d_shipping_options_name)){
			$mnf_body = str_replace("{{shipping_method}}", $d_shipping_options_name, $mnf_body);
		}

		$mnf_body = func_eol2br(stripslashes($mnf_body));

		$mail_smarty->assign("message_body", $mnf_body);

		if ($submit_to_operator == 'through_distributor_website') {
			$mail_smarty->assign('order', $order);
        		$mail_smarty->assign('mnf_operator_notify', 'Y');
		        func_send_mail($mnf_to, 'mail/order_notification_subj.tpl', 'mail/order_notification.tpl', $config['Company']['orders_department'], true);

	                $log = "<B>From: </B>".$config['Company']['orders_department']."<br /><B>To: </B>".$mnf_to."<br /><B>Subject: </B>".$d_email_subject_14;
        	        func_log_order($orderid, 'X', $log, $login);

        		$mail_smarty->assign('mnf_operator_notify', 'N');
		} else {
        		if (empty($order_after_refund['shipping_groups'][$mnf_id]['products'])) {
			        $top_message = array(
                		'content' => func_get_langvar_by_name('msg_full_refunded_nothing_email', array('distributor' => $order_after_refund['shipping_groups'][$mnf_id]['group_name'])),
		                'type'    => 'I'
        			);

				$section_name_top_message = $top_message;
				x_session_save("section_name_top_message");

		        	func_header_location('order.php?orderid=' . $orderid);
		        } else {

        			$mail_smarty->assign('order', $order_after_refund);

				func_send_mail($mnf_to, "mail/order_notification_subj.tpl", "mail/order_notification_mnf.tpl", $config['Company']['orders_department'], false);
	                        $log = "<B>From: </B>".$config['Company']['orders_department']."<br /><B>To: </B>".$mnf_to."<br /><B>Subject: </B>".$d_email_subject_14;
        	                func_log_order($orderid, 'X', $log, $login);

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


		$current_dc_status = func_query_first_cell("SELECT dc_status FROM $sql_tbl[order_groups] WHERE orderid = '$orderid' AND manufacturerid='$mnf_id'");
		$current_dc_status_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='$current_dc_status'");

                if ($set_status_K == "Y"){

			if ($current_dc_status != "K"){
                                $new_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='K'");
				$log = "<B>".$code.":</B> dc_status: ". $current_dc_status_value . " -> ". $new_value;
				func_log_order($orderid, 'X', $log, $login);
			}

	                db_query("UPDATE $sql_tbl[order_groups] SET notify_sent = 'Y', dc_status='K'"
        	        . " WHERE orderid = '$orderid' AND manufacturerid='$mnf_id'");
		}
		else  {

                        if ($current_dc_status != "C"){
                                $new_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='C'");
                                $log = "<B>".$code.":</B> dc_status: ". $current_dc_status_value . " -> ". $new_value;
                                func_log_order($orderid, 'X', $log, $login);

				db_query("UPDATE $sql_tbl[order_groups] SET dc_dispatched_time='".time()."' WHERE orderid = '$orderid' AND manufacturerid='$mnf_id'");

                        }

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
					func_change_order_status($orderid, 'E', '', $mnf_id);
			        } else {
					func_change_order_status($orderid, 'C', '', $mnf_id);
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
/*
		$message_body .= "<br />--\r\n";
		$message_body .= "<br />Distributor website login credentials are as follows:\r\n";
		$message_body .= "<br />Website: $d_url_to_login_to_distributor_website\r\n";
		$message_body .= "<br />Login/username: $d_login \r\n";
		$message_body .= "<br />Password: $d_password \r\n";
*/

		$mail_smarty->assign('mnf_operator_notify', 'Y');
		$mail_smarty->assign('message_body', $message_body);
		$mail_smarty->assign('d_email_subject_14', $d_email_subject_14);

		if (empty($order_after_refund['shipping_groups'][$mnf_id]['products'])) {
			$mail_smarty->assign('order', $order);
		} else {
			$mail_smarty->assign('order', $order_after_refund);
		}

		func_send_mail($d_order_entry_operator_email, "mail/order_notification_subj.tpl", "mail/order_notification_mnf.tpl", $config['Company']['orders_department'], false);

		$log = "<B>From: </B>".$config['Company']['orders_department']."<br /><B>To: </B>".$d_order_entry_operator_email."<br /><B>Subject: </B>".$d_email_subject_14;
		func_log_order($orderid, 'X', $log, $login);

		$top_message = array("content" => "Sent.");

                $current_dc_status = func_query_first_cell("SELECT dc_status FROM $sql_tbl[order_groups] WHERE orderid = '$orderid' AND manufacturerid='$mnf_id'");
                $current_dc_status_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='$current_dc_status'");
                $code = func_query_first_cell("SELECT code FROM $sql_tbl[manufacturers] WHERE manufacturerid='$mnf_id'");

                if ($current_dc_status != "E"){
 	              $new_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='E'");
                      $log = "<B>".$code.":</B> dc_status: ". $current_dc_status_value . " -> ". $new_value;
                      func_log_order($orderid, 'X', $log, $login);
                }

                db_query("UPDATE $sql_tbl[order_groups] SET dc_status='E' WHERE orderid = '$orderid' AND manufacturerid='$mnf_id'");
	}

	func_header_location("order.php?orderid=".$orderid);
} 
elseif ($mode == 'request_additional_shipping_charge'){

        $code = func_query_first_cell("SELECT code FROM $sql_tbl[manufacturers] WHERE manufacturerid='$mnf_id'");
        $manufacturer_name = func_query_first_cell("SELECT manufacturer FROM $sql_tbl[manufacturers] WHERE manufacturerid='$mnf_id'");

//        $section_name = "request_additional_shipping_charge_".$mnf_id;
	$section_name = "main_order_tabs-email_communications";
        x_session_save("section_name");
        $log = "'Send (Request additional shipping charge)' at '".$manufacturer_name.": Request additional shipping charge'";
        func_log_order($orderid, 'X', $log, $login);

	$mnf_body = func_eol2br(stripslashes($mnf_body));
	$mail_smarty->assign('message_body', $mnf_body);
	$mail_smarty->assign('order', $order);
	$mail_smarty->assign('mnf_operator_notify', 'Y');
	$mail_smarty->assign('cidev_hide_invoice', 'Y');
	$mail_smarty->assign('d_email_subject_14', $d_email_subject_14);

	func_send_mail($mnf_to, "mail/order_notification_subj.tpl", "mail/order_notification_mnf.tpl", $config['Company']['orders_department'], false);
	$top_message = array("content" => "Sent.");

	$log = "<B>From: </B>".$config['Company']['orders_department']."<br /><B>To: </B>".$mnf_to."<br /><B>Subject: </B>".$d_email_subject_14;
	func_log_order($orderid, 'X', $log, $login);


        $current_dc_status = func_query_first_cell("SELECT dc_status FROM $sql_tbl[order_groups] WHERE orderid = '$orderid' AND manufacturerid='$mnf_id'");
        $current_dc_status_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='$current_dc_status'");

        if ($current_dc_status != "M"){
	        $new_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='M'");
                $log = "<B>".$code.":</B> dc_status: ". $current_dc_status_value . " -> ". $new_value;
                func_log_order($orderid, 'X', $log, $login);
	}

	db_query("UPDATE $sql_tbl[order_groups] SET dc_status='M' WHERE orderid = '$orderid' AND manufacturerid='$mnf_id'");

	func_header_location("order.php?orderid=".$orderid);
}
elseif ($mode == 'request_missing_information'){

//        $section_name = "request_missing_information";
	$section_name = "main_order_tabs-email_communications";
        x_session_save("section_name");
        $log = "'Send (Request missing information)' at 'Request missing information'";
        func_log_order($orderid, 'X', $log, $login);

        $mnf_body = func_eol2br(stripslashes($mnf_body));
        $mail_smarty->assign('message_body', $mnf_body);
        $mail_smarty->assign('order', $order);
        $mail_smarty->assign('mnf_operator_notify', 'Y');
        $mail_smarty->assign('cidev_hide_invoice', 'Y');
        $mail_smarty->assign('d_email_subject_14', $d_email_subject_14);

        func_send_mail($mnf_to, "mail/order_notification_subj.tpl", "mail/order_notification_mnf.tpl", $config['Company']['orders_department'], false);
        $top_message = array("content" => "Sent.");

        $log = "<B>From: </B>".$config['Company']['orders_department']."<br /><B>To: </B>".$mnf_to."<br /><B>Subject: </B>".$d_email_subject_14;
        func_log_order($orderid, 'X', $log, $login);

        func_header_location("order.php?orderid=".$orderid);
}
elseif ($mode == 'backorder_decision_request'){

//        $section_name = "backorder_decision_request";
	$section_name = "main_order_tabs-email_communications";
        x_session_save("section_name");
        $log = "'Send (Backorder decision request)' at 'Backorder decision request'";
        func_log_order($orderid, 'X', $log, $login);

	$mnf_body = func_eol2br(stripslashes($mnf_body));
        $mail_smarty->assign('message_body', $mnf_body);
        $mail_smarty->assign('order', $order);
        $mail_smarty->assign('mnf_operator_notify', 'Y');
        $mail_smarty->assign('cidev_hide_invoice', 'Y');
        $mail_smarty->assign('d_email_subject_14', $d_email_subject_14);

        func_send_mail($mnf_to, "mail/order_notification_subj.tpl", "mail/order_notification_mnf.tpl", $config['Company']['orders_department'], false);
        $top_message = array("content" => "Sent.");

        $log = "<B>From: </B>".$config['Company']['orders_department']."<br /><B>To: </B>".$mnf_to."<br /><B>Subject: </B>".$d_email_subject_14;
        func_log_order($orderid, 'X', $log, $login);

        func_header_location("order.php?orderid=".$orderid);
}
elseif ($mode == 'waive'){

        $code = func_query_first_cell("SELECT code FROM $sql_tbl[manufacturers] WHERE manufacturerid='$mnf_id'");
        $manufacturer_name = func_query_first_cell("SELECT manufacturer FROM $sql_tbl[manufacturers] WHERE manufacturerid='$mnf_id'");

//        $section_name = "request_additional_shipping_charge_".$mnf_id;
	$section_name = "main_order_tabs-email_communications";
        x_session_save("section_name");
        $log = "'Waive' at '".$manufacturer_name.": Request additional shipping charge'";
        func_log_order($orderid, 'X', $log, $login);

	$current_actual_shipping_net = func_query_first_cell("SELECT actual_shipping_net FROM $sql_tbl[order_groups] WHERE orderid='$orderid' AND manufacturerid='$mnf_id'");
	if (!($current_actual_shipping_net == 0 || $current_actual_shipping_net == '0.00')){
		$log = "<B>".$code.":</B> actual_shipping_net: ". $current_actual_shipping_net . " -> 0.00";
		func_log_order($orderid, 'X', $log, $login);
	}

	db_query("UPDATE $sql_tbl[order_groups] SET actual_shipping_net='0', actual_shipping_gross='0', actual_shipping_gst='0', actual_shipping_pst='0' WHERE orderid='$orderid' AND manufacturerid='$mnf_id'");
	func_header_location("order.php?orderid=".$orderid);
}
elseif ($mode == 'mode_info_request_survey'){

	$current_dc_status = func_query_first_cell("SELECT dc_status FROM $sql_tbl[order_groups] WHERE orderid = '$orderid' AND manufacturerid='$mnf_id'");
	$code = func_query_first_cell("SELECT code FROM $sql_tbl[manufacturers] WHERE manufacturerid='$mnf_id'");
        $manufacturer_name = func_query_first_cell("SELECT manufacturer FROM $sql_tbl[manufacturers] WHERE manufacturerid='$mnf_id'");

//        $section_name = "information_request_".$mnf_id;
	$section_name = "main_order_tabs-stock_request";
        x_session_save("section_name");
        $log = "'Update the order' at '".$manufacturer_name.": Stock request'";
        func_log_order($orderid, 'X', $log, $login);

//	if ($current_dc_status == "K"){


//func_print_r($_POST);
//die();

	    $log = "";

	    if (isset($actual_shipping_net)){
		    db_query("UPDATE $sql_tbl[order_groups] SET stock_request_shipping_cost='$actual_shipping_net' WHERE orderid='$orderid' AND manufacturerid='$mnf_id'");
	    }

            if (!empty($actual_shipping_net)){

		    $current_actual_shipping_net = func_query_first_cell("SELECT actual_shipping_net FROM $sql_tbl[order_groups] WHERE orderid='$orderid' AND manufacturerid='$mnf_id'");

		    if ($current_actual_shipping_net != $actual_shipping_net){
			$log .= "<B>".$code.":</B> actual_shipping_net: ". $current_actual_shipping_net . " -> ". $actual_shipping_net ."<br />";


###


                                $actual_shipping_gross = $actual_shipping_net;

                                if ($order['shipping_groups'][$mnf_id]['all_distributor_info']['d_drop_ship_fee_select'] == "applies_to_all_orders"){
                                        if (!empty($order['shipping_groups'][$mnf_id]['all_distributor_info']['d_drop_ship_fee_in_us'])){
                                                $actual_shipping_gross += $order['shipping_groups'][$mnf_id]['all_distributor_info']['d_drop_ship_fee_in_us'];
                                        }
                                }
                                elseif ($order['shipping_groups'][$mnf_id]['all_distributor_info']['d_drop_ship_fee_select'] == "applies_to_orders_below_minimum_order_amount_only"){
                                        if (!empty($order['shipping_groups'][$mnf_id]['all_distributor_info']['d_drop_ship_fee_in_us'])){

                                                $sum_cost_to_us = 0;

                                                if (!empty($order['shipping_groups'][$mnf_id]["products"]) && is_array($order['shipping_groups'][$mnf_id]["products"])){
                                                        foreach ($order['shipping_groups'][$mnf_id]["products"] as $v_pr){
                                                                $sum_cost_to_us += $v_pr["cost_to_us"];
                                                        }
                                                }

                                                if ($sum_cost_to_us < $order['shipping_groups'][$mnf_id]['all_distributor_info']['d_minimum_order_amount_in_us'] && $order['shipping_groups'][$mnf_id]['all_distributor_info']['d_minimum_order_amount_in_us'] > 0){
                                                        $actual_shipping_gross += $order['shipping_groups'][$mnf_id]['all_distributor_info']['d_drop_ship_fee_in_us'];
                                                }
                                        }
                                }
###


	                db_query("UPDATE $sql_tbl[order_groups] SET actual_shipping_net='".addslashes($actual_shipping_net)."', actual_shipping_gross='".addslashes($actual_shipping_gross)."' WHERE orderid='$orderid' AND manufacturerid='$mnf_id'");
		    }
            }

	    if (!empty($stock_status) && is_array($stock_status)){

	      foreach ($stock_status as $ks => $vs){

//		if ($vs == "all_in_stock") continue;

                if (!empty($items_stock) && is_array($items_stock) && !empty($products) && is_array($products)){

                        foreach ($products as $k => $v){

			    if ($v["productid"] == $ks){
                                $productid = $v["productid"];
###
				db_query("UPDATE $sql_tbl[order_details] SET stock_request_status='$vs' WHERE orderid='$orderid' AND productid='$productid'");
###

                                $amount = $v["amount"];
                                $item_stock = trim($items_stock[$productid]);

				$current_item_stock = func_query_first_cell("SELECT items_stock FROM $sql_tbl[order_details] WHERE orderid='$orderid' AND productid='$productid'");
				$current_back = func_query_first_cell("SELECT back FROM $sql_tbl[order_details] WHERE orderid='$orderid' AND productid='$productid'");

				$update_in_db = false;

                                if ($item_stock != "" && $vs == "some_in_stock"){
                                        $item_stock = abs(intval($item_stock));
                                        $back = $amount - $item_stock;
					$update_in_db = true;
                                } elseif ($vs == "discontinued" || $vs == "out_of_stock"){
					$item_stock = 0;
					$back = $amount;
					$update_in_db = true;
				} elseif ($vs == "all_in_stock"){
					$item_stock = $amount;
					$back = 0;
					$update_in_db = true;
				}

				if ($update_in_db){
	                                db_query("UPDATE $sql_tbl[order_details] SET items_stock='$item_stock', back='$back' WHERE orderid='$orderid' AND productid='$productid'");

					if ($current_item_stock != $item_stock){
						$log .= "<B>".$v["productcode"].":</B> items_stock: ". $current_item_stock . " -> ". $item_stock ."<br />";
					}

					if ($current_back != $back){
                                        	$log .= "<B>".$v["productcode"].":</B> back: ". $current_back . " -> ". $back ."<br />";
	                                }
				}
			    }
                        }
                }

                if (!empty($eta_date_mm_dd_yyyy) && is_array($eta_date_mm_dd_yyyy) && !empty($products) && is_array($products)){

                        foreach ($products as $k => $v){

			    if ($v["productid"] == $ks){

                                $productid = $v["productid"];
                                $eta_date = trim($eta_date_mm_dd_yyyy[$productid]);

				$current_eta_date_mm_dd_yyyy = func_query_first_cell("SELECT eta_date_mm_dd_yyyy FROM $sql_tbl[products] WHERE productid='$productid'");
				$current_forsale = func_query_first_cell("SELECT forsale FROM $sql_tbl[products] WHERE productid='$productid'");
				$current_r_avail = func_query_first_cell("SELECT r_avail FROM $sql_tbl[products] WHERE productid='$productid'");

                                if ($vs == "some_in_stock" || $vs == "out_of_stock"){

                                        if ($current_eta_date_mm_dd_yyyy != $eta_date){
                                                $log .= "<B>".$v["productcode"].":</B> ". $current_eta_date_mm_dd_yyyy . " -> ". $eta_date ."<br />";
                                        }

                                	db_query("UPDATE $sql_tbl[products] SET eta_date_mm_dd_yyyy='$eta_date' WHERE productid='$productid'");

					$p_offer_backorder = $offer_backorder[$productid];
					if (empty($p_offer_backorder)){
						$p_offer_backorder = "N";
					}

					db_query("UPDATE $sql_tbl[order_details] SET offer_backorder='$p_offer_backorder' WHERE productid='$productid' AND orderid='$orderid'");

                                        if ($current_forsale == 'N'){
                                                $log .= "<B>".$v["productcode"].":</B> forsale: ". $current_forsale . " -> Y <br />";
						db_query("UPDATE $sql_tbl[products] SET forsale='Y', r_avail='0' WHERE productid='$productid'");
                                        }


                                } elseif ($vs == "discontinued"){

                                        if ($current_eta_date_mm_dd_yyyy != ''){
                                                $log .= "<B>".$v["productcode"].":</B> eta_date_mm_dd_yyyy: ". $current_eta_date_mm_dd_yyyy . " -> <br />";
                                        }

                                        if ($current_forsale != 'N'){
                                                $log .= "<B>".$v["productcode"].":</B> forsale: ". $current_forsale . " -> N <br />";
                                        }

                                        if ($current_r_avail != '0'){
                                                $log .= "<B>".$v["productcode"].":</B> r_avail: ". $current_r_avail . " -> 0 <br />";
                                        }

					db_query("UPDATE $sql_tbl[products] SET eta_date_mm_dd_yyyy='', forsale='N', r_avail='0' WHERE productid='$productid'");
/*
					$current_update_search_index = func_query_first_cell("SELECT update_search_index FROM $sql_tbl[products] WHERE productid='$productid'");
					if ($current_update_search_index == "N"){
						db_query("UPDATE $sql_tbl[products] SET update_search_index='D' WHERE productid='$productid'");
					}
*/
				} 
			    }
                        }
                }

#
##

		if (!empty($products) && is_array($products)){
			foreach ($products as $k => $v){

			    if ($v["productid"] == $ks){

				$productid = $v["productid"];

                                if ($vs == "all_in_stock"){

                                        if (!empty($v["eta_date_mm_dd_yyyy"]) || $v["r_avail"] == "0"){

		                                $current_eta_date_mm_dd_yyyy = func_query_first_cell("SELECT eta_date_mm_dd_yyyy FROM $sql_tbl[products] WHERE productid='$productid'");
                		                $current_forsale = func_query_first_cell("SELECT forsale FROM $sql_tbl[products] WHERE productid='$productid'");
                                		$current_r_avail = func_query_first_cell("SELECT r_avail FROM $sql_tbl[products] WHERE productid='$productid'");


                                                if (!empty($v["eta_date_mm_dd_yyyy"])){
                                                        $tmp_mktime = time() - 24*60*60;
                                                        $eta_date = date("m/d/Y", $tmp_mktime);

                                                        if ($current_eta_date_mm_dd_yyyy != $eta_date){
	                                                        $log .= "<B>".$v["productcode"].":</B> eta_date_mm_dd_yyyy: ". $current_eta_date_mm_dd_yyyy . " -> ".$eta_date."<br />";
                                                        }

                                                        db_query("UPDATE $sql_tbl[products] SET eta_date_mm_dd_yyyy='$eta_date' WHERE productid='$productid'");
                                                }

                                                if ($v["r_avail"] == "0"){

                                                        if ($current_r_avail != '1000000'){
                                                                $log .= "<B>".$v["productcode"].":</B> r_avail: ". $current_r_avail . " -> 1000000 <br />";
                                                        }

                                                        db_query("UPDATE $sql_tbl[products] SET r_avail='1000000' WHERE productid='$productid'");
                                                }

                                                if ($v["forsale"] == "N"){

		                                        if ($current_forsale != 'Y'){
                	                	                $log .= "<B>".$v["productcode"].":</B> forsale: ". $current_forsale . " -> Y <br />";
                        		                }

                                                        db_query("UPDATE $sql_tbl[products] SET forsale='Y' WHERE productid='$productid'");
                                                }
                                        }
                                }
			    }
			}
		}
##
#

	     }
	   }

           if (!empty($cost_to_us) && is_array($cost_to_us)){
                        foreach ($cost_to_us as $k => $v){
                                $v = trim($v);
                                if ($v != ""){
                                        $v = str_replace(",", ".", $v);
                                        $v = str_replace(" ", "", $v);

					$current_item_cost_to_us = func_query_first_cell("SELECT item_cost_to_us FROM $sql_tbl[order_details] WHERE orderid='$orderid' AND productid='$k'");

					if ($current_item_cost_to_us != $v){
						$product_code = func_query_first_cell("SELECT productcode FROM $sql_tbl[products] WHERE productid='$k'");
						$log .= "<B>".$product_code.":</B> item_cost_to_us: ". $current_item_cost_to_us. " -> ".$v."<br />";
					}

                                        db_query("UPDATE $sql_tbl[order_details] SET item_cost_to_us='$v' WHERE orderid='$orderid' AND productid='$k'");
                                }
                        }
           }
//	}

	if ($current_dc_status != "M"){
		$current_dc_status_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='$current_dc_status'");
		$new_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='M'");
		$log .= "<B>".$code .":</B> dc_status: ". $current_dc_status_value. " -> ".$new_value."<br />";
	}

	db_query("UPDATE $sql_tbl[order_groups] SET dc_status='M' WHERE orderid = '$orderid' AND manufacturerid='$mnf_id'");


	if (!empty($log)){
		func_log_order($orderid, 'X', $log, $login);
	}

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

//func_print_r($mnfs);

require $xcart_dir."/admin/ground_map.php";

$smarty->assign("order_manufacturers", $mnfs);

#
##
###
if (!empty($mnfs) && is_array($mnfs)){
	$find_one_DC_Pending_availability_check_OR_Pending_order_entry = false;
	$find_one_IO_status =  false;

	foreach ($mnfs as $k => $v){
		if ($v["dc_status"] == "K" || $v["dc_status"] == "E" || $v["dc_status"] == "M" || $v["dc_status"] == "T"){
			$find_one_DC_Pending_availability_check_OR_Pending_order_entry = true;
//			break;
		}

		if ($v["cb_status"] == "IO"){
			$find_one_IO_status = true;
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

        $instock_and_outofstock_items_table = func_instock_and_outofstock_items_table($products, "backorder_decision_request");
        $cidev_instock_items_table = $instock_and_outofstock_items_table["instock"];
        $cidev_outofstock_items_table = $instock_and_outofstock_items_table["outofstock"];
        $cidev_discontinued_items_table = $instock_and_outofstock_items_table["discontinued"];

/*
func_print_r($instock_and_outofstock_items_table["additional_info"]);

	if ($instock_and_outofstock_items_table["additional_info"]["count_instock_items"] == 0 && $instock_and_outofstock_items_table["additional_info"]["count_eta_unknown"] == count($products)){
		$backorder_decision_request_subject_line = $config["backorder_decision_request"]["backorder_subject_line_condition_no_stock_no_eta"];
		$backorder_decision_request_message = $config["backorder_decision_request"]["backorder_message_body_condition_no_stock_no_eta"];
	} 
	elseif ($instock_and_outofstock_items_table["additional_info"]["count_instock_items"] == 0 && $instock_and_outofstock_items_table["additional_info"]["count_eta_with_date"] > 0){
                $backorder_decision_request_subject_line = $config["backorder_decision_request"]["backorder_subject_line_condition_no_stock_defined_eta"];
                $backorder_decision_request_message = $config["backorder_decision_request"]["backorder_message_body_condition_no_stock_defined_eta"];
        }
        elseif ($instock_and_outofstock_items_table["additional_info"]["count_instock_items"] > 0 && $instock_and_outofstock_items_table["additional_info"]["count_out_of_stock_products_with_eta_unknown"] == $instock_and_outofstock_items_table["additional_info"]["count_out_of_stock_items"] && $instock_and_outofstock_items_table["additional_info"]["count_out_of_stock_items"] > 0){
                $backorder_decision_request_subject_line = $config["backorder_decision_request"]["backorder_subject_line_condition_partially_in_stock_no_eta"];
                $backorder_decision_request_message = $config["backorder_decision_request"]["backorder_message_body_condition_partially_in_stock_no_eta"];
        }
        elseif ($instock_and_outofstock_items_table["additional_info"]["count_instock_items"] > 0 && $instock_and_outofstock_items_table["additional_info"]["count_out_of_stock_products_with_eta_with_date"] > 0 && $instock_and_outofstock_items_table["additional_info"]["count_out_of_stock_items"] > 0){
                $backorder_decision_request_subject_line = $config["backorder_decision_request"]["backorder_subject_line_condition_partially_in_stock_defined_eta"];
                $backorder_decision_request_message = $config["backorder_decision_request"]["backorder_message_body_condition_partially_in_stock_defined_eta"];
        }
*/


        if ($instock_and_outofstock_items_table["additional_info"]["count_instock_items"] == 0 && $instock_and_outofstock_items_table["additional_info"]["count_outofstock_products_with_offer_backorder_Y"]!= 0 && $instock_and_outofstock_items_table["additional_info"]["count_discontinued_products_with_empty_offer_backorder"] == 0){
                $backorder_decision_request_subject_line = $config["backorder_decision_request"]["backorder_subject_line_condition_case_a"];
                $backorder_decision_request_message = $config["backorder_decision_request"]["backorder_message_body_condition_case_a"];
        }
	elseif ($instock_and_outofstock_items_table["additional_info"]["count_instock_items"] == 0 && $instock_and_outofstock_items_table["additional_info"]["count_outofstock_products_with_offer_backorder_Y"] == 0 && $instock_and_outofstock_items_table["additional_info"]["count_discontinued_products_with_empty_offer_backorder"] != 0){
                $backorder_decision_request_subject_line = $config["backorder_decision_request"]["backorder_subject_line_condition_case_b"];
                $backorder_decision_request_message = $config["backorder_decision_request"]["backorder_message_body_condition_case_b"];
        }
        elseif ($instock_and_outofstock_items_table["additional_info"]["count_instock_items"] == 0 && $instock_and_outofstock_items_table["additional_info"]["count_outofstock_products_with_offer_backorder_Y"] != 0 && $instock_and_outofstock_items_table["additional_info"]["count_discontinued_products_with_empty_offer_backorder"] != 0){
                $backorder_decision_request_subject_line = $config["backorder_decision_request"]["backorder_subject_line_condition_case_c"];
                $backorder_decision_request_message = $config["backorder_decision_request"]["backorder_message_body_condition_case_c"];
        }
        elseif ($instock_and_outofstock_items_table["additional_info"]["count_instock_items"] != 0 && $instock_and_outofstock_items_table["additional_info"]["count_outofstock_products_with_offer_backorder_Y"] == 0 && $instock_and_outofstock_items_table["additional_info"]["count_discontinued_products_with_empty_offer_backorder"] != 0){
                $backorder_decision_request_subject_line = $config["backorder_decision_request"]["backorder_subject_line_condition_case_d"];
                $backorder_decision_request_message = $config["backorder_decision_request"]["backorder_message_body_condition_case_d"];
        }
        elseif ($instock_and_outofstock_items_table["additional_info"]["count_instock_items"] != 0 && $instock_and_outofstock_items_table["additional_info"]["count_outofstock_products_with_offer_backorder_Y"] != 0 && $instock_and_outofstock_items_table["additional_info"]["count_discontinued_products_with_empty_offer_backorder"] == 0){
                $backorder_decision_request_subject_line = $config["backorder_decision_request"]["backorder_subject_line_condition_case_e"];
                $backorder_decision_request_message = $config["backorder_decision_request"]["backorder_message_body_condition_case_e"];
        }
        elseif ($instock_and_outofstock_items_table["additional_info"]["count_instock_items"] != 0 && $instock_and_outofstock_items_table["additional_info"]["count_outofstock_products_with_offer_backorder_Y"] != 0 && $instock_and_outofstock_items_table["additional_info"]["count_discontinued_products_with_empty_offer_backorder"] != 0){
                $backorder_decision_request_subject_line = $config["backorder_decision_request"]["backorder_subject_line_condition_case_f"];
                $backorder_decision_request_message = $config["backorder_decision_request"]["backorder_message_body_condition_case_f"];
        }



//	$backorder_decision_request_subject_line = $config["backorder_decision_request"]["backorder_subject_line"];
	$backorder_decision_request_subject_line = str_replace("{{orderid}}", $order["order_prefix"].$orderid, $backorder_decision_request_subject_line);
	$backorder_decision_request_subject_line = str_replace("{{c-fullname}}", $userinfo["firstname"], $backorder_decision_request_subject_line);

//	$backorder_decision_request_message = $config["backorder_decision_request"]["backorder_message_body"];
	$backorder_decision_request_message = str_replace("{{orderid}}", $order["order_prefix"].$orderid, $backorder_decision_request_message);
	$backorder_decision_request_message = str_replace("{{c-fullname}}", $userinfo["firstname"], $backorder_decision_request_message);
	$backorder_decision_request_message = str_replace("{{instock}}", $cidev_instock_items_table, $backorder_decision_request_message);
	$backorder_decision_request_message = str_replace("{{outofstock}}", $cidev_outofstock_items_table, $backorder_decision_request_message);
	$backorder_decision_request_message = str_replace("{{discontinued}}", $cidev_discontinued_items_table, $backorder_decision_request_message);

###
        $signature = func_get_signature(false, $products);
        $backorder_decision_request_message = str_replace("{{signature}}", $signature, $backorder_decision_request_message);

        $firstname = trim($userinfo["firstname"]);
        $c_firstname_arr = explode(" ", $firstname);
        $c_firstname = array_shift($c_firstname_arr);
//        $backorder_decision_request_message = str_replace("{{userfirstname}}", $c_firstname, $backorder_decision_request_message);
        $backorder_decision_request_message = str_replace("{{userfirstname}}", $userfirstname, $backorder_decision_request_message);
        $backorder_decision_request_message = str_replace("{{userfullname}}", $userfullname, $backorder_decision_request_message);
###
	$smarty->assign("backorder_decision_request_subject_line", $backorder_decision_request_subject_line);
	$smarty->assign("backorder_decision_request_message", $backorder_decision_request_message);

}

if ($find_one_IO_status){
	$request_missing_information_message = $config["Purchase_Order"]["po_missing_instructions"];
	$request_missing_information_message = str_replace("{{orderid}}", $order["order_prefix"].$orderid, $request_missing_information_message);

	$customer_firstname = $userinfo["firstname"];
	if (strtolower($customer_firstname) == "unknown"){
		$customer_firstname = "Sir/Madam";	
	}

	$request_missing_information_message = str_replace("{{fullname}}", $customer_firstname, $request_missing_information_message);

	$missing_information_replace_text = "";

	if ($userinfo["phone"] == "(000) 000-0000" || strtolower($userinfo["email"]) == "unknown@unknown.com" || strtolower($userinfo["firstname"]) == "unknown"){
		$missing_information_replace_text .= "<B>Contact information</B><br />";

                if (strtolower($userinfo["firstname"]) == "unknown"){
                         $missing_information_replace_text .= "Full Name is missing<br />";
                }
		if ($userinfo["phone"] == "(000) 000-0000"){
			$missing_information_replace_text .= "Phone number is missing<br />";
		}
		if (strtolower($userinfo["email"]) == "unknown@unknown.com"){
			 $missing_information_replace_text .= "Email address is missing<br />";
		}
	}

	if (strtolower($order["po_details"]["name_of_purchaser"]) == "unknown" || $order["po_details"]["purchase_manager_phone"] == "(000) 000-0000" || $order["po_details"]["po_fax"] == "(000) 000-0000" || strtolower($order["po_details"]["purchase_manager_email"]) == "unknown@unknown.com"){
		if (!empty($missing_information_replace_text)){
			$missing_information_replace_text .= "<br />";
		}

		$missing_information_replace_text .= "<B>Purchase manager</B><br />";
		if (strtolower($order["po_details"]["name_of_purchaser"]) == "unknown"){
			$missing_information_replace_text .= "Full name is missing<br />";
		}
		if ($order["po_details"]["purchase_manager_phone"] == "(000) 000-0000"){
			$missing_information_replace_text .= "Phone number is missing<br />";
		}
		if ($order["po_details"]["po_fax"] == "(000) 000-0000"){
			$missing_information_replace_text .= "Fax number is missing<br />";
		}
		if (strtolower($order["po_details"]["purchase_manager_email"]) == "unknown@unknown.com"){
			$missing_information_replace_text .= "Email is missing<br />";
		}
	}

	 if (strtolower($order["po_details"]["accounts_payable_full_name"]) == "unknown" || $order["po_details"]["accounts_payable_phone"] == "(000) 000-0000" || $order["po_details"]["accounts_payable_fax"] == "(000) 000-0000" || strtolower($order["po_details"]["accounts_payable_email"]) == "unknown@unknown.com"){
                if (!empty($missing_information_replace_text)){
                        $missing_information_replace_text .= "<br />";
                }

		$missing_information_replace_text .= "<B>Accounts payable</B><br />";
                if (strtolower($order["po_details"]["accounts_payable_full_name"]) == "unknown"){
                        $missing_information_replace_text .= "Full name is missing<br />";
                }
                if ($order["po_details"]["accounts_payable_phone"] == "(000) 000-0000"){
                        $missing_information_replace_text .= "Phone number is missing<br />";
                }
                if ($order["po_details"]["accounts_payable_fax"] == "(000) 000-0000"){
                        $missing_information_replace_text .= "Fax number is missing<br />";
                }
                if (strtolower($order["po_details"]["accounts_payable_email"]) == "unknown@unknown.com"){
                        $missing_information_replace_text .= "Email is missing<br />";
                }
	}

	$request_missing_information_message = str_replace("{{missing_information}}", $missing_information_replace_text, $request_missing_information_message);

	$smarty->assign("request_missing_information_message", $request_missing_information_message);

	$request_missing_information_subject_line = $config["Purchase_Order"]["po_missing_subject_line"];
        $request_missing_information_subject_line = str_replace("{{orderid}}", $order["order_prefix"].$orderid, $request_missing_information_subject_line);
        $request_missing_information_subject_line = str_replace("{{fullname}}", $userinfo["firstname"], $request_missing_information_subject_line);
	$smarty->assign("request_missing_information_subject_line", $request_missing_information_subject_line);


//func_print_r($request_missing_information_message, $order["po_details"]);
}
###
##
#

if (!empty($userinfo)){

	$b_company = $userinfo["additional_fields"][0]["value"];
	$s_company = $userinfo["additional_fields"][1]["value"];

        $b_company = htmlspecialchars_decode($b_company);
        $s_company = htmlspecialchars_decode($s_company);

	$b_company_company = urlencode($b_company);
	$s_company_company = urlencode($s_company);

	$smarty->assign("b_company_company", $b_company_company);
	$smarty->assign("s_company_company", $s_company_company);

	$fraud_Google_address_search_exclusions = trim($config["Fraud_check"]["fraud_Google_address_search_exclusions"]);
	if (!empty($fraud_Google_address_search_exclusions)){
        	$fraud_Google_address_search_exclusions = str_replace(",", "+-", $fraud_Google_address_search_exclusions);
	        $fraud_Google_address_search_exclusions = str_replace(" ", "+", $fraud_Google_address_search_exclusions);
        	$fraud_Google_address_search_exclusions = "+-".$fraud_Google_address_search_exclusions;
	}

/*
	$fraud_Google_phone_search_exclusions = trim($config["Fraud_check"]["fraud_Google_phone_search_exclusions"]);
	if (!empty($fraud_Google_phone_search_exclusions)){
        	$fraud_Google_phone_search_exclusions = str_replace(",", "+-", $fraud_Google_phone_search_exclusions);
	        $fraud_Google_phone_search_exclusions = str_replace(" ", "+", $fraud_Google_phone_search_exclusions);
        	$fraud_Google_phone_search_exclusions = "+-".$fraud_Google_phone_search_exclusions;
	}
*/

	$fraud_Google_email_search_exclusions = trim($config["Fraud_check"]["fraud_Google_email_search_exclusions"]);
	if (!empty($fraud_Google_email_search_exclusions)){
        	$fraud_Google_email_search_exclusions = str_replace(",", "+-", $fraud_Google_email_search_exclusions);
	        $fraud_Google_email_search_exclusions = str_replace(" ", "+", $fraud_Google_email_search_exclusions);
        	$fraud_Google_email_search_exclusions = "+-".$fraud_Google_email_search_exclusions;
		$smarty->assign("fraud_Google_email_search_exclusions", $fraud_Google_email_search_exclusions);
	}

/*
        $fraud_Google_search_negative_words = trim($config["Fraud_check"]["fraud_Google_search_negative_words"]);
        if (!empty($fraud_Google_search_negative_words)){
                $fraud_Google_search_negative_words = str_replace(" ", "+", $fraud_Google_search_negative_words);
                $fraud_Google_search_negative_words = "+".$fraud_Google_search_negative_words;
		$fraud_Google_address_search_exclusions .= $fraud_Google_search_negative_words;
        }
*/

	$google_billing_address = $userinfo["b_address"] . (!empty($userinfo["b_address_2"])? " $userinfo[b_address_2]": "") ." ". $userinfo["b_city"]. " ". $userinfo["b_state"]. " ". $userinfo["b_zipcode"];
	$google_billing_address = str_replace(" ", "+", $google_billing_address);
	$google_billing_address = str_replace("#", "", $google_billing_address);
	$google_billing_address = str_replace("&", "and", $google_billing_address);
	$spokeo_billing_address = $google_billing_address;
	$google_billing_address .= $fraud_Google_address_search_exclusions;

	$smarty->assign("google_billing_address", $google_billing_address);
	$smarty->assign("spokeo_billing_address", $spokeo_billing_address);

	$google_shipping_address = $userinfo["s_address"] . (!empty($userinfo["s_address_2"])? " $userinfo[s_address_2]": "") ." ". $userinfo["s_city"]. " ". $userinfo["s_state"]. " ". $userinfo["s_zipcode"];
	$google_shipping_address = str_replace(" ", "+", $google_shipping_address);
	$google_shipping_address = str_replace("#", "", $google_shipping_address);
	$google_shipping_address = str_replace("&", "and", $google_shipping_address);

	$spokeo_shipping_address = $google_shipping_address;
	$google_shipping_address .= $fraud_Google_address_search_exclusions;
	
	$smarty->assign("google_shipping_address", $google_shipping_address);
	$smarty->assign("spokeo_shipping_address", $spokeo_shipping_address);

#
##
###
/*
        $po_fax_area_code = "";

        $po_fax_number = $order["po_details"]["po_fax"];
        $po_fax_number = preg_replace("/[^0-9]/S","", $po_fax_number);
        $po_fax_number_strlen = strlen($po_fax_number);

        if ($po_fax_number_strlen == 11 && $po_fax_number{0} == "1"){
                $po_fax_number{0} = "";
                $po_fax_number = trim($po_fax_number);
                $po_fax_number_strlen = strlen($po_fax_number);
        }

        if ($po_fax_number_strlen >= 10){
                $tmp_counter = 0;
                for ($i=$po_fax_number_strlen; $i>=0; $i--){
                        if ($tmp_counter > 7 && $tmp_counter <= 10){
                                $po_fax_area_code = $po_fax_number{$i}.$po_fax_area_code;
                        }
                        $tmp_counter++;
                }

	        $po_fax_area_codes = func_query_first("SELECT * FROM $sql_tbl[Telephone_area_codes] WHERE area_code='".addslashes($po_fax_area_code)."'");
        
	        if (!empty($po_fax_area_codes)){
        	        $po_fax_area_code_info = $po_fax_area_codes["area"] . " (".trim($po_fax_area_codes["state"]) . ")";
                	$smarty->assign("po_fax_area_code_info", $po_fax_area_code_info);
	        }
        }
*/

	if (!empty($order["po_details"]["po_fax"])){
		$po_fax_area_code_info = func_phone_or_fax_area_code_info($order["po_details"]["po_fax"]);
		$smarty->assign("po_fax_area_code_info", $po_fax_area_code_info);

		$purchase_manager_phone_code_info = func_phone_or_fax_area_code_info($order["po_details"]["purchase_manager_phone"]);
		$smarty->assign("purchase_manager_phone_code_info", $purchase_manager_phone_code_info);

                $accounts_payable_phone_code_info = func_phone_or_fax_area_code_info($order["po_details"]["accounts_payable_phone"]);
                $smarty->assign("accounts_payable_phone_code_info", $accounts_payable_phone_code_info);

                $accounts_payable_fax_code_info = func_phone_or_fax_area_code_info($order["po_details"]["accounts_payable_fax"]);
                $smarty->assign("accounts_payable_fax_code_info", $accounts_payable_fax_code_info);
	}
###
##
#

/*
	$userinfo_area_code = "";

	$google_phone = $userinfo["phone"];

	$google_phone = preg_replace("/[^0-9]/S","", $userinfo["phone"]);

	$google_phone_strlen = strlen($google_phone);

	if ($google_phone_strlen == 11 && $google_phone{0} == "1"){
	        $google_phone{0} = "";
        	$google_phone = trim($google_phone);
	        $google_phone_strlen = strlen($google_phone);
	}

	if ($google_phone_strlen >= 10){

		$tmp_counter = 0;
		$google_phone_new = "";
		for ($i=$google_phone_strlen; $i>=0; $i--){

			if ($tmp_counter > 7 && $tmp_counter <= 10){
				$userinfo_area_code = $google_phone{$i}.$userinfo_area_code;
			}

			$google_phone_new = $google_phone{$i}.$google_phone_new;

			if ($tmp_counter == 4){
				$google_phone_new = "-".$google_phone_new;
			}

                        if ($tmp_counter == 7){
                                $google_phone_new = ") ".$google_phone_new;
                        }

                        if ($tmp_counter == 10){
                                $google_phone_new = "(".$google_phone_new;

                	        if ($google_phone_strlen > 10){
        	                        $google_phone_new = "] ".$google_phone_new;
	                        }
                        }

			$tmp_counter++;
		}

		if ($google_phone_strlen > 10){
			$google_phone_new = "[+".$google_phone_new;

			$google_phone_new = urlencode($google_phone_new);
		}

		$google_phone = $google_phone_new;
	}

//func_print_r($google_phone, $google_phone_new, $userinfo_area_code);

	$google_phone = $google_phone . (!empty($userinfo["phone_ext"]) ? " ext $userinfo[phone_ext]": "");
	$google_phone =  str_replace(" ", "+", $google_phone);
	$google_phone .= $fraud_Google_phone_search_exclusions;
*/

	$google_phone_result = func_google_phone_and_area_code($userinfo["phone"], $userinfo["phone_ext"]);
	$google_phone = $google_phone_result["google_phone"];
	$userinfo_area_code = $google_phone_result["userinfo_area_code"];

	$smarty->assign("google_phone", $google_phone);


	if (!empty($order["po_details"]["purchase_manager_phone"])){
		$google_purchase_manager_phone_result = func_google_phone_and_area_code($order["po_details"]["purchase_manager_phone"], $order["po_details"]["purchase_manager_phone_ext"]);
		$smarty->assign("google_purchase_manager_phone", $google_purchase_manager_phone_result["google_phone"]);

                $google_accounts_payable_phone_result = func_google_phone_and_area_code($order["po_details"]["accounts_payable_phone"], $order["po_details"]["accounts_payable_phone_ext"]);
                $smarty->assign("google_accounts_payable_phone", $google_accounts_payable_phone_result["google_phone"]);
	}

	$userinfo_site_arr = explode("@", $userinfo["email"]);
	$userinfo_site = "http://www.".$userinfo_site_arr[1];
	$smarty->assign("userinfo_site", $userinfo_site);

/*	
	$userinfo_phone = $userinfo["phone"];
	$userinfo_phone = str_replace(" ", "", $userinfo_phone);	
	$userinfo_phone = str_replace("(", "", $userinfo_phone);	
	$userinfo_phone = str_replace(")", "", $userinfo_phone);	
	$userinfo_area_code = substr($userinfo_phone, 0, 3);
*/

	$Telephone_area_codes = func_query_first("SELECT * FROM $sql_tbl[Telephone_area_codes] WHERE area_code='".addslashes($userinfo_area_code)."'");

	if (!empty($Telephone_area_codes)){
		$Telephone_area_code_info = $Telephone_area_codes["area"] . " (".trim($Telephone_area_codes["state"]) . ")";
		$smarty->assign("Telephone_area_code_info", $Telephone_area_code_info);
	}
}

$cidev_order_details = $order["details"];

$cidev_order_details_err = explode("TransID #", $cidev_order_details);
if (!empty($cidev_order_details_err[1])){

	if (strpos($cidev_order_details_err[1], ')') !== false){
			$cidev_order_details_TransID_arr = explode(")", $cidev_order_details_err[1]);
			$cidev_order_details_TransID = $cidev_order_details_TransID_arr[0];
	} else {
		$cidev_order_details_TransID = substr($cidev_order_details_err[1], 0, -1);
	}

	$smarty->assign("cidev_order_details_TransID", $cidev_order_details_TransID);
}

#
##
###
if (!empty($mnfs) && is_array($mnfs)){
        foreach ($mnfs as $k => $v){
		$all_distributor_links[$k]["distributor_links"] = func_query("SELECT * FROM $sql_tbl[links_to_distributor_invoices] WHERE orderid='$orderid' && manufacturerid='$k'");
		$all_distributor_links[$k]["count_links_to_distributor_invoices"] = count($all_distributor_links[$k]["distributor_links"]);
        }
}
$smarty->assign('all_distributor_links', $all_distributor_links);
###
##
#

/*
$ca_statuses = func_query("SELECt * FROM $sql_tbl[order_statuses] WHERE type='CA'");
$smarty->assign('ca_statuses', $ca_statuses);
*/

$smarty->assign('current_date', time());


#
##
###
$department_arr = array(
                        "customer" => "Customer",
                        "distributor" => "Distributor",
                        "our_customer_service" => "Our customer service",
			"third_party" => "Compose email to third party"
                        );
$department_arr_keys = array_keys($department_arr);

foreach($department_arr as $department => $department_name){
	$department_info = func_query("SELECT * FROM $sql_tbl[templates_for_communication] WHERE department='$department' AND active='Y' ORDER BY pos");
	$department_full_arr[$department] = $department_info;
}

//func_print_r($department_full_arr);
$smarty->assign("department_full_arr", $department_full_arr);
###
##
#

if (!empty($order["po_number"])){
	$count_po_number = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[orders] WHERE po_number='".addslashes($order["po_number"])."'");

	if ($count_po_number > 1){
		$used_po_for_the_same_order = func_query("SELECT order_prefix, orderid FROM $sql_tbl[orders] WHERE po_number='".addslashes($order["po_number"])."' AND orderid!='$orderid'");
		$last_index_used_po_for_the_same_order = count($used_po_for_the_same_order) - 1;

		$smarty->assign('last_index_used_po_for_the_same_order', $last_index_used_po_for_the_same_order);
		$smarty->assign('used_po_for_the_same_order', $used_po_for_the_same_order);
		$smarty->assign('count_po_number', $count_po_number);
	}
}


#
## xcart_order_page_permissions
###
$operator_membershipid = func_query_first_cell("SELECT membershipid FROM $sql_tbl[customers] WHERE $sql_tbl[customers].login='$login'");

$order_page_permissions = func_query("SELECT * FROM $sql_tbl[order_page_permissions]");
$allowed_elements = array();
if (!empty($order_page_permissions)){
        foreach ($order_page_permissions as $k => $v){
                $membership_ids_arr = explode(",", $v["membership_ids"]);

                $membership_ids_arr[] = 0; // for super admin

                if (in_array($operator_membershipid, $membership_ids_arr)){
                        $allowed_to_use = "Y";
                } else {
                        $allowed_to_use = "N";
                }

                $order_page_permissions[$k]["membership_ids_arr"] = $membership_ids_arr;
                $order_page_permissions[$k]["allowed_to_use"] = $allowed_to_use;
                $allowed_elements[$v["element_id"]] = $allowed_to_use;
        }
}

$smarty->assign("allowed_elements", $allowed_elements);
$smarty->assign("order_page_permissions", $order_page_permissions);
//func_print_r($allowed_elements);
###
##
#


#
##
###
$tabs_key = 0;
/*
if ($order["note_is_taken_care_of"] == "N" && $order["customer_notes"] != ""){
	$main_order_tabs[$tabs_key]["title"] = "Customer notes";
	$main_order_tabs[$tabs_key]["section"] = "customer_notes";
	$main_order_tabs[$tabs_key]["anchor"] = $main_order_tabs[$tabs_key]["section"];
	$tabs_key++;
}
*/

/*
$main_order_tabs[$tabs_key]["title"] = "Order progress";
$main_order_tabs[$tabs_key]["section"] = "order_progress";
$main_order_tabs[$tabs_key]["anchor"] = $main_order_tabs[$tabs_key]["section"];
$tabs_key++;
*/

if ($find_one_DC_Pending_availability_check_OR_Pending_order_entry){
	$main_order_tabs[$tabs_key]["title"] = "Stock request";
	$main_order_tabs[$tabs_key]["section"] = "stock_request";
	$main_order_tabs[$tabs_key]["anchor"] = $main_order_tabs[$tabs_key]["section"];
	$tabs_key++;
}

$main_order_tabs[$tabs_key]["title"] = "Order status";
$main_order_tabs[$tabs_key]["section"] = "order_details";
$main_order_tabs[$tabs_key]["anchor"] = $main_order_tabs[$tabs_key]["section"];
$tabs_key++;

if ($find_back){
 $main_order_tabs[$tabs_key]["title"] = "Alt items";
 $main_order_tabs[$tabs_key]["section"] = "alt_items";
 $main_order_tabs[$tabs_key]["anchor"] = $main_order_tabs[$tabs_key]["section"];
 $tabs_key++;
}

$main_order_tabs[$tabs_key]["title"] = "Customer info";
$main_order_tabs[$tabs_key]["section"] = "customer_info";
$main_order_tabs[$tabs_key]["anchor"] = $main_order_tabs[$tabs_key]["section"];
$tabs_key++;

/*
$main_order_tabs[$tabs_key]["title"] = "Compose email";
$main_order_tabs[$tabs_key]["section"] = "compose_email";
$main_order_tabs[$tabs_key]["anchor"] = $main_order_tabs[$tabs_key]["section"];
$tabs_key++;
*/

if ($allowed_elements["email_tab_1"] != "N"){
	$main_order_tabs[$tabs_key]["title"] = "Email communications";
	$main_order_tabs[$tabs_key]["section"] = "email_communications";
	$main_order_tabs[$tabs_key]["anchor"] = $main_order_tabs[$tabs_key]["section"];
	$tabs_key++;
}

$main_order_tabs[$tabs_key]["title"] = "Accounting";
$main_order_tabs[$tabs_key]["section"] = "accounting";
$main_order_tabs[$tabs_key]["anchor"] = $main_order_tabs[$tabs_key]["section"];
$tabs_key++;

$main_order_tabs[$tabs_key]["title"] = "Logs";
$main_order_tabs[$tabs_key]["section"] = "logs";
$main_order_tabs[$tabs_key]["anchor"] = $main_order_tabs[$tabs_key]["section"];
$tabs_key++;

$main_order_tabs[$tabs_key]["title"] = "Reference";
$main_order_tabs[$tabs_key]["section"] = "reference";
$main_order_tabs[$tabs_key]["anchor"] = $main_order_tabs[$tabs_key]["section"];
$tabs_key++;

$main_order_tabs[$tabs_key]["title"] = "Ground map";
$main_order_tabs[$tabs_key]["section"] = "ground_map";
$main_order_tabs[$tabs_key]["anchor"] = $main_order_tabs[$tabs_key]["section"];
$tabs_key++;


$smarty->assign('main_order_tabs', $main_order_tabs);


$order_tabs[0]["title"] = "Important messages";
$order_tabs[0]["section"] = "important_messages";
$order_tabs[0]["anchor"] = "0";

$order_tabs[1]["title"] = "All logs and messages";
$order_tabs[1]["section"] = "all_logs_and_messages";
$order_tabs[1]["anchor"] = "1";

$smarty->assign('order_tabs', $order_tabs);

$type_names = array (
	"C" => "Customer",
	"S" => "Customer service",
	"X" => "System"
);
$smarty->assign('type_names', $type_names);

$order_logs = func_query("SELECT * FROM $sql_tbl[order_logs] WHERE orderid='$orderid' ORDER BY id DESC");

if (!empty($order_logs) && is_array($order_logs)){

	$link_to_virtual_terminal_transaction = array();

	foreach ($order_logs as $k => $v){

		$log = stripslashes($v["log"]);

		if (substr($log, 0, 12) == "<br /><br />"){
			$log = substr_replace($log, '', 0, 12);
		}

                if (substr($log, 0, 6) == "<br />"){
                        $log = substr_replace($log, '', 0, 6);
                }

		$order_logs[$k]["log"] = $log;
	
		if (!empty($v["login"])){
			$order_logs[$k]["firstname"] = func_query_first_cell("SELECT firstname FROM $sql_tbl[customers] WHERE login='$v[login]'");
		}

		if (strpos($log, 'virtual terminal transaction') !== false){
			$log_arr = explode("<a href", $log);
			$log_arr2 = explode("</a>", $log_arr[1]);
			$link_to_virtual_terminal_transaction[] = "<a href".$log_arr2[0]."</a>";
		}
	}

	$smarty->assign('order_logs', $order_logs);

	if (!empty($link_to_virtual_terminal_transaction)){
		$smarty->assign('link_to_virtual_terminal_transaction', $link_to_virtual_terminal_transaction);
	}
}
###
##
#

//$allowed_paymentid_for_vt = array("4", "2");
//if (in_array($order["paymentid"], $allowed_paymentid_for_vt)){
	$all_vt_processors = func_query("SELECT paymentid, payment_method, transaction_id_link, vt FROM $sql_tbl[payment_methods] WHERE vt='Y' ORDER BY orderby");
	$smarty->assign('all_vt_processors', $all_vt_processors);
//}


$all_cb_status_eq_P = true;
$cb_status_eq_P_found = false;

$all_cb_status_eq_3 = true;
$cb_status_eq_3_found = false;

$all_cb_status_eq_V = true;
$cb_status_eq_V_found = false;

$all_cb_status_eq_H = true;
$cb_status_eq_H_found = false;

$all_cb_status_eq_R = true;
$cb_status_eq_R_found = false;

if (!empty($order["shipping_groups"])){
	foreach ($order["shipping_groups"] as $k => $v){
		if ($v["cb_status"] == "P"){
			$cb_status_eq_P_found = true;
		} else {
			$all_cb_status_eq_P = false;
		}

                if ($v["cb_status"] == "3"){
                        $cb_status_eq_3_found = true;
                } else {
                        $all_cb_status_eq_3 = false;
                }

                if ($v["cb_status"] == "V"){
                        $cb_status_eq_V_found = true;
                } else {
                        $all_cb_status_eq_V = false;
                }

                if ($v["cb_status"] == "H"){
                        $cb_status_eq_H_found = true;
                } else {
                        $all_cb_status_eq_H = false;
                }

                if ($v["cb_status"] == "R"){
                        $cb_status_eq_R_found = true;
                } else {
                        $all_cb_status_eq_R = false;
                }
	}
}

if ($all_cb_status_eq_P && $cb_status_eq_P_found){
	$smarty->assign("all_cb_status_eq_P", "Y");
}
if ($all_cb_status_eq_3 && $cb_status_eq_3_found){
        $smarty->assign("all_cb_status_eq_3", "Y");
}
if ($all_cb_status_eq_V && $cb_status_eq_V_found){
        $smarty->assign("all_cb_status_eq_V", "Y");
}
if ($all_cb_status_eq_H && $cb_status_eq_H_found){
        $smarty->assign("all_cb_status_eq_H", "Y");
}
if ($all_cb_status_eq_R && $cb_status_eq_R_found){
        $smarty->assign("all_cb_status_eq_R", "Y");
}


//func_print_r($order);

//func_print_r($all_vt_processors);

$attention_tags_values = func_query("SELECT * FROM $sql_tbl[attention_tags_values] WHERE active='Y' ORDER BY orderby, status");

if (!empty($attention_tags_values) && is_array($attention_tags_values)){
        foreach ($attention_tags_values as $k => $v){

		$operators = func_query("SELECT * FROM $sql_tbl[attention_tags_values_logins] WHERE status_id='$v[status_id]'");

		$set_active = false;

		if (!empty($operators)){
			foreach ($operators as $kk => $vv){
				if ($vv["action"] == "set" && ($vv["login"] == "_ANY_" || $vv["login"] == $login)){
					$set_active = true;
					break;
				}
			}

	                $attention_tags_values[$k]["operators"] = $operators;
		}

		if (!$set_active){
			$attention_tags_values[$k]["active"] = "N";
		}
        }
}

$smarty->assign("attention_tags_values", $attention_tags_values);

# END: random:18298_18304_18324 [2009 Jun 08 09:50] 
# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
