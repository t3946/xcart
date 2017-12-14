<?php

namespace Modules\Goods\Helpers;


use Mindy\QueryBuilder\Q\QOr;
use Modules\Brand\Models\BrandModel;
use Modules\Brand\Models\BrandStorefrontModel;
use Modules\Distributor\Models\SupplierFeedModel;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\FilterModel;
use Modules\Goods\Models\FilterProductModel;
use Modules\Goods\Models\FilterValueModel;
use Modules\Goods\Models\PricingModel;
use Modules\Goods\Models\ProductLinksModel;
use Modules\Goods\Models\ProductModel;
use Modules\Goods\Models\ProductStorefrontModel;
use Modules\Goods\Models\ProductUpcChangesModel;

class SupplierFeedHelper
{
    /**
     * @param ProductModel $model
     * @return ProductModel
     */
    public static function getEtaDate($model)
    {
        $todayDate = strtotime(date("Y-m-d"));

        if (($model->eta_date_lock == "Y")
            && ($model->getOldAttribute('eta_date_mm_dd_yyyy') > $todayDate)
            && (($model->getOldAttribute('eta_date_mm_dd_yyyy') > $model->eta_date_mm_dd_yyyy) || empty($model->eta_date_mm_dd_yyyy))
        ) {
            $model->eta_date_mm_dd_yyyy = $model->getOldAttribute('eta_date_mm_dd_yyyy');
        } else {
            $model->eta_date_lock = "N";
        }

        return $model;
    }

    /**
     * @param ProductModel $model
     * @return ProductModel
     */
    public static function getWeightOptions($model)
    {
        if ($model->weight_lock == 'Y' || (!$model->weight && $model->getOldAttribute('weight'))) {
            $model->weight = $model->getOldAttribute('weight');
        }
        if ($model->shipping_weight_lock == 'Y' || (!$model->shipping_weight && $model->getOldAttribute('shipping_weight'))) {
            $model->shipping_weight = $model->getOldAttribute('shipping_weight');
        }
        if ($model->dim_lock == 'Y') {
            $model->dim_x = $model->getOldAttribute('dim_x');
            $model->dim_y = $model->getOldAttribute('dim_y');
            $model->dim_z = $model->getOldAttribute('dim_z');
        } else {
            $aDimFeed = [$model->dim_x, $model->dim_y, $model->dim_z];
            $aDimOld = [$model->getOldAttribute('dim_x'), $model->getOldAttribute('dim_y'), $model->getOldAttribute('dim_z')];
            rsort($aDimFeed);
            rsort($aDimOld);
            $model->dim_x = empty($aDimFeed[0]) ? $aDimOld[0] : $aDimFeed[0];
            $model->dim_y = empty($aDimFeed[1]) ? $aDimOld[1] : $aDimFeed[1];
            $model->dim_z = empty($aDimFeed[2]) ? $aDimOld[2] : $aDimFeed[2];
        }
        if ($model->shipping_dim_lock == 'Y') {
            $model->shipping_dim_x = $model->getOldAttribute('shipping_dim_x');
            $model->shipping_dim_y = $model->getOldAttribute('shipping_dim_y');
            $model->shipping_dim_z = $model->getOldAttribute('shipping_dim_z');
        } else {
            $aShipDimFeed = [$model->shipping_dim_x, $model->shipping_dim_y, $model->shipping_dim_z];
            $aShipDimOld = [$model->getOldAttribute('shipping_dim_x'), $model->getOldAttribute('shipping_dim_y'), $model->getOldAttribute('shipping_dim_z')];
            rsort($aShipDimFeed);
            rsort($aShipDimOld);
            $model->shipping_dim_x = empty($aShipDimFeed[0]) ? $aShipDimOld[0] : $aShipDimFeed[0];
            $model->shipping_dim_y = empty($aShipDimFeed[1]) ? $aShipDimOld[1] : $aShipDimFeed[1];
            $model->shipping_dim_z = empty($aShipDimFeed[2]) ? $aShipDimOld[2] : $aShipDimFeed[2];
        }

        return $model;
    }

    /**
     * @param ProductModel $model
     * @return array
     */
    public static function getUPC($model)
    {
        $newUPC = ProductHelper::calculateUPC($model->upc);
        $oldUPC = $model->getOldAttribute('upc');
        if ($oldUPC != $newUPC) {
            $model->upc = $newUPC;
        } else {
            $model->upc = $oldUPC;
        }

        return [$model, $oldUPC != $newUPC];
    }

