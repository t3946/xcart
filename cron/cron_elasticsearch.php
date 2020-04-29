<?php
use Modules\Core\Helpers\CoreHelper;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\UpdatedProductModel;
use Modules\Sites\Models\SiteModel;

define("CIDEV_CRON_START", "CRON");

require __DIR__ . DIRECTORY_SEPARATOR . "../www/top.inc.php";
require __DIR__ . DIRECTORY_SEPARATOR . "../www/init.php";

ini_set('memory_limit', '512M');
set_time_limit(0);

$start_time = time();
$counter    = 0;

$cidev_storefronts = \Modules\Sites\Models\SiteModel::objects()->exclude(['code' => 'S3'])->asArray(true)->all();

#
## 1. Секция обновления продуктов в индексе Elastic
###

$cidev_updated_products = UpdatedProductModel::objects()->filter(['type__in' => [6, 61]]);

$total_items = $cidev_updated_products->count();

$updated_ok   = 0;
$update_fail  = 0;
$deleted_ok   = 0;
$deleted_fail = 0;
$processed    = 0;
$requests     = 0;
$body         = '';

$updated_ok_flag  = false;
$update_fail_flag = false;
$deleted_ok_flag  = false;
$flag61           = false;


foreach ($cidev_updated_products as $record) {
    $counter++;
    if ($counter % 100 == 0) {
        func_flush('.');
        if ($counter % 5000 == 0) {
            func_flush("<br />\n");
        }
        func_flush();
    }

    if ($product_model = $record->product)
    {
        $storefronts_for_product = [];
        {
            if ($product_model->forsale === 'Y') {
                if (in_array($record->type, ['6', '61'], true))
                {
                    if ($record->type === '61') {
                                /*
                             пройти по списку магазинов отличных от полученных магазинов продукта и выполнить запрос на удаление данных продукта в индексах этих магазинов

                             отправить данные продукта в индексы его магазинов
                             получить код ответа сервера индекса на каждую отправку
                            */
                        if (!$flag61) {
                            foreach (SiteModel::objects() as $site) {
                                $classElasticSearch = new Xcart\ElasticSearch($config['ElasticSearch_options']['es_url'], $site->domain);
                                $classElasticSearch->setType('product');
                                $classElasticSearch->delete($product_model->productid);
                                $requests++;
                            }
                            $flag61 = true;
                        }
                    }

                    foreach ($product_model->sites as $site) {
                        $data_arr  = [];
                        $data_json = '';
                        $classElasticSearch = new Xcart\ElasticSearch($config['ElasticSearch_options']['es_url'], $site->domain);
                        $classElasticSearch->setType('product');
                        $classElasticSearch->delete($product_model->productid);
                        $requests++;

                        $data_arr = [
                            'productname' => $product_model->getFrontendName(),
                            'sku' => $product_model->productcode,
                            'upc' => $product_model->upc,
                            'brand' => $product_model->brand->brand,
                            'description' => CoreHelper::stripTags(str_replace(['/r/n', "\r\n"], ' ', $product_model->fulldescr)),
                            'description.seo_fulldescr' => CoreHelper::stripTags($product_model->seo_fulldescr),
                            'productname.seo_productname' => CoreHelper::stripTags($product_model->seo_product_name),
                            'productname.seo_h2' => CoreHelper::stripTags($product_model->seo_h2),
                            'productname.title_tag' =>  CoreHelper::stripTags($product_model->title_tag),
                        ];
                        $classElasticSearch->setQueryParam($data_arr);
                        $result = $classElasticSearch->add($product_model->productid);
                        $requests++;
                    }
                }

                if ($result['created'] === true) {
                    $updated_ok_flag = true;
                } else {
                    $update_fail_flag = true;
                }
            }
            else {

                foreach ($product_model->sites as $site) {
                    $classElasticSearch = new Xcart\ElasticSearch($config['ElasticSearch_options']['es_url'], $site->domain);
                    $classElasticSearch->setType('product');
                    $classElasticSearch->delete($product_model->productid);
                    $requests++;
                }

                $deleted_ok_flag = true;
                $deleted_ok++;
                $record->delete();
            }
        }
        if (!$deleted_ok_flag) {
            if ((!$updated_ok_flag && !$update_fail_flag) || ($update_fail_flag)) {
                $update_fail++;
                $record->source = 're-queued';
                $record->update(['source' => 're-queued']);
            } else {
                $updated_ok++;
                $record->delete();
            }
        }
    }
    else {
        foreach (SiteModel::objects() as $site) {
            $classElasticSearch = new Xcart\ElasticSearch($config['ElasticSearch_options']['es_url'], $site->domain);
            $classElasticSearch->setType('product');
            $classElasticSearch->delete($record->resourceid);
            $requests++;
        }
        $record->delete();
        $deleted_ok++;
    }

    $processed++;

    $current_time      = time();
    $diff_time_in_mins = ($current_time - $start_time) / 60;

    if ($diff_time_in_mins > $config["ElasticSearch_options"]["es_maximum_work_time_per_start_in_minutes"]) {

        $rest_documents_to_index = $total_items - ($updated_ok + $deleted_ok);

        $subj = "ES-robot statistics (Products)";
        $body
              = "
                Total products to index: $total_items 
                Products processed: $processed 
                Products updated 'ok': $updated_ok 
                Products updated 'fail': $update_fail
                Products deleted 'ok': $deleted_ok 
                Products deleted 'fail': $deleted_fail 
                Rest documents to index: $rest_documents_to_index 
                Requests made to ES: $requests
                Working time:  $diff_time_in_mins minutes";

        break;
    }
}

