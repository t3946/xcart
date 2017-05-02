<?php
use Mindy\QueryBuilder\Expression;
use Modules\Product\Models\ProductModel;
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
    $oMail = \Xcart\App\Main\Xcart::app()->mail;
    $oMail->to = 'team@s3stores.com';
    $oMail->from = 'team@s3stores.com';
    $oMail->subject = sprintf('Attention! Xcart cron %s Already launched', LOG_CATEGORY);
    $oMail->body = LOG_CATEGORY . ' already launched';
    $oMail->sendEmail();
    //die("Already launched");
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

$two_shippings = func_query_hash("SELECT shippingid, shipping, vol_threshold, dim_factor FROM $sql_tbl[shipping] WHERE shippingid='1' OR shippingid='65'", "shippingid", false);

$all_froogle_options = func_query_hash(" SELECT storefrontid, MerchantID, ClientID, BingMerchantID, BingCatalogID, enable_incremental_feed_updates FROM $sql_tbl[froogle_options]", 'storefrontid', false);


if (!empty($all_froogle_options) && is_array($all_froogle_options)) {
    foreach ($all_froogle_options as $k => $v) {
        $all_froogle_options[$k]["ClientID"] = text_decrypt($v["ClientID"]);
    }
}

$cidev_storefronts = $storefronts;
ksort($cidev_storefronts);

if (!empty($cidev_storefronts) && is_array($cidev_storefronts)) {

    foreach ($cidev_storefronts as $storefrontid => $sf_info) {
        $cidev_storefronts[$storefrontid] = func_get_storefront_info($storefrontid);
    }
    $cidev_storefronts[0] = func_get_storefront_info(0);

    $amazon_inventory_batch_count = $amazon_products_batch_count = 0;
    $ainventory = $aproducts = [];
    $BingMerchantID = $BingCatalogID = '';

    foreach ($cidev_storefronts as $storefrontid => $sf_info) {
        print("\n " . strftime("%X") . " --- storefront: " . $storefrontid . " --- \n");
        /** @var StoreFrontMarketPlace[] $aExternalMarketPlaces */
        $aExternalMarketPlaces = StoreFrontMarketPlace::getMarketPlacesByStoreFront($storefrontid);
        $cnt = 0;
        $sqlProductUpdate = /** @lang MySQL */
            <<<SQL
SELECT p.productid, pp.time_stamp, p.forsale, GROUP_CONCAT(pp.type ORDER BY pp.type) utype
  FROM xcart_cidev_updated_products pp
  INNER JOIN xcart_products p ON p.productid = pp.resourceid
  INNER JOIN xcart_products_sf PS ON PS.productid = p.productid
WHERE PS.sfid = :sfid AND pp.type <= :type AND p.forsale = :forsale
GROUP BY p.productid
ORDER BY utype DESC, forsale DESC
SQL;
        $aUpdatedProducts = Connection::getInstance()->fetchAll($sqlProductUpdate. " LIMIT 3000", ['sfid' => $storefrontid, 'type' => 2, 'forsale' => 'Y']);
        $timeout = 60 * 20;
        $storefront_time_start = time();

        if (empty($aUpdatedProducts)) {
            $aUpdatedProducts = Connection::getInstance()->fetchAll($sqlProductUpdate. " LIMIT 130", ['sfid' => $storefrontid, 'type' => 2, 'forsale' => 'N']);
        }

        if (!empty($aUpdatedProducts)) {
            $log_text = "Storefront: " . $sf_info["domain"] . " Storefrontid: " . $sf_info["storefrontid"];
            func_backprocess_log("incremental feeds", $log_text);
            foreach ($aUpdatedProducts as $product) {
                if ((time() - $storefront_time_start) > $timeout) {
                    func_backprocess_log("incremental feeds", "Time out processing {$timeout} sec. StorefrontID: {$storefrontid} ...");
                    break;
                }
                /** @var $oProduct ProductModel */
                $oProduct = ProductModel::objects()->get(['productid' => $product['productid']]);
                //if ($storefrontid == $product["maxsf"])
                db_query_param(/** @lang MySQL */
                    "DELETE FROM xcart_cidev_updated_products WHERE resourceid=:productid AND time_stamp <= :started_at AND (type='2' || type='1')",
                    ['productid' => $product['productid'], 'started_at' => $started_at]
                );
                if ($oProduct) {
                    $oProduct->last_incremental_update = time();
                    $oProduct->save();
                    $googleOneRow = null;
                    foreach ($aExternalMarketPlaces as $oExternalMarketPlace) {
                        if (is_null($googleOneRow) && in_array($product["utype"], ['1', '1,2', '2,1'])) {
                            $googleOneRow = $oExternalMarketPlace->getGoogleOneRow($oProduct->getDataModel(), EXTRA_LOG);
                        }
                        if ($oExternalMarketPlace->getExternalMarketPlaceEntity()->getMarketPlaceStatus() == 'Y') {
                            $oExternalMarketPlace->addProductToBatch($oProduct->getDataModel(), $product["utype"], $googleOneRow, EXTRA_LOG);
                        }
                        if ($oExternalMarketPlace->getCurrentInventoryBatchCount() == $oExternalMarketPlace->getInventoryBatchCount()) {
                            $oExternalMarketPlace->submitInventoryBatch(SUBMIT_DISABLE, EXTRA_LOG);
                        }
                        if ($oExternalMarketPlace->getCurrentProductsBatchCount() == $oExternalMarketPlace->getProductsBatchCount()) {
                            $oExternalMarketPlace->submitProductsBatch(SUBMIT_DISABLE, EXTRA_LOG);
                        }
                    }
                }
                $cnt++;
            }
        }

        foreach ($aExternalMarketPlaces as $oExternalMarketPlace) {
            $aInventory = $oExternalMarketPlace->getInventory();
            if ($oExternalMarketPlace->getCurrentInventoryBatchCount() > 0 && !empty($aInventory) && is_array($aInventory)) {
                $oExternalMarketPlace->submitInventoryBatch(SUBMIT_DISABLE, EXTRA_LOG);
            }
            $aProducts = $oExternalMarketPlace->getProducts();
            if ($oExternalMarketPlace->getCurrentProductsBatchCount() > 0 && !empty($aProducts) && is_array($aProducts)) {
                $oExternalMarketPlace->submitProductsBatch(SUBMIT_DISABLE, EXTRA_LOG);
            }
        }

        print ("processed: " . $cnt . " items !!>\n");

        if ($cnt > 0) {

            $log_text = "processed: " . $cnt . " items";
            func_backprocess_log("incremental feeds", $log_text);
        }
    }

    db_query_param(/** @lang MySQL */
        "DELETE FROM xcart_cidev_updated_products WHERE type='3' AND time_stamp <= :started_at", ['started_at' => $started_at]);
}

db_query_param(/** @lang MySQL */
    "UPDATE xcart_config SET value='N' WHERE name=:name", ['name' => LOG_CATEGORY]);

$str_time = (new DateTime('now'))->diff($start_time)->format('%H:%I:%S');
$log_text = "Cron completed. Processing time: {$str_time}";
func_backprocess_log("incremental feeds", $log_text);

die("DONE!");
