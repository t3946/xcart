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
use Modules\User\Models\SessionDataModel;
use Modules\User\Models\SurfPathModel;

class ProductsToMoveHelper
{

    public static function isNeedToTransferProduct($productid)
    {
        /** @var ProductModel $product_model */
        /** @var UpdatedProductModel $queue_model */
        if ( (!$product_model = ProductModel::objects()->get(['productid' => $productid]) ) || self::isHaveAnyPaidOrder($productid) || self::isHaveVisits($productid) || self::isMoreThanOneSf($productid) || $product_model->amazon_fba_avail > 0){
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
        $flag = false;

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


        (new ProductsSfMovesModel(['batch_id' => $batch_id, 'productid' => $productid, 'resource_id' => $old_sfid, 'resource_type' => "SF"]))->save();


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
        foreach ($product_categories_models as $product_categories_model) {

            /** @var CategoryModel $category_model */
            if ($category_model = $product_categories_model->category)
            {
                if ( $ancestors_categories = $category_model->getObjects()->ancestors(true)->order(['lft'])->all())
                {
                    $count = count($ancestors_categories);
                    $parent_category = null;
                    $parent_id = null;
                    for($i = 0; $i < $count; $i++){
                        $category = null;

                        /** @var CategoryModel $model */
//                        $model = $ancestors_categories[$i];
//
//                        $clone = clone $model;
//                        $clone->categoryid = null;
//                        $clone->root = null;
//                        $clone->lft = null;
//                        $clone->rgt = null;
//                        $clone->level = null;
//                        $clone->parent_id = $parent_id;
////
//                        $clone->save();
//
//                        $parent_id = $clone->pk;


                        if ($i == 0){
                            $parent_category = CategoryModel::objects()
                                                            ->get(['category' => $ancestors_categories[0]->category, 'parentid' => 0, 'storefrontid' => $sfid])
                                ?: (new CategoryModel(['category' => $ancestors_categories[0]->category, 'storefrontid' => $sfid]));

                            $parent_category->save();
                            $parent_id = $parent_category->categoryid;
                        }
                        else {


                            $category = CategoryModel::objects()->getOrCreate([
                                'parentid' => $parent_id,
                                'category' => $ancestors_categories[ $i ]->category,
                                'storefrontid' => $sfid
                            ]);
                            $parent_id = $category->categoryid;
                        }
                    }


                    /** @var ProductsSfMovesModel $products_sf_moves_model */
                    $products_sf_moves_model = ProductsSfMovesModel::objects()->getOrNew(['batch_id' => $batch_id,
                                                                                          'productid' => $product_categories_model->productid,
                                                                                          'resource_id' => $product_categories_model->categoryid,
                                                                                          'resource_type' => 'CS',
                                                                                          'resource_extra_value' => $product_categories_model->main,]);
                    if ($products_sf_moves_model[1]) {
                        $products_sf_moves_model[0]->save();
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