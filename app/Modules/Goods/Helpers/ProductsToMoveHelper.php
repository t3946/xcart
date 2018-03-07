<?php


namespace Modules\Goods\Helpers;

use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QAnd;
use Mindy\QueryBuilder\Q\QOr;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\FilterModel;
use Modules\Goods\Models\FilterProductModel;
use Modules\Goods\Models\FilterValueModel;
use Modules\Goods\Models\ProductCategoriesModel;
use Modules\Goods\Models\ProductModel;
use Modules\Goods\Models\ProductsSfMovesModel;
use Modules\Goods\Models\ProductStorefrontModel;
use Modules\Goods\Models\UpdatedProductModel;
use Modules\Sites\Models\SiteModel;
use Modules\User\Models\SessionDataModel;
use Modules\User\Models\SurfPathModel;

class ProductsToMoveHelper
{

    public static function isNeedToTransferProduct($productid)
    {
        /** @var ProductModel $product_model */
        /** @var UpdatedProductModel $queue_model */
        if ( (!$product_model = ProductModel::objects()->get(['productid' => $productid]) ) || self::isHaveAnyPaidOrder($productid) || self::isHaveVisits($productid) || self::isMoreThanOneSf($productid) || $product_model->amazon_fba_avail > 0 || self::isInThisBrandsAndCategories($productid) || $product_model->forsale == 'N'){
            return false;
        }
        else {
            return true;
        }

    }

    public static function isHaveAnyPaidOrder($productid)
    {
        /** @var ProductModel $product_model */
        $qs = ProductModel::objects()->getQuerySet();

        if ($pm = $qs->select([(new Expression("distinct({$qs->getTableAlias()}.productid)"))->toSQL()])->filter(
            [
                'order_details__order_groups__cb_status__in' => ['P', 'AP', 'R', 'H', 'O'],
                'forsale' => 'Y',
                'productid' => $productid
            ])->all() ) {

            return true;
        }
        else {
            return false;
        }
    }

    public static function isHaveVisits($productid)
    {
        /** @var ProductModel $product_model */
        $qs = SurfPathModel::objects()->getQuerySet();


        if ($s_model = SessionDataModel::objects()->filter([
                                                           'surf_meta__surf_path__product__productid' => $productid,
                                                           "{$qs->getTableAlias()}.resource_type" => 'P',
                                                       ])->exclude([
                                                                       new QOr([
                                                                                   new QOr(['data__contains' => '"login_type";s:1:"A";',]),
                                                                                   new QOr(['data__contains' => '"login_type";s:1:"P";',]),
                                                                                   new QOr(['data__contains' => '"username";s:0:"";']),
                                                                                   new QOr(['data' => ""])
                                                                               ]),

                                                                   ])

                                   ->all()){

            return true;
        }
        else {
            return false;
        }
    }

    public static function isInThisBrandsAndCategories($productid)
    {
        $exclude_brands_ar = [230,7364,280,3235,285,282,7355,198,61,5346,4372,5325,69,3635,276,225,10,4302,226,4303,366,3799,3,1642,8,5336,7295];

        $exclude_brands_ts = [286,7950,3101,356,3496,3559,7086,340,309,3467,3461,3527,466,3503,308,307,477,583,353,329];

        $exclude_categories = [50859];

        /** @var ProductModel $product_model */
        $product_model = ProductModel::objects()->get(['productid' => $productid]);

        $sf_models = $product_model->sites->all();

        if (count($sf_models) > 1 || count($sf_models) === 0){
            return true;
        }

        /** @var SiteModel $sf_model */
        $sf_model = $sf_models[0];

        if ( ($sf_model->code == 'AR' && in_array($product_model->brandid, $exclude_brands_ar))
             || ($sf_model->code == 'TS' && in_array($product_model->brandid, $exclude_brands_ts))
        ){
            return true;
        }

        if ($category_models = $product_model->categories->all()){
            foreach ($category_models as $category_model){
                /** @var CategoryModel $category_model */
                $rootModel = $category_model->isRoot() ? $category_model : CategoryModel::objects()->get([ 'root' => $category_model->root, 'lft' => 1 ]);

                if (in_array($rootModel->categoryid, $exclude_categories)){
                    return true;
                }
            }
        }

        return false;

    }

    public static function isMoreThanOneSf($productid)
    {
        $products_sf_models = ProductStorefrontModel::objects()->filter(['productid' => $productid])->all();

        if (count($products_sf_models) > 1){
            return true;
        }
        else {
            return false;
        }

    }

    public static function isAlwaysProductMoved($productid, $sfid)
    {
        if ($p_model = ProductStorefrontModel::objects()->get(['productid' => $productid, 'sfid' => $sfid])){
            return true;
        }
        else {
            return false;
        }
    }

    public static function isValidProduct($product_model, $queue_model)
    {
        return self::isNeedToTransferProduct($product_model->productid);
    }

    public static function isValidGroupProduct($group_productid, $queue_model)
    {
        $flag = true;

        /** @var ProductModel $product_model */
        $product_model = ProductModel::objects()->get(['productid' => $group_productid]);

        $child_products = $product_model->childs->all();

        foreach ($child_products as $child_product){

            $flag = self::isValidProduct($child_product, $queue_model);
            if (!$flag){
                return $flag;
            }
        }

        return $flag;
    }

