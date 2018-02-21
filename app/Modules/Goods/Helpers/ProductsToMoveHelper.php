<?php


namespace Modules\Goods\Helpers;

use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\ProductCategoriesModel;
use Modules\Goods\Models\ProductsSfMovesModel;

class ProductsToMoveHelper
{

    public static function processingCategoriesInNewSf($category_models, $sfid, $batch_id)
    {
        /** @var ProductCategoriesModel $category_model */
        foreach ($category_models as $category_model) {

            $ancestors_categories = CategoryModel::objects($category_model)->ancestors(true)->all();
            $ancestors_categories = array_reverse($ancestors_categories);

            $count = count($ancestors_categories);
            $parent_category = null;
            $parent_id = null;
            for($i = 0; $i < $count; $i++){

                $category = null;

                if ($i == 0){
                    $parent_category = CategoryModel::objects()->get(['category' => $ancestors_categories[0]->category, 'storefrontid' => $sfid]) ?: (new CategoryModel(['category' => $ancestors_categories[0]->category, 'storefrontid' => $sfid]));
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
                                       'resource_extra_value' => $category_model->main]))->save();

            $category_model->update(['categoryid' => $parent_id]);

        }
    }

}