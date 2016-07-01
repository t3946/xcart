<?php
session_start();


require "./top.inc.php";
require "./init.php";

global $xcart_dir, $config;
require_once $xcart_dir . "/include/class/classAmazonMWS.php";
require_once $xcart_dir . "/include/class/classOrderGroup.php";

$classOrderGroup = new classOrderGroup(['orderid'=>62713, 'manufacturerid'=>12]);

$classOrderGroup->printAccounting();

$classOrderGroup
    ->addAccountingNet(-1)
    ->addAccountingGross(-1)
    ->addAccountingNetCostToUs(3)
    ->addAccountingGrossCostToUs(3)
    ->addAccountingNetShipping(6)
    ->addAccountingGrossShipping(6)
    ->addAccountingNetRefundToCustomer(8)
    ->addAccountingGrossRefundToCustomer(8)
    ->addAccountingNetRefundToUs(10)
    ->addAccountingGrossRefundToUs(10)
    ->recalculateAccounting()
;

$classOrderGroup->printAccounting();

exit;

$classAmazonMWS = new classAmazonMWS();
func_backprocess_log($classAmazonMWS::BACK_PROCESS_LOG_NAME_SETTLEMENT, $log_text);

$classAmazonMWS->setReportType('_GET_V2_SETTLEMENT_REPORT_DATA_XML_')
    ->setReportId('2083516945016965')
    ->_Request('GetReport')
    ->processReportSettlementData();






