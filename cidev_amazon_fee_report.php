<?php

define("CIDEV_CRON_START", "CRON");
session_start();

require "./top.inc.php";
require "./init.php";

global $xcart_dir, $config;
require_once $xcart_dir . "/include/class/classAmazonMWS.php";

ini_set('memory_limit', '512M');
set_time_limit(0);

const LOG_CATEGORY = 'cidev_amazon_fee_report';

if ($config[LOG_CATEGORY] == "Y") {
    die("Already launched"); // ################################
}
db_query("REPLACE $sql_tbl[config] SET value='Y', name='" . LOG_CATEGORY . "'");
$start_time = time();

$log_text = " * * *  Cron started  * * * ";

$classAmazonMWS = new classAmazonMWS();
func_backprocess_log($classAmazonMWS::BACK_PROCESS_LOG_NAME, $log_text);

$classAmazonMWS->setStartDate(new DateTime('-3 days', new DateTimeZone('UTC')))
    ->_Request('RequestReport')
    ->_Request('GetReportRequestList')
    ->_Request('GetReportList')
    ->_Request('GetReport')
    ->_Request('UpdateReportAcknowledgements')
    ->processReportFeeData();


db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='" . LOG_CATEGORY . "'");

$current_time = time();

$pid_diff = $current_time - $start_time;
$hour = intval($pid_diff / (60 * 60));
$minutes = intval(($pid_diff - $hour * 60 * 60) / 60);
$seconds = ($pid_diff - $hour * 60 * 60 - $minutes * 60);

$str_time = sprintf("%02d:%02d:%02d", $hour, $minutes, $seconds);

$log_text = "Cron completed. ";
$log_text .= "Processing time: $str_time";
func_backprocess_log($classAmazonMWS::BACK_PROCESS_LOG_NAME, $log_text);

die("DONE!");