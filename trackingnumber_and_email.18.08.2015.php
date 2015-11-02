<?php
define("CIDEV_CRON_START", "CRON");

require "./top.inc.php";
require "./init.php";

ini_set('memory_limit', '512M');
set_time_limit(0);

x_load('backoffice','mail');

$curr_time = time();
$thank_you_days = trim($config["thankyou_for_order"]["thank_you_days"]);
$thank_you_days = isset($thank_you_days) ? abs(intval($thank_you_days)) : 0;
$days_to_check = 60*60*24*$thank_you_days;
$diff_time = $curr_time - $days_to_check;

//func_print_r("diff_time: ".$diff_time);

$orders = func_query("
	SELECT $sql_tbl[orders].orderid, $sql_tbl[orders].order_prefix, $sql_tbl[orders].firstname, $sql_tbl[orders].email FROM $sql_tbl[orders] 
	WHERE 
		thankyou_for_order_email_sent!='Y'
		AND tracking_all_filled = 'Y'
		AND tracking_fill_time!='0'
		AND tracking_fill_time < '$diff_time'
");

if (!empty($orders)){

	$from = "orders@s3stores.com";

	foreach ($orders as $k => $v){

		$cb_dc_statuses = func_query("SELECT cb_status, dc_status FROM $sql_tbl[order_groups] WHERE orderid='$v[orderid]'");

		if (!empty($cb_dc_statuses)){
	
			$count_cb_dc_statuses = count($cb_dc_statuses);

			$counter = 0;
			foreach ($cb_dc_statuses as $kk => $vv){

				if (
					($vv["cb_status"] == "P" || $vv["cb_status"] == "O" || $vv["cb_status"] == "H") &&
					($vv["dc_status"] == "S" || $vv["dc_status"] == "L" || $vv["dc_status"] == "C")
				){
					$counter++;
				}
			}

			if ($count_cb_dc_statuses == $counter){
				# Send email
				$to = $v["email"];

			        $subj = $config["thankyou_for_order"]["thank_you_subject"];
				$subj = str_replace("{{orderid}}", $v["order_prefix"].$v["orderid"], $subj);
				$subj = str_replace("{{c-fullname}}", $v["firstname"], $subj);

			        $body = $config["thankyou_for_order"]["thank_you_message_body"];
				$body = str_replace("{{orderid}}", $v["order_prefix"].$v["orderid"], $body);
				$body = str_replace("{{c-fullname}}", $v["firstname"], $body);

				func_send_simple_mail($to, $subj, $body, $from);

				db_query("UPDATE $sql_tbl[orders] SET thankyou_for_order_email_sent='Y' WHERE orderid='$v[orderid]'");

				$log = "Thank you email sent by system <br />";
//				$log.= $subj . "<br />";
//				$log.= $body;
				func_log_order($v["orderid"], 'X', $log);
			}
		}
	}
}

print"Done.";
?>
