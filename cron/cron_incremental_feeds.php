<?php

use Mindy\QueryBuilder\Aggregation\Count;
use Mindy\QueryBuilder\Aggregation\Min;
use Mindy\QueryBuilder\Expression;
use Modules\Goods\Models\ProductModel;
use Modules\Goods\Models\UpdatedProductModel;
use Xcart\Connection;
use Xcart\External_Marketplaces\StoreFrontMarketPlace;

define("CIDEV_CRON_START", "CRON");
session_start();

require "../top.inc.php";
require "../init.php";

global $xcart_dir, $config, $storefronts, $aManufacturerZones;

$start_time = new \DateTime('now');

$debug_requests = 'N';
$froogle_tracing_token = 'ANY78kLeWOxH4je4ZmHHsdNUGUhaxDLr2qkUcqeZ3MPGH1qjH2RdLqjUjqYTc95GthRPCu8dconorTv7DtGlvI5RDlQlVyq4xzMqr9hiS5aaTT9NlPQrsJc';
const LOG_CATEGORY = 'cidev_incremental_feeds_launched_v_3';

define("FROOGLE_TAIL", '...');
define("FROOGLE_TAIL_LEN", strlen(constant("FROOGLE_TAIL")));
define('FROOGLE_MAX_DESCRIPTION_LENGTH', 10 * 1024); //The content in an attribute in an item exceeds 10 KB.

define('EXCLUDE_CATEGORYID_BRANCH', 5099);
define('SUBMIT_DISABLE', 'N');
define('EXTRA_LOG', 'N');

set_time_limit(0);

if ($config[LOG_CATEGORY] == "Y") {
    func_backprocess_log('incremental feeds', 'Already launched');
    $oMail = \Xcart\App\Main\Xcart::app()->oldMail;
    $oMail->to = 'team@s3stores.com';
    $oMail->from = 'team@s3stores.com';
    $oMail->subject = sprintf('Attention! Xcart cron %s Already launched', LOG_CATEGORY);
    $oMail->body = LOG_CATEGORY . ' already launched';
    $oMail->sendEmail();
    if (!isset($argv) || (isset($argv) && !in_array('--force-flag', $argv))) {
        die("Already launched"); // ################################
    }
}

$xcart_states_US = func_query_param(/** @lang MySQL */
    <<<SQL
SELECT stateid, state, code, country_code, base_state_zipcode, city FROM xcart_states
LEFT JOIN xcart_geo_litecity_location ON country = country_code AND postalCode = base_state_zipcode
 WHERE base_state_zipcode!='' AND country_code=:co GROUP BY stateid
SQL
    , ['co' => 'US']);

db_query_param(/** @lang MySQL */
    "REPLACE xcart_config SET value='Y', name=:name", ['name' => LOG_CATEGORY]);

$started_at = time();

func_backprocess_log("incremental feeds", " ");
$log_text = " * * *  Cron started  * * * SUBMIT_DISABLE = '" . SUBMIT_DISABLE . "', EXTRA_LOG = '" . EXTRA_LOG . "'";
func_backprocess_log("incremental feeds", $log_text);
if ($start_time->format('G') == "0") {
    $products = ProductModel::objects()
        ->filter([
            'forsale' => 'Y',
            new Expression('eta_date_mm_dd_yyyy = UNIX_TIMESTAMP(DATE(NOW()))'),
        ])
        ->all();
    if (!empty($products)) {
        foreach ($products as $product) {
            db_query_param($qqq = /** @lang MySQL */
                "INSERT IGNORE INTO xcart_cidev_updated_products (resourceid, type, time_stamp, source) VALUES (:productid, '2', '" . time() . "', 'eta_end')", ['productid' => $product->productid]);
        }
    }
}


$cidev_storefronts = $storefronts;
ksort($cidev_storefronts);

UpdatedProductModel::objects()->delete(
    [
        'mask' => 0
    ]
);

