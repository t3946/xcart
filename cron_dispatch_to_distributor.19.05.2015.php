<?php

//require "./auth.php";

define("CIDEV_CRON_START", "CRON");

require "./top.inc.php";
require "./init.php";

set_time_limit(0);

x_load("mail", "order");

$orders = db_query("SELECT SQL_NO_CACHE orderid, manufacturerid, cb_status, dc_status, bd_status FROM $sql_tbl[order_groups] WHERE cb_status IN ('P','O','3','H') AND dc_status='DP'");

while ($order = db_fetch_array($orders)) {

	func_flush(".");

	$mnfs = func_get_order_manufacturers($order["orderid"]);

	$order_manufacturer = $mnfs[$order["manufacturerid"]];

	$good_time_to_send_email_to_distributor = $order_manufacturer["good_time_to_send_email_to_distributor"];

	if ($good_time_to_send_email_to_distributor == "Y" || 1==1){
		# Send email

		$mail_smarty->assign("message_body", $order_manufacturer["mess_body"]);
		$mail_smarty->assign('d_email_subject_14', $order_manufacturer["d_subject_line_8"]);
		$mail_smarty->assign('mnf_operator_notify', 'Y');
		$mail_smarty->assign('cidev_hide_invoice', "Y");

		$mnf_to = $order_manufacturer["email"];

//$mnf_to = "xcartmaster@gmail.com";

		if (!empty($mnf_to)){
			func_send_mail($mnf_to, 'mail/order_notification_subj.tpl', 'mail/order_notification.tpl', $config['Company']['orders_department'], true);

			$log = $order_manufacturer["code"]. ": 'Send (Dispatch to distributor)'. CRON";

	                $current_dc_status = $order["dc_status"];
        	        $current_dc_status_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='$current_dc_status'");

			if ($current_dc_status != "C"){
				$new_value = func_query_first_cell("SELECT name FROM $sql_tbl[order_statuses] WHERE code='C'");
				$log .= "<br />dc_status: ". $current_dc_status_value . " -> ". $new_value . "<br />";
				db_query("UPDATE $sql_tbl[order_groups] SET dc_status='C', dc_dispatched_time='".time()."' WHERE orderid = '$order[orderid]' AND manufacturerid='$order[manufacturerid]'");
			}

			func_log_order($order["orderid"], 'X', $log);
		}
	}
//func_print_r($order_manufacturer);
}
db_free_result($orders);

print"<br />Done!";
?>
