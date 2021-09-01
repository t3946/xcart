<?php

use Modules\Goods\Models\ProductVideosModel;

define("CIDEV_CRON_START", "CRON");

require __DIR__ . DIRECTORY_SEPARATOR . "../www/top.inc.php";
require __DIR__ . DIRECTORY_SEPARATOR . "../www/init.php";

$start_time = new DateTime('now');

$regexp = '/.*?\/embed\/.*?(\?list.*)/i';

$video_models = ProductVideosModel::objects()->all();

$count = 0;
$clear_count = 0;

/** @var ProductVideosModel $video_model */
foreach ($video_models as $video_model){

    if (preg_match($regexp, $video_model->video)){
        $re = '/(\?list.*)/i';
        $video_model->video = preg_replace($re, '', $video_model->video);
        $video_model->update(['video']);

        $clear_count++;
    }
    $count++;
}


echo "Время обработки = {$time}\r\nОбщее количество видео = {$count}\r\n Количество очищенных видеоссылок = {$clear_count}";


$time = (new DateTime('now'))->diff($start_time)->format('%H:%I:%S');

$log_category = "Clear video";
$log_text = "Время обработки = {$time}\r\nОбщее количество видео = {$count}\r\n Количество очищенных видеоссылок = {$clear_count}";
func_backprocess_log($log_category, $log_text);

