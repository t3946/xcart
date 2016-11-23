<?php
session_start();

ini_set('memory_limit', '512M');
set_time_limit(0);

require "./top.inc.php";
require "./init.php";

$classAmazonMWS = new Xcart\AmazonMWS();
$classAmazonMWS->setReportType('_GET_RESERVED_INVENTORY_DATA_')->setBackProcessName($classAmazonMWS::BACK_PROCESS_LOG_NAME_ORDER_INFO)
    ->_Request('RequestReport')
    ->_Request('GetReportRequestList')
    ->_Request('GetReportList')
    ->_Request('GetReport')
    ->_Request('UpdateReportAcknowledgements')
    ->processReportReservedInventory();