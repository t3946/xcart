<?php

use Mindy\QueryBuilder\Q\QOr;
use Modules\Amazon\Helpers\AmazonProductHelper;
use Modules\Amazon\Stores\AmazonPoolStore;
use Xcart\Product;

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
//    if ($config[$log] == "Y") {
//        $oMail = \Xcart\App\Main\Xcart::app()->mail;
//        $oMail->to = 'team@s3stores.com';
//        $oMail->from = 'team@s3stores.com';
//        $oMail->subject = sprintf('Attention! Xcart cron %s Already launched', $log);
//        $oMail->body = $log . ' already launched';
//        $oMail->sendEmail();
//        die("Already launched"); // ################################
//    }
//    db_query_param(/** @lang MySQL */
//        "REPLACE xcart_config SET value='Y', name=:name", ['name' => $log]);

    $counter_received = [];
    $counter_send = 0;

    $start_time = new DateTime('now');
    $log_text = " * * *  Cron {$log} started  * * * ";
    func_backprocess_log(Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_ORDER_INFO, $log_text);
    $amzPool = new AmazonPoolStore();
    switch ($p_arg) {
        case 'competitive_pricing':
            $max_products = 20;
            $client = $amzPool->getProductClientPackExt();
            $counter_received = [
                'CompetitivePricing' => 0,
                'MyPrice' => 0,
            ];

            while ($aProductsBatch = Product::objects()
                ->filter(['forsale' => 'Y', new QOr(['amazon_enabled' => 'Y', 'amazon_fba' => 'Y'])])
                ->paginate($i++, $max_products)
                ->all())
            {
                $counter_send += count($aProductsBatch);

                $aSKUs = array_map(function($a) {return $a->productcode;}, $aProductsBatch);
                $log_text = 'ERROR in competitive_pricing cron for SKU\'s: ' . implode(', ', $aSKUs) . "\n";

                try {
                    $counter_dropped = $aSKUs;
                    $pricing = $client->callGetCompetitivePricingForSKU($aSKUs);
                    if ($products = AmazonProductHelper::getCompetitivePricingForSKU($pricing, $aProductsBatch)) {
                        foreach ($products as $aAmazonFbaProduct) {
                            $aAmazonFbaProduct->save();
                            $counter_received['CompetitivePricing']++;

                            $key = array_search($aAmazonFbaProduct->productcode, $counter_dropped);
                            if ($key !== false) {
                                unset($counter_dropped[$key]);
                            }
                        }
                    }
                }
                catch (\Exception $e) {
                    func_backprocess_log(Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_ORDER_INFO, $log_text . $e->getMessage());
                }

                if (!empty($counter_dropped)) {
                    func_backprocess_log(Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_ORDER_INFO, "Skipped SKU's in CompetitivePricing: ".implode(', ', $counter_dropped));
                }


                try {
                    $counter_dropped = $aSKUs;
                    $myPricing = $client->callGetMyPriceForSKU($aSKUs);
                    if ($products = AmazonProductHelper::getMyPriceForSKU($myPricing, $aProductsBatch)) {
                        foreach ($products as $aAmazonFbaProduct) {
                            $aAmazonFbaProduct->save();
                            $counter_received['MyPrice']++;

                            $counter_dropped3[] = $aAmazonFbaProduct->productcode;

                            $key = array_search($aAmazonFbaProduct->productcode, $counter_dropped);
                            if ( $key !== false) {
                                unset($counter_dropped[$key]);
                            }
                        }
                    }
                }
                catch (\Exception $e) {
                    func_backprocess_log(Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_ORDER_INFO, $log_text . $e->getMessage());
                }

                if (!empty($counter_dropped)) {
                    func_backprocess_log(Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_ORDER_INFO, "Skipped SKU's in MyPrice: ".implode(', ', $counter_dropped));
                }
            }
            break;
        case 'lowest_offer':
            echo "Report {$param_reports[$p_arg]} start\n";
            $oAmazonProduct = new \Xcart\AmazonMWS('MarketplaceWebServiceProducts_Client', '/Products/2011-10-01');
            $max_products = 20;
            $i = 1;
            while ($aProductsBatch = Product::objects()
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
            while ($aProductsBatch = Product::objects()
                ->filter(['forsale' => 'Y', new QOr(['amazon_enabled' => 'Y', 'amazon_fba' => 'Y'])])
                ->paginate($i++, $max_products)
                ->all())
            {
                $counter_send += count($aProductsBatch);

                $oAmazonProduct
                    ->setProducts($aProductsBatch)
                    //->enableLog($log)
                    ->_Request('ListInventorySupply');

                $counter_received = [
                    'ListInventorySupply' => $oAmazonProduct->getCountSaved(),
                ];
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
    $log_text = "Cron {$log} completed. Processing time: {$str_time}";

    if ($counter_send) {
        $log_text .= ". Sended: {$counter_send}.";

        if (!empty($counter_received)) {
            foreach ($counter_received as $name => $received) {
                $log_text .= " Received {$name}: {$received}";
            }
        }
    }

    func_backprocess_log(Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_ORDER_INFO, $log_text);
}
die("DONE!");