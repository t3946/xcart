<?php
session_start();


require "./top.inc.php";
require "./init.php";

global $xcart_dir, $config;
require_once $xcart_dir . "/include/class/classAmazonMWS.php";
require_once $xcart_dir . "/include/class/classOrderGroup.php";

$classOrderGroup = new classOrderGroup(['orderid'=>62099, 'manufacturerid'=>12]);
var_dump($classOrderGroup->getOrderAmazonDetails()->isRefundExists());

exit;

/*$classAmazonMWS = new classAmazonMWS();
//func_backprocess_log($classAmazonMWS::BACK_PROCESS_LOG_NAME_SETTLEMENT, $log_text);

$classAmazonMWS->setReportType('_GET_V2_SETTLEMENT_REPORT_DATA_XML_')->setBackProcessName($classAmazonMWS::BACK_PROCESS_LOG_NAME_SETTLEMENT)
    ->setReportId(['1979271148016951'])
    ->_Request('GetReport')
    ->processReportSettlementData();

*/




