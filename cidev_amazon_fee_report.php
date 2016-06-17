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
//    die("Already launched"); // ################################
}
//db_query("REPLACE $sql_tbl[config] SET value='Y', name='".LOG_CATEGORY."'");
$started_at = time();

$log_text = " * * *  Cron started  * * * ";
func_backprocess_log("AmazonMWS", $log_text);

$classAmazonMWS = new classAmazonMWS();

$classAmazonMWS->_Request('RequestReport')
    -> _Request('GetReportRequestList')
    ->_Request('GetReportList')
    ->_Request('GetReport')
    ->_Request('UpdateReportAcknowledgements')
    ->processReportFeeData();


//db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='".LOG_CATEGORY."'");

$log_text = "Cron completed.";
func_backprocess_log("AmazonMWS", $log_text);

die("DONE!");