<?php
define("CIDEV_CRON_START", "CRON");
session_start();

require "./top.inc.php";
require "./init.php";

global $config;

ini_set('memory_limit', '512M');
set_time_limit(0);

const LOG_CATEGORY = 'cidev_amazon_orders_v2';

if ($config[LOG_CATEGORY] == "Y") {
    func_backprocess_log(Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_ORDERS, 'Already launched');
    die("Already launched"); // ################################
}
db_query("REPLACE $sql_tbl[config] SET value='Y', name='" . LOG_CATEGORY . "'");
$start_time = time();

$log_text = " * * *  Cron started  * * * ";

$classAmazonMWS = new Xcart\AmazonMWS('MarketplaceWebServiceOrders_Client', '/Orders/2013-09-01');
func_backprocess_log(Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_ORDERS, $log_text);

$classAmazonMWS->_Request('OrderListRequest');

db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='" . LOG_CATEGORY . "'");

$current_time = time();

$pid_diff = $current_time - $start_time;
$hour = intval($pid_diff / (60 * 60));
$minutes = intval(($pid_diff - $hour * 60 * 60) / 60);
$seconds = ($pid_diff - $hour * 60 * 60 - $minutes * 60);

$str_time = sprintf("%02d:%02d:%02d", $hour, $minutes, $seconds);

$log_text = "Cron completed. ";
$log_text .= "Processing time: $str_time";
func_backprocess_log(Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_ORDERS, $log_text);

die("DONE!");