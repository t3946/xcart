<?php
session_start();


require "./top.inc.php";
require "./init.php";

global $xcart_dir, $config;
require_once $xcart_dir . "/include/class/classAmazonMWS.php";
require_once $xcart_dir . "/include/class/classOrderGroup.php";

$v_description_csv_UPPER = 'PURCHASE AUTHORIZED ON 03/31 EDUCATIONAL PROD. 9704847445 CO S386091723009039 CARD 8956';
$sRegex = '/PURCHASE AUTHORIZED ON (\d{2})\/(\d{2})/';
preg_match($sRegex,$v_description_csv_UPPER, $aMatches);
if (!empty($aMatches)) {
    $sMonth = $aMatches[1];
    $sDay = $aMatches[2];
    $dCurDate = new DateTime();
    $sCurYear = $dCurDate->format('Y');

    $dTransactionDate = new DateTime();
    $dTransactionDate->setDate($sCurYear, intval($sMonth), intval($sDay));

    $useDate = $dTransactionDate;

    if ($dCurDate < $dTransactionDate) {
        $dTransactionDateLastYear = new DateTime();
        $dTransactionDateLastYear->setDate(intval($sCurYear)-1, intval($sMonth), intval($sDay));
        $useDate = $dTransactionDateLastYear;
    }

    $subDate = clone $useDate;
    $addDate = clone $useDate;
    $subDate->sub(new DateInterval('P4D'));
    $addDate->add(new DateInterval('P4D'));

    $sSearchString = "https://mail.google.com/mail/u/0/#search/after: %s before: %s ";
     sprintf($sSearchString,$subDate->format('Y/m/d'), $addDate->format('Y/m/d'));
    $sSearchString = "https://mail.google.com/mail/u/0/#search/after: %s before: %s ";
    echo urlencode(sprintf($sSearchString,$subDate->format('Y/m/d'), $addDate->format('Y/m/d')));

}
/*require_once $xcart_dir . "/include/class/classOrderGroupInvoices.php";
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
}*/


exit;

/*$classAmazonMWS = new classAmazonMWS();
//func_backprocess_log($classAmazonMWS::BACK_PROCESS_LOG_NAME_SETTLEMENT, $log_text);

$classAmazonMWS->setReportType('_GET_V2_SETTLEMENT_REPORT_DATA_XML_')->setBackProcessName($classAmazonMWS::BACK_PROCESS_LOG_NAME_SETTLEMENT)
    ->setReportId(['1979271148016951'])
    ->_Request('GetReport')
    ->processReportSettlementData();

*/




