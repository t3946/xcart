<?php

use Mindy\QueryBuilder\Expression;
use Modules\Order\Models\OrderGroupModel;
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
    $oMail = \Xcart\App\Main\Xcart::app()->mail;
    $oMail->to = 'team@s3stores.com';
    $oMail->from = ('team@s3stores.com');
    $oMail->body = LOG_CATEGORY . ' already launched';
    $oMail->subject = sprintf('Attention! Xcart cron %s Already launched', LOG_CATEGORY);
    $oMail->sendEmail();
    die("Already launched"); // ################################
}
db_query("REPLACE $sql_tbl[config] SET value='Y', name='" . LOG_CATEGORY . "'");
$start_time = new DateTime('now');

$log_text = " * * *  Cron started  * * * ";
func_backprocess_log(LOG_CATEGORY, $log_text);

$aOrderGroups = OrderGroup::model()->findAll(
    SQLBuilder::getInstance()
        ->addCondition("cb_status ='P'")
        ->addCondition("cb_update_datetime > DATE_SUB(NOW(), INTERVAL 1 MONTH)")
        ->addInnerJoin('order_transactions', 'ot', 'main.orderid = ot.orderid')
        ->addCondition("ot.paymentid IN (5, 17, 21, 100)")
        ->addGroupBy('orderid')
        ->addOrderBy('cb_update_datetime DESC'));

/*$params = [
    'cb_status' => 'P',
    'cb_update_datetime__lt' => new Expression('DATE_SUB(NOW(), INTERVAL 1 MONTH)'),
];
if ($groups = OrderGroupModel::objects()->getQuerySet()
    ->join('inner join', 'order_transactions', ['orderid' => 'ot.orderid'], 'ot')
    ->filter($params)
    ->group(['orderid'])
    ->order(['-cb_update_datetime'])
    ->all()){

    $countOrders = count($groups);
    func_backprocess_log(LOG_CATEGORY, "Processing {$countOrders} orders.");

    foreach ($groups as $group) {
        $group->
    }
}
exit;*/

if (!empty($aOrderGroups)) {
    $countOrders = count($aOrderGroups);
    func_backprocess_log(LOG_CATEGORY, "Processing {$countOrders} orders.");
    foreach ($aOrderGroups as $oOrderGroup) {
        $fOrderGroupTotalAmount = 0;
        $aTransactions = OrderTransactions::getOrderTransactionsByOrderIdAndStatus($oOrderGroup->getOrderId(), ['capture']);
        if (!empty($aTransactions)) {
            foreach ($aTransactions as $oTransaction) {
                try {
                    $oPayPalTransaction = (new Paypal())->getTransaction($oTransaction->getField('transaction_id'));
                    if ($oPayPalTransaction->getState() == 'completed') {
                        $fOrderGroupTotalAmount += floatval($oPayPalTransaction->getAmount()->total);
                    }
                } catch (Exception $ex) {
                    func_backprocess_log(LOG_CATEGORY, "Get transaction error. Order ID:{$oOrderGroup->getOrderId()}. " . $ex->getMessage());
                }
            }
        }
        if (round($fOrderGroupTotalAmount, 2) != $oOrderGroup->getOrderInstance()->getOrderTotalGross()) {
            $oAttentionTag = new AttentionTag(['status_id' => 44]);
            if (!($oOrderGroup->getOrderInstance()->isAttentionTagSet($oAttentionTag->getStatusId()))) {
                Modules\Order\Helpers\OrderTagEventHelper::orderTagEvent($oAttentionTag->getStatusId(), $oOrderGroup->getOrderId());
                $sLog = "Difference in OrderId: " . $oOrderGroup->getOrderId() . ". TransactionsTotal(" . $fOrderGroupTotalAmount . ") - OrderTotal(" . $oOrderGroup->getOrderInstance()->getOrderTotalGross() . ")";
                func_backprocess_log(LOG_CATEGORY, $sLog);
            }
        }
    }
}

$aOrderGroups = OrderGroup::model()->findAll(
    SQLBuilder::getInstance()
        ->addCondition("cb_status ='AP'")
        ->addCondition("cb_update_datetime > DATE_SUB(NOW(), INTERVAL 1 MONTH)")
        ->addCondition("dc_status IN ('C', 'S', 'G', 'L')")
);
if (!empty($aOrderGroups)) {
    $countOrders = count($aOrderGroups);
    $message = "Found CB: AP; DC: C,S,G,L {$countOrders} orders.";
    func_backprocess_log(LOG_CATEGORY, $message);
    $oMail = \Xcart\App\Main\Xcart::app()->mail;
    $oMail->init();
    $oMail->to = 'team@s3stores.com';
    $oMail->from = 'team@s3stores.com';
    $oMail->body = $message;
    $oMail->subject = LOG_CATEGORY . " invalid orders found";
    $oMail->sendEmail();
    $oAttentionTag = new AttentionTag(['status_id' => 44]);
    foreach ($aOrderGroups as $oOrderGroup) {
        if (!($oOrderGroup->getOrderInstance()->isAttentionTagSet($oAttentionTag->getStatusId()))) {
            Modules\Order\Helpers\OrderTagEventHelper::orderTagEvent($oAttentionTag->getStatusId(), $oOrderGroup->getOrderId());
        }
    }
}

Config::model(['name' => LOG_CATEGORY])->setValue('N')->_update();
$str_time = (new DateTime('now'))->diff($start_time)->format('%H:%I:%S');
$log_text = "Cron completed. Processing time: {$str_time}";
func_backprocess_log(LOG_CATEGORY, $log_text);

die("DONE!");