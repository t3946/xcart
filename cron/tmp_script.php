<?php

use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QOr;
use Modules\Goods\Models\ProductModel;
use Modules\Goods\Models\ProductVideosModel;

define("CIDEV_CRON_START", "CRON");

require __DIR__ . DIRECTORY_SEPARATOR . "../www/top.inc.php";
require __DIR__ . DIRECTORY_SEPARATOR . "../www/init.php";

function cutTags($fulldescr, $flag = true, $tags = [])
{
    $mass = [
        'div',
        'span',
        'p',
        'br',
        'ol',
        'ul',
        'li',
        'table',
        'thead',
        'tbody',
        'th',
        'tr',
        'td',
    ];

    $regexps = [
        '/<script[^>]*?>.*?<\/script>/i',
        '/<noscript[^>]*?>.*?<\/noscript>/i',
        '/<style[^>]*?>.*?<\/style>/i',
        '/<video[^>]*?>.*?<\/video>/i',
        '/<a[^>]*?>.*?<\/a>/i',
        '/<iframe[^>]*?>.*?<\/iframe>/i'
    ];
    foreach ($regexps as $regexp) {
        if (preg_match($regexp, $fulldescr)) {
            $fulldescr = preg_replace($regexp, "", $fulldescr);
        }
    }

    $fulldescr = trim($fulldescr);

    if (!$flag) {
        $mass = [];
    }

    if (!empty($tags) && is_array($tags)){
        foreach ($tags as $tag){
            $regexp = '/<(\D+)\s?[^>]*?>/';
            if (preg_match($regexp, $tag, $matches)){
                $mass[] = $matches[1];
            }
            else {
                $mass[] = $tag;
            }
        }
    }

    $tag = "";

    $tags_string = "";
    foreach ($mass as $tag){
        $tags_string .= "<$tag>";
    }

    $fulldescr = strip_tags($fulldescr, $tags_string);

    foreach ($mass as $tag) {

        $regexp = "/(<{$tag})([^>]*)(>)/i";

        if (preg_match($regexp, $fulldescr)) {
            $fulldescr = preg_replace($regexp, "$1$3", $fulldescr);
        }

    }

    return $fulldescr;
}

$start_time = new DateTime('now');

$regexp = '/<iframe[^>]*?src=("|\'|\\"|\\\')(.*?)(\'|\\"|\\\')[^>]*?>/i';
$regexp_2 = '/<video[^>]*?>\s+?<source[^>]*?src=("|\'|\\"|\\\')(.*?)("|\'|\\"|\\\')[^>]*?>/i';
$regexp_3 = '/<video[^>]*?>\s?<source[^>]*?src=("|\'|\\"|\\\')(.*?)("|\'|\\"|\\\')[^>]*?>/i';

$all_products_with_clear_fulldescr = 0;
$product_count_all = 0;
$product_count = 0;

$qor_1 = ['fulldescr__contains' => 'iframe'];
$qor_2 = ['fulldescr__contains' => '<video'];

$product_models = ProductModel::objects()->filter([
                                                    new Qor([
                                                        $qor_1, $qor_2
                                                            ])
                                                  ])->all();

foreach ($product_models as $product_model){

    $video = $videos = [];
    $video_url = '';
    if ( (preg_match($regexp, $product_model->fulldescr, $matches)) || (preg_match($regexp_2, $product_model->fulldescr, $matches)) || (preg_match($regexp_3, $product_model->fulldescr, $matches))){

        $video_url = $matches[2];

        if (!empty($video_url)) {
            (new ProductVideosModel([
                                        'product_id' => $product_model->productid,
                                        'video' => $video_url,
                                        'name' => $product_model->product
                                    ]))->save();
        }

        $product_count++;
    }
    $tmp_fulldescr = '';
    $tmp_fulldescr = cutTags($product_model->fulldescr);
    $product_model->fulldescr = $tmp_fulldescr;
    $product_model->update(['fulldescr']);
    $product_count_all++;
}

$product_models = ProductModel::objects()->all();

foreach ($product_models as $product_model){
    if ($product_model->fulldescr) {
        $product_model->fulldescr = cutTags($product_model->fulldescr);
        $product_model->update(['fulldescr']);
        $all_products_with_clear_fulldescr++;
    }
}


echo "All {$product_count_all}, is clear ==> {$product_count}";


$time = (new DateTime('now'))->diff($start_time)->format('%H:%I:%S');

$log_category = "Cut_video";
$log_text = "Время обработки = {$time}\r\n Всего продуктов обработано = {$product_count_all}\r\n Продуктов очищено = {$product_count}\r\nОчищенно описаний ==> {$all_products_with_clear_fulldescr}\r\n";
func_backprocess_log($log_category, $log_text);