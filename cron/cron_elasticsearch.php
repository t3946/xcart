<?php
use Modules\Core\Helpers\CoreHelper;

define("CIDEV_CRON_START", "CRON");

require "../top.inc.php";
require "../init.php";

ini_set('memory_limit', '512M');
set_time_limit(0);

$start_time = time();
$counter    = 0;

$cidev_storefronts = $storefronts;
foreach ($cidev_storefronts as $storefrontid => $sf_info) {
    $cidev_storefronts[$storefrontid] = func_get_storefront_info($storefrontid);
}

$cidev_storefronts[0] = func_get_storefront_info(0);

#
## 1. Секция обновления продуктов в индексе Elastic
###

$cidev_updated_products = db_query($query = "Select * From xcart_cidev_updated_products where (type = 6 or type = 61) and time_stamp < '$start_time'");


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

    $products = func_query("Select PS.sfid, P.*, $sql_tbl[storefronts].domain, $sql_tbl[brands].brand As brand From xcart_products P left join xcart_products_sf PS ON PS.productid = P.productid LEFT JOIN $sql_tbl[storefronts] ON PS.sfid = $sql_tbl[storefronts].storefrontid LEFT JOIN $sql_tbl[brands] ON $sql_tbl[brands].brandid = P.brandid where P.productid = '$record[resourceid]'");

    if (!empty($products))
    {
        $updated_ok_flag  = false;
        $update_fail_flag = false;
        $deleted_ok_flag  = false;
        $flag61           = false;

        $storefronts_for_product = [];

        foreach ($products as $k => $product) {

            if (empty($product["domain"])) {
                $products[$k]["domain"] = "www.artistsupplysource.com";
            }

            $storefronts_for_product[] = $products[$k]["domain"];
        }

        foreach ($products as $product)
        {
            $product_model = \Modules\Product\Models\ProductModel::objects()->get(['productid' => $product['productid']]);
            if ($product_model->isGroupRoot()) {
                continue;
            }
            if ($product["forsale"] == "Y") {
                if ($record["type"] == "6")
                {
                    $data_arr  = [];
                    $data_json = "";
                    $url       = $config["ElasticSearch_options"]["es_url"] . $product["domain"] . "/product/" . $product["productid"];

                    $product["fulldescr"] = str_replace("/r/n", " ", $product["fulldescr"]);
                    $product["fulldescr"] = str_replace("\r\n", " ", $product["fulldescr"]);

                    $data_arr["productname"] = $product_model->getTitle();
                    $data_arr["sku"]         = $product["productcode"];
                    $data_arr["upc"]         = $product["upc"];
                    $data_arr["brand"]       = $product["brand"];
                    $data_arr["description"] = $text = CoreHelper::stripTags($product["fulldescr"]);

                    $data_arr["description.seo_fulldescr"]   = CoreHelper::stripTags($product["seo_fulldescr"]);
                    $data_arr["productname.seo_productname"] = CoreHelper::stripTags($product["seo_product_name"]);
                    $data_arr["productname.seo_h2"]          = CoreHelper::stripTags($product["seo_h2"]);
                    $data_arr["productname.title_tag"]       = CoreHelper::stripTags($product["title_tag"]);

                    $data_json = json_encode($data_arr);

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
                }
                elseif ($record["type"] == "61") {
                    /*
                     пройти по списку магазинов отличных от полученных магазинов продукта и выполнить запрос на удаление данных продукта в индексах этих магазинов

                     отправить данные продукта в индексы его магазинов
                     получить код ответа сервера индекса на каждую отправку
                    */

                    if (!$flag61) {
                        foreach ($cidev_storefronts as $k => $v) {
                            if (!in_array($v["domain"], $storefronts_for_product)) {

                                $data_json = "";
                                $url       = $config["ElasticSearch_options"]["es_url"] . $v["domain"] . "/product/" . $product["productid"];

                                // (Delete for current prouct at first too)
                                $ch = curl_init($url);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json"]);
                                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                $result_json = curl_exec($ch);
                                curl_close($ch);
                                $requests++;
                            }
                        }
                        $flag61 = true;
                    }

                    $product["fulldescr"] = str_replace("/r/n", " ", $product["fulldescr"]);
                    $product["fulldescr"] = str_replace("\r\n", " ", $product["fulldescr"]);

                    $data_arr["productname"] = $product_model->getTitle();
                    $data_arr["sku"]         = $product["productcode"];
                    $data_arr["upc"]         = $product["upc"];
                    $data_arr["brand"]       = $product["brand"];
                    $data_arr["description"] = CoreHelper::stripTags($product["fulldescr"]);

                    $data_arr["description.seo_fulldescr"]   = CoreHelper::stripTags($product["seo_fulldescr"]);
                    $data_arr["productname.seo_productname"] = CoreHelper::stripTags($product["seo_product_name"]);
                    $data_arr["productname.seo_h2"]          = CoreHelper::stripTags($product["seo_h2"]);
                    $data_arr["productname.title_tag"]       = CoreHelper::stripTags($product["title_tag"]);

                    $data_json = json_encode($data_arr);

                    $url = $config["ElasticSearch_options"]["es_url"] . $product["domain"] . "/product/" . $product["productid"];
                    $ch  = curl_init($url);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json"]);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $result_json = curl_exec($ch);
                    $info        = curl_getinfo($ch);
                    curl_close($ch);
                    $result = json_decode($result_json, true);
                    $requests++;
                } // elseif ($record["type"] == "61")

                if ($info["http_code"] == "200" || $info["http_code"] == "201") {
                    $updated_ok_flag = true;
                }
                else {
                    $update_fail_flag = true;
                }
            }
            else { //if ($product["forsale"] == "Y")

                foreach ($cidev_storefronts as $k => $v) {
                    $data_json = "";
                    $url       = $config["ElasticSearch_options"]["es_url"] . $v["domain"] . "/product/" . $product["productid"];

                    // Delete prouct
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json"]);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $result_json = curl_exec($ch);
                    curl_close($ch);
                    $result = json_decode($result_json, true);
                    $requests++;
                }

                db_query("DELETE FROM $sql_tbl[cidev_updated_products] WHERE resourceid='$record[resourceid]' AND type='$record[type]' AND time_stamp='$record[time_stamp]' AND source='$record[source]'");

                $deleted_ok_flag = true;
                $deleted_ok++;
            } // else

        } // foreach ($products as $product)

        if (!$deleted_ok_flag) {
            if (
                (!$updated_ok_flag && !$update_fail_flag)
                || ($update_fail_flag)
            ) {
                $update_fail++;
                db_query("UPDATE $sql_tbl[cidev_updated_products] SET source = 're-queued' WHERE resourceid='$record[resourceid]' AND type='$record[type]' AND time_stamp='$record[time_stamp]' AND source='$record[source]'");
            }
            else {
                $updated_ok++;

                db_query("DELETE FROM $sql_tbl[cidev_updated_products] WHERE resourceid='$record[resourceid]' AND type='$record[type]' AND time_stamp='$record[time_stamp]' AND source='$record[source]'");
            }
        } // if (!$deleted_ok_flag)

    } // if (!empty($products))
    else {
        foreach ($cidev_storefronts as $k => $v) {

            $data_json = "";
            $url       = $config["ElasticSearch_options"]["es_url"] . $v["domain"] . "/product/" . $record["resourceid"];

            // (Delete for current prouct at first too)
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json"]);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result_json = curl_exec($ch);
            curl_close($ch);
            $requests++;
        }

        db_query("DELETE FROM $sql_tbl[cidev_updated_products] WHERE resourceid='$record[resourceid]' AND type='$record[type]' AND time_stamp='$record[time_stamp]' AND source='$record[source]'");
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
db_free_result($cidev_updated_products);

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
$cidev_updated_products = db_query($query = "Select * From xcart_cidev_updated_products where type ='8'  and time_stamp < '$start_time'");

$total_items = db_num_rows($cidev_updated_products);

$updated_ok   = 0;
$update_fail  = 0;
$deleted_ok   = 0;
$deleted_fail = 0;
$processed    = 0;
$data_arr     = [];

while ($record = db_fetch_array($cidev_updated_products)) {

    $counter++;
    if ($counter % 100 == 0) {
        func_flush(".");
        if ($counter % 5000 == 0) {
            func_flush("<br />\n");
        }
        func_flush();
    }

    $categoryid = $record["resourceid"];

    $category_info = func_query_first("Select C.avail, C.storefrontid, C.global_product_count As p_count, C.description, C.category From xcart_categories C where C.categoryid = '$categoryid'");

    if ($category_info["avail"] != 'Y' || $category_info["p_count"] <= "0")
    {
        foreach ($cidev_storefronts as $k => $v)
        {
            $classElasticSearch = new Xcart\ElasticSearch($config["ElasticSearch_options"], $v["domain"]);

            $classElasticSearch->setType('category');

            $result = $classElasticSearch->delete($categoryid);

        }

        db_query("DELETE FROM $sql_tbl[cidev_updated_products] WHERE resourceid='$record[resourceid]' AND type='$record[type]' AND time_stamp='$record[time_stamp]' AND source='$record[source]'");

        $deleted_ok++;
    }
    else {

        $classElasticSearch = new Xcart\ElasticSearch($config["ElasticSearch_options"], $cidev_storefronts[$category_info["storefrontid"]]["domain"]);

        $classElasticSearch->setType('category');

        $result = $classElasticSearch->delete($categoryid);

        $info = $classElasticSearch->curl_info;

        $data_arr["category"]         = $category_info["category"];
        $category_info["description"] = str_replace("/r/n", " ", $category_info["description"]);
        $category_info["description"] = str_replace("\r\n", " ", $category_info["description"]);
        $data_arr["description"]      = CoreHelper::stripTags($category_info["description"]);

        $classElasticSearch->setQueryParam($data_arr);

        $result = $classElasticSearch->add($categoryid);

        $info = $classElasticSearch->curl_info;

        if (!in_array($info["http_code"], ["200", "201"])) {
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

    if ($diff_time_in_mins > $config["ElasticSearch_options"]["es_maximum_work_time_per_start_in_minutes"]) {

        $rest_documents_to_index = $total_items - ($updated_ok + $deleted_ok);

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
        break;
    }
}
db_free_result($cidev_updated_products);

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
