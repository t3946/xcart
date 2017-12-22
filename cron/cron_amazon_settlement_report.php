<?php
define("CIDEV_CRON_START", "CRON");
session_start();

require __DIR__ . DIRECTORY_SEPARATOR . "../www/top.inc.php";
require __DIR__ . DIRECTORY_SEPARATOR . "../www/init.php";

global $xcart_dir, $config, $sql_tbl;

ini_set('memory_limit', '512M');
set_time_limit(0);

const LOG_CATEGORY = 'cidev_amazon_settlement_report';

if ($config[LOG_CATEGORY] == "Y") {
    func_backprocess_log(Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_SETTLEMENT, 'Already launched');
    $oMail = \Xcart\App\Main\Xcart::app()->oldMail;
    $oMail->to = 'team@s3stores.com';
    $oMail->from = ('team@s3stores.com');
    $oMail->subject = sprintf('Attention! Xcart cron %s Already launched', LOG_CATEGORY);
    $oMail->body = Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_SETTLEMENT . ' already launched';
    $oMail->sendEmail();
    die("Already launched"); // ################################
}
db_query("REPLACE $sql_tbl[config] SET value='Y', name='" . LOG_CATEGORY . "'");

$start_time = new DateTime('now');

$log_text = " * * *  Cron started  * * * ";

$classAmazonMWS = new Xcart\AmazonMWS();
func_backprocess_log(Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_SETTLEMENT, $log_text);

$classAmazonMWS->setReportType('_GET_V2_SETTLEMENT_REPORT_DATA_XML_')->
                 setBackProcessName(Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_SETTLEMENT)->
                 setProcessWithoutAcknowledgedFlag()->
                 enableLog('settlement-reports')->
                 _Request('GetReportList')->
                 _Request('GetReport')->
                 _Request('UpdateReportAcknowledgements')->
                 processReportSettlementData();


Xcart\Config::model(['name' => LOG_CATEGORY])->setValue('N')->_update();
$str_time = (new DateTime('now'))->diff($start_time)->format('%H:%I:%S');
$log_text = "Cron completed. Processing time: {$str_time}";
func_backprocess_log(Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_SETTLEMENT, $log_text);

die("DONE!");