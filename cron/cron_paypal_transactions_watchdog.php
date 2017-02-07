<?php

use \Xcart\OrderGroup;
use \Xcart\SQLBuilder;
use \Xcart\OrderTransactions;
use \Xcart\Config;
use \Xcart\Paypal;
use \Xcart\AttentionTag;
use \Xcart\Logs;

global $config, $sql_tbl;

define("CIDEV_CRON_START", "CRON");
session_start();

require "../top.inc.php";
require "../init.php";

set_time_limit(0);
const LOG_CATEGORY = 'cron_paypal_transactions_watchdog';

if ($config[LOG_CATEGORY] == "Y") {
    func_backprocess_log(LOG_CATEGORY, 'Already launched');
    Xcart\Mail::model()->
    setTo('team@s3stores.com')->
    setFrom('team@s3stores.com')->
    setBody(LOG_CATEGORY . ' already launched')->
    setSubject(sprintf('Attention! Xcart cron %s Already launched', LOG_CATEGORY))->sendEmail();
    die("Already launched"); // ################################
}
db_query("REPLACE $sql_tbl[config] SET value='Y', name='" . LOG_CATEGORY . "'");
$start_time = new DateTime('now');

$log_text = " * * *  Cron started  * * * ";
func_backprocess_log(LOG_CATEGORY, $log_text);

$aOrderGroups = OrderGroup::model()->findAll(
    SQLBuilder::getInstance()
        ->addCondition("cb_status ='P'")
        ->addCondition("cb_update_datetime > DATE_SUB(NOW(), INTERVAL 2 MONTH)")
        ->addInnerJoin('order_transactions', 'ot', 'main.orderid = ot.orderid')
        ->addCondition("ot.paymentid IN (5, 17, 21, 100)")
        ->addGroupBy('orderid')
        ->addOrderBy('cb_update_datetime DESC')
        ->setLimit('2'));

if (!empty($aOrderGroups)) {
    $countOrders = count($aOrderGroups);
    func_backprocess_log(LOG_CATEGORY, "Processing {$countOrders} orders.");
    foreach ($aOrderGroups as $oOrderGroup) {
        $fOrderGroupTotalAmount = 0;
        $aTransactions = OrderTransactions::getOrderTransactionsByOrderIdAndStatus($oOrderGroup->getOrderId(), ['completed']);
        if (!empty($aTransactions)) {
            foreach ($aTransactions as $oTransaction) {
                try {
                    $oPayPalTransaction = (new Paypal())->getTransaction($oTransaction->getField('transaction_id'));
                    if ($oPayPalTransaction->getState() == 'completed') {
                        $fOrderGroupTotalAmount += floatval($oPayPalTransaction->getAmount()->total);
                    }
                } catch (Exception $ex) {
                    func_backprocess_log(LOG_CATEGORY, "Get transaction error. Order ID:{$oOrderGroup->getOrderId()}. ".$ex->getMessage());
                }
            }
        }
        if (round($fOrderGroupTotalAmount, 2) != $oOrderGroup->getOrderInstance()->getOrderTotalGross()){
            $oAttentionTag = new AttentionTag(['status_id' => 44]);
            $aInsertArray = ['orderid' => $oOrderGroup->getOrderId(), 'status_id' => $oAttentionTag->getStatusId()];
            func_array2insert('orders_additional_tags', $aInsertArray, true);
            $sLog = "Attention tag added: " . $oAttentionTag->getStatus() . "\n";
            Logs::_log('orders', $oOrderGroup->getOrderId(), 'X', $sLog);
            $sLog = "Difference in OrderId: ".$oOrderGroup->getOrderId(). ". TransactionsTotal(" . $fOrderGroupTotalAmount . ") - OrderTotal(". $oOrderGroup->getOrderInstance()->getOrderTotalGross().")";
            func_backprocess_log(LOG_CATEGORY, $sLog);
        }
    }
}
Config::model(['name' => LOG_CATEGORY])->setValue('N')->_update();
$str_time = (new DateTime('now'))->diff($start_time)->format('%H:%I:%S');
$log_text = "Cron completed. Processing time: {$str_time}";
func_backprocess_log(LOG_CATEGORY, $log_text);

die("DONE!");