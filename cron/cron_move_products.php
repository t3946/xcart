<?php

define("CIDEV_CRON_START", "CRON");

require __DIR__ . DIRECTORY_SEPARATOR . "../www/top.inc.php";
require __DIR__ . DIRECTORY_SEPARATOR . "../www/init.php";

use Mindy\QueryBuilder\Expression;
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
//
//    if ($move_model = ProductsSfMovesModel::objects()->filter(['productid' => $model->resourceid])->order(['-batch_id'])->all() ) {
//        $batch_id = $move_model[0]->batch_id + 1;
//    }
//    else {
//        $batch_id = 0;
//    }

    /** @var ProductModel $product_model */
    $product_model = ProductModel::objects()->get(['productid' => $model->resourceid]);

    if (!$product_model){
        continue;
    }

    if ($product_model->amazon_fba_avail > 0){
        continue;
    }

    if (!ProductsToMoveHelper::isNeedToTransferProduct($model->resourceid)){
        continue;
    }

    if ($product_model->isGroupRoot() || $product_model->isGroupChild()){
        if (!ProductsToMoveHelper::isValidGroupProduct($product_model->group_root)) {
            echo "It's not valid group product for move to new sf {$product_model->group_root}";
            continue;
        } else {
            /** @var ProductModel $group_product */
            $group_product = ProductModel::objects()->get(['productid' => $product_model->group_root]);
            $childs = $group_product->childs->all();

            foreach ($childs as $child){

                if (ProductsToMoveHelper::processingMoveProductToNewSf($child, $model)){
                    $count_processed++;
                }
            }

            ProductsToMoveHelper::processingMoveProductToNewSf($group_product, $model);
        }
    }
    elseif (!ProductsToMoveHelper::isValidProduct($product_model)){
        continue;
    } else {
        if (ProductsToMoveHelper::processingMoveProductToNewSf($product_model, $model)){
            $count_processed++;
        }
    }

    $model->delete();



}

$time = (new DateTime('now'))->diff($start_time)->format('%H:%I:%S');

$log_category = "products_sf_moves";
$log_text = "Время обработки = {$time}\r\n Всего продуктов обработано = {$count_all}\r\n Продуктов перенесено = {$count_processed}";
func_backprocess_log($log_category, $log_text);