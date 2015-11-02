<?php
require "./auth.php";
x_load("order");

$date_select = time() - 28*24*60*60;
$orderids = func_query("SELECT orderid FROM $sql_tbl[orders] WHERE date > '$date_select' ");
//$orderids = func_query("SELECT orderid FROM $sql_tbl[orders] WHERE orderid='26791'");  // for tests with one order

if (!empty($orderids) && is_array($orderids)){
	foreach ($orderids as $k => $v){
		$orderid = $v["orderid"];
		func_check_and_send_request_availability_email($orderid, 'CRON');
	}
}
?>
