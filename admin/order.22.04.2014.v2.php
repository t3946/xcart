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


#
##
###
if ($REQUEST_METHOD == "POST" && $mode == "unlock_order") {

	db_query("UPDATE $sql_tbl[orders] SET time_last_opened_or_saved='0' WHERE orderid='".addslashes($orderid)."'");

	$unlock_message = "Order unlocked.";
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

	$time_unlock = $time_last_opened_or_saved + $time_for_order_in_mins*60;

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

		$warning_message = "This order is locked by $operator_firstname ($login_last_opened_or_saved) until ".date("G:i", $time_unlock).".<br />
If you need to make urgent changes to the order, ask Sergey Vorozhtsov to unlock it.";

		$smarty->assign("warning_message", $warning_message);
		$smarty->assign("you_cannot_modify_order", "Y");
	} else {
//		$lock_message = "Order locked to you from ".date("G:i", $time_last_opened_or_saved)." for ".$time_for_order_in_mins." minutes";
		$lock_message = 'You locked this order. Nobody can make any changes to it.<br /> The order will be unlocked at '.date("G:i", $time_unlock).'. You can also ';

		$smarty->assign("lock_message", $lock_message);
	}
}
###
##
#
//func_print_r($time_last_opened_or_saved);


x_session_register("section_name");

if (!empty($_GET["orderid"]) && !empty($section_name)){
	$redirect_url = "order.php?orderid=".$_GET["orderid"]."#".$section_name;
	$section_name = "";
	x_session_save("section_name");
	func_header_location($redirect_url);
}

#
##
###
require "./gi-find.php";
###
##
#

