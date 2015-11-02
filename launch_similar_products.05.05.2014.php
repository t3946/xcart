<?php

//require "./auth.php";

define("CIDEV_CRON_START", "CRON");

require "./top.inc.php";
require "./init.php";

set_time_limit(0);

x_load("mail");

$started_at = time();

$subj = "Start updating process of similar products";
$body = "Started at: ".date("Y-m-d H:i:s", $started_at)."\n";
func_send_simple_mail($config["Product_Page"]["similar_script_notification_email"], $subj, $body, "supplier.feeds@s3stores.com");

$similar_cron_generated_flag_arr = func_query("SELECT SQL_NO_CACHE DISTINCT similar_cron_generated_flag FROM $sql_tbl[products]");
$similar_cron_generated_flag_arr_count = count($similar_cron_generated_flag_arr);

if ($similar_cron_generated_flag_arr_count == "1" && $similar_cron_generated_flag_arr[0]["similar_cron_generated_flag"] == "Y"){
	db_query("UPDATE $sql_tbl[products] SET similar_cron_generated_flag='N'");
}

$count_products = func_query_first_cell("SELECT SQL_NO_CACHE COUNT(*) FROM $sql_tbl[products] WHERE similar_cron_generated_flag='N'");
$take_per_cycle = 100;
$count_cycles = ceil($count_products/$take_per_cycle);
$cnt = 0;
$usleep_time = 1000000 / 20; // micro secs

for ($i = 0; $i < $count_cycles; $i++) {

    $products = db_query("SELECT SQL_NO_CACHE productid, cost_to_us FROM $sql_tbl[products] WHERE similar_cron_generated_flag='N' LIMIT $take_per_cycle");

    while ($product_info = db_fetch_array($products)) {

	$cat = func_query_first_cell("SELECT SQL_NO_CACHE categoryid FROM $sql_tbl[products_categories] WHERE main='Y' AND productid='$product_info[productid]'");
#
## Main from product.php
###
        $tmp_products1 = db_query("
                SELECT SQL_NO_CACHE $sql_tbl[products].productid, $sql_tbl[products].cost_to_us FROM $sql_tbl[products]
                LEFT JOIN $sql_tbl[products_categories]
                        ON $sql_tbl[products_categories].productid = $sql_tbl[products].productid
                WHERE 
                        $sql_tbl[products_categories].categoryid = '$cat' AND $sql_tbl[products_categories].main='Y' AND $sql_tbl[products].cost_to_us > '$product_info[cost_to_us]' AND $sql_tbl[products].productid != '$product_info[productid]' AND $sql_tbl[products].forsale='Y' AND $sql_tbl[products].avail > 0
                ORDER BY $sql_tbl[products].cost_to_us ASC LIMIT 3");

        if ($tmp_products1){
                $tmp_products1_1 = array();
                $tmp_counter = 0;
                while($p = db_fetch_array($tmp_products1)) {
                        $tmp_products1_1[$tmp_counter]["productid"] = $p["productid"];
                        $tmp_products1_1[$tmp_counter]["cost_to_us"] = $p["cost_to_us"];
                        $tmp_counter++;
                }
                db_free_result($tmp_products1);
                unset($tmp_products1);
                $tmp_products1 = $tmp_products1_1;
        }


        $tmp_products2 = db_query("
                SELECT SQL_NO_CACHE $sql_tbl[products].productid, $sql_tbl[products].cost_to_us FROM $sql_tbl[products]
                LEFT JOIN $sql_tbl[products_categories]
                        ON $sql_tbl[products_categories].productid = $sql_tbl[products].productid
                WHERE 
                        $sql_tbl[products_categories].categoryid = '$cat' AND $sql_tbl[products_categories].main='Y' AND $sql_tbl[products].cost_to_us <= '$product_info[cost_to_us]' AND $sql_tbl[products].productid != '$product_info[productid]' AND $sql_tbl[products].forsale='Y' AND $sql_tbl[products].avail > 0
                ORDER BY $sql_tbl[products].cost_to_us DESC LIMIT 3");

        if ($tmp_products2){
                $tmp_products2_1 = array();
                $tmp_counter = 0;
                while($p = db_fetch_array($tmp_products2)) {
                        $tmp_products2_1[$tmp_counter]["productid"] = $p["productid"];
                        $tmp_products2_1[$tmp_counter]["cost_to_us"] = $p["cost_to_us"];
                        $tmp_counter++;
                }
                db_free_result($tmp_products2);
                unset($tmp_products2);
                $tmp_products2 = $tmp_products2_1;
        }

        $tmp_products_arr = array();

        if (!empty($tmp_products1) && is_array($tmp_products1)){
                foreach ($tmp_products1 as $k => $v){
                        $tmp_products_arr[$k]["productid"] = $v["productid"];
                        $tmp_products_arr[$k]["cost_to_us"] = $v["cost_to_us"];

                        if ($v["cost_to_us"] > 0)
                                $kf = $product_info["cost_to_us"]/$v["cost_to_us"];

                        $tmp_products_arr[$k]["rate"] =  1 - $kf;
                }
        }

        $count_tmp_products = count($tmp_products_arr);

        if (!empty($tmp_products2) && is_array($tmp_products2)){
                foreach ($tmp_products2 as $k => $v){
                        $tmp_products_arr[$count_tmp_products]["productid"] = $v["productid"];
                        $tmp_products_arr[$count_tmp_products]["cost_to_us"] = $v["cost_to_us"];

                        if ($product_info["cost_to_us"] > 0)
                                $kf = $v["cost_to_us"]/$product_info["cost_to_us"];

                        $tmp_products_arr[$count_tmp_products]["rate"] = 1- $kf;
                        $count_tmp_products++;
                }
        }

	$similar_productids_arr = array();
        if (!empty($tmp_products_arr) && is_array($tmp_products_arr)){

                $tmp_products_arr = array_values(my_array_sort($tmp_products_arr, 'rate'));

		foreach ($tmp_products_arr as $k => $v){
			$similar_productids_arr[] = $v["productid"];			

			if ($k == "2")
				break;
		}
	}

	$similar_productids = implode(",", $similar_productids_arr);
	unset($similar_productids_arr);
###
##
#

	db_query("UPDATE $sql_tbl[products] SET similar_cron_generated_flag='Y', similar_productids='$similar_productids', similar_time='".time()."' WHERE productid='$product_info[productid]'");

	$cnt++;
	if ($cnt % 100 == 0) {
		func_flush(".");
		if($cnt % 5000 == 0) {
			func_flush("<br />\n");
		}
		func_flush();
	}

	usleep($usleep_time);
    }
    db_free_result($products);
}

$finished_at = time();

$duration = $finished_at - $started_at;
$duration = $duration/(60*60);
$duration = round($duration,1);

$subj = "Finish updating process of similar products";
$body = "Updated products: ".$cnt."\n";
$body .= "Started at: ".date("Y-m-d H:i:s", $started_at)."\n";
$body .= "Finished at: ".date("Y-m-d H:i:s", $finished_at)."\n";
$body .= "Duration: ".$duration." Hours\n";

func_send_simple_mail($config["Product_Page"]["similar_script_notification_email"], $subj, $body, "supplier.feeds@s3stores.com");

print"<br />Done!";
?>