    /**
     * @param ProductModel $model
     * @param bool $is_created
     * @param SupplierFeedModel $feed
     * @param array $data
     * @param array $dont_update_fields
     * @param array $defaults
     * @return mixed|ProductModel
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Exception
     */
    public static function feedProduct($model, $is_created, $feed, $data, $dont_update_fields, $defaults)
    {
        $model->controlled_by_feed = $feed->feed_file_name;

        $model->source_sfid = $feed->storefront_id;
        $model->manufacturerid = $feed->manufacturerid;

        $discontinuedDate = $data['discontinued_date'];
        if (!empty($discontinuedDate)) {
            $discontinuedDateTimeDiff = strtotime($discontinuedDate) - time();
            if ($discontinuedDateTimeDiff < (60 * 60 * 24 * 20)) {
                if ($model->forsale != "N") {
                    $model->forsale = "N";
                    $model->update_search_index = "Y";
                    return $model;
                }
            }
        }

        if (!empty($model->fulldescr) && $feed->native_full_description != "Y") {
            $model->fulldescr = ProductHelper::cleanProductFullDescription($model->fulldescr);
        }

        $model = SupplierFeedHelper::getEtaDate($model);

        $model = SupplierFeedHelper::getWeightOptions($model);

        list($model, $upc_different) = SupplierFeedHelper::getUPC($model);

        if ($upc_different) {
            list($upcModel, $is_upc_changed_created) = ProductUpcChangesModel::objects()->getOrNew(['productid' => $model->productid]);
            $upcModel->setAttributes(
                [
                    'productid' => $model->productid,
                    'original_upc' => $model->getOldAttribute('upc'),
                    'corrected_upc' => $model->upc
                ]
            );
            $upcModel->save();
        }

        if (!$is_created) {
            if ($model->isGroupChild()) {
                $model->product = $model->getOldAttribute('product');
            }

            if ($dont_update_fields) {
                foreach ($dont_update_fields as $fieldUnset) {
                    $trimDesc = trim($model->fulldescr);
                    if ($fieldUnset != 'fulldescr' || $fieldUnset == 'fulldescr' && !empty($trimDesc)) {
                        $model->setAttribute($fieldUnset, $model->getOldAttribute($fieldUnset));
                    }
                }
            }
        }

        if ($is_created) {

            if ($defaults) {
                $model->setAttributes(array_merge($data, $defaults));
            }

            (new ProductStorefrontModel([
                'productid' => $model->productid,
                'sfid' => $feed->storefront_id]))
                ->save();

            (new PricingModel([
                'productid' => $model->productid,
                'quantity' => 1,
                'price' => $model->distributor->calculatePrice($model)]))
                ->save();

            $clean_url = func_clean_url_autogenerate('P', $model->productid, array('product' => $model->product, 'productcode' => $model->productcode));
            func_clean_url_add($clean_url, 'P', $model->productid);
            func_build_quick_flags($model->productid);
            func_build_quick_prices($model->productid);

        }

        //Images section
        $aImages = $data['supplier_images'];
        $aAltImageNames = $data['alt_names'];
        if (!empty($aImages) && is_array($aImages)) {
            foreach ($aImages as $kImg => $IMAGE_URL) {
                $modelDImage = ImageHelper::uploadMainImage(
                    $IMAGE_URL,
                    empty($aAltImageNames[$kImg]) ? $model->product : $aAltImageNames[$kImg],
                    $feed->manufacturerid,
                    $model->productid);
                if ($modelDImage && $modelDImage->getIsNewRecord()) {
                    $modelDImage->id = $model->productid;
                    $modelDImage->orderby = ($kImg + 1) * 10;
                    $modelDImage->save();
                    if (class_exists('Imagick')) {
                        $imageParam = $modelDImage->getAttributes();
                        $imageParam['image_path'] = '../' . $imageParam['image_path'];
                        $image_info = func_set_correct_det_img($imageParam, true);
                    }
                }
            }
        }

        //Files section
        $aFiles = $data['product_files'];
        if (!empty($aFiles) && is_array($aFiles)) {
            $orderBy = 0;
            foreach ($aFiles as $aFile) {
                $fileModel = ProductHelper::uploadProductFile($aFile['name'], $aFile['link'], $model->productid);
                if ($fileModel && $fileModel->getIsNewRecord()) {
                    $fileModel->avail = 'Y';
                    $fileModel->date = time();
                    $fileModel->orderby = ++$orderBy * 10;
                    $fileModel->save();
                }
            }
        }

        //Related section
        $params = [];
        $aRelatedInternalId = $data['related_internal_id'];
        $aRelatedInternalSKU = $data['related_sku'];
        if (!empty($aRelatedInternalId)) {
            $params['supplier_internal_product_id__in'] = $aRelatedInternalId;
        }
        if (!empty($aRelatedInternalSKU)) {
            $params['productcode__in'] = $aRelatedInternalSKU;
        }
        if (!empty($params)) {
            if ($aRelatedProducts = ProductModel::objects()->filter(new QOr($params))->all()) {
                foreach ($aRelatedProducts as $relatedProductModel) {
                    ProductLinksModel::objects()->getOrCreate(['productid1' => $model->productid, 'productid2' => $relatedProductModel->productid]);
                    ProductLinksModel::objects()->getOrCreate(['productid1' => $relatedProductModel->productid, 'productid2' => $model->productid]);
                }
            }
        }

        //Brand section
        $brandName = $data['brand_name'];

        if (!empty($brandName)) {

            /** @var BrandModel $brandModel */
            list($brandModel, $brand_created) = BrandModel::objects()->getOrCreate(['brand' => $brandName]);

            if ($brand_created) {
                $brandModel->setAttributes(
                    [
                        'brand' => $brandName,
                        'orderby' => 10,
                        'prevent_search_indexing_of_all_brand_products' => $model->prevent_search_indexing_this_product_page == 'Y' ? 'Y' : 'N',
                        'prevent_search_indexing_brand_page' => $model->prevent_search_indexing_this_product_page == 'Y' ? 'Y' : 'N'
                    ]);

                $brandModel->save();

                (new BrandStorefrontModel([
                    'brandid' => $brandModel->brandid,
                    'sfid' => $feed->storefront_id,
                ]))
                    ->save();

                $clean_url = func_clean_url_autogenerate('M', $brandModel->brandid, array('brand' => $brandName));

                func_clean_url_add($clean_url, 'M', $brandModel->brandid);

            }
            if ($brandModel->parent_brand_id) {
                $brandModel = $brandModel->parent;
            }
            $model->brandid = $brandModel->brandid;
        }

        //Attributes section
        FilterProductModel::objects()->delete(['productid' => $model->productid, 'is_feed' => 1]);
        $aAttributes = $data['attributes'];
        if (!empty($aAttributes)) {
            foreach ($aAttributes as $f_name => $fv_name_arr) {
                if (!empty($fv_name_arr) && is_array($fv_name_arr)) {
                    list($filterModel) = FilterModel::objects()->getOrCreate(['f_name' => $f_name, 'storefrontid' => $feed->storefront_id]);
                    foreach ($fv_name_arr as $fv_name) {
                        $fv_name = trim($fv_name);
                        if (!empty($fv_name)) {
                            list($filterValueModel) = FilterValueModel::objects()->getOrCreate(['f_id' => $filterModel->f_id, 'fv_name' => $fv_name]);
                            FilterProductModel::objects()->getOrCreate(['fv_id' => $filterValueModel->fv_id, 'productid' => $model->productid, 'is_feed' => 1]);
                        }
                    }
                }
            }
        }

        $aSupplierCategory = $data['supplier_categories'];

        if (!empty($aSupplierCategory) && is_array($aSupplierCategory)) {

            $parent_id = $feed->base_category_id;

            if ($model->isCategorized()) {
                $parent_id = 0;
            }

            $lastCategory = null;

            $cats_arr = $aSupplierCategory;

            if (count($aSupplierCategory) == 1) {
                $cats_arr = explode("/", reset($aSupplierCategory));
            }

            if ($cats_arr) {

                foreach ($cats_arr as $v_cat) {

                    /** @var CategoryModel $modelCat */
                    list($modelCat, $is_cat_created) = CategoryModel::objects()->getOrCreate(
                        [
                            'parentid' => $parent_id,
                            'category' => $v_cat,
                            'storefrontid' => $feed->storefront_id
                        ]);

                    if ($is_cat_created) {
                        $modelCat->setAttributes([
                            'prevent_index_products' => $model->prevent_search_indexing_this_product_page == 'Y' ? 'Y' : 'N',
                            'prevent_index_category_page' => $model->prevent_search_indexing_this_product_page == 'Y' ? 'Y' : 'N',
                            'is_bold' => 'Y',
                            'order_by' => 10
                        ]);

                        $modelCat->categoryid_path = $modelCat->parent->categoryid_path . "/" . $modelCat->categoryid;

                        $modelCat->save();

                        $clean_url = func_clean_url_autogenerate('C', $modelCat->categoryid, array('category' => $modelCat->category));
                        func_clean_url_add($clean_url, 'C', $modelCat->categoryid);
                    }

                    $lastCategory = $modelCat;
                    $parent_id = $modelCat->categoryid;
                }

                if ($lastCategory) {
                    if (!$model->isCategorized() || ($model->isGroupRoot() && $is_created && $model->isCategorized())) {
                        $model->setMainCategory($lastCategory);
                    }
                }
            }
        } else {
            $model->setMainCategory(CategoryModel::objects()->get(['categoryid' => $feed->base_category_id]));
        }

        return $model;
    }

    public static function getFileFtp($file_name, $config)
    {
        $home_ftp = $config["Supplier_feeds"]["Feeds_storage_path"];
        $login = $config["Supplier_feeds"]["Feeds_storage_login"];
        $pass = $config["Supplier_feeds"]["Feeds_storage_password"];

        $ftp_connect = ftp_connect($home_ftp);

        if (!ftp_login($ftp_connect, $login, $pass)) {
            return false;
        }

        ftp_pasv($ftp_connect, true);

        $temp_file = tmpfile();
        ftp_fget($ftp_connect, $temp_file, $file_name, FTP_ASCII);
        $content = stream_get_contents($temp_file, -1, 0);
        fclose($temp_file);

        ftp_close($ftp_connect);

        return $content;
    }
}