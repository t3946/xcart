<?php
use Mindy\QueryBuilder\Q\QOr;
use Modules\Brand\Models\BrandModel;
use Modules\Brand\Models\BrandStorefrontModel;
use Modules\Distributor\Models\DistributorFeedFieldModel;
use Modules\Distributor\Models\SupplierFeedModel;
use Modules\Product\Helpers\ImageHelper;
use Modules\Product\Helpers\ProductHelper;
use Modules\Product\Models\CategoryModel;
use Modules\Product\Models\FilterModel;
use Modules\Product\Models\FilterProductModel;
use Modules\Product\Models\FilterValueModel;
use Modules\Product\Models\PricingModel;
use Modules\Product\Models\ProductCategoriesModel;
use Modules\Product\Models\ProductLinksModel;
use Modules\Product\Models\ProductModel;
use Modules\Product\Models\ProductStorefrontModel;
use Modules\Product\Models\ProductUpcChangesModel;
use Modules\Product\Stores\SupplierFeedStore;

define("CIDEV_CRON_START", "CRON");
session_start();

require "../top.inc.php";
require "../init.php";

global $config, $xcart_dir;

set_time_limit(0);
ini_set('memory_limit', '512M');

$feed_types = ["I" => "inventory", "P" => "product"];
$log_category = "supplier_feeds_v_2";

if (isset($argv) && is_array($argv)) {
    $feed_types = [];
    switch ($argv[1]) {
        case "I":
            $feed_types = ["I" => "inventory"];
            $log_category = "supplier_feeds_inventory";
            break;
        case "P":
            $feed_types = ["P" => "product"];
            $log_category = "supplier_feeds_product";
            break;
        default:
            $feed_types = ["I" => "inventory", "P" => "product"];
    }
}

