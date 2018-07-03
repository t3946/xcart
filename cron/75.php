<?php

use Modules\Goods\Models\ProductModel;

define("CIDEV_CRON_START", "CRON");

require __DIR__ . DIRECTORY_SEPARATOR . "../www/top.inc.php";
require __DIR__ . DIRECTORY_SEPARATOR . "../www/init.php";


$json = file_get_contents('./json.txt');

$mass = json_decode($json, true);
$dx = [];
$data = [];
$orig = 0;
$all = 0;
foreach ($mass as $product){
        
    /** @var ProductModel $model */
    $model = ProductModel::objects()->get(['productcode' => $product['productcode']]);

    $orig++;

    $manufacturerid = $model->manufacturerid;

    $dx[] = $manufacturerid;

    if ($model->isGroupRoot()){
        $childs = $model->getFrontendChilds();
        foreach ($childs as $child){
            $data[$manufacturerid][] = $child->productcode;

            $all++;
        }
    } else {
        $data[$manufacturerid][] = $model->productcode;
        $all++;
    }

}

echo "Original => {$orig} and All => {$all}";

$data = json_encode($data);

file_put_contents('./data.json', $data);

$dx = json_encode($dx);

file_put_contents('./dx.json', $dx);