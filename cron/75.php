<?php

use Modules\Goods\Models\ProductModel;

define("CIDEV_CRON_START", "CRON");

require __DIR__ . DIRECTORY_SEPARATOR . "../www/top.inc.php";
require __DIR__ . DIRECTORY_SEPARATOR . "../www/init.php";


$json = file_get_contents('./json.txt');

$mass = json_decode($json, true);
$data = $dx = [];
foreach ($mass as $product){

    /** @var ProductModel $model */
    $model = ProductModel::objects()->get(['productcode' => $product['productcode']]);

    $row = [];

    $manufacturerid = $model->manufacturerid;

    $dx[] = $manufacturerid;

    if ($model->isGroupRoot()){

        $child_products = [];

        $childs = $model->getFrontendChilds();
        foreach ($childs as $child){
            $ch = [];

            $ch['productcode'] = "TA-{$model->productcode}";
            $ch['product'] = $model->product;
            $ch['forsale'] = $model->forsale;
            $ch['fulldescr'] = $model->fulldescr;
            $ch['r_avail'] = $model->r_avail;
            $ch['eta_date_mm_dd_yyyy'] = $model->eta_date_mm_dd_yyyy;
            $ch['cost_to_us'] = $model->cost_to_us;

            if (!empty($model->list_price)){
                $ch['list_price'] = $model->list_price;
            }

            if (!empty($model->new_map_price)) {
                $ch['new_map_price'] = $model->new_map_price;
            }

            $ch['brand_name'] = $model->brand->brand;

            if ($images = $model->getImages()) {
                foreach ($images as $image){
                    $ch['images'][] = $image->getCdnURL();
                    $ch['alt_names'][] = $model->product;
                }
            }

            $ch['brand_normalized'] = $model->brand_normalized;

            if (!empty($model->upc)){
                $ch['upc'] = $model->upc;
            }

            if (!empty($model->dim_x)){
                $ch['dim_x'] = $model->dim_x;
            }

            if (!empty($model->dim_y)){
                $ch['dim_y'] = $model->dim_y;
            }

            if (!empty($model->dim_z)){
                $ch['dim_z'] = $model->dim_z;
            }

            if (!empty($model->weight)){
                $ch['weight'] = $model->weight;
            }

            if (!empty($model->shipping_dim_x)){
                $ch['shipping_dim_x'] = $model->shipping_dim_x;
            }

            if (!empty($model->shipping_dim_y)){
                $ch['shipping_dim_y'] = $model->shipping_dim_y;
            }

            if (!empty($model->shipping_dim_z)){
                $ch['shipping_dim_z'] = $model->shipping_dim_z;
            }

            if (!empty($model->shipping_weight)){
                $ch['shipping_weight'] = $model->shipping_weight;
            }

            $ch['seo_meta_descr'] = $model->seo_meta_descr;
            $ch['seo_product_name'] = $model->seo_product_name;

        }

        $row['is_group'] = true;
        $row['brand_normalized'] = $model->brand_normalized;
        $row['brand_name'] = $model->brand->brand;
        $row['forsale'] = 'Y';
        $row['child_products'] = $child_products;

        if (!empty($product['product'])) {
            $row['product'] = $product['product'];
        } else {
            $row['product'] = $model->product;
        }


        if (!empty($product['fulldescr'])) {
            $row['fulldescr'] = $product['fulldescr'];
        } else {
            $row['fulldescr'] = $model->fulldescr;
        }

        if (!empty($product['seo_product_name'])) {
            $row['seo_product_name'] = $product['seo_product_name'];
        } else {
            $row['seo_product_name'] = $model->seo_product_name;
        }

        if (!empty($product['seo_meta_descr'])) {
            $row['seo_meta_descr'] = $product['seo_meta_descr'];
        } else {
            $row['seo_meta_descr'] = $model->seo_meta_descr;
        }



    }

    else {

        $row['productcode'] = "TA-{$model->productcode}";

        if (!empty($product['product'])) {
            $row['product'] = $product['product'];
        } else {
            $row['product'] = $model->product;
        }

        if (!empty($product['fulldescr'])){
            $row['fulldescr'] = $product['fulldescr'];
        } else {
            $row['fulldescr'] = $model->fulldescr;
        }

        if (!empty($product['seo_product_name'])) {
            $row['seo_product_name'] = $product['seo_product_name'];
        } else {
            $row['seo_product_name']  = $model->seo_product_name;
        }

        if (!empty($product['seo_meta_descr'])){
            $row['seo_meta_descr'] = $product['seo_meta_descr'];
        } else {
            $row['seo_meta_descr'] = $model->seo_meta_descr;
        }

        $row['cost_to_us'] = $model->cost_to_us;

        if (!empty($model->list_price)) {
            $row['list_price'] = $model->list_price;
        }

        if (!empty($model->new_map_price)){
            $row['new_map_price'] = $model->new_map_price;
        }

        $row['r_avail'] = $model->r_avail;
        $row['forsale'] = $model->forsale;
        $row['eta_date_mm_dd_yyyy'] = $model->eta_date_mm_dd_yyyy;

        if (!empty($model->upc)){
            $row['upc'] = $model->upc;
        }

        if (!empty($model->dim_x)){
            $row['dim_x'] = $model->dim_x;
        }

        if (!empty($model->dim_y)){
            $row['dim_y'] = $model->dim_y;
        }

        if (!empty($model->dim_z)){
            $row['dim_z'] = $model->dim_z;
        }

        if (!empty($model->weight)){
            $row['weight'] = $model->weight;
        }

        if (!empty($model->shipping_dim_x)){
            $row['shipping_dim_x'] = $model->shipping_dim_x;
        }

        if (!empty($model->shipping_dim_y)){
            $row['shipping_dim_y'] = $model->shipping_dim_y;
        }

        if (!empty($model->shipping_dim_z)){
            $row['shipping_dim_z'] = $model->shipping_dim_z;
        }

        if (!empty($model->shipping_weight)){
            $row['shipping_weight'] = $model->shipping_weight;
        }

        $row['brand_name'] = $model->brand->brand;

        if ($images = $model->getImages()) {
            foreach ($images as $image){
                $row['images'][] = $image->getCdnURL();
                $row['alt_names'][] = $model->product;
            }
        }

        $row['brand_normalized'] = $model->brand_normalized;


    }

    $data[$manufacturerid][] = $row;

}


$data = json_encode($data);

file_put_contents('./data.json', $data);

$dx = json_encode($dx);

file_put_contents('./dx.json', $dx);