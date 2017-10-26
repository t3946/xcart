<?php
define("CIDEV_CRON_START", "CRON");
session_start();

require "../top.inc.php";
require "../init.php";

global $config, $sql_tbl;

set_time_limit(0);

register_shutdown_function('process_errors');

const LOG_CATEGORY = 'cidev_amazon_orders_v2';

if ($config[LOG_CATEGORY] == "Y") {
    func_backprocess_log(Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_ORDERS, 'Already launched');
    $oMail = \Xcart\App\Main\Xcart::app()->oldMail;
    $oMail->to = 'team@s3stores.com';
    $oMail->from = ('team@s3stores.com');
    $oMail->subject = sprintf('Attention! Xcart cron %s Already launched', LOG_CATEGORY);
    $oMail->body = Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_ORDERS . ' already launched';
    $oMail->sendEmail();
    die("Already launched"); // ################################
}
db_query("REPLACE $sql_tbl[config] SET value='Y', name='" . LOG_CATEGORY . "'");

$start_time = new DateTime('now');

$log_text = " * * *  Cron started  * * * ";
func_backprocess_log(Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_ORDERS, $log_text);

$classAmazonMWS = new Xcart\AmazonMWS('MarketplaceWebServiceOrders_Client', '/Orders/2013-09-01');

$classAmazonMWS->_Request('OrderListRequest');

Xcart\Config::model(['name' => LOG_CATEGORY])->setValue('N')->_update();
$str_time = (new DateTime('now'))->diff($start_time)->format('%H:%I:%S');
$log_text = "Cron completed. Processing time: {$str_time}";
func_backprocess_log(Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_ORDERS, $log_text);

die("DONE!");

function process_errors()
{
    $error = error_get_last();
    if ($error && ($error['type'] & 1)) {
        $oMail = \Xcart\App\Main\Xcart::app()->oldMail;
        $oMail->to = 'team@s3stores.com';
        $oMail->from = ('team@s3stores.com');
        $oMail->subject = sprintf('Attention! Xcart cron %s failed', LOG_CATEGORY);
        $oMail->body = $error['message'];
        $oMail->sendEmail();
    }
}
