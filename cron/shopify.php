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
$group_mass = [];
foreach ($file as $id) {
    $row = [];
    $row_2 = [];
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
        $row['price'] = $model->getFrontendPrice();
        $row['maxPrice'] = $model->getFrontendPrice(2);

        $row_2['sku'] = $model->productcode;

        $children = $model->getFrontendChilds();

        /** @var ProductModel $child */
        foreach ($children as $child){
            $m = [];
            $m['sku'] = $child->productcode;
            $m['price'] = $child->getFrontendPrice();
         $row_2['childs'][] = $m;
        }
        $group_mass[] = $row_2;
    }

    if (empty($row['price'] || empty($row['maxPrice']))) {
        $b = 1;
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

$group = json_encode($group_mass);

file_put_contents('./group_childs', $group);

echo "\r\n{$items_count}\r\n";

$data = json_encode($data);

file_put_contents('./list.txt', $data);