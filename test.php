<?php
session_start();


require "./top.inc.php";
require "./init.php";

global $xcart_dir, $config;
require_once $xcart_dir . "/include/class/classAmazonMWS.php";

$classAmazonMWS = new classAmazonMWS();
func_backprocess_log($classAmazonMWS::BACK_PROCESS_LOG_NAME_SETTLEMENT, $log_text);

$classAmazonMWS->setReportType('_GET_V2_SETTLEMENT_REPORT_DATA_XML_')
    ->setReportId('2083516945016965')
    ->_Request('GetReport')
    ->processReportSettlementData();






