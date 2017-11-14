<?php
define("CIDEV_CRON_START", "CRON");
session_start();

require "../top.inc.php";
require "../init.php";

global $config;

$log_category = "cron_request_availability";

if ($config[$log_category] == "Y") {
    $oMail = \Xcart\App\Main\Xcart::app()->oldMail;
    $oMail->to = 'team@s3stores.com';
    $oMail->from = ('team@s3stores.com');
    $oMail->subject = sprintf('Attention! Xcart cron %s Already launched', $log_category);
    $oMail->body = $log_category . ' already launched';
    $oMail->sendEmail();
    die("Already launched"); // ################################
}

$start_time = new DateTime('now');
$log_text = " * * *  Cron started  * * * ";
func_backprocess_log($log_category, $log_text);

$date_select = time() - 28*24*60*60;
$orderids = func_query_param(/** @lang MySQL */
    "SELECT o.orderid FROM xcart_orders o
			INNER JOIN xcart_k.xcart_order_groups og ON og.orderid = o.orderid AND og.cb_status IN ('P','Q','O','AP') AND og.dc_status IN ('T')
 			WHERE o.date > :date_select 
 			  AND o.amazon_fulfillment_channel != 'AFN'
 			group by o.orderid", ['date_select' => $date_select]);

if (!empty($orderids) && is_array($orderids)){
	foreach ($orderids as $k => $v){
		func_check_and_send_request_availability_email($v["orderid"], 'CRON');
	}
}

Xcart\Config::model(['name' => $log_category])->setValue('N')->_update();
$str_time = (new DateTime('now'))->diff($start_time)->format('%H:%I:%S');
$log_text = "Cron completed. Processing time: {$str_time}";
func_backprocess_log($log_category, $log_text);

die("DONE!");