$current_time      = time();
$diff_time_in_mins = ($current_time - $start_time) / 60;
if ($diff_time_in_mins <= $config["ElasticSearch_options"]["es_maximum_work_time_per_start_in_minutes"] && $total_items > 0) {

    $rest_documents_to_index = $total_items - ($updated_ok + $deleted_ok);

    $subj = "ES-robot statistics (Products)";
    $body
          = "
                Total products to index: $total_items 
                Products processed: $processed 
                Products updated 'ok': $updated_ok 
                Products updated 'fail': $update_fail
                Products deleted 'ok': $deleted_ok 
                Products deleted 'fail': $deleted_fail 
                Rest documents to index: $rest_documents_to_index 
                Requests made to ES: $requests
                Working time:  $diff_time_in_mins minutes";
}
if ($body != "") {
    func_backprocess_log("ElasticSearch updates", $body);
}

###
##
#

#
## 2. Секция брендов
###
$start_time = time();

$cidev_updated_products = db_query($query = "Select * From xcart_cidev_updated_products where type ='7'  and time_stamp < '$start_time'");

$total_items = db_num_rows($cidev_updated_products);

$updated_ok   = 0;
$update_fail  = 0;
$deleted_ok   = 0;
$deleted_fail = 0;
$processed    = 0;
$requests     = 0;
$body         = "";

