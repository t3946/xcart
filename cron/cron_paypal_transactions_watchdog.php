<?php

use Mindy\QueryBuilder\Expression;
use Modules\Order\Helpers\OrderTagEventHelper;
use Modules\Order\Helpers\OrderTransactionHelper;
use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Stores\OrderStore;
use Modules\Order\Stores\OrderTransactionStore;
use Modules\Payment\Helpers\PaymentHelper;
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

$params = [
    'cb_status' => 'P',
    'cb_update_datetime__gt' => new Expression('DATE_SUB(NOW(), INTERVAL 1 MONTH)'),
    'ot.paymentid__in' => [5, 17, 21, 100]
];
if ($groups = OrderGroupModel::objects()->getQuerySet()
    ->join('inner join', 'xcart_order_transactions', ['orderid' => 'ot.orderid'], 'ot')
    ->filter($params)
    ->group(['orderid'])
    ->order(['-cb_update_datetime'])
    ->all()) {

    $countOrders = count($groups);
    func_backprocess_log(LOG_CATEGORY, "Processing {$countOrders} order groups.");

    foreach ($groups as $group) {

        $order = $group->order;

        foreach ($order->transactions as $trx) {
            try {
                OrderTransactionStore::lookupSelf($trx);
            } catch (Exception $ex) {

                func_backprocess_log(LOG_CATEGORY, "Lookup transaction error. TXN_ID: {$trx->transaction_id} Order ID:{$group->orderid}. " . $ex->getMessage());

            }
        }

        $store = new OrderStore($order);
        if ($store->getAmountDeficit() != 0) {
            OrderTagEventHelper::orderTagEvent(44, $order->orderid);
            func_backprocess_log(LOG_CATEGORY, "Difference in OrderId: " . $order->orderid . " Order Deficit: {$store->getAmountDeficit()}");
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