if ($config[$log_category] == "Y") {
    //die("Already launched"); // ################################
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

$supplier_feeds = SupplierFeedModel::objects()->filter(['enabled' => 'Y', 'feed_type__in' =>  $feed_types, 'feed_file_name' => 'feed147i.txt']);

if (empty($supplier_feeds) || !is_array($supplier_feeds)) {
    $log_text = "--- xcart_supplier_feeds does not have 'enabled' rows. Script stopped.";
    func_backprocess_log($log_category, $log_text);
    func_backprocess_log("supplier feeds errors", $log_text);
    db_query_param(/** @lang MYSQL */
        'UPDATE xcart_config SET value=:value WHERE name=:name', ['value' => 'N', 'name' => $log_category]);
    die($log_text);
}

$product_cols_replace = array(
    "sku" => "productcode",
    "quantity" => "r_avail",
    "eta_date" => "eta_date_mm_dd_yyyy",
    "title" => "product",
    "listprice" => "list_price"
);

foreach ($supplier_feeds as $k => $supplierFeedModel) {
    $local_file = null;
    $distributorModel = $supplierFeedModel->distributor->get();
    $start_supplier_time = new DateTime('now');

    $discontinued_products_count = $updated_products_count = $inserted_products_count = 0;
    $all_feed_productcodes = $lastFeedFields = [];

    $md5_arr = explode(".", $supplierFeedModel->feed_file_name);
    array_pop($md5_arr);
    $md5_file = implode(".", $md5_arr) . ".md5";

    $md5_file_is_found = false;
    $ftp = ftp_connect($config["Supplier_feeds"]["Feeds_storage_path"]);
    if ($ftp && @ftp_login($ftp, $config["Supplier_feeds"]["Feeds_storage_login"], $config["Supplier_feeds"]["Feeds_storage_password"])) {
        ftp_pasv($ftp, true);
        $local_file = $xcart_dir . "/files/product_feeds_v2/" . str_replace("/", "_", $md5_file);
        $server_file = $md5_file;
        $file_is_found = false;
        if (@ftp_get($ftp, $local_file, $server_file, FTP_BINARY)) {
            $md5_file_is_found = true;
        }
        ftp_quit($ftp);
    }
    if ($md5_file_is_found) {
        $handle = fopen($local_file, "r");
        $md5 = fread($handle, filesize($local_file));
        fclose($handle);
        if ($md5 == $supplierFeedModel->last_md5) {
            $log_text = "manufacturerid: " . $supplierFeedModel->manufacturerid . ". md5 = last_md5. Feed skipped. ";
            $log_text .= "md5file: " . $md5 . " - md5db: " . $supplierFeedModel->last_md5;
            func_backprocess_log("supplier feeds errors", $log_text);
            continue;
        }
    } else {
        $log_text = "manufacturerid: " . $supplierFeedModel->manufacturerid . ". md5 file is not found. Skipped.";
        func_backprocess_log($log_category, $log_text);
        func_backprocess_log("supplier feeds errors", $log_text);
        continue;
    }

    $ftp = ftp_connect($config["Supplier_feeds"]["Feeds_storage_path"]);

    if ($ftp && @ftp_login($ftp, $config["Supplier_feeds"]["Feeds_storage_login"], $config["Supplier_feeds"]["Feeds_storage_password"])) {
        ftp_pasv($ftp, true);

        $local_file = $xcart_dir . "/files/product_feeds_v2/" . str_replace("/", "_", $supplierFeedModel->feed_file_name);
        $server_file = $supplierFeedModel->feed_file_name;

        $file_is_found = false;
        if (@ftp_get($ftp, $local_file, $server_file, FTP_BINARY)) {
            $file_is_found = true;
        }
        ftp_quit($ftp);
        if ($file_is_found) {
            $handle = fopen($local_file, "r");
            $contents = fread($handle, filesize($local_file));
            fclose($handle);
            $supplierFeed = new SupplierFeedStore($contents);
            if (empty($supplierFeed->products) || !is_array($supplierFeed->products)) {
                $log_text = "manufacturerid: " . $supplierFeedModel->manufacturerid . ". No products found. (" . $feed_types[$supplierFeedModel->feed_type] . ")";
                func_backprocess_log($log_category, $log_text);
                func_backprocess_log("supplier feeds errors", $log_text);
                continue;
            }
            if ($supplierFeed->count() != $supplierFeed->products_in_feed) {
                $log_text = "manufacturerid: {$supplierFeedModel->manufacturerid}. Corrupted feed file (by products in feed count). ({$feed_types[$supplierFeedModel->feed_type]}){$supplierFeed->count()} vs {$supplierFeed->products_in_feed}";
                func_backprocess_log($log_category, $log_text);
                func_backprocess_log("supplier feeds errors", $log_text);
                db_query_param(/** @lang MySQL */
                    'UPDATE xcart_config SET value=:value WHERE name=:name', ['value' => 'N', 'name' => $log_category]);
                continue;
            }
            if ($supplierFeed->supplier_id != $supplierFeedModel->manufacturerid) {
                $log_text = "manufacturerid: {$supplierFeedModel->manufacturerid}. Wrong supplier_id. ({$feed_types[$supplierFeedModel->feed_type]}) . Feed skipped.";
                func_backprocess_log($log_category, $log_text);
                func_backprocess_log("supplier feeds errors", $log_text);
                continue;
            }
            if ($supplierFeedModel->last_update_items_count > 0) {
                if (($supplierFeed->products_in_feed / $supplierFeedModel->last_update_items_count) < $supplierFeedModel->threshold) {
                    $log_text = "manufacturerid: {$supplierFeedModel->manufacturerid}. Too few products in feed in comparison with last update {$supplierFeedModel->products_in_feed} against {$supplierFeedModel->last_update_items_count}. ({$feed_types[$supplierFeedModel->feed_type]})";
                    func_backprocess_log($log_category, $log_text);
                    continue;
                }
            }
            $create_date_time_diff = time() - $supplierFeed->getFeedDate();
            $log_text = "manufacturerid: {$supplierFeedModel->manufacturerid}. Started. ({$feed_types[$supplierFeedModel->feed_type]})";
            func_backprocess_log($log_category, $log_text);

            /** @var ProductModel $modelProduct */
            foreach ($supplierFeed->products as $kp => $aProduct) {
                print($kp . ' --> ' . $aProduct['productcode'] . "\n");
                if (empty($aProduct['productcode'])) {
                    continue;
                }

                $lastFeedFields = array_unique(array_merge($lastFeedFields, array_keys($aProduct)));

                $modelProduct = ProductModel::objects()->filter(['productcode' => $aProduct['productcode']])->get();
                if (!$modelProduct) {
                    $modelProduct= new ProductModel();
                }

                $modelProduct->setAttributes($aProduct);
                $all_feed_productcodes[] = $modelProduct->productcode;

                if ($modelProduct->getIsNewRecord()) {
                    $discontinuedDate = $modelProduct->getFromQueryAttribute('discontinued_date');
                    if (!empty($discontinuedDate)) {
                        $discontinuedDateTimeDiff = strtotime($discontinuedDate) - time();
                        if ($discontinuedDateTimeDiff < (60 * 60 * 24 * 20)) {
                            if ($modelProduct->forsale != "N") {
                                $modelProduct->forsale = "N";
                                $modelProduct->update_search_index = "Y";
                            }
                        }
                    }
                    $todayDate = strtotime(date("Y-m-d"));
                    if (($modelProduct->eta_date_lock == "Y")
                        && ($modelProduct->getOldAttribute('eta_date_mm_dd_yyyy') > $todayDate)
                        && (($modelProduct->getOldAttribute('eta_date_mm_dd_yyyy') > $modelProduct->eta_date_mm_dd_yyyy) || empty($modelProduct->eta_date_mm_dd_yyyy))
                    ) {
                        $modelProduct->eta_date_mm_dd_yyyy = $modelProduct->getOldAttribute('eta_date_mm_dd_yyyy');
                    } else {
                        $modelProduct->eta_date_lock = "N";
                    }
                }

                switch ($supplierFeedModel->feed_type) {
                    case 'I' :
                        if ($modelProduct->getIsNewRecord()) {
                            continue;
                        }
                        $modelProduct->controlled_by_feed = $supplierFeedModel->feed_file_name;
                        break;
                    case 'P' :
                        if ($modelProduct->getIsNewRecord()) {
                            if (!empty($supplierFeed->defaults) && is_array($supplierFeed->defaults)) {
                                $modelProduct->setAttributes($supplierFeed->defaults);
                            }

                        } else {
                            if ($supplierFeedModel->add_new_only == "Y") {continue;}
                            if (!empty($supplierFeed->dont_update_fields) && is_array($supplierFeed->dont_update_fields)) {
                                foreach ($supplierFeed->dont_update_fields as $fieldUnset) {
                                    $trimDesc = trim($modelProduct->fulldescr);
                                    if ($fieldUnset != 'fulldescr' || $fieldUnset == 'fulldescr' && !empty($trimDesc)) {
                                        $modelProduct->setAttribute($fieldUnset, $modelProduct->getOldAttribute($fieldUnset));
                                    }
                                }
                            }
                            if ($modelProduct->weight_lock == 'Y' || (!$modelProduct->weight && $modelProduct->getOldAttribute('weight'))) {
                                $modelProduct->weight = $modelProduct->getOldAttribute('weight');
                            }
                            if ($modelProduct->shipping_weight_lock == 'Y' || (!$modelProduct->shipping_weight && $modelProduct->getOldAttribute('shipping_weight'))) {
                                $modelProduct->shipping_weight = $modelProduct->getOldAttribute('shipping_weight');
                            }
                            if ($modelProduct->dim_lock == 'Y') {
                                $modelProduct->dim_x = $modelProduct->getOldAttribute('dim_x');
                                $modelProduct->dim_y = $modelProduct->getOldAttribute('dim_y');
                                $modelProduct->dim_z = $modelProduct->getOldAttribute('dim_z');
                            } else {
                                rsort($aDimFeed = [$modelProduct->dim_x, $modelProduct->dim_y, $modelProduct->dim_z]);
                                rsort($aDimOld = [$modelProduct->getOldAttribute('dim_x'), $modelProduct->getOldAttribute('dim_y'), $modelProduct->getOldAttribute('dim_z')]);
                                $modelProduct->dim_x = empty($aDimFeed[0]) ? $aDimOld[0] : $aDimFeed[0];
                                $modelProduct->dim_y = empty($aDimFeed[1]) ? $aDimOld[1] : $aDimFeed[1];
                                $modelProduct->dim_z = empty($aDimFeed[2]) ? $aDimOld[2] : $aDimFeed[2];
                            }
                            if ($modelProduct->shipping_dim_lock == 'Y') {
                                $modelProduct->shipping_dim_x = $modelProduct->getOldAttribute('shipping_dim_x');
                                $modelProduct->shipping_dim_y = $modelProduct->getOldAttribute('shipping_dim_y');
                                $modelProduct->shipping_dim_z = $modelProduct->getOldAttribute('shipping_dim_z');
                            } else {
                                rsort($aShipDimFeed = [$modelProduct->shipping_dim_x, $modelProduct->shipping_dim_y, $modelProduct->shipping_dim_z]);
                                rsort($aShipDimOld = [$modelProduct->getOldAttribute('shipping_dim_x'), $modelProduct->getOldAttribute('shipping_dim_y'), $modelProduct->getOldAttribute('shipping_dim_z')]);
                                $modelProduct->dim_x = empty($aShipDimFeed[0]) ? $aShipDimOld[0] : $aShipDimFeed[0];
                                $modelProduct->dim_y = empty($aShipDimFeed[1]) ? $aShipDimOld[1] : $aShipDimFeed[1];
                                $modelProduct->dim_z = empty($aShipDimFeed[2]) ? $aShipDimOld[2] : $aShipDimFeed[2];
                            }
                        }

                        if (!empty($modelProduct->fulldescr) && $supplierFeedModel->native_full_description != "Y") {
                            $modelProduct->fulldescr = ProductHelper::cleanProductFullDescription($modelProduct->fulldescr);
                        }

                        $modelProduct->save();

                        if ($modelProduct->getIsNewRecord()) {
                            (new ProductCategoriesModel([
                                'categoryid' => $supplierFeedModel->base_category_id,
                                'productid' => $modelProduct->productid,
                                'main' => 'Y']))
                                ->save();

                            (new ProductStorefrontModel([
                                'productid' => $modelProduct->productid,
                                'sfid' => $supplierFeedModel->storefront_id]))
                                ->save();

                            (new PricingModel([
                                'productid' => $modelProduct->productid,
                                'quantity' => 1,
                                'price' => $modelProduct->distributor->calculatePrice($modelProduct)]))
                                ->save();

                            $clean_url = func_clean_url_autogenerate('P', $modelProduct->productid, array('product' => $modelProduct->product, 'productcode' => $modelProduct->productcode));
                            func_clean_url_add($clean_url, 'P', $modelProduct->productid);

                            func_build_quick_flags($modelProduct->productid);
                            func_build_quick_prices($modelProduct->productid);

                        } else {
                            /** @var PricingModel $pricingModel */
                            $pricingModel = PricingModel::objects()->get(['productid' => $modelProduct->productid, 'quantity' => 1]);
                            $priceDistrib = $modelProduct->distributor->calculatePrice($modelProduct);
                            if ($pricingModel && $pricingModel->price != $priceDistrib) {
                                $pricingModel->price = $priceDistrib;
                                $pricingModel->save();
                                func_build_quick_prices($modelProduct->productid);
                            }

                        }

                        //Images section
                        $aImages = $modelProduct->getFromQueryAttribute('images');
                        $aAltImageNames = $modelProduct->getFromQueryAttribute('alt_names');
                        if (!empty($aImages) && is_array($aImages)) {
                            foreach ($aImages as $kImg => $IMAGE_URL) {
                                $modelDImage = ImageHelper::uploadMainImage($IMAGE_URL, empty($aAltImageNames[$kImg]) ? $modelProduct->product : $aAltImageNames[$kImg], $supplierFeedModel->manufacturerid);
                                if ($modelDImage && $modelDImage->getIsNewRecord()){
                                    $modelDImage->id = $modelProduct->productid;
                                    $modelDImage->orderby = ($kImg + 1) * 10;
                                    $modelDImage->save();
                                    $image_info = func_set_correct_det_img($modelDImage->getAttributes(), true);
                                }
                            }
                        }

                        //Related section
                        $params = [];
                        $aRelatedInternalId = $modelProduct->getFromQueryAttribute('related_internal_id');
                        $aRelatedInternalSKU = $modelProduct->getFromQueryAttribute('related_sku');
                        if (!empty($aRelatedInternalId)) {
                            $params['supplier_internal_product_id__in'] = $aRelatedInternalId;
                        }
                        if (!empty($aRelatedInternalSKU)) {
                            $params['productcode__in'] = $aRelatedInternalSKU;
                        }
                        if (!empty($params)) {
                            $aRelatedProducts = ProductModel::objects()->filter(new QOr($params))->all();
                            if (!empty($aRelatedProducts)) {
                                foreach ($aRelatedProducts as $relatedModel) {
                                    $relatedModel = ProductLinksModel::objects()->getOrCreate(['productid1' => $modelProduct->productid, 'productid2' => $relatedModel->productid]);
                                }
                            }
                        }

                        //Brand section
                        $brandName = $modelProduct->getFromQueryAttribute('brand_name');
                        if (!empty($brandName)) {
                            $brandModel = BrandModel::objects()->get(['brand' => $brandName]);
                            if (!$brandModel) {
                                $brandModel = (new BrandModel([
                                    'brand' => $brandName,
                                    'orderby' => 10
                                ]));
                                $brandModel->save();
                                (new BrandStorefrontModel([
                                    'brandid' => $brandModel->brandid,
                                    'sfid' => $supplierFeedModel->storefront_id,
                                ]))
                                    ->save();
                                $clean_url = func_clean_url_autogenerate('M', $brandModel->brandid, array('brand' => $brandName));
                                func_clean_url_add($clean_url, 'M', $brandModel->brandid);
                            }
                            $modelProduct->brandid = $brandModel->brandid;
                        }

                        //Attributes section
                        FilterProductModel::objects()->delete(['productid' => $modelProduct->productid]);
                        $aAttributes = $modelProduct->getFromQueryAttribute('attributes');
                        if (!empty($aAttributes)) {
                            $attributes_str = '';
                            foreach ($aAttributes as $f_name => $fv_name_arr) {
                                if (!empty($fv_name_arr) && is_array($fv_name_arr)) {
                                    if (!empty($attributes_str)) {
                                        $attributes_str .= "<br />";
                                        $filterModel = FilterModel::objects()->getOrCreate(['f_name' => $f_name, 'storefrontid' => $supplierFeedModel->storefront_id]);
                                        foreach ($fv_name_arr as $fv_name) {
                                            $filterValueModel = FilterValueModel::objects()->getOrCreate(['f_id' => $filterModel->f_id, 'fv_name' => $fv_name]);
                                            FilterProductModel::objects()->getOrCreate(['fv_id' => $filterValueModel->fv_id, 'productid' => $modelProduct->productid]);
                                        }
                                    }
                                    $attributes_str .= $f_name . ": " . implode(", ", $fv_name_arr);
                                }
                            }
/*                            if (!empty($attributes_str) && !empty($modelProduct->fulldescr)) {
                                $modelProduct->fulldescr .= "<br /><br />Specifications:<br /><br />" . $attributes_str;
                            }*/
                        }

                        $aSupplierCategory = $modelProduct->getFromQueryAttribute('supplier_categories');
                        $aSupplierCategory = reset($aSupplierCategory);
                        if (!empty($aSupplierCategory)) {
                            $cats_arr = explode("/", $aSupplierCategory);
                            if (!empty($cats_arr) && is_array($cats_arr)) {
                                $parentid = $supplierFeedModel->base_category_id;
                                $lastCategory = null;
                                foreach ($cats_arr as $v_cat) {
                                    $modelCat = CategoryModel::objects()->get([
                                        'parentid' => $parentid,
                                        'category' => $v_cat]);
                                    if (!$modelCat) {
                                        $modelCat = new CategoryModel([
                                            'parentid' => $parentid,
                                            'category' => $v_cat,
                                            'storefrontid' => $supplierFeedModel->storefront_id,
                                            'is_bold' => 'Y',
                                            'order_by' => 10
                                        ]);
                                        /** @var CategoryModel $parentModel */
                                        $parentModel = CategoryModel::objects()->get(['categoryid' => $parentid]);
                                        if ($parentModel) {
                                            $modelCat->categoryid_path = $parentModel->categoryid_path . "/" . $modelCat->categoryid;
                                        }
                                        $modelCat->save();
                                    }
                                    $lastCategory = $modelCat;
                                }
                                if ($lastCategory) {
                                   if ($modelProduct->pc_classify_status && !in_array($modelProduct->pc_classify_status, ['AC', 'ACC', 'MC'])) {
                                       $productCatModel = ProductCategoriesModel::objects()->get(['productid' => $modelProduct->productid, 'main' => 'Y']);
                                       if ($productCatModel) {
                                           $productCatModel->categoryid = $lastCategory->categoryid;
                                           $productCatModel->save();
                                       }
                                   }
                                }
                            }
                        }

                        $newUPC = Xcart\Product::calculateUPC($modelProduct->upc);
                        if ($modelProduct->upc != $newUPC) {
                            $upcModel = ProductUpcChangesModel::objects()->get(['productid' => $modelProduct->productid]);
                            if (!$upcModel){
                                $upcModel = new ProductUpcChangesModel(['productid' => $modelProduct->productid, 'original_upc' => $modelProduct->upc, 'corrected_upc' => $newUPC]);
                                $upcModel->save();
                            }
                            $modelProduct->upc = $newUPC;
                        }

                        break;
                }

                $modelProduct->save();
            }

            if (!empty($all_feed_productcodes) && is_array($all_feed_productcodes) && $supplierFeedModel->disable_search_of_discontinued_items != 'Y') {
                print("Search of discontinued section\n");


                $mc = $distributorModel->code;
                $mc2 = substr($mc, 0, strpos($mc, '-'));
                print("Entering discontinue section for " . $mc . " or " . $mc2 . " \r\n");

                if ($supplierFeedModel->multiple_feed_destinations == 'Y') {
                    $provider_search_cond1 = $supplierFeedModel->feed_file_name;

                    if ($supplierFeedModel->feed_type == "I") {
                        $current_letter_in_replacement = "i";
                        $set_new_letter_for_replacement = "p";
                    } else {
                        // $feed_type == "P"
                        $current_letter_in_replacement = "p";
                        $set_new_letter_for_replacement = "i";
                    }

                    if (strpos($provider_search_cond1, "-") !== false) {
                        $provider_search_cond2 = str_replace($current_letter_in_replacement . "-", $set_new_letter_for_replacement . "-", $provider_search_cond1);
                    } else {
                        $provider_search_cond2 = str_replace($current_letter_in_replacement . ".", $set_new_letter_for_replacement . ".", $provider_search_cond1);
                    }
                    $provider_search_cond = " AND (controlled_by_feed='$provider_search_cond1' OR controlled_by_feed='$provider_search_cond2')";
                } else {
                    $provider_search_cond = "";
                }

                print("SELECT COUNT(*) FROM $sql_tbl[products] WHERE (productcode LIKE '" . $mc . "-%' or productcode like '" . $mc2 . "-%') AND forsale='Y' $provider_search_cond");
                print("\r\n");
                $count_products = func_query_first_cell("SELECT COUNT(1) FROM $sql_tbl[products] xp1 INNER JOIN $sql_tbl[products_sf] xp2 ON xp1.productid = xp2.productid AND xp2.sfid = {$supplierFeedModel->storefront_id} WHERE (productcode LIKE '" . $mc . "-%' OR productcode LIKE '" . $mc2 . "-%') AND forsale='Y' $provider_search_cond");
                print($count_products . " for sale = Y\r\n");
                if ($count_products > 0) {
                    $manufacturer_code_products = db_query("SELECT xp1.productid, xp1.productcode, xp1.forsale, xp1.update_search_index, xp1.provider
																	FROM $sql_tbl[products] xp1
																	INNER JOIN $sql_tbl[products_sf] xp2 ON xp1.productid = xp2.productid AND xp2.sfid = {$supplierFeedModel->storefront_id}
																	WHERE (productcode LIKE '" . $mc . "-%' OR productcode LIKE '" . $mc2 . "-%') AND forsale='Y' $provider_search_cond");
                    $line_number = 0;
                    print "<br />Second iteration:<br />";
                    while ($prod = db_fetch_array($manufacturer_code_products)) {
                        $line_number++;
                        if ($line_number % 100 == 0) {
                            func_flush(".");
                            if ($line_number % 5000 == 0) {
                                func_flush("<br />\n");
                            }
                            func_flush();
                        }
                        $_productcode = strtoupper(trim($prod["productcode"]));
                        if (!in_array($_productcode, $all_feed_productcodes) && $prod["forsale"] != "N") {
                            $discontinued_products_count++;
                            $update_search_index = $prod["update_search_index"];
                            if ($update_search_index == "N") {
                                $update_search_index = "D";
                            }
                            db_query("UPDATE $sql_tbl[products] SET r_avail='0', forsale='N'  WHERE productid='" . $prod["productid"] . "'");
                        }
                    }
                }
            }

            /* --------------------------------------------------------------------------------------------------- */
        } else {
            $log_text = "manufacturerid: " . $supplierFeedModel->manufacturerid . ". File is not found. Skipped.";
            func_backprocess_log($log_category, $log_text);
            func_backprocess_log("supplier feeds errors", $log_text);
            continue;
        }
    } else {
        $log_text = "manufacturerid: " . $supplierFeedModel->manufacturerid . ". Could not open host. Script stopped.";
        func_backprocess_log($log_category, $log_text);
        func_backprocess_log("supplier feeds errors", $log_text);
        db_query_param(/** @lang MySQL */
            'UPDATE xcart_config SET value=:value WHERE name=:name', ['value' => 'N', 'name' => $log_category]);
        die($log_text);
    }

    $feedProductCount = 0;
    if ($supplierFeed) {
        $feedProductCount = $supplierFeed->products_in_feed;
    }
    $last_update_period = time() - $supplierFeedModel->last_update_time;
    $average_update_period = round(($supplierFeedModel->average_update_period + $last_update_period) / 2, 0);
    $new_products_count = $feedProductCount - $updated_products_count - $discontinued_products_count;

    $feedModel = SupplierFeedModel::objects()->get(['feed_id' => $supplierFeedModel->feed_id]);
    if ($feedModel) {
        $feedModel->setAttributes([
            "last_md5" => $md5,
            "last_update_time" => time(),
            "average_update_period" => $average_update_period,
            "last_update_period" => $last_update_period,
            "last_feed_fields" => $last_feed_fields_arr_vals,
            "last_update_items_count" => $feedProductCount
        ]);
        $feedModel->save();
    }

    if (!empty($lastFeedFields)) {
        db_query_param(/** @lang MySQL */
            "UPDATE xcart_manufacturer_feed_fields SET locked=:locked WHERE manufacturerid=:manufacturerid AND feed_id = :feed_id",
                ['locked' => 'N', 'manufacturerid' => $supplierFeedModel->manufacturerid, 'feed_id' => $supplierFeedModel->feed_id]);
        foreach ($lastFeedFields as $fieldName) {
            $FieldModel = DistributorFeedFieldModel::objects()->getOrCreate([
                'field_name' => $fieldName,
                'manufacturerid' => $supplierFeedModel->manufacturerid,
                'feed_id' => $supplierFeedModel->feed_id
            ]);
            $FieldModel->locked = 'Y';
            $FieldModel->save();
        }
    }

    $str_time = (new DateTime('now'))->diff($start_supplier_time)->format('%H:%I:%S');

    $log_text = "manufacturerid: {$distributorModel->manufacturerid}:{$distributorModel->manufacturer} - completed. \n";
    $log_text .= "processed {$feedProductCount} items.\n";
    $log_text .= "found new {$new_products_count} items.\n";
    $log_text .= "updated {$updated_products_count} items.\n";
    if ($supplierFeedModel->feed_type == "P") { // product
        $log_text .= "inserted {$inserted_products_count} items.\n";
    }
    $log_text .= "discontinued: {$discontinued_products_count}\n";
    $log_text .= "Duration: " . $str_time . "\n";
    func_backprocess_log($log_category, $log_text);
}
######################################################################################


$str_time = (new DateTime('now'))->diff($start_time)->format('%H:%I:%S');
db_query_param(/** @lang MySQL */
    'UPDATE xcart_config SET value=:value WHERE name=:name', ['value' => 'N', 'name' => $log_category]);
$log_text = "Cron completed. Duration: " . $str_time;
func_backprocess_log($log_category, $log_text);

die("DONE!");
