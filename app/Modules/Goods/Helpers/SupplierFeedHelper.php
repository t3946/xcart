<?php

namespace Modules\Goods\Helpers;


use DateTime;
use Exception;
use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QOr;
use Modules\Brand\Models\BrandModel;
use Modules\Brand\Models\BrandStorefrontModel;
use Modules\Distributor\Models\SupplierFeedModel;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\FilterModel;
use Modules\Goods\Models\FilterProductModel;
use Modules\Goods\Models\FilterValueModel;
use Modules\Goods\Models\ImageDModel;
use Modules\Goods\Models\ProductLinksModel;
use Modules\Goods\Models\ProductModel;
use Modules\Goods\Models\ProductStorefrontModel;
use Modules\Goods\Models\ProductUpcChangesModel;
use Modules\Goods\Models\ProductVideosModel;
use Modules\Goods\Stores\SupplierFeedStore;
use Modules\Menu\Models\CleanUrlModel;
use Xcart\App\Helpers\Paths;

class SupplierFeedHelper
{
    /**
     * @param ProductModel $model
     * @return ProductModel
     */
    public static function getEtaDate(ProductModel $model): ProductModel
    {
        $todayDate = strtotime(date("Y-m-d"));

        if (($model->eta_date_lock === "Y")
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
    public static function getWeightOptions(ProductModel $model): ProductModel
    {
        if ($model->weight_lock === 'Y' || (!$model->weight && $model->getOldAttribute('weight'))) {
            $model->weight = $model->getOldAttribute('weight');
        }
        if ($model->shipping_weight_lock === 'Y' || (!$model->shipping_weight && $model->getOldAttribute('shipping_weight'))) {
            $model->shipping_weight = $model->getOldAttribute('shipping_weight');
        }
        if ($model->dim_lock === 'Y') {
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
        if ($model->shipping_dim_lock === 'Y') {
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
    public static function getUPC(ProductModel $model): array
    {
        $newUPC = ProductHelper::calculateUPC($model->upc);
        $oldUPC = $model->getOldAttribute('upc');
        if ($newUPC && $oldUPC !== $newUPC) {
            $model->upc = $newUPC;
        } else {
            $model->upc = $oldUPC;
        }

        return [$model, $oldUPC !== $model->upc];
    }

    /**
     * @param ProductModel $model
     */
    public static function getVideos(ProductModel $model): void
    {
        /** @var ProductVideosModel $video_model */
        if ($model->videos) {
            foreach ($model->videos as $video) {
                $filter = [];
                foreach ($video as $key => $value) {
                    $filter[$key] = $value;
                }
                $filter['product_id'] = $model->productid;
                [$video_model, $is_created] = ProductVideosModel::objects()->getOrNew($filter);

                if ($is_created) {
                    $video_model->save();
                }
            }
        }
    }

    /**
     * @param ProductModel $model
     * @param bool $is_created
     * @param SupplierFeedModel $feed
     * @param array $data
     * @param array $dont_update_fields
     * @param array $defaults
     * @return ProductModel
     * @throws Exception
     */
    public static function feedProduct($model, $is_created, $feed, $data, $dont_update_fields, $defaults): ProductModel
    {

        $model->manufacturerid = $feed->manufacturerid;

        $discontinuedDate = $data['discontinued_date'];
        if (!empty($discontinuedDate)) {
            $discontinuedDateTimeDiff = strtotime($discontinuedDate) - time();
            if ($discontinuedDateTimeDiff < (60 * 60 * 24 * 20)) {
                if ($model->forsale !== "N") {
                    $model->forsale = "N";
                    $model->update_search_index = "Y";
                    return $model;
                }
            }
        }

        if ($is_created && !empty($model->fulldescr) && $feed->native_full_description !== "Y") {
            $model->fulldescr = ProductHelper::cleanProductFullDescription($model->fulldescr);
        }

        $model = self::getEtaDate($model);

        $model = self::getWeightOptions($model);

        self::getVideos($model);

        [$model, $upc_different] = self::getUPC($model);
        if ($upc_different) {
            [$upcModel] = ProductUpcChangesModel::objects()->getOrNew(['productid' => $model->productid]);
            /** @var ProductUpcChangesModel $upcModel */
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
            if (empty($data['feed_child']) && $model->isGroupChild()) {
                $model->product = $model->getOldAttribute('product');
            }

            if ($dont_update_fields) {
                foreach ($dont_update_fields as $fieldUnset) {
                    $trimDesc = trim($model->fulldescr);
                    if ($fieldUnset !== 'fulldescr' || ($fieldUnset === 'fulldescr' && !empty($trimDesc))) {
                        $model->setAttribute($fieldUnset, $model->getOldAttribute($fieldUnset));
                    }
                }
            }
        }

        if ($is_created) {
            $model->source_sfid = $feed->storefront_id;
            if ($defaults) {
                $model->setAttributes(array_merge($data, $defaults));
            }
            (new ProductStorefrontModel([
                'productid' => $model->productid,
                'sfid' => $feed->storefront_id]))
                ->save();
            [$url] = CleanUrlModel::objects()->getOrNew(['resource_type' => 'P', 'resource_id' => $model->productid]);
            $url->clean_url = func_clean_url_autogenerate('P', $model->productid, ['product' => $model->getFrontendName(), 'productcode' => $model->productcode]);
            $url->save();
        }

        self::feedImages($model, $feed, $data);

        self::feedFiles($model, $data);

        self::feedRelated($model, $data);

        $model = self::feedBrand($model, $feed, $data['brand_name']);

        $model = self::feedAttributes($model, $feed, $data['attributes']);

        $model = self::feedCategories($model, $is_created, $feed, $data['supplier_categories']);

        return $model;
    }

    /**
     * @param ProductModel $model
     * @param SupplierFeedModel $feed
     * @param array $data
     * @throws \Exception
     */
    public static function feedImages($model, $feed, $data): void
    {
        $aImages = $data['supplier_images'];
        $aAltImageNames = $data['alt_names'];

        if ($aImages && is_array($aImages)) {

            foreach ($aImages as $k => $v) {
                if (empty($v)) {
                    unset($aImages[$k], $aAltImageNames[$k]);
                }
            }

            $uploads = array_filter(
                array_map(static fn($v) => !empty($v) ? '.' . ImageHelper::getImageFileName($v, $feed->manufacturerid) : null, $aImages),
            );

            if ($uploads) {

                $p_images = ImageDModel::objects()->filter(['image_path__in' => $uploads, 'id' => $model->productid])->valuesList('image_path', true);

                foreach ($uploads as $key => $url) {

                    if (!$url) {
                        continue;
                    }

                    $url_q = preg_quote($url, '/');

                    if (!preg_grep("/{$url_q}/i", $p_images)) {

                        $name = $aAltImageNames[$key] ?? $model->getFrontendName();

                        /** @var ImageDModel $image */
                        $image = ImageHelper::uploadMainImage($aImages[$key], ltrim($url, '.'), $name);

                        print "Upload image --> {$aImages[$key]}" . PHP_EOL;

                        if ($image && $image->getIsNewRecord()) {

                            $image->id = $model->productid;
                            $image->orderby = ($key + 1) * 10;
                            $image->save();

                            if (class_exists('Imagick')) {
                                $imageParam = $image->getAttributes();
                                $imageParam['image_path'] = Paths::get('www') . DIRECTORY_SEPARATOR . $imageParam['image_path'];
                                func_set_correct_det_img($imageParam, true);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @param ProductModel $model
     * @param array $data
     * @throws Exception
     */
    public static function feedFiles($model, $data): void
    {
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
    }

    /**
     * @param ProductModel $model
     * @param array $data
     */
    public static function feedRelated($model, $data): void
    {
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
            /** @var ProductModel[] $aRelatedProducts */
            if ($aRelatedProducts = ProductModel::objects()->filter([new QOr($params)])->all()) {
                foreach ($aRelatedProducts as $relatedProductModel) {
                    ProductLinksModel::objects()->getOrCreate(['productid1' => $model->productid, 'productid2' => $relatedProductModel->productid]);
                    ProductLinksModel::objects()->getOrCreate(['productid1' => $relatedProductModel->productid, 'productid2' => $model->productid]);
                }
            }
        }
    }

    /**
     * @param ProductModel $model
     * @param SupplierFeedModel $feed
     * @param string $data
     * @return ProductModel
     * @throws Exception
     */
    public static function feedBrand($model, $feed, $data): ProductModel
    {
        if (!empty($data)) {

            if (!$brand = BrandModel::objects()->filter([
                'brand' => $data
            ])->limit(1)->order(['brandid'])->get()) {

                $brand = new BrandModel([
                    'brand' => $data,
                    'orderby' => 10,
                    'prevent_search_indexing_of_all_brand_products' => $model->prevent_search_indexing_this_product_page === 'Y' ? 'Y' : 'N',
                    'prevent_search_indexing_brand_page' => $model->prevent_search_indexing_this_product_page === 'Y' ? 'Y' : 'N',
                    'avail' => true
                ]);

                $brand->save();


                [$url] = CleanUrlModel::objects()->getOrNew(['resource_type' => 'M', 'resource_id' => $brand->brandid]);
                $url->clean_url = func_clean_url_autogenerate('M', $brand->brandid, array('brand' => $data));
                $url->save();
            }

            BrandStorefrontModel::objects()->getOrCreate([
                'brandid' => $brand->brandid,
                'sfid' => $feed->storefront_id,
            ]);

            if ($brand->parent_brand_id) {
                $brand = $brand->parent;
            }

            $model->brandid = $brand->brandid;

        }

        if ($model && $model->forsale === 'Y' && $brand_model = $model->brand) {
            $model->forsale = $brand_model->avail ? 'Y' : 'N';
        }

        return $model;
    }

    /**
     * Attributes section
     * @param ProductModel $model
     * @param SupplierFeedModel $feed
     * @param array $data
     * @return ProductModel
     */
    public static function feedAttributes($model, $feed, $data): ProductModel
    {
        $fv_ids = [];

        if (!empty($data)) {
            foreach ($data as $f_name => $fv_name_arr) {
                if (!empty($fv_name_arr)) {
                    [$filterModel] = FilterModel::objects()->getOrCreate(['f_name' => $f_name, 'storefrontid' => $feed->storefront_id]);
                    if (!is_array($fv_name_arr)) {
                        $fv_name_arr = [$fv_name_arr];
                    }
                    foreach ($fv_name_arr as $fv_name) {
                        $fv_name = trim($fv_name);
                        if (!empty($fv_name)) {
                            [$filterValueModel] = FilterValueModel::objects()->getOrCreate(['f_id' => $filterModel->f_id, 'fv_name' => $fv_name]);
                            FilterProductModel::objects()->getOrCreate(['fv_id' => $filterValueModel->fv_id, 'productid' => $model->productid, 'is_feed' => 1]);
                            $fv_ids[] = $filterValueModel->fv_id;
                        }
                    }
                }
            }
        }
        $qs = FilterProductModel::objects();
        if ($fv_ids) {
            $qs->exclude(['fv_id__in' => $fv_ids]);
        }
        $qs->delete(['productid' => $model->productid, 'is_feed' => 1]);

        return $model;
    }

    /**
     * @param ProductModel $model
     * @param boolean $is_created
     * @param SupplierFeedModel $feed
     * @param array $categories
     * @return ProductModel
     * @throws Exception
     */
    public static function feedCategories($model, $is_created, $feed, $categories): ProductModel
    {
        $product_sfid = null;

        if (!$is_created && !$model->isGroupRoot()) {
            return $model;
        }

        if (!empty($categories) && is_array($categories)) {

            $parent_id = $feed->base_category_id;

            if ($model->isGroupRoot()) {
                $parent_id = null;
                /** @var ProductModel $child_model */
                if ($is_created) {
                    if ($child_model = $model->childs->limit(1)->get()) {
                        $product_sfid = $child_model->getMainCategory()->storefrontid;
                    }
                } else {
                    $product_sfid = $model->sites->limit(1)->get()->storefrontid;
                }
            }
            if ($model->isGroupChild() && $parent = $model->parent) {
                $product_sfid = $parent->sites->limit(1)->get()->storefrontid;
            }

            $lastCategory = null;

            $cats_arr = $categories;

            if (count($categories) === 1) {
                $cats_arr = explode("/", reset($categories));
            }

            if ($cats_arr) {

                foreach ($cats_arr as $v_cat) {

                    /** @var CategoryModel $modelCat */
                    [$modelCat, $is_cat_created] = CategoryModel::objects()->limit(1)->getOrCreate(
                        [
                            'parentid' => $parent_id ?: 0,
                            'category' => $v_cat,
                            'storefrontid' => $product_sfid ?? $feed->storefront_id
                        ]);

                    if ($is_cat_created) {
                        $modelCat->setAttributes([
                            'prevent_index_products' => $model->prevent_search_indexing_this_product_page === 'Y' ? 'Y' : 'N',
                            'prevent_index_category_page' => $model->prevent_search_indexing_this_product_page === 'Y' ? 'Y' : 'N',
                            'is_bold' => 'Y',
                            'order_by' => 10
                        ]);

                        $modelCat->categoryid_path = $modelCat->parent->categoryid_path . "/" . $modelCat->categoryid;

                        $modelCat->save();

                        [$url] = CleanUrlModel::objects()->getOrNew(['resource_type' => 'C', 'resource_id' => $modelCat->categoryid]);
                        $url->clean_url = func_clean_url_autogenerate('C', $modelCat->categoryid, array('category' => $modelCat->category));
                        $url->save();
                    }

                    $lastCategory = $modelCat;
                    $parent_id = $modelCat->categoryid;
                }

                if ($lastCategory) {
                    if ($is_created || $model->isGroupRoot()) {
                        $model->setMainCategory($lastCategory);
                    }
                }
            }
        } else {
            /** @var CategoryModel $cat */
            if ($feed->base_category_id && $cat = CategoryModel::objects()->get(['categoryid' => $feed->base_category_id])) {
                $model->setMainCategory($cat);
            }
        }

        return $model;
    }

    /**
     * @param array $data
     * @param SupplierFeedStore $feed
     * @return array
     * @return ProductModel
     * @throws Exception
     * @throws \Doctrine\DBAL\DBALException
     */
    public static function feedChilds($data, $feed): array
    {
        /** @var ProductModel $child */

        foreach ($data['child_products'] as $child_data) {
            if ($child = ProductModel::objects()->get(['productcode' => $child_data['productcode']])) {
                if (empty($data['productcode']) && ($parent = $child->parent)) {
                    $data['productcode'] = $parent->productcode;
                }
                if (!isset($data['pc_classify_status']) && $child->isCategorized()) {
                    $data['supplier_categories'] = array_reverse(CategoryModel::objects($child->getMainCategory())->parents(true)->valuesList('category', true));
                    $data['pc_classify_status'] = 'ACC';
                }
            }
        }
        if (empty($data['productcode'])) {
            $data['productcode'] = ProductHelper::getNewGroupSKU($feed->feed_model->manufacturerid);
        }

        $data['manufacturerid'] = $feed->feed_model->manufacturerid;
        /** @var ProductModel $group */
        [$group, $is_created] = ProductModel::objects()->getOrNew(['productcode' => $data['productcode']]);

        $group->setAttributes(array_merge($data, ['parent' => $group]));
        if ($is_created) {
            $group->save();
            $group = self::feedProduct($group, $is_created, $feed->feed_model, $data, $feed->dont_update_fields, $feed->defaults);
            $group->group_root = $group->productid;
            $group->save();
        }

        $childs = [];
        foreach ($data['child_products'] as $key => $child_data) {

            $data['child_products'][$key]['feed_child'] = true;
            $data['child_products'][$key]['group_root'] = $group->productid;
            $data['child_products'][$key]['brand_name'] = $data['brand_name'];

            $data['child_products'][$key]['supplier_categories'] = $data['supplier_categories'];

            if (isset($data['pc_classify_status'])) {
                $data['child_products'][$key]['pc_classify_status'] = $data['pc_classify_status'];
            }

            $childs[] = $child_data['productcode'];
        }

        if ($childs) {
            $params = [
                'group_root' => null,
                'product' => new Expression('TRIM(CONCAT(COALESCE(group_mask, ""), " ", product))'),
                'group_mask' => null
            ];

            $group->childs->exclude(['productcode__in' => $childs])->update($params);
        }

        return $data['child_products'];
    }


    public static function getChanged(ProductModel $model): array
    {
        if ($data = $model->getChangedAttributes()) {
            foreach ($data as $k => $v) {
                if ($model->getOldAttribute($k) == $v) {
                    unset($data[$k]);
                }
            }
        }
        return $data;
    }

    public static function discontinueProducts($all_feed_productcodes, SupplierFeedModel $feed): int
    {
        $discontinued_products_count = 0;

        if (!empty($all_feed_productcodes) && is_array($all_feed_productcodes) && $feed->disable_search_of_discontinued_items !== 'Y') {
            print("Search of discontinued section\n");

            $i = 0;
            $d_products = [];

            $filter = [
                'manufacturerid' => $feed->manufacturerid,
                'forsale' => 'Y'
            ];

            if ($feed->isMultiStore()) {
                $filter['sites__storefrontid'] = $feed->storefront_id;
            }

            while ($discountinued_products = ProductModel::without_group()
                ->filter($filter)
                ->paginate(++$i, 10000)
                ->valuesList('productcode', true)) {
                foreach ($discountinued_products as $productcode) {
                    if (!in_array($productcode, $all_feed_productcodes, true)) {
                        $discontinued_products_count++;
                        $d_products[] = $productcode;
                    }
                }
            }

            if ($d_products) {
                ProductModel::objects()->filter(['productcode__in' => $d_products])->update(['r_avail' => 0, 'forsale' => 'N', 'update_search_index' => 'D']);
            }
        }
        return $discontinued_products_count;
    }

    public static function feedStatistic(SupplierFeedModel $feed, $params, $feedProductCount = 0): string
    {
        $new_products_count = $params['new_products_count'];
        $updated_products_count = $params['updated_products_count'];
        $inserted_products_count = $params['inserted_products_count'];
        $discontinued_products_count = $params['discontinued_products_count'];
        $skippedProductsCount = $params['skippedProductsCount'];
        $duplicate_sku = $params['duplicate_sku'];
        $start_supplier_time = $params['start_supplier_time'];

        $last_update_period = time() - $feed->last_update_time;
        $average_update_period = round(($feed->average_update_period + $last_update_period) / 2, 0);

        $feed->setAttributes([
            "last_md5" => $params['md5'],
            "last_update_time" => time(),
            "average_update_period" => $average_update_period,
            "last_update_period" => $last_update_period,
            "last_feed_fields" => $params['last_feed_fields_arr_vals'],
            "last_update_items_count" => $feedProductCount,
            'feed_source' => $params['feed_source'],
            'feed_source_date' => $params['feed_source_date'],
            'process_time' => $params['process_time'],
        ]);
        $feed->save();

        $distributorModel = $feed->distributor;

        $log = "manufacturerid: {$distributorModel->manufacturerid}:{$distributorModel->manufacturer} - completed. \n";
        $log .= "processed {$feedProductCount} items.\n";
        $log .= "found new {$new_products_count} items.\n";
        $log .= "updated {$updated_products_count} items.\n";
        if ($inserted_products_count) {
            $log .= "inserted {$inserted_products_count} items.\n";
        }
        $log .= "discontinued: {$discontinued_products_count}\n";
        $log .= "skipped: {$skippedProductsCount}\n";

        if ($duplicate_sku) {
            $sku_d = implode(', ', $duplicate_sku);
            $log .= "Duplicated SKU's:{$sku_d}\n";
        }

        $log .= "Duration: " . (new DateTime('now'))->diff($start_supplier_time)->format('%H:%I:%S') . "\n";
        return $log;
    }
}