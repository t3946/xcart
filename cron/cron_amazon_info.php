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
$param_reports = [
    'competitive_pricing' => 'GetCompetitivePricing',
    'lowest_offer' => 'GetLowestOfferListingsForSKU',
    'list_inventory' => 'ListInventorySupply',
    'reserved_inventory' => '_GET_RESERVED_INVENTORY_DATA_',
];
if (isset($argv) && is_array($argv) && !empty($argv[1])) {
    $p_arg = $argv[1];
    $log = LOG_CATEGORY.'_'.$p_arg;
    if ($config[$log] == "Y") {
        $oMail = \Xcart\App\Main\Xcart::app()->mail;
        $oMail->to = 'team@s3stores.com';
        $oMail->from = 'team@s3stores.com';
        $oMail->subject = sprintf('Attention! Xcart cron %s Already launched', $log);
        $oMail->body = $log . ' already launched';
        $oMail->sendEmail();
        die("Already launched"); // ################################
    }
    db_query_param(/** @lang MySQL */
        "REPLACE xcart_config SET value='Y', name=:name", ['name' => $log]);

    $start_time = new DateTime('now');
    $log_text = " * * *  Cron started  * * * ";
    func_backprocess_log(Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_ORDER_INFO, $log_text);

    switch ($p_arg) {
        case 'competitive_pricing':
        case 'lowest_offer':
            echo "Report {$param_reports[$p_arg]} start\n";
            $oAmazonProduct = new \Xcart\AmazonMWS('MarketplaceWebServiceProducts_Client', '/Products/2011-10-01');
            $max_products = 20;
            $i = 1;
            while ($aProductsBatch = \Xcart\Product::objects()
                ->filter(['forsale' => 'Y', new QOr(['amazon_enabled' => 'Y', 'amazon_fba' => 'Y'])])
                ->paginate($i++, $max_products)
                ->all()) {
                $oAmazonProduct
                    ->setProducts($aProductsBatch)
                    //->enableLog($log)
                    ->_Request($param_reports[$p_arg]);
            }
            break;
        case 'list_inventory':
            echo "Report {$param_reports[$p_arg]} start\n";
            $oAmazonProduct = new \Xcart\AmazonMWS('FBAInventoryServiceMWS_Client', '/FulfillmentInventory/2010-10-01');
            $max_products = 50;
            $i = 1;
            while ($aProductsBatch = \Xcart\Product::objects()
                ->filter(['forsale' => 'Y', new QOr(['amazon_enabled' => 'Y', 'amazon_fba' => 'Y'])])
                ->paginate($i++, $max_products)
                ->all()) {
                $oAmazonProduct
                    ->setProducts($aProductsBatch)
                    //->enableLog($log)
                    ->_Request('ListInventorySupply');
            }
            break;
        case 'reserved_inventory' :
            echo "Report {$param_reports[$p_arg]} start\n";
            $oAmazonProduct = new Xcart\AmazonMWS();
            $oAmazonProduct->setReportType('_GET_RESERVED_INVENTORY_DATA_')
                ->setBackProcessName(Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_ORDER_INFO)
                ->_Request('RequestReport')
                ->_Request('GetReportRequestList')
                ->_Request('GetReportList')
                ->_Request('GetReport')
                ->_Request('UpdateReportAcknowledgements')
                //->enableLog($log)
                ->processReportReservedInventory();
            $oAmazonProduct->groupAmazonFBAProducts();
            break;
    }

    Xcart\Config::model(['name' => $log])->setValue('N')->_update();
    $str_time = (new DateTime('now'))->diff($start_time)->format('%H:%I:%S');
    $log_text = "Cron completed. Processing time: {$str_time}";
    func_backprocess_log(Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_ORDER_INFO, $log_text);
}
die("DONE!");