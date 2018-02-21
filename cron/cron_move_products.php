<?php

define("CIDEV_CRON_START", "CRON");

require __DIR__ . DIRECTORY_SEPARATOR . "../www/top.inc.php";
require __DIR__ . DIRECTORY_SEPARATOR . "../www/init.php";

use Modules\Goods\Helpers\ProductsToMoveHelper;
use Modules\Goods\Models\ProductCategoriesModel;
use Modules\Goods\Models\ProductModel;
use Modules\Goods\Models\ProductsSfMovesModel;
use Modules\Goods\Models\ProductStorefrontModel;
use Modules\Goods\Models\UpdatedProductModel;

$models = UpdatedProductModel::objects()->filter(['type' => 2])->all();

foreach ($models as $model){
    $product_id = $model->resourceid;
    $storefront_id = $model->extra_data_int;

    /** @var ProductsSfMovesModel [] $p_moves_models */
    if ($p_moves_models = ProductsSfMovesModel::objects()->filter(['productid' => $model->resourceid, 'resource_type' => "SF"])->all()){
        $p_moves_model = array_pop($p_moves_models);
        $batch_id = $p_moves_model->batch_id + 1;
    }
    else {
        $batch_id = 0;
        $p_moves_model = new ProductsSfMovesModel(['batch_id' => $batch_id, 'productid' => $model->resourceid]);
    }

    /** @var ProductModel $product_model */
    $product_model = ProductModel::objects()->get(['productid' => $model->resourceid]);
    $product_model->update(['source_sfid' => $model->extra_data_int]);

    /** @var ProductStorefrontModel [] $products_sf_models */
    $products_sf_models = ProductStorefrontModel::objects()->filter(['productid' => $model->resourceid])->all();

    if (count($products_sf_models) > 1){
        continue;
    }

    (new ProductsSfMovesModel(['batch_id' => $batch_id, 'productid' => $model->resourceid, 'resource_id' => $products_sf_models[0]->source_sfid, 'resource_type' => "SF"]))->save();

    $products_sf_models[0]->update(['sfid' => $model->extra_data_int]);


    /** @var ProductCategoriesModel $categories_models */
    $categories_models = $product_model->product_categories->all();

    ProductsToMoveHelper::processingCategoriesInNewSf($categories_models, $model->extra_data_int, $batch_id);

}

$breakpoint = 1;