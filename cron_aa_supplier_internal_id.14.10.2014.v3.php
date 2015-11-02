<?php

//require "./auth.php";

define("CIDEV_CRON_START", "CRON");

require "./top.inc.php";
require "./init.php";

set_time_limit(0);

x_load("mail");

$started_at = time();

func_flush(".");

$f_ids = func_query("SELECT f_id FROM $sql_tbl[cidev_filters] WHERE storefrontid='12'");

if (!empty($f_ids)){
	foreach ($f_ids as $k => $v){

		$f_id = $v["f_id"];

		$fv_ids = func_query("SELECT fv_id FROM $sql_tbl[cidev_filter_values] WHERE f_id='$f_id'");
		if (!empty($fv_ids)){
			foreach ($fv_ids as $kk => $vv){

				$fv_id = $vv["fv_id"];

				db_query("DELETE FROM $sql_tbl[cidev_filter_products] WHERE fv_id='$fv_id'");
			}
		}

		db_query("DELETE FROM $sql_tbl[cidev_filter_values] WHERE f_id='$f_id'");
		db_query("DELETE FROM $sql_tbl[cidev_filters] WHERE f_id='$f_id'");
	}
}

//func_print_r($f_ids);

//die("123");


$Parsed_IDs = 0;
$Granular_product_pages_reached = 0;
$count_marked_as_in_stock_products = 0;
$count_marked_as_out_of_stock_products = 0;
$count_updated_products = 0;
$New_products_found = 0;

$count_cycles = 50000;
$cnt = 0;
$sleep_time = 7; //  seconds


$parse_err = array();
$retrieve_err = array();

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);



