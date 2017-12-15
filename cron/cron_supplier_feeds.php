<?php

use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QOr;
use Modules\Distributor\Models\DistributorFeedFieldModel;
use Modules\Distributor\Models\SupplierFeedModel;
use Modules\Goods\Helpers\SupplierFeedHelper;
use Modules\Goods\Helpers\ProductHelper;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\ProductModel;
use Modules\Goods\Stores\SupplierFeedStore;

define("CIDEV_CRON_START", "CRON");
session_start();

require "../top.inc.php";
require "../init.php";

global $config, $xcart_dir;

set_time_limit(0);
ini_set('memory_limit', '1024M');

$feed_types = ["I" => "inventory", "P" => "product"];
$log_category = "supplier_feeds_v_2";

if (isset($argv) && is_array($argv)) {
    switch ($argv[1]) {
        case "I":
            $feed_types = ["I" => "inventory"];
            $log_category = "supplier_feeds_inventory";
            break;
        case "P":
            $feed_types = ["P" => "product"];
            $log_category = "supplier_feeds_product";
            break;
    }
}

if ($config[$log_category] == "Y") {
    $oMail = \Xcart\App\Main\Xcart::app()->oldMail;
    $oMail->to = 'team@s3stores.com';
    $oMail->from = ('team@s3stores.com');
    $oMail->subject = sprintf('Attention! Xcart cron %s Already launched', $log_category);
    $oMail->body = $log_category . ' already launched';
    $oMail->sendEmail();
    if (!isset($argv) || (isset($argv) && !in_array('--force-flag', $argv))) {
        die("Already launched"); // ################################
    }
}
db_query_param('REPLACE xcart_config SET value=:value, name=:name', ['value' => 'Y', 'name' => $log_category]);

$start_time = new DateTime('now');
$log_text = " * * *  Cron started  * * * ";
func_backprocess_log($log_category, $log_text);

if (empty($config["Supplier_feeds"]["Feeds_storage_path"]) || empty($config["Supplier_feeds"]["Feeds_storage_login"]) || empty($config["Supplier_feeds"]["Feeds_storage_password"])) {
    $log_text = "--- login credentials incorrect. Script stopped.";
    func_backprocess_log($log_category, $log_text);
    func_backprocess_log("supplier feeds errors", $log_text);
    db_query_param(/** @lang MySQL */
        'UPDATE xcart_config SET value=:value WHERE name=:name', ['value' => 'N', 'name' => $log_category]);
    die($log_text);
}

$supplier_feeds = SupplierFeedModel::objects()->filter(['enabled' => 'Y', 'new_cron' => 'Y', 'feed_type__in' => array_keys($feed_types)])->all();

if (!$supplier_feeds) {
    $log_text = "--- xcart_supplier_feeds does not have 'enabled' rows. Script stopped.";
    func_backprocess_log($log_category, $log_text);
    func_backprocess_log("supplier feeds errors", $log_text);
    db_query_param(/** @lang MYSQL */
        'UPDATE xcart_config SET value=:value WHERE name=:name', ['value' => 'N', 'name' => $log_category]);
    die($log_text);
}

