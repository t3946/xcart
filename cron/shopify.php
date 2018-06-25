<?php

use Modules\Goods\Models\ProductModel;

define("CIDEV_CRON_START", "CRON");

require __DIR__ . DIRECTORY_SEPARATOR . "../www/top.inc.php";
require __DIR__ . DIRECTORY_SEPARATOR . "../www/init.php";


$file = file_get_contents('./xcart.txt');
echo "{$file}\r\n";
$file = explode(';', $file);
echo count($file);
$data = [];
$items_count = 0;
foreach ($file as $id) {
    $row = [];

    /** @var ProductModel $model */
    $model = ProductModel::objects()->get(['productid' => $id]);

    $row['productcode'] = $model->productcode;
    $row['name'] = $model->product;
    if ($model->brand->brand) {
        $row['brand'] = $model->brand->getProductFrontendName();
    }
    if (!$model->isGroupRoot()) {
        $row['price'] = $model->getFrontendPrice();
    } else {
        $row['minPrice'] = $model->getFrontendPrice();
        $row['maxPrice'] = $model->getFrontendPrice(2);
    }
    $row['descr'] = $model->getFrontendDescription();
    $row['stock'] = $model->r_avail;

    if ($images = $model->getImages()){
        /** @var \Modules\Goods\Models\ImageDModel $image */
        foreach ($images as $image){
            $row['images'][] = $image->getCdnURL();
        }
    }
    $items_count++;
    $data[$model->productcode] = $row;
}

echo "\r\n{$items_count}\r\n";

$data = json_encode($data);

file_put_contents('./list.txt', $data);