while ($record = db_fetch_array($cidev_updated_products)) {
    $counter++;
    if ($counter % 100 == 0) {
        func_flush(".");
        if ($counter % 5000 == 0) {
            func_flush("<br />\n");
        }
        func_flush();
    }

    $brandid = $record["resourceid"];

    $brand_info = func_query_first("SELECT avail, brand, descr FROM $sql_tbl[brands] WHERE brandid='$brandid'");

    $count_products_with_brand = 0;
    if ($brand_info["avail"] == "Y") {
        $stores                = func_query("SELECT  PS.sfid, COUNT(P.productid) as count_products, SF.domain
                              From xcart_products P
                                    inner join xcart_products_sf PS ON PS.productid = P.productid
                                    left join xcart_storefronts SF ON SF.storefrontid = PS.sfid
                              where P.forsale = 'Y' and P.brandid = '$brandid'
                              Group By PS.sfid");
        $storefronts_for_brand = [];
        if (!empty($stores)) {
            foreach ($stores as $k => $store) {

                if (empty($store["domain"])) {
                    $stores[$k]["domain"] = "www.artistsupplysource.com";
                }

                $storefronts_for_brand[] = $stores[$k]["domain"];
            }
        }

        if (!empty($stores)) {
            foreach ($stores as $v) {
                if (!empty($v["count_products"])) {
                    $count_products_with_brand += $v["count_products"];
                }
            }
        }
    }

    if ($brand_info["avail"] != 'Y' || $count_products_with_brand == "0") {

        foreach ($cidev_storefronts as $k => $v) {

            $data_json = "";
            $url       = $config["ElasticSearch_options"]["es_url"] . $v["domain"] . "/brand/" . $brandid;

            // Delete brand
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json"]);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result_json = curl_exec($ch);
            $info        = curl_getinfo($ch);
            curl_close($ch);
            $result = json_decode($result_json, true);
            $requests++;
        }

        db_query("DELETE FROM $sql_tbl[cidev_updated_products] WHERE resourceid='$record[resourceid]' AND type='$record[type]' AND time_stamp='$record[time_stamp]' AND source='$record[source]'");
        $deleted_ok++;
    }
    elseif (!empty($stores)) {
        /*
                  отправить запрос на обновление данных бренда в индексы полученных магазинов бренда
                  по каждому запросу получить статус ответа
        */
        foreach ($cidev_storefronts as $k => $v)
        {
            if (!in_array($v["domain"], $storefronts_for_brand)) {
                // Delete at SFs not for brand
                $data_json = "";
                $url       = $config["ElasticSearch_options"]["es_url"] . $cidev_storefronts[$k]["domain"] . "/brand/" . $brandid;
                $ch        = curl_init($url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json"]);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result_json = curl_exec($ch);
                curl_close($ch);
                $requests++;
            }
        }

        $update_fail_flag = false;

        foreach ($cidev_storefronts as $k => $v)
        {
            if (in_array($v["domain"], $storefronts_for_brand))
            {
                $data_json = "";
                $url       = $config["ElasticSearch_options"]["es_url"] . $cidev_storefronts[$k]["domain"] . "/brand/" . $brandid;

                $data_arr["name"]        = $brand_info["brand"];
                $brand_info["descr"]     = str_replace("/r/n", " ", $brand_info["descr"]);
                $brand_info["descr"]     = str_replace("\r\n", " ", $brand_info["descr"]);
                $data_arr["description"] = CoreHelper::stripTags($brand_info["descr"]);
                $data_json               = json_encode($data_arr);

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json"]);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result_json = curl_exec($ch);
                $info        = curl_getinfo($ch);
                curl_close($ch);
                $result = json_decode($result_json, true);
                $requests++;

                if ($info["http_code"] != "200" && $info["http_code"] != "201") {
                    $update_fail_flag = true;
                }
            }
        }

        if ($update_fail_flag) {
            db_query("UPDATE $sql_tbl[cidev_updated_products] SET source = 're-queued' WHERE resourceid='$record[resourceid]' AND type='$record[type]' AND time_stamp='$record[time_stamp]' AND source='$record[source]'");
            $update_fail++;
        }
        else {
            db_query("DELETE FROM $sql_tbl[cidev_updated_products] WHERE resourceid='$record[resourceid]' AND type='$record[type]' AND time_stamp='$record[time_stamp]' AND source='$record[source]'");
            $updated_ok++;
        }
    }

    $processed++;

    $current_time      = time();
    $diff_time_in_mins = ($current_time - $start_time) / 60;

    if ($diff_time_in_mins > $config["ElasticSearch_options"]["es_maximum_work_time_per_start_in_minutes"])
    {
        $rest_documents_to_index = $total_items - ($updated_ok + $deleted_ok);

        $subj = "ES-robot statistics (Brands)";
        $body
              = "
                Total brands to index: $total_items 
                Brands processed: $processed 
                Brands updated 'ok': $updated_ok 
                Brands updated 'fail': $update_fail
                Brands deleted 'ok': $deleted_ok 
                Brands deleted 'fail': $deleted_fail 
                Rest documents to index: $rest_documents_to_index 
                Requests made to ES: $requests
                Working time:  $diff_time_in_mins minutes";

        break;
    }
}
db_free_result($cidev_updated_products);

$current_time      = time();
$diff_time_in_mins = ($current_time - $start_time) / 60;
if ($diff_time_in_mins <= $config["ElasticSearch_options"]["es_maximum_work_time_per_start_in_minutes"] && $total_items > 0) {

    $rest_documents_to_index = $total_items - ($updated_ok + $deleted_ok);

    $subj = "ES-robot statistics (Brands)";
    $body
          = "
                Total brands to index: $total_items 
                Brands processed: $processed 
                Brands updated 'ok': $updated_ok 
                Brands updated 'fail': $update_fail
                Brands deleted 'ok': $deleted_ok 
                Brands deleted 'fail': $deleted_fail 
                Rest documents to index: $rest_documents_to_index 
                Requests made to ES: $requests
                Working time:  $diff_time_in_mins minutes";
}
if ($body != "") {
    func_backprocess_log("ElasticSearch updates", $body);
}

###
##
#

#
## Секция категорий
###


$cidev_updated_products = UpdatedProductModel::objects()->filter(['type' => 8]);

$total_items = $cidev_updated_products->count();

$updated_ok   = 0;
$update_fail  = 0;
$deleted_ok   = 0;
$deleted_fail = 0;
$processed    = 0;
$data_arr     = [];

foreach ($cidev_updated_products as $record) {

    $counter++;
    if ($counter % 100 == 0) {
        func_flush(".");
        if ($counter % 5000 == 0) {
            func_flush("<br />\n");
        }
        func_flush();
    }

    $categoryid = $record['resourceid'];

    /** @var CategoryModel $categoryModel */
    $categoryModel = CategoryModel::objects()->get(['categoryid' => $categoryid]);

    $classElasticSearch = new Xcart\ElasticSearch($config['ElasticSearch_options']['es_url'], $categoryModel->site->domain);

    $classElasticSearch->setType('category');

    $result = $classElasticSearch->delete($categoryid);

    if ($categoryModel->avail === 'Y' || $categoryModel->active_product_count > 0) {
        $info = $classElasticSearch->curl_info;

        $data_arr = [
            'category' => $categoryModel->getFrontendName(),
            'description' => CoreHelper::stripTags(str_replace(['/r/n', "\r\n"], '', $categoryModel->description)),
        ];

        $classElasticSearch->setQueryParam($data_arr);

        $result = $classElasticSearch->add($categoryid);

        if ($result['created'] !== true) {
            $update_fail++;
        } else {
            $record->delete();
            $updated_ok++;
        }
    } else {
        $deleted_ok++;
    }

    $processed++;

    $current_time      = time();
    $diff_time_in_mins = ($current_time - $start_time) / 60;

    if ($diff_time_in_mins > $config["ElasticSearch_options"]["es_maximum_work_time_per_start_in_minutes"]) {

        $rest_documents_to_index = $total_items - ($updated_ok + $deleted_ok);

        $subj = 'ES-robot statistics (Categories)';
        $body
              = "
                Total categories to index: $total_items 
                Categories processed: $processed 
                Categories updated 'ok': $updated_ok 
                Categories updated 'fail': $update_fail
                Categories deleted 'ok': $deleted_ok 
                Categories deleted 'fail': $deleted_fail 
                Rest documents to index: $rest_documents_to_index 
                Working time:  $diff_time_in_mins minutes";

        func_backprocess_log('ElasticSearch updates', $body);
        break;
    }
}

if ($diff_time_in_mins <= $config["ElasticSearch_options"]["es_maximum_work_time_per_start_in_minutes"] && $total_items > 0) {

    $rest_documents_to_index = $total_items - ($products_indexed_ok + $products_deleted_from_index_ok);

    $subj = "ES-robot statistics (Categories)";
    $body
          = "
                Total categories to index: $total_items 
                Categories processed: $processed 
                Categories updated 'ok': $updated_ok 
                Categories updated 'fail': $update_fail
                Categories deleted 'ok': $deleted_ok 
                Categories deleted 'fail': $deleted_fail 
                Rest documents to index: $rest_documents_to_index 
                Working time:  $diff_time_in_mins minutes";

    func_backprocess_log("ElasticSearch updates", $body);
}
###
##
#

print"<br />DONE!";