if (!empty($cidev_storefronts) && is_array($cidev_storefronts)) {

    foreach ($cidev_storefronts as $storefrontid => $sf_info) {
        $cidev_storefronts[$storefrontid] = func_get_storefront_info($storefrontid);
    }
    $cidev_storefronts[0] = func_get_storefront_info(0);

    $amazon_inventory_batch_count = $amazon_products_batch_count = 0;
    $ainventory = $aproducts = [];
    $BingMerchantID = $BingCatalogID = '';

    foreach ($cidev_storefronts as $storefrontid => $sf_info) {

        $cnt = 0;

        print("\n " . strftime("%X") . " --- storefront: " . $storefrontid . " --- \n");

        /** @var StoreFrontMarketPlace[] $aExternalMarketPlaces */
        $aExternalMarketPlaces = StoreFrontMarketPlace::getMarketPlacesByStoreFront($storefrontid);

        $defaultMask = 0;
        foreach ($aExternalMarketPlaces as $market) {
            $defaultMask += $market->getExternalMarketPlaceEntity()->mask;
        }

        if ($doubles = UpdatedProductModel::objects()
            ->getQuerySet()
            ->select(
                [
                    'resourceid',
                    'cnt' => new Count('*'),
                    'utype' => new Min('type'),
                    'gtype' => new Expression('GROUP_CONCAT(type ORDER BY type)'),
                    'gmask' => new Expression("GROUP_CONCAT(IFNULL(mask, 'null'))")
                ])
            ->filter(
                [
                    'product__sites__storefrontid' => $storefrontid,
                    'type__lte' => 2
                ])
            ->group(['resourceid'])
            ->having(['cnt__gt' => 1])
            ->all()) {
            /** @var UpdatedProductModel[] $doubles */
            /** @var UpdatedProductModel $new */
            foreach ($doubles as $double) {
                UpdatedProductModel::objects()->delete(['resourceid' => $double->resourceid, 'type__in' => $double->getFromQueryAttribute('gtype')]);
                list($new) = UpdatedProductModel::objects()->getOrNew(['resourceid' => $double->resourceid, 'type' => $double->getFromQueryAttribute('utype')]);

                $or_mask = array_reduce(
                    explode(',', $double->getFromQueryAttribute('gmask')),
                    function($a, $b) use ($defaultMask) {
                        if ($a === 'null') {
                            $a = $defaultMask;
                        }
                        if ($b === 'null') {
                            $b = $defaultMask;
                        }
                        return $a | $b;
                        }, 0
                );

                $new->setAttributes([
                    'source' => 'cron_group',
                    'mask' => $or_mask
                ]);
                $new->save();
            }
        }

        /** @var UpdatedProductModel[] $queues */
        if ($queues = UpdatedProductModel::objects()
            ->select(['*', 'product__forsale', 'utype' => new Expression('GROUP_CONCAT(type ORDER BY type)')])
            ->filter(
                [
                    'product__sites__storefrontid' => $storefrontid,
                    'type__lte' => 2
                ])
            ->group(['resourceid'])
            ->order(['-utype', '-product__forsale'])
            ->limit(3000)
            ->all()) {
            $timeout = 60 * 20;
            $storefront_time_start = time();
            $log_text = "Storefront: " . $sf_info["domain"] . " Storefrontid: " . $sf_info["storefrontid"];
            func_backprocess_log("incremental feeds", $log_text);

            foreach ($queues as $queue_o) {

                if ($queue = UpdatedProductModel::objects()
                    ->get(
                        [
                            'resourceid' => $queue_o->resourceid,
                            'type' => $queue_o->type
                        ])
                ) {

                    if (is_null($queue->mask)) {
                        $queue->mask = $defaultMask;
                        if ($queue->mask === 0) {
                            UpdatedProductModel::objects()->delete(['resourceid' => $queue->resourceid, 'type' => $queue->type]);
                            continue;
                        }
                        $queue->save();
                    }

                    if ((time() - $storefront_time_start) > $timeout) {
                        func_backprocess_log("incremental feeds", "Time out processing {$timeout} sec. StorefrontID: {$storefrontid} ...");
                        break;
                    }

                    if ($oProduct = $queue->product) {

                        /** @var $oProduct ProductModel */
                        $oProduct->last_incremental_update = time();

                        $oProduct->save();

                        if ($oProduct->isGroupRoot()) {
                            $queue->mask = 0;
                            $queue->save();
                            continue;
                        }

                        $googleOneRow = null;

                        foreach ($aExternalMarketPlaces as $oExternalMarketPlace) {
                            if (is_null($googleOneRow)) {
                                $googleOneRow = $oExternalMarketPlace->getGoogleOneRow($oProduct, $queue, EXTRA_LOG);
                            }

                            if ($oExternalMarketPlace->getExternalMarketPlaceEntity()->getMarketPlaceStatus() == 'Y') {
                                if (!($oExternalMarketPlace->addProductToBatch($queue, $googleOneRow, EXTRA_LOG))) {

                                }
                            }
                            if ($oExternalMarketPlace->getCurrentInventoryBatchCount() == $oExternalMarketPlace->getInventoryBatchCount()) {
                                if ($oExternalMarketPlace->submitInventoryBatch(SUBMIT_DISABLE, EXTRA_LOG)) {
                                    $oExternalMarketPlace->successInventory();
                                }
                            }
                            if ($oExternalMarketPlace->getCurrentProductsBatchCount() == $oExternalMarketPlace->getProductsBatchCount()) {
                                if ($oExternalMarketPlace->submitProductsBatch(SUBMIT_DISABLE, EXTRA_LOG)) {
                                    $oExternalMarketPlace->successProduct();
                                }
                            }
                        }
                    }
                    $cnt++;
                }
            }
        }

        foreach ($aExternalMarketPlaces as $oExternalMarketPlace) {
            $aInventory = $oExternalMarketPlace->getInventory();
            if ($oExternalMarketPlace->getCurrentInventoryBatchCount() > 0 && !empty($aInventory) && is_array($aInventory)) {
                if ($oExternalMarketPlace->submitInventoryBatch(SUBMIT_DISABLE, EXTRA_LOG)) {
                    $oExternalMarketPlace->successInventory();
                }
            }
            $aProducts = $oExternalMarketPlace->getProducts();
            if ($oExternalMarketPlace->getCurrentProductsBatchCount() > 0 && !empty($aProducts) && is_array($aProducts)) {
                if ($oExternalMarketPlace->submitProductsBatch(SUBMIT_DISABLE, EXTRA_LOG)) {
                    $oExternalMarketPlace->successProduct();
                }
            }
        }

        print ("processed: " . $cnt . " items !!>\n");

        if ($cnt > 0) {

            $log_text = "processed: " . $cnt . " items";
            func_backprocess_log("incremental feeds", $log_text);
        }
    }

    UpdatedProductModel::objects()->delete(
        [
            'type' => 3,
            'time_stamp__lte' => $started_at
        ]
    );

    UpdatedProductModel::objects()->delete(
        [
            'mask' => 0
        ]
    );

}

db_query_param(/** @lang MySQL */
    "UPDATE xcart_config SET value='N' WHERE name=:name", ['name' => LOG_CATEGORY]);

$str_time = (new DateTime('now'))->diff($start_time)->format('%H:%I:%S');
$log_text = "Cron completed. Processing time: {$str_time}";
func_backprocess_log("incremental feeds", $log_text);

die("DONE!");
