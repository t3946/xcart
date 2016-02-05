<?php

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

$transaction_logs = func_query("SELECT $sql_tbl[transaction_logs].*, $sql_tbl[payment_methods].payment_method, $sql_tbl[payment_methods].transaction_id_link, $sql_tbl[payment_methods].transaction_link_anchor, $sql_tbl[customers].firstname, $sql_tbl[customers].usertype FROM $sql_tbl[transaction_logs] LEFT JOIN $sql_tbl[payment_methods] ON $sql_tbl[payment_methods].paymentid=$sql_tbl[transaction_logs].paymentid LEFT JOIN $sql_tbl[customers] ON $sql_tbl[customers].login=$sql_tbl[transaction_logs].login WHERE $sql_tbl[transaction_logs].orderid='$orderid' ORDER BY $sql_tbl[transaction_logs].date");

if (!empty($transaction_logs)){
	foreach ($transaction_logs as $k_transaction_log => $v_transaction_log){
//		$transaction_logs[$k_transaction_log]["unserialize_transaction_log"] = unserialize($v_transaction_log["transaction_log"]);
	}
}

#
## For OLD orders. Get First transaction
###
if ($transaction_logs[0]["usertype"] != "C"){

	$first_customers_transaction_found = false;

	#
	# For PayPal order
	#
	if (strpos($order["details"], "TransID #") !== false){
		$cidev_order_details_err = explode("TransID #", $order["details"]);
        	if (strpos($cidev_order_details_err[1], ')') !== false){
                	        $cidev_order_details_TransID_arr = explode(")", $cidev_order_details_err[1]);
                        	$cidev_order_details_TransID = $cidev_order_details_TransID_arr[0];
	        } else {
        	        $cidev_order_details_TransID = substr($cidev_order_details_err[1], 0, -1);
	        }

		if (strpos($order["details"], "Reason:") !== false){
			$cidev_transaction_status_err = explode("Reason:", $order["details"]);
			$cidev_transaction_status_err2 = explode(":", $cidev_transaction_status_err[1]);
			$order_transaction_status = trim($cidev_transaction_status_err2[0]);
		} else {
			$transaction_status = "";
		}

		$transaction_logs["-1"]["id"] = "-1";
		$transaction_logs["-1"]["orderid"] = $orderid;
		$transaction_logs["-1"]["usertype"] = "C"; // Customer
		$transaction_logs["-1"]["paymentid"] = $order["paymentid"];
		$transaction_logs["-1"]["transaction_id"] = $cidev_order_details_TransID;
		$transaction_logs["-1"]["transaction_status"] = $order_transaction_status;
		$transaction_logs["-1"]["transaction_currency"] = $order["currency"];
		$transaction_logs["-1"]["transaction_total"] = $order["total"];
		$transaction_logs["-1"]["date"] = $order["date"];
		$transaction_logs["-1"]["login"] = $order["login"];
		$transaction_logs["-1"]["transaction_log"] = $order["details"];
		$transaction_logs["-1"]["firstname"] = $order["firstname"];

		$transaction_payment_methods_info = func_query_first("SELECT * FROM $sql_tbl[payment_methods] WHERE paymentid='$order[paymentid]'");
		$transaction_logs["-1"]["payment_method"] = $order["payment_method"];
		$transaction_logs["-1"]["transaction_id_link"] = $transaction_payment_methods_info["transaction_id_link"];
		$transaction_logs["-1"]["transaction_link_anchor"] = $transaction_payment_methods_info["transaction_link_anchor"];

		$first_customers_transaction_found = true;
	}

	if ($first_customers_transaction_found){
		$transaction_logs = my_array_sort($transaction_logs, "date");
		$transaction_logs = array_values($transaction_logs);
	}
}
###
##
#

//func_print_r($transaction_logs);
$smarty->assign("transaction_logs", $transaction_logs);

?>