for ($i = 1; $i <= $count_cycles; $i++) {

###
//$i = "39257";
###

	$url = "http://www.aajewelry.com/quickshop/product/view/id/".$i."/?keepThis=true&width=650&height=500&modal=false";

        curl_setopt($ch, CURLOPT_URL, $url);
        $output = curl_exec($ch);
        if (curl_errno($ch) != 0 || curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
                $retrieve_err[] = $i;
                continue;
        }

	$Parsed_IDs++;

	$output = str_replace(array("\n", "\r"), '', $output);

        $sku_quickview = func_parse_sku_quickview($output);
	
        if (!$sku_quickview) {
                $parse_err[] = $i;
                continue;
        }

	$first_letters_in_sku_quickview = substr($sku_quickview, 0, 2);
	$first_letters_in_sku_quickview = strtoupper($first_letters_in_sku_quickview);

	if ($first_letters_in_sku_quickview == "M-"){
		continue;
	}

	$Granular_product_pages_reached++;

	$productcode = 'AAJ-'.$sku_quickview;

	$product_info_arr = func_query_first("SELECT productid, productcode, forsale, avail, r_avail, eta_date_mm_dd_yyyy, cost_to_us, update_search_index FROM $sql_tbl[products] WHERE productcode='".addslashes($productcode)."'");

	if (empty($product_info_arr)){
		$New_products_found++;
		continue;
	}


	$productcode = $product_info_arr["productcode"];
	$productid = $product_info_arr["productid"];

	$current_forsale = $product_info_arr["forsale"];
	$current_r_avail = $product_info_arr["r_avail"];
//	$current_avail = $product_info_arr["avail"];
	$current_cost_to_us = $product_info_arr["cost_to_us"];
	$current_eta_date_mm_dd_yyyy = $product_info_arr["eta_date_mm_dd_yyyy"];

	$new_forsale = "Y";
	$new_r_avail = $current_r_avail;
//	$new_avail = $current_avail;
	$new_cost_to_us = $current_cost_to_us;
	$new_eta_date_mm_dd_yyyy = $current_eta_date_mm_dd_yyyy;
	$new_eta_date_mm_dd_yyyy_time = "";

	$product_is_updated = false;
	$marked_as_out_of_stock_products = false;
	$marked_as_in_stock_products = false;


	$parsed_cost_to_us = func_parse_cost_to_us($output);

	if (!empty($parsed_cost_to_us)){
		$new_cost_to_us = $parsed_cost_to_us;
	}


	$add_to_cart_button = func_parse_add_to_cart_button($output);

	if ($add_to_cart_button){
		$new_r_avail = 1000;
//		$new_avail = $new_r_avail;
		$new_eta_date_mm_dd_yyyy = "";
	}

	if (!$add_to_cart_button || empty($new_cost_to_us) || $new_cost_to_us == "0.00") {
                $new_r_avail = 0;
//                $new_avail = $new_r_avail;

		$new_cost_to_us = $current_cost_to_us;

		$new_eta_date_mm_dd_yyyy_time = time() + 60*60*24*35;
		$new_eta_date_mm_dd_yyyy = date("m/d/Y", $new_eta_date_mm_dd_yyyy_time);
	}


	if ($new_r_avail == "0"){
		if ($current_r_avail > 0){
			$marked_as_out_of_stock_products = true;
		}
	} else {
		if ($current_r_avail == 0){
			$marked_as_in_stock_products = true;
		}
	}

	if ($new_forsale != $current_forsale || $new_eta_date_mm_dd_yyyy != $current_eta_date_mm_dd_yyyy || $current_r_avail != $new_r_avail || $current_cost_to_us != $new_cost_to_us){

		$update_search_index = 'Y';
		if ($new_forsale == 'N' && $product_info_arr["update_search_index"] == "N"){
			$update_search_index = 'D';
		}

		db_query("UPDATE $sql_tbl[products] SET supplier_internal_id='$i', r_avail='$new_r_avail', eta_date_mm_dd_yyyy='$new_eta_date_mm_dd_yyyy', forsale='$new_forsale', update_search_index='$update_search_index', supplier_internal_id_last_parsed_update='".time()."', supplier_internal_id_last_parsed='".time()."', cost_to_us='$new_cost_to_us' WHERE productid='$productid'");
		$product_is_updated = true;
	} else {
		db_query("UPDATE $sql_tbl[products] SET supplier_internal_id_last_parsed='".time()."' WHERE productid='$productid'");
	}

	if ($product_is_updated){
		$count_updated_products++;
	}

	if ($marked_as_out_of_stock_products){
		$count_marked_as_out_of_stock_products++;
	}

	if ($marked_as_in_stock_products){
		$count_marked_as_in_stock_products++;
	}


//$output .= '<div class="short-description"><div class="std"><table><tr><td class="key">.<br /></td><td class="value">No Refunds, Exchange Only</td></tr><br/><tr><td class="key">,--Stone</td><td class="value">Diamond</td></tr><br/><tr><td class="key">Metal</td><td class="value">14K White</td></tr><br/><tr><td class="key">Toe Ring Size</td><td class="value">1.50, 1.75, 2.00, 2.25, 2.50, 2.75, 3.00, 3.25, 3.50, 3.75, 4.00, 4.25, 4.50, 4.75 or 5.00</td></tr></table></div></div>';


        $filters = func_parse_filters($output);

//func_print_r($filters);
//die("asd");

	if (!empty($filters) && is_array($filters)){
		foreach ($filters as $filter){

			$f_name = $filter["f_name"];

			$f_id = func_query_first_cell("SELECT f_id FROM $sql_tbl[cidev_filters] WHERE f_name='".addslashes($f_name)."' AND storefrontid='12'");

			if (empty($f_id)){
				db_query("INSERT INTO $sql_tbl[cidev_filters] (f_name, storefrontid) VALUES ('".addslashes($f_name)."', '12')");
				$f_id = db_insert_id();
			}

			foreach ($filter["fv_name_arr"] as $fv_name){

				$fv_id = func_query_first_cell("SELECT fv_id FROM $sql_tbl[cidev_filter_values] WHERE f_id='$f_id' AND fv_name='".addslashes($fv_name)."'");

				if (empty($fv_id)){
					db_query("INSERT INTO $sql_tbl[cidev_filter_values] (f_id, fv_name) VALUES ('$f_id', '".addslashes($fv_name)."')");
					$fv_id = db_insert_id();
				}

				$fp_id = func_query_first_cell("SELECT fp_id FROM $sql_tbl[cidev_filter_products] WHERE fv_id='$fv_id' AND productid='$productid'");
				if (empty($fp_id)){
					db_query("INSERT INTO $sql_tbl[cidev_filter_products] (fv_id, productid) VALUES ('$fv_id', '$productid')");
				}
			}
		}
	}


	$cnt++;
	if ($cnt % 10 == 0) {
		func_flush(".");
		if($cnt % 500 == 0) {
			func_flush("<br />\n");
		}
		func_flush();
	}

	sleep($sleep_time);
}

curl_close($ch);


$count_discontinued_products = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[products] WHERE supplier_internal_id='' AND productcode LIKE 'AAJ-%'");

db_query("UPDATE $sql_tbl[products] SET forsale='N' WHERE supplier_internal_id='' AND productcode LIKE 'AAJ-%'");


$finished_at = time();

$duration = $finished_at - $started_at;
$duration = $duration/(60*60);
$duration = round($duration,1);

$subj = "A&A Jewelry base parsing process finished...";
$body = "Parsed ID's: ".$Parsed_IDs."\n";
$body .= "Granular product pages reached: ".$Granular_product_pages_reached."\n";
$body .= "Marked 'In stock': ".$count_marked_as_in_stock_products."\n";
$body .= "Marked 'Out of stock': ".$count_marked_as_out_of_stock_products."\n";
$body .= "Marked as discontinued: ".$count_discontinued_products."\n";
$body .= "New products found: ".$New_products_found."\n";
$body .= "Started at: ".date("Y-m-d H:i:s", $started_at)."\n";
$body .= "Finished at: ".date("Y-m-d H:i:s", $finished_at)."\n";
$body .= "Duration: ".$duration." Hours\n";

func_send_simple_mail("feeds@s3stores.com", $subj, $body, "xcart@s3stores.com");
//func_send_simple_mail("xcartmaster@gmail.com", $subj, $body, "xcart@s3stores.com");

print"<br />Done!";
?>
