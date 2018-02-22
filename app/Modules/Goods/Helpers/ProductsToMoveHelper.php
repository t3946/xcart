<?php


namespace Modules\Goods\Helpers;

use Mindy\QueryBuilder\Expression;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\FilterModel;
use Modules\Goods\Models\FilterProductModel;
use Modules\Goods\Models\FilterValueModel;
use Modules\Goods\Models\ProductCategoriesModel;
use Modules\Goods\Models\ProductModel;
use Modules\Goods\Models\ProductsSfMovesModel;
use Modules\Goods\Models\ProductStorefrontModel;
use Modules\Goods\Models\UpdatedProductModel;

class ProductsToMoveHelper
{

    public static function isNeedToTransferProduct($productid)
    {
        /** @var ProductModel $product_model */
        $qs = ProductModel::objects()->getQuerySet();

        if ($pm = $qs->select([(new Expression("distinct({$qs->getTableAlias()}.productid)"))->toSQL()])->filter(
            [
                'order_details__order_groups__cb_status__in' => ['P'],
                'forsale' => 'Y',
                'surf_path__id__isnull' => false,
                'productid' => $productid
            ])->all() ) {
            return false;
        }
        else {
            return true;
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

    public static function isValidProduct($product_model)
    {
        return true;
    }

    public static function isValidGroupProduct($group_productid)
    {
        $flag = false;

        /** @var ProductModel $product_model */
        $product_model = ProductModel::objects()->get(['productid' => $group_productid]);

        $child_products = $product_model->childs;

        foreach ($child_products as $child_product){
            $flag = self::isValidProduct($child_product);
            if ($flag){
                return $flag;
            }
        }

        return $flag;
    }

    public static function processingMoveProductToNewSf($product_model, $queue_model)
    {
        /** @var UpdatedProductModel $queue_model */

        /** @var ProductsSfMovesModel [] $p_moves_models */

        if (self::isAlwaysProductMoved($queue_model->resourceid, $queue_model->extra_data_int)){
            return false;
        }

        if ($p_moves_models = ProductsSfMovesModel::objects()->filter(['productid' => $queue_model->resourceid, 'resource_type' => "SF"])->all()){
            $p_moves_model = array_pop($p_moves_models);
            $batch_id = $p_moves_model->batch_id + 1;
        }
        else {
            $batch_id = 0;
            $p_moves_model = new ProductsSfMovesModel(['batch_id' => $batch_id, 'productid' => $queue_model->resourceid]);
        }

        /** @var ProductModel $product_model */
        $product_model->source_sfid = $queue_model->extra_data_int;
        $product_model->save();


        /** @var ProductStorefrontModel [] $products_sf_models */
        $products_sf_models = ProductStorefrontModel::objects()->filter(['productid' => $queue_model->resourceid])->all();

        if (count($products_sf_models) > 1){
            return false;
        }
        /** @var ProductStorefrontModel $products_sf_models */
        $products_sf_model = $products_sf_models[0];
        $old_sfid = $products_sf_model->sfid;
        $products_sf_model->sfid = $queue_model->extra_data_int;
        $products_sf_model->save();

        $products_sf_model->sfid = $old_sfid;
        $products_sf_model->delete();


        (new ProductsSfMovesModel(['batch_id' => $batch_id, 'productid' => $queue_model->resourceid, 'resource_id' => $products_sf_models[0]->sfid, 'resource_type' => "SF"]))->save();


        /** @var ProductCategoriesModel $categories_models */
        $categories_models = $product_model->product_categories->all();

        ProductsToMoveHelper::processingCategoriesToNewSf($categories_models, $queue_model->extra_data_int, $batch_id);

        ProductsToMoveHelper::processingFilterAndValuesToNewSf($queue_model->resourceid, $queue_model->extra_data_int, $batch_id);

        return true;
    }

    public static function processingCategoriesToNewSf($category_models, $sfid, $batch_id)
    {
        /** @var ProductCategoriesModel $category_model */
        foreach ($category_models as $category_model) {

            $ancestors_categories = CategoryModel::objects($category_model->category)->ancestors(true)->all();
            $ancestors_categories = array_reverse($ancestors_categories);

            $count = count($ancestors_categories);
            $parent_category = null;
            $parent_id = null;
            for($i = 0; $i < $count; $i++){

                $category = null;

                if ($i == 0){
                    $parent_category = CategoryModel::objects()
                                                    ->get(['category' => $ancestors_categories[0]->category, 'parentid' => 0, 'storefrontid' => $sfid])
                                                    ?: (new CategoryModel(['category' => $ancestors_categories[0]->category, 'storefrontid' => $sfid]));

                    $parent_category->save();
                    $parent_id = $parent_category->categoryid;
                }
                else {
                    $category = CategoryModel::objects()
                                             ->get(['parentid' => $parent_id, 'category' => $ancestors_categories[ $i ]->category, 'storefrontid' => $sfid])
                                             ? : (new CategoryModel(['parentid' => $parent_id, 'category' => $ancestors_categories[ $i ]->category, 'storefrontid' => $sfid]));
                    $category->save();
                    $parent_id = $category->categoryid;
                }
            }

            (new ProductsSfMovesModel(['batch_id' => $batch_id,
                                       'productid' => $category_model->productid,
                                       'resource_id' => $category_model->categoryid,
                                       'resource_type' => 'CS',
                                       'resource_extra_value' => $category_model->main,
                                      ]))->save();
            $category_model->categoryid = $parent_id;
            $category_model->save();

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