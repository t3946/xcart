<?php

use Mindy\QueryBuilder\Q\QOr;

define("CIDEV_CRON_START", "CRON");
session_start();

require "../top.inc.php";
require "../init.php";

global $config, $sql_tbl;

ini_set('memory_limit', '2048M');
set_time_limit(0);

const LOG_CATEGORY = 'cron_amazon_info';

if ($config[LOG_CATEGORY] == "Y") {
    $oMail = \Xcart\App\Main\Xcart::app()->mail;
    $oMail->to = 'team@s3stores.com';
    $oMail->from = ('team@s3stores.com');
    $oMail->subject = sprintf('Attention! Xcart cron %s Already launched', LOG_CATEGORY);
    $oMail->body = Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_ORDER_INFO . ' already launched';
    $oMail->sendEmail();
    die("Already launched"); // ################################
}
db_query("REPLACE $sql_tbl[config] SET value='Y', name='" . LOG_CATEGORY . "'");

$start_time = new DateTime('now');

$log_text = " * * *  Cron started  * * * ";

func_backprocess_log(Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_ORDER_INFO, $log_text);

echo  "Report 1 start\n";

$oAmazonProduct = new \Xcart\AmazonMWS('MarketplaceWebServiceProducts_Client', '/Products/2011-10-01');

$max_products = 20;
$i = 1;
while ($aProductsBatch = \Xcart\Product::objects()
    ->filter(['forsale' => 'Y', new QOr(['amazon_enabled' => 'Y', 'amazon_fba' => 'Y'])])
    ->paginate($i++, $max_products)
    ->all()) {
    $oAmazonProduct
        ->setProducts($aProductsBatch)
        ->enableLog('amazon-info')
        ->_Request('GetCompetitivePricing')
        ->_Request('GetLowestOfferListingsForSKU');
    $i++;
}

echo  "Report 2 start\n";

$oAmazonProduct = new \Xcart\AmazonMWS('FBAInventoryServiceMWS_Client','/FulfillmentInventory/2010-10-01');
$max_products = 50;
$i = 1;
while ($aProductsBatch = \Xcart\Product::objects()
    ->filter(['forsale' => 'Y', new QOr(['amazon_enabled' => 'Y', 'amazon_fba' => 'Y'])])
    ->paginate($i++, $max_products)
    ->all()) {
    $oAmazonProduct
        ->setProducts($aProductsBatch)
        ->enableLog('amazon-info')
        ->_Request('ListInventorySupply');
}
echo  "Report 3 start\n";

$oAmazonProduct = new Xcart\AmazonMWS();

$oAmazonProduct->setReportType('_GET_RESERVED_INVENTORY_DATA_')
    ->setBackProcessName(Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_ORDER_INFO)
    ->_Request('RequestReport')
    ->_Request('GetReportRequestList')
    ->_Request('GetReportList')
    ->_Request('GetReport')
    ->_Request('UpdateReportAcknowledgements')
    ->enableLog('amazon-info')
    ->processReportReservedInventory();

$oAmazonProduct->groupAmazonFBAProducts();

Xcart\Config::model(['name' => LOG_CATEGORY])->setValue('N')->_update();
$str_time = (new DateTime('now'))->diff($start_time)->format('%H:%I:%S');
$log_text = "Cron completed. Processing time: {$str_time}";
func_backprocess_log(Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_ORDER_INFO, $log_text);

die("DONE!");