if ($REQUEST_METHOD == "POST") {

//func_print_r($_POST);
//die();


if ($mode == "submit_message" && !empty($notes) && !empty($orderid)) {

        $section_name = "order_logs";
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
                $section_name = "customer_info";

                if ($send_email == "Y"){
                        $log = "'Apply changes and Send emails' at 'Customer info'";
                } else {
                        $log = "'Apply changes' at 'Customer info'";
                }
	} else {
		$section_name = "order_info";

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

					db_query("UPDATE $sql_tbl[order_details] SET product_options='$order_details_product_options', extra_data='$extra_data' WHERE orderid='$orderid' AND productid='$v[productid]'");
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

}
elseif ($mode == "accounting_apply") {

	$log = "'Update' at 'Accounting' pressed";
	func_log_order($orderid, 'X', $log, $login);

	$section_name = "accounting";
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

    $section_name = "order_info";
    x_session_save("section_name");
    func_log_order($orderid, 'X', $log, $login);

    if (!empty($order['refund_groups'][$notify_mid])) {
        $order['refund_groups'][$notify_mid]['notify_status'] = 'S';
    }

    if (func_check_email($userinfo['email'])) {

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

	$code = func_query_first_cell("SELECT code FROM $sql_tbl[manufacturers] WHERE manufacturerid='$mnf_id'");
	$manufacturer_name = func_query_first_cell("SELECT manufacturer FROM $sql_tbl[manufacturers] WHERE manufacturerid='$mnf_id'");

	if ($mode == "cidev_send_email_to_operator") {
	        $section_name = "order_entry_".$mnf_id;
	        x_session_save("section_name");
        	$log = "'Submit to order entry operator' at '".$manufacturer_name.": Order entry'";
	        func_log_order($orderid, 'X', $log, $login);
	}

        if ($mode == "mnf_notify" && $set_status_K == "Y") {
                $section_name = "request_availability_".$mnf_id;
                x_session_save("section_name");
                $log = "'Send (Request availability)' at '".$manufacturer_name.": Request availability'";
                func_log_order($orderid, 'X', $log, $login);
        }

        if ($mode == "mnf_notify" && $set_status_K != "Y") {
                $section_name = "dispatch_to_distributor_".$mnf_id;
                x_session_save("section_name");
                $log = "'Send (Dispatch to distributor)' at '".$manufacturer_name.": Dispatch to distributor'";
                func_log_order($orderid, 'X', $log, $login);
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
		$mail_smarty->assign('d_email_subject_14', $d_email_subject_14);
		$mail_smarty->assign('order', $order);
		$mail_smarty->assign('order', $order_after_refund);
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

        $section_name = "request_additional_shipping_charge_".$mnf_id;
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
elseif ($mode == 'backorder_decision_request'){

        $section_name = "backorder_decision_request";
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

        $section_name = "request_additional_shipping_charge_".$mnf_id;
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

        $section_name = "information_request_".$mnf_id;
        x_session_save("section_name");
        $log = "'Update the order' at '".$manufacturer_name.": Information request'";
        func_log_order($orderid, 'X', $log, $login);

//	if ($current_dc_status == "K"){


//func_print_r($_POST);
//die();

	    $log = "";

            if (!empty($actual_shipping_net)){

		    $current_actual_shipping_net = func_query_first_cell("SELECT actual_shipping_net FROM $sql_tbl[order_groups] WHERE orderid='$orderid' AND manufacturerid='$mnf_id'");

		    if ($current_actual_shipping_net != $actual_shipping_net){
			$log .= "<B>".$code.":</B> actual_shipping_net: ". $current_actual_shipping_net . " -> ". $actual_shipping_net ."<br />";

	                db_query("UPDATE $sql_tbl[order_groups] SET actual_shipping_net='".addslashes($actual_shipping_net)."', actual_shipping_gross='".addslashes($actual_shipping_net)."' WHERE orderid='$orderid' AND manufacturerid='$mnf_id'");
		    }
            }

	    if (!empty($stock_status) && is_array($stock_status)){
	      foreach ($stock_status as $ks => $vs){

//		if ($vs == "all_in_stock") continue;

                if (!empty($items_stock) && is_array($items_stock) && !empty($products) && is_array($products)){

                        foreach ($products as $k => $v){

			    if ($v["productid"] == $ks){
                                $productid = $v["productid"];
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
					$back = $v["amount"];
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
				$current_avail = func_query_first_cell("SELECT avail FROM $sql_tbl[products] WHERE productid='$productid'");

                                if ($vs == "some_in_stock" || $vs == "out_of_stock"){

                                        if ($current_eta_date_mm_dd_yyyy != $eta_date){
                                                $log .= "<B>".$v["productcode"].":</B> ". $current_eta_date_mm_dd_yyyy . " -> ". $eta_date ."<br />";
                                        }

                                	db_query("UPDATE $sql_tbl[products] SET eta_date_mm_dd_yyyy='$eta_date' WHERE productid='$productid'");
                                } elseif ($vs == "discontinued"){

                                        if ($current_eta_date_mm_dd_yyyy != ''){
                                                $log .= "<B>".$v["productcode"].":</B> eta_date_mm_dd_yyyy: ". $current_eta_date_mm_dd_yyyy . " -> <br />";
                                        }

                                        if ($current_forsale != 'N'){
                                                $log .= "<B>".$v["productcode"].":</B> forsale: ". $current_forsale . " -> N <br />";
                                        }

                                        if ($current_avail != '0'){
                                                $log .= "<B>".$v["productcode"].":</B> avail: ". $current_avail . " -> 0 <br />";
                                        }

					db_query("UPDATE $sql_tbl[products] SET eta_date_mm_dd_yyyy='', forsale='N', avail='0' WHERE productid='$productid'");
				} 
			    }
                        }
                }

#
##

		if (!empty($products) && is_array($products)){
			foreach ($products as $k => $v){
				$productid = $v["productid"];

                                if ($vs == "all_in_stock"){

                                        if (!empty($v["eta_date_mm_dd_yyyy"]) || $v["avail"] == "0"){

		                                $current_eta_date_mm_dd_yyyy = func_query_first_cell("SELECT eta_date_mm_dd_yyyy FROM $sql_tbl[products] WHERE productid='$productid'");
                		                $current_forsale = func_query_first_cell("SELECT forsale FROM $sql_tbl[products] WHERE productid='$productid'");
                                		$current_avail = func_query_first_cell("SELECT avail FROM $sql_tbl[products] WHERE productid='$productid'");


                                                if (!empty($v["eta_date_mm_dd_yyyy"])){
                                                        $tmp_mktime = time() - 24*60*60;
                                                        $eta_date = date("m/d/Y", $tmp_mktime);

                                                        if ($current_eta_date_mm_dd_yyyy != $eta_date){
	                                                        $log .= "<B>".$v["productcode"].":</B> eta_date_mm_dd_yyyy: ". $current_eta_date_mm_dd_yyyy . " -> ".$eta_date."<br />";
                                                        }

                                                        db_query("UPDATE $sql_tbl[products] SET eta_date_mm_dd_yyyy='$eta_date' WHERE productid='$productid'");
                                                }

                                                if ($v["avail"] == "0"){

                                                        if ($current_avail != '1000000'){
                                                                $log .= "<B>".$v["productcode"].":</B> avail: ". $current_avail . " -> 1000000 <br />";
                                                        }

                                                        db_query("UPDATE $sql_tbl[products] SET avail='1000000' WHERE productid='$productid'");
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

        $instock_and_outofstock_items_table = func_instock_and_outofstock_items_table($products, "backorder_decision_request");
        $cidev_instock_items_table = $instock_and_outofstock_items_table["instock"];
        $cidev_outofstock_items_table = $instock_and_outofstock_items_table["outofstock"];

//func_print_r($instock_and_outofstock_items_table["additional_info"]);

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


//	$backorder_decision_request_subject_line = $config["backorder_decision_request"]["backorder_subject_line"];
	$backorder_decision_request_subject_line = str_replace("{{orderid}}", $order["order_prefix"].$orderid, $backorder_decision_request_subject_line);
	$backorder_decision_request_subject_line = str_replace("{{c-fullname}}", $userinfo["firstname"], $backorder_decision_request_subject_line);

//	$backorder_decision_request_message = $config["backorder_decision_request"]["backorder_message_body"];
	$backorder_decision_request_message = str_replace("{{orderid}}", $order["order_prefix"].$orderid, $backorder_decision_request_message);
	$backorder_decision_request_message = str_replace("{{c-fullname}}", $userinfo["firstname"], $backorder_decision_request_message);
	$backorder_decision_request_message = str_replace("{{instock}}", $cidev_instock_items_table, $backorder_decision_request_message);
	$backorder_decision_request_message = str_replace("{{outofstock}}", $cidev_outofstock_items_table, $backorder_decision_request_message);

	$smarty->assign("backorder_decision_request_subject_line", $backorder_decision_request_subject_line);
	$smarty->assign("backorder_decision_request_message", $backorder_decision_request_message);

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

	$fraud_Google_phone_search_exclusions = trim($config["Fraud_check"]["fraud_Google_phone_search_exclusions"]);
	if (!empty($fraud_Google_phone_search_exclusions)){
        	$fraud_Google_phone_search_exclusions = str_replace(",", "+-", $fraud_Google_phone_search_exclusions);
	        $fraud_Google_phone_search_exclusions = str_replace(" ", "+", $fraud_Google_phone_search_exclusions);
        	$fraud_Google_phone_search_exclusions = "+-".$fraud_Google_phone_search_exclusions;
	}

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
	$spokeo_billing_address = $google_billing_address;
	$google_billing_address .= $fraud_Google_address_search_exclusions;

	$smarty->assign("google_billing_address", $google_billing_address);
	$smarty->assign("spokeo_billing_address", $spokeo_billing_address);

	$google_shipping_address = $userinfo["s_address"] . (!empty($userinfo["s_address_2"])? " $userinfo[s_address_2]": "") ." ". $userinfo["s_city"]. " ". $userinfo["s_state"]. " ". $userinfo["s_zipcode"];
	$google_shipping_address = str_replace(" ", "+", $google_shipping_address);
	$google_shipping_address = str_replace("#", "", $google_shipping_address);
	$spokeo_shipping_address = $google_shipping_address;
	$google_shipping_address .= $fraud_Google_address_search_exclusions;
	
	$smarty->assign("google_shipping_address", $google_shipping_address);
	$smarty->assign("spokeo_shipping_address", $spokeo_shipping_address);

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
	$smarty->assign("google_phone", $google_phone);

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

$ca_statuses = func_query("SELECt * FROM $sql_tbl[order_statuses] WHERE type='CA'");
$smarty->assign('ca_statuses', $ca_statuses);

$smarty->assign('current_date', time());


#
##
###
$department_arr = array(
                        "customer" => "Customer",
                        "distributor" => "Distributor",
                        "our_customer_service" => "Our customer service"
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
##
###
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
	}

	$smarty->assign('order_logs', $order_logs);
}
###
##
#
//$allowed_paymentid_for_vt = array("4", "2");
//if (in_array($order["paymentid"], $allowed_paymentid_for_vt)){
	$all_vt_processors = func_query("SELECT paymentid, payment_method, transaction_id_link, vt FROM $sql_tbl[payment_methods] WHERE vt='Y' ORDER BY orderby");
	$smarty->assign('all_vt_processors', $all_vt_processors);
//}

/*
$all_cb_status_eq_P = true;
$cb_status_eq_P_found = false;
if (!empty($order["shipping_groups"])){
	foreach ($order["shipping_groups"] as $k => $v){
		if ($v["cb_status"] == "P"){
			$cb_status_eq_P_found = true;
		} else {
			$all_cb_status_eq_P = false;
			break;
		}
	}
}

if (!$cb_status_eq_P_found){
	$all_cb_status_eq_P = false;
}

if ($all_cb_status_eq_P){
	$smarty->assign("all_cb_status_eq_P", "Y");
}
*/

//func_print_r($order);

//func_print_r($all_vt_processors);

# END: random:18298_18304_18324 [2009 Jun 08 09:50] 
# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
