<?php
session_start();


require "./top.inc.php";
require "./init.php";

global $xcart_dir, $config;
require_once $xcart_dir . "/include/class/classAmazonMWS.php";
require_once $xcart_dir . "/include/class/classOrderGroup.php";

require_once $xcart_dir . "/include/class/classOrderGroupInvoices.php";
$oGroupInvoices = new classOrderGroupInvoices();
$oInvoices = $oGroupInvoices->getOrderGroupInvoices(['orderid' => 62760, 'manufacturerid' => 12]);
if ($oInvoices->countOrderGroupInvoices() == 1) {
    $oLastInvoice = $oInvoices->getLastInvoice();
    if ($oLastInvoice->getOrderGroupInvoiceProductsTotal() != 0 && $oLastInvoice->setOrderGroupInvoicesShippingTotal() != 0) {
        $oInvoices->createCloneInvoice($oLastInvoice)->getLastInvoice()->setCostToUsForProductsCharged(0)->
        setTaxChargedExceptHST(0)->setOrderGroupInvoiceProductsTotal(0)->calculateOrderGroupInvoiceTotal()->_insert(true);
        $oLastInvoice->setOrderGroupInvoicesShippingCharged(0)->setOrderGroupInvoicesDropShipFeeCharged(0)->
        setOrderGroupInvoicesShippingTotal(0)->calculateOrderGroupInvoiceTotal()->_insert(true);
    }
}


exit;

/*$classAmazonMWS = new classAmazonMWS();
//func_backprocess_log($classAmazonMWS::BACK_PROCESS_LOG_NAME_SETTLEMENT, $log_text);

$classAmazonMWS->setReportType('_GET_V2_SETTLEMENT_REPORT_DATA_XML_')->setBackProcessName($classAmazonMWS::BACK_PROCESS_LOG_NAME_SETTLEMENT)
    ->setReportId(['1979271148016951'])
    ->_Request('GetReport')
    ->processReportSettlementData();

*/




