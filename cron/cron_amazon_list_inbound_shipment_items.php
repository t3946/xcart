<?php
use CaponicaAmazonMwsComplete\AmazonClient\FbaInboundClient;

define("CIDEV_CRON_START", "CRON");
session_start();
set_time_limit(0);

require "../top.inc.php";
require "../init.php";

global $config;

const LOG_CATEGORY = 'cron_amazon_list_inbound_shipment_items';

if ($config[LOG_CATEGORY] == "Y") {
    $oMail = \Xcart\App\Main\Xcart::app()->mail;
    $oMail->to = 'team@s3stores.com';
    $oMail->from = 'team@s3stores.com';
    $oMail->subject = sprintf('Attention! Xcart cron %s Already launched', LOG_CATEGORY);
    $oMail->body = LOG_CATEGORY . ' already launched';
    $oMail->sendEmail();
    die("Already launched");
}
db_query_param(/** @lang MySQL */
    "REPLACE xcart_config SET value='Y', name=:name",['name' => LOG_CATEGORY]);

$start_time = new DateTime('now');
$log_text = " * * *  Cron started  * * * ";
func_backprocess_log(LOG_CATEGORY, $log_text);

$cl_ver = FbaInboundClient::MWS_CLIENT_VERSION; //use for autoload Amazon library
$oAmazon = new \Xcart\AmazonMWS('FBAInboundServiceMWS_Client', '/FulfillmentInboundShipment/2010-10-01');
$oAmazon
    //->enableLog('amazon-list-inbound')
    ->_Request('GetListInboundItems')
    ->_Request('GetListInboundShipments');


Xcart\Config::model(['name' => LOG_CATEGORY])->setValue('N')->_update();
$str_time = (new DateTime('now'))->diff($start_time)->format('%H:%I:%S');
$log_text = "Cron completed. Processing time: {$str_time}";
func_backprocess_log(LOG_CATEGORY, $log_text);

die("DONE!");
