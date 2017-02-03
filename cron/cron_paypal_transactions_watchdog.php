<?php

use \Xcart\OrderGroup;
use \Xcart\SQLBuilder;
use \Xcart\OrderTransactions;
use \Xcart\Config;
use \Xcart\Paypal;

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
    //die("Already launched"); // ################################
}
db_query("REPLACE $sql_tbl[config] SET value='Y', name='" . LOG_CATEGORY . "'");
$start_time = new DateTime('now');

$log_text = " * * *  Cron started  * * * ";
func_backprocess_log(LOG_CATEGORY, $log_text);

$aOrderGroups = OrderGroup::model()->findAll(
    SQLBuilder::getInstance()
        ->addCondition("cb_status ='P'")
        ->addCondition("cb_update_datetime > DATE_SUB(NOW(), INTERVAL 1 MONTH)")
        ->addGroupBy('orderid')
        ->addOrderBy('cb_update_datetime DESC'));

if (!empty($aOrderGroups)) {
    foreach ($aOrderGroups as $oOrderGroup) {
        $aTransactions = OrderTransactions::getOrderTransactionsByOrderIdAndStatus($oOrderGroup->getOrderId(), ['completed']);
        if (!empty($aTransactions)) {
            foreach ($aTransactions as $oTransaction) {
                try {
                    $oPayPalAmount = (new Paypal())->getTransaction($oTransaction->getField('transaction_id'))->getAmount();
                    if (floatval($oPayPalAmount->total) != $oTransaction->getTransactionAmount() || $oPayPalAmount->currency != $oTransaction->getField('currency')) {

                    }
                    exit;
                } catch (Exception $ex) {
                }
            }
        }
    }
}
Config::model(['name' => LOG_CATEGORY])->setValue('N')->_update();
$str_time = (new DateTime('now'))->diff($start_time)->format('%H:%i:%s');
$log_text = "Cron completed. Processing time: {$str_time}";
func_backprocess_log(LOG_CATEGORY, $log_text);

die("DONE!");