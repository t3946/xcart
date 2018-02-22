<?php


namespace Modules\Goods\Helpers;

use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\FilterModel;
use Modules\Goods\Models\FilterProductModel;
use Modules\Goods\Models\FilterValueModel;
use Modules\Goods\Models\ProductCategoriesModel;
use Modules\Goods\Models\ProductsSfMovesModel;

class ProductsToMoveHelper
{

    public static function isValidProduct($productid)
    {
        return false;
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