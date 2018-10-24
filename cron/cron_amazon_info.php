<?php

use Mindy\QueryBuilder\Q\QOr;
use Modules\Amazon\Helpers\AmazonProductHelper;
use Modules\Amazon\Models\AmazonProductsFieldsModel;
use Modules\Amazon\Stores\AmazonInventoryStore;
use Modules\Amazon\Stores\AmazonPoolStore;
use Modules\Goods\Models\ProductModel;
use Xcart\Product;

define("CIDEV_CRON_START", "CRON");
session_start();

require __DIR__ . DIRECTORY_SEPARATOR . "../www/top.inc.php";
require __DIR__ . DIRECTORY_SEPARATOR . "../www/init.php";

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
//        $oMail = \Xcart\App\Main\Xcart::app()->oldMail;
//        $oMail->to = 'team@s3stores.com';
//        $oMail->from = 'team@s3stores.com';
//        $oMail->subject = sprintf('Attention! Xcart cron %s Already launched', $log);
//        $oMail->body = $log . ' already launched';
//        $oMail->sendEmail();
//        if (!isset($argv) || (isset($argv) && !in_array('--force-flag', $argv))) {
//            die("Already launched"); // ################################
//        }
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

            while ($aProductsBatch = ProductModel::objects()
                ->filter(['forsale' => 'Y', 'amazon_enabled' => 'Y'])
                ->order(['-amazon_verified'])
                ->paginate($i++, $max_products)
                ->all())
            {

                $filtered_products_cp = array_filter($aProductsBatch, function($a) {
                    if($a->amazon_fields && ($amz = $a->amazon_fields->limit(1)->get())) {
                        if ($amz->sleep_cp) {
                            $amz->sleep_cp--;
                        }

                        if ($a->amazon_verified === 'Y') {
                            $amz->sleep_cp = 0;
                        } else if (!$amz->sleep_cp) {
                            $amz->sleep_cp = 48;
                            $amz->save(['sleep_cp']);
                            return true;
                        }

                        $amz->save(['sleep_cp']);
                        return !$amz->sleep_cp;

                    }
                    return true;
                });

                $aSKUs = array_values(array_map(function($a) {return $a->productcode;}, $filtered_products_cp));
                $counter_dropped = $aSKUs;

                $log_text = 'ERROR in getCompetitivePricingForSKU competitive_pricing cron for SKU\'s: ' . implode(', ', $aSKUs) . "\n";

                try {
                    if ($aSKUs) {

                        $pricing = $client->callGetCompetitivePricingForSKU($aSKUs);
                        if ($filtered_products_cp && $products = AmazonProductHelper::getCompetitivePricingForSKU($pricing, $filtered_products_cp)) {
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
                }
                catch (\Exception $e) {
                    func_backprocess_log(Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_ORDER_INFO, $log_text . $e->getMessage());
                }

                if (!empty($counter_dropped)) {
                    foreach ($counter_dropped as $drop) {
                        if ($drop_model = ProductModel::objects()->get(['productcode' => $drop])) {
                            [$amz] = AmazonProductsFieldsModel::objects()->getOrNew(['productid' => $drop_model->productid]);
                            $amz->sleep_cp = 48;
                            $amz->save();
                        }
                    }
                    func_backprocess_log(Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_ORDER_INFO, "Skipped SKU's in CompetitivePricing: ".implode(', ', $counter_dropped));
                }

                $log_text = 'ERROR in getMyPriceForSKU competitive_pricing cron for SKU\'s: ' . implode(', ', $aSKUs) . "\n";
                try {
                    $filtered_products_mp = array_filter($aProductsBatch, function($a) {
                        if($a->amazon_fields && ($amz = $a->amazon_fields->limit(1)->get())) {
                            if ($amz->sleep_mp) {
                                $amz->sleep_mp--;
                            }

                            if ($a->amazon_verified === 'Y') {
                                $amz->sleep_mp = 0;
                            } else if (!$amz->sleep_mp) {
                                $amz->sleep_mp = 48;
                                $amz->save(['sleep_mp']);
                                return true;
                            }

                            $amz->save(['sleep_mp']);
                            return !$amz->sleep_mp;
                        }
                        return true;
                    });

                    $aSKUs = array_values(array_map(function($a) {return $a->productcode;}, $filtered_products_mp));

                    $counter_dropped = $aSKUs;

                    if ($aSKUs) {
                        $myPricing = $client->callGetMyPriceForSKU($aSKUs);
                        if ($filtered_products_mp && $products = AmazonProductHelper::getMyPriceForSKU($myPricing, $filtered_products_mp)) {
                            foreach ($products as $aAmazonFbaProduct) {
                                $aAmazonFbaProduct->save();

                                if ($aAmazonFbaProduct->ASIN) {
                                    if ($pp = ProductModel::objects()->get(['productid' => $aAmazonFbaProduct->productid])) {
                                        if (!$pp->ASIN) {
                                            $pp->ASIN = $aAmazonFbaProduct->ASIN;
                                            $pp->save();
                                        }
                                    }
                                }

                                $counter_received['MyPrice']++;

                                $counter_dropped3[] = $aAmazonFbaProduct->productcode;

                                $key = array_search($aAmazonFbaProduct->productcode, $counter_dropped);
                                if ($key !== false) {
                                    unset($counter_dropped[$key]);
                                }
                            }
                        }
                    }
                }
                catch (\Exception $e) {
                    func_backprocess_log(Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_ORDER_INFO, $log_text . $e->getMessage());
                }

                if (!empty($counter_dropped)) {
                    foreach ($counter_dropped as $drop) {
                        if ($drop_model = ProductModel::objects()->get(['productcode' => $drop])) {
                            [$amz] = AmazonProductsFieldsModel::objects()->getOrNew(['productid' => $drop_model->productid]);
                            $amz->sleep_mp = 48;
                            $amz->save(['sleep_mp']);
                        }
                    }
                    func_backprocess_log(Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_ORDER_INFO, "Skipped SKU's in MyPrice: ".implode(', ', $counter_dropped));
                }

                $counter_send += max(count($filtered_products_cp), count($filtered_products_mp));
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
            $client = $amzPool->getFbaInventoryClientPack();

            $max_products = 50;
            $i = 1;

            /* process products without Missing SKUs*/

            while ($aProductsBatch = ProductModel::objects()
                ->filter(
                    [
                        'forsale' => 'Y',
                        new QOr(['amazon_enabled' => 'Y', 'amazon_fba' => 'Y']),
                        'missing_products__missing_productcode__isnull' => true
                    ]
                )
                ->paginate($i++, $max_products)
                ->all())
            {
                $counter_send += count($aProductsBatch);

                $aSKUs = array_map(function ($a) { return $a->productcode;}, $aProductsBatch);
                $counter_dropped = $aSKUs;
                try {
                    $inventory = $client->callGetListInventory($aSKUs);

                    if ($products = AmazonProductHelper::getListInventory($inventory, $aProductsBatch)) {
                        foreach ($products as $aAmazonFbaProduct) {
                            $aAmazonFbaProduct->save();
                            $counter_received['ListInventorySupply']++;

                            $key = array_search($aAmazonFbaProduct->productcode, $counter_dropped);
                            if ($key !== false) {
                                unset($counter_dropped[$key]);
                            }
                        }
                    }
                } catch (\Exception $e) {
                    $log_text = 'ERROR in getMyPriceForSKU competitive_pricing cron for SKU\'s: ' . implode(', ', $aSKUs) . "\n";
                    func_backprocess_log(Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_ORDER_INFO, $log_text . $e->getMessage());
                }
                if (!empty($counter_dropped)) {
                    func_backprocess_log(Xcart\AmazonMWS::BACK_PROCESS_LOG_NAME_ORDER_INFO, "Skipped SKU's in ListInventory: ".implode(', ', $counter_dropped));
                }
            }

            /* process products with Missing SKUs*/

            $amazonStore = new AmazonInventoryStore($client);

            if ($amazonStore->groupProductsById) {
                foreach ($amazonStore->groupProductsById as $amzProduct) {
                    if ($amzProduct->save()) {
                        $counter_received['ListInventorySupplyMissing']++;
                    }
                }
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
        case 'recommendations' :
            $client = $amzPool->getRecommendationClientPack();
            $recommendations = $client->callGetListRecommendations();
            $recommendations_result = $recommendations->getListRecommendationsResult();
            break;
    }

//    Xcart\Config::model(['name' => $log])->setValue('N')->_update();
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