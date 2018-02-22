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

$models = UpdatedProductModel::objects()->filter(['type' => 9])->all();

$start_time = new DateTime('now');
$count_all = count($models);
$count_processed = 0;

foreach ($models as $model){

    /** @var UpdatedProductModel $model */
    $product_id = $model->resourceid;
    $storefront_id = $model->extra_data_int;


    /** @var ProductModel $product_model */
    $product_model = ProductModel::objects()->get(['productid' => $model->resourceid]);

    if ($product_model->isGroupRoot() || $product_model->isGroupChild()){
        echo "It's group product";
        exit;
    }

/*    $model = ProductsSfMovesModel::objects()->filter(['productid' => $product_id])->order('batch_id')->desc->limit(1);*/

    /** @var ProductsSfMovesModel [] $p_moves_models */
    if ($p_moves_models = ProductsSfMovesModel::objects()->filter(['productid' => $model->resourceid, 'resource_type' => "SF"])->all()){
        $p_moves_model = array_pop($p_moves_models);
        $batch_id = $p_moves_model->batch_id + 1;
    }
    else {
        $batch_id = 0;
        $p_moves_model = new ProductsSfMovesModel(['batch_id' => $batch_id, 'productid' => $model->resourceid]);
    }


    $product_model->source_sfid = $model->extra_data_int;
    $product_model->save();


    /** @var ProductStorefrontModel [] $products_sf_models */
    $products_sf_models = ProductStorefrontModel::objects()->filter(['productid' => $model->resourceid])->all();

    if (count($products_sf_models) > 1){
        continue;
    }
    /** @var ProductStorefrontModel $products_sf_models */
    $products_sf_model = $products_sf_models[0];
    $old_sfid = $products_sf_model->sfid;
    $products_sf_model->sfid = $model->extra_data_int;
    $products_sf_model->save();

    $products_sf_model->sfid = $old_sfid;
    $products_sf_model->delete();


    (new ProductsSfMovesModel(['batch_id' => $batch_id, 'productid' => $model->resourceid, 'resource_id' => $products_sf_models[0]->sfid, 'resource_type' => "SF"]))->save();


    /** @var ProductCategoriesModel $categories_models */
    $categories_models = $product_model->product_categories->all();

    ProductsToMoveHelper::processingCategoriesToNewSf($categories_models, $model->extra_data_int, $batch_id);

    ProductsToMoveHelper::processingFilterAndValuesToNewSf($model->resourceid, $model->extra_data_int, $batch_id);

    $model->delete();

    $count_processed++;

}

$time = (new DateTime('now'))->diff($start_time)->format('%H:%I:%S');

$log_category = "products_sf_moves";
$log_text = "Время обработки = {$time}\r\n Всего продуктов обработано = {$count_all}\r\n Продуктов перенесено = {$count_processed}";
func_backprocess_log($log_category, $log_text);
$breakpoint = 1;

/*
 * статистика сессии крона - записывается в backprocess logs для process_id = products_sf_moves
итоги:
время обработки
всего продуктов обработано
продуктов перенесено (включая групповые)
 */