/** @var SupplierFeedModel[] $supplier_feeds */
foreach ($supplier_feeds as $k => $supplierFeedModel) {

    $discontinued_products_count = $updated_products_count = $inserted_products_count = $new_products_count = $skippedProductsCount = 0;
    $all_feed_productcodes = $lastFeedFields = $last_feed_fields_arr_vals = [];

    clearstatcache();

    $start_supplier_time = new DateTime();

    $md5_arr = explode(".", $supplierFeedModel->feed_file_name);
    array_pop($md5_arr);
    $md5_file = implode(".", $md5_arr) . ".md5";

    if ($md5 = SupplierFeedHelper::getFileFtp($md5_file, $config)) {
        if ($md5 == $supplierFeedModel->last_md5) {
            $log_text = "manufacturerid: {$supplierFeedModel->manufacturerid}. md5 = last_md5. Feed skipped. md5file: {$md5} - md5db: {$supplierFeedModel->last_md5}";
            func_backprocess_log("supplier feeds errors", $log_text);
            continue;
        } else {
            $log_text = "md5file: " . $md5 . " - md5db: " . $supplierFeedModel->last_md5;
            func_backprocess_log($log_category, $log_text);
        }
    } else {
        $log_text = "manufacturerid: " . $supplierFeedModel->manufacturerid . ". md5 file is not found. Skipped.";
        func_backprocess_log($log_category, $log_text);
        func_backprocess_log("supplier feeds errors", $log_text);
        continue;
    }

    $contents = SupplierFeedHelper::getFileFtp($supplierFeedModel->feed_file_name, $config);

    if (!$contents) {
        $log_text = "manufacturerid: " . $supplierFeedModel->manufacturerid . ". File read error. Skipped.";
        func_backprocess_log($log_category, $log_text);
        func_backprocess_log("supplier feeds errors", $log_text);
        continue;
    }

    $supplierFeed = new SupplierFeedStore($supplierFeedModel, $contents);

    if (!$supplierFeed->isValid()) {

        $log_text = implode($supplierFeed->errors, PHP_EOL);

        func_backprocess_log($log_category, $log_text);
        func_backprocess_log("supplier feeds errors", $log_text);
        continue;
    }

    $create_date_time_diff = time() - $supplierFeed->getFeedDate();
    $log_text = "manufacturerid: {$supplierFeedModel->manufacturerid}. Started. ({$supplierFeedModel->getField('feed_type')->toText()})";
    func_backprocess_log($log_category, $log_text);

    foreach ($supplierFeed->products as $kp => $prod) {

        $products = [];

        if (isset($prod['is_group']) && $prod['is_group'] === true) {
            if ($prod['child_products']) {
                $products = SupplierFeedHelper::feedChilds($prod, $supplierFeed);
            }
        } else {
            $products[] = $prod;
        }

        foreach ($products as $aProduct) {

            print($kp . ' --> ' . $aProduct['productcode'] . "\n");

            if (empty($aProduct['productcode']) || (isset($aProduct['cost_to_us']) && floatval($aProduct['cost_to_us']) <= 0)) {
                $skippedProductsCount++;
                continue;
            }

            /** @var ProductModel $modelProduct */

            list($modelProduct, $is_created) = ProductModel::objects()->getOrNew(['productcode' => $aProduct['productcode']]);

            switch ($supplierFeedModel->feed_type) {
                case 'I' :
                    if ($is_created) {
                        $new_products_count++;
                        continue;
                    }

                    break;
                case 'P' :
                    $modelProduct->save();
                    if (!isset($aProduct['is_group'])) {
                        if (!isset($aProduct['cost_to_us'])) {
                            $skippedProductsCount++;
                            continue;
                        }
                        if (!$is_created && $supplierFeedModel->add_new_only == "Y") {
                            $skippedProductsCount++;
                            continue;
                        }
                    }
                    break;
            }

            if ($modelProduct) {

                $modelProduct->setAttributes($aProduct);

                $modelProduct = SupplierFeedHelper::feedProduct($modelProduct, $is_created, $supplierFeedModel, $aProduct, $supplierFeed->dont_update_fields, $supplierFeed->defaults);

                if ($is_created) {
                    $new_products_count++;
                    $inserted_products_count++;
                } else if ($modelProduct->getChangedAttributes()) {
                    print_r($modelProduct->getChangedAttributes());
                    $updated_products_count++;
                }

                $modelProduct->save();

                $all_feed_productcodes[] = $modelProduct->productcode;
                $last_feed_fields_arr_vals = $aProduct;
                $lastFeedFields = array_unique(array_merge($lastFeedFields, array_keys($aProduct)));
            }
        }
    }

    if (!empty($all_feed_productcodes) && is_array($all_feed_productcodes) && $supplierFeedModel->disable_search_of_discontinued_items != 'Y') {
        print("Search of discontinued section\n");

        /** @var ProductModel[] $discountinued_models */
        if ($discountinued_models = ProductModel::objects()->filter(
            [
                'sites__through__sfid' => $supplierFeedModel->storefront_id,
                'manufacturerid' => $supplierFeedModel->manufacturerid,
                'forsale' => 'Y',
                new QOr(['productid__isnt' => new Expression('group_root'), 'group_root_isnull' => true])
            ])->all()) {

            print "<br />Second iteration:<br />";

            foreach ($discountinued_models as $d_model) {
                if (!in_array(strtoupper(trim($d_model->productcode)), $all_feed_productcodes)) {
                    $discontinued_products_count++;

                    if ($d_model->update_search_index == "N") {
                        $d_model->update_search_index = "D";
                    }

                    $d_model->setAttributes(['r_avail' => 0, 'forsale' => 'N',]);

                    $d_model->save();
                }
            }
        }
    }


    $feedProductCount = 0;
    if ($supplierFeed) {
        $feedProductCount = $supplierFeed->products_in_feed;
    }
    $last_update_period = time() - $supplierFeedModel->last_update_time;
    $average_update_period = round(($supplierFeedModel->average_update_period + $last_update_period) / 2, 0);

    if ($supplierFeedModel) {
        $supplierFeedModel->setAttributes([
            "last_md5" => $md5,
            "last_update_time" => time(),
            "average_update_period" => $average_update_period,
            "last_update_period" => $last_update_period,
            "last_feed_fields" => $last_feed_fields_arr_vals,
            "last_update_items_count" => $feedProductCount
        ]);
        $supplierFeedModel->save();
    }

    if (!empty($lastFeedFields)) {
        if (!empty($supplierFeed->dont_update_fields)) {
            $lastFeedFields = array_diff($lastFeedFields, $supplierFeed->dont_update_fields);
        }

        DistributorFeedFieldModel::objects()->filter([
            'manufacturerid' => $supplierFeedModel->manufacturerid,
            'feed_id' => $supplierFeedModel->feed_id
        ])
            ->update(['locked' => 'N']);

        foreach ($lastFeedFields as $fieldName) {

            /** @var DistributorFeedFieldModel $FieldModel */

            list($FieldModel) = DistributorFeedFieldModel::objects()->getOrNew([
                'field_name' => $fieldName,
                'manufacturerid' => $supplierFeedModel->manufacturerid,
                'feed_id' => $supplierFeedModel->feed_id
            ]);
            $FieldModel->locked = 'Y';
            $FieldModel->save();
        }
    }

    $distributorModel = $supplierFeedModel->distributor;

    $log_text = "manufacturerid: {$distributorModel->manufacturerid}:{$distributorModel->manufacturer} - completed. \n";
    $log_text .= "processed {$feedProductCount} items.\n";
    $log_text .= "found new {$new_products_count} items.\n";
    $log_text .= "updated {$updated_products_count} items.\n";
    if ($inserted_products_count) {
        $log_text .= "inserted {$inserted_products_count} items.\n";
    }
    $log_text .= "discontinued: {$discontinued_products_count}\n";
    $log_text .= "skipped: {$skippedProductsCount}\n";
    $log_text .= "Duration: " . (new DateTime('now'))->diff($start_supplier_time)->format('%H:%I:%S') . "\n";
    func_backprocess_log($log_category, $log_text);
}
######################################################################################


db_query_param(/** @lang MySQL */
    'UPDATE xcart_config SET value=:value WHERE name=:name', ['value' => 'N', 'name' => $log_category]);
$log_text = "Cron completed. Duration: " . (new DateTime('now'))->diff($start_time)->format('%H:%I:%S');
func_backprocess_log($log_category, $log_text);

die("DONE!");