    public static function processingMoveProductToNewSf($product_model, $productid, $sfid)
    {
        /** @var UpdatedProductModel $queue_model */



        if (self::isAlwaysProductMoved($productid, $sfid)){
            return false;
        }

        /** @var ProductsSfMovesModel $move_model */
        if ($move_model = ProductsSfMovesModel::objects()->filter(['productid' => $productid])->order(['-batch_id'])->limit(1)->get() ) {
            $batch_id = $move_model->batch_id + 1;
        }
        else {
            $batch_id = 0;
        }

        /** @var ProductModel $product_model */
        /** @var ProductStorefrontModel [] $products_sf_models */
        $products_sf_models = ProductStorefrontModel::objects()->filter(['productid' => $productid])->all();

        $product_model->source_sfid = $sfid;
        $product_model->save();

        /** @var ProductStorefrontModel $products_sf_models */
        $products_sf_model = $products_sf_models[0];
        $old_sfid = $products_sf_model->sfid;
        $products_sf_model->sfid = $sfid;
        $products_sf_model->save();

        $products_sf_model->sfid = $old_sfid;
        $products_sf_model->delete();


        (new ProductsSfMovesModel(['batch_id' => $batch_id, 'productid' => $productid, 'resource_id' => $old_sfid, 'resource_type' => ProductsSfMovesModel::RESOURCE_TYPE_SITE]))->save();


        /** @var ProductCategoriesModel $categories_models */
        $product_categories_models = $product_model->product_categories->all();

        ProductsToMoveHelper::processingCategoriesToNewSf($product_categories_models, $sfid, $batch_id);

        ProductsToMoveHelper::processingFilterAndValuesToNewSf($productid, $sfid, $batch_id);

        return true;
    }

    public static function processingCategoriesToNewSf($product_categories_models, $sfid, $batch_id)
    {
        $count = count($product_categories_models);

        if ($count > 1 ){
            $number_of_last_category = $count - 1;
        }
        else {
            $number_of_last_category = 0;
        }


        /** @var ProductCategoriesModel $product_categories_model */
        foreach ($product_categories_models as $product_categories_model)
        {
            /** @var CategoryModel $category_model */
            if ($category_model = $product_categories_model->category)
            {
                if ( $ancestors_categories = $category_model->getObjects()->ancestors(true)->order(['lft'])->all())
                {
                    $parent_category = null;
                    $parent_id = null;

                    /** @var CategoryModel $model */
                    foreach ($ancestors_categories as $model)
                    {
                        if ($model->isRoot()) {
                            [$parent_category, $isNew] = CategoryModel::objects()->getOrCreate([
                               'category' => $model->category,
                               'parentid' => 0,
                               'storefrontid' => $sfid
                           ]);

                        }
                        else {
                            [$parent_category, $isNew] = CategoryModel::objects()->getOrCreate([
                               'category' => $model->category,
                               'parentid' => $parent_id,
                               'storefrontid' => $sfid
                           ]);

                        }

                        if ($isNew){
                            $clean_url = func_clean_url_autogenerate('C', $parent_category->categoryid, array('category' => $parent_category->category));
                            func_clean_url_add($clean_url, 'C', $parent_category->categoryid);
                        }

                        $parent_id = $parent_category->pk;
                    }


                    /** @var ProductsSfMovesModel $products_sf_moves_model */
                    [$products_sf_moves_model, $isNew] = ProductsSfMovesModel::objects()->getOrNew(['batch_id' => $batch_id,
                                                                                          'productid' => $product_categories_model->productid,
                                                                                          'resource_id' => $product_categories_model->categoryid,
                                                                                          'resource_type' => 'CS',
                                                                                          'resource_extra_value' => $product_categories_model->main,
                                                                                               ]);
                    if ($isNew) {
                        $products_sf_moves_model->save();

                    }

                    $product_categories_model->delete();

                    $product_categories_model->categoryid = $parent_id;
                    $product_categories_model->insert();

                }
            }
        }
    }

    public static function processingFilterAndValuesToNewSf($productid, $sfid, $batch_id)
    {
        $filter_product_models = FilterProductModel::objects()->filter(['productid' => $productid])->all();

        /** @var FilterProductModel $filter_product_model */
        foreach ($filter_product_models as $filter_product_model){

            (new ProductsSfMovesModel(['batch_id' => $batch_id,
                                       'productid' => $productid,
                                       'resource_id' => $filter_product_model->fv_id,
                                       'resource_type' => 'FL']))->save();

            $filter_value_model = $filter_product_model->filter_val;

            $filter_model = $filter_value_model->filter;

            $new_filter_model = FilterModel::objects()->get([
                                                             'f_name' => $filter_model->f_name,
                                                             'storefrontid' => $sfid,
                                                            ]) ?:
                                           (new FilterModel([
                                                             'f_name' => $filter_model->f_name,
                                                             'f_order_by' => $filter_model->f_order_by,
                                                             'f_active' => $filter_model->f_active,
                                                             'storefrontid' => $sfid,
                                                            ]) );

            $new_filter_model->save();


            $new_filter_value_model = FilterValueModel::objects()->get([
                                                                        'f_id' => $new_filter_model->f_id,
                                                                        'fv_name' => $filter_value_model->fv_name,
                                                                       ]) ?:
                                                 (new FilterValueModel([
                                                                        'f_id' => $new_filter_model->f_id,
                                                                        'fv_name' => $filter_value_model->fv_name,
                                                                        'fv_order_by' => $filter_value_model->fv_order_by,
                                                                        'fv_active' => $filter_value_model->fv_active,
                                                                       ]));
            $new_filter_value_model->save();
            $old_fv_id = $filter_product_model->fv_id;
            $filter_product_model->fv_id = $new_filter_value_model->fv_id;
            $filter_product_model->save();
            $filter_product_model->fv_id = $old_fv_id;
            $filter_product_model->delete();
        }
    }

}