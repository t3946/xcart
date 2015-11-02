<?php
define("CIDEV_CRON_START", "CRON");

require "./top.inc.php";
require "./init.php";

ini_set('memory_limit', '512M');
set_time_limit(0);

$start_time = time();

// update  (D - Y) and (Y - N) products (update_search_index - forsale) to not index
db_query("UPDATE $sql_tbl[products] As P SET P.update_search_index = 'N' WHERE (P.forsale = 'Y' and P.update_search_index = 'D') or (P.forsale = 'N' and P.update_search_index = 'Y')");



$products = db_query($query="
SELECT 
	$sql_tbl[products].productid, $sql_tbl[products].productcode, $sql_tbl[products].product, $sql_tbl[products].update_search_index, $sql_tbl[products].upc, $sql_tbl[products].fulldescr, $sql_tbl[products].forsale, $sql_tbl[products].brandid, $sql_tbl[brands].brand, $sql_tbl[products_sf].sfid, $sql_tbl[storefronts].domain 
FROM 
	$sql_tbl[products] 
LEFT JOIN $sql_tbl[products_sf] ON $sql_tbl[products].productid = $sql_tbl[products_sf].productid
LEFT JOIN $sql_tbl[brands] ON $sql_tbl[products].brandid = $sql_tbl[brands].brandid
LEFT JOIN $sql_tbl[storefronts] ON $sql_tbl[products_sf].sfid = $sql_tbl[storefronts].storefrontid
WHERE 
	(($sql_tbl[products].update_search_index='Y' AND $sql_tbl[products].forsale='Y') OR ($sql_tbl[products].update_search_index='D' AND $sql_tbl[products].forsale='N'))
");
//print($query);

$total_items = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[products] WHERE ($sql_tbl[products].update_search_index='Y' AND $sql_tbl[products].forsale='Y') OR ($sql_tbl[products].update_search_index='D' AND $sql_tbl[products].forsale='N')");

$counter = 0;
$products_indexed_ok = 0;
$products_indexed_fail = 0;
$products_deleted_from_index_ok = 0;
$products_deleted_from_index_fail = 0;

while ($product = db_fetch_array($products)) {

	$counter++;
	if ($counter % 100 == 0) {
		func_flush(".");
		if($counter % 5000 == 0) {
			func_flush("<br />\n");
		}
		func_flush();
	}

	if (empty($product["domain"])){
		$product["domain"] = "www.artistsupplysource.com";
	}

	$data_json = "";
	$url = $config["ElasticSearch_options"]["es_url"].$product["domain"]."/product/".$product["productid"];

	if ($product["update_search_index"] == "Y"){

		// Delete at first
                $ch = curl_init($url);                                                                     
                curl_setopt($ch, CURLOPT_HTTPHEADER, array ("Accept: application/json"));
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result_json = curl_exec($ch);
                curl_close($ch);

	
		$data_arr["productname"] = $product["product"];
		$data_arr["sku"] = $product["productcode"];
		$data_arr["upc"] = $product["upc"];
		$data_arr["brand"] = $product["brand"];
		$product["fulldescr"] = str_replace("/r/n", " ", $product["fulldescr"]);
		$product["fulldescr"] = str_replace("\r\n", " ", $product["fulldescr"]);
		$data_arr["description"] = strip_tags($product["fulldescr"]);
		$data_json = json_encode($data_arr);

		$ch = curl_init($url);                                                                     
		curl_setopt($ch, CURLOPT_HTTPHEADER, array ("Accept: application/json")); 
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		$result_json = curl_exec($ch);
		curl_close($ch);
		$result = json_decode($result_json, true);
	
		if ($result["created"] == "1"){
			$products_indexed_ok++;
			db_query("UPDATE $sql_tbl[products] SET update_search_index='N' WHERE productid='$product[productid]'");
		} else {
			$products_indexed_fail++;
			db_query("UPDATE $sql_tbl[products] SET update_search_index='N' WHERE productid='$product[productid]'");
		}
	} 
	elseif ($product["update_search_index"] == "D"){

                $ch = curl_init($url);                                                                     
                curl_setopt($ch, CURLOPT_HTTPHEADER, array ("Accept: application/json"));
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result_json = curl_exec($ch);
                curl_close($ch);
                $result = json_decode($result_json, true);

		if ($result["found"] == "1"){
			$products_deleted_from_index_ok++;
			db_query("UPDATE $sql_tbl[products] SET update_search_index='N' WHERE productid='$product[productid]'");
		} else {
			$products_deleted_from_index_fail++;
			db_query("UPDATE $sql_tbl[products] SET update_search_index='N' WHERE productid='$product[productid]'");
		}
	}

        $current_time = time();
        $diff_time_in_mins = ($current_time - $start_time)/60;

        if ($diff_time_in_mins > $config["ElasticSearch_options"]["es_maximum_work_time_per_start_in_minutes"]){

                $rest_documents_to_index = $total_items - ($products_indexed_ok + $products_deleted_from_index_ok);

                $subj = "ES-robot statistics";
                $body = "
		Total products to index: $total_items 
		Products indexed 'ok': $products_indexed_ok 
		Products indexed 'fail': $products_indexed_fail 
		Products deleted from index 'ok': $products_deleted_from_index_ok 
		Products deleted from index 'fail': $products_deleted_from_index_fail 
		Rest documents to index: $rest_documents_to_index 
		Working time:  $diff_time_in_mins minutes";
		
		func_backprocess_log("ElasticSearch updates", $body);
//                func_send_simple_mail($config["ElasticSearch_options"]["es_report_email"], $subj, $body, "xcart@s3stores.com");
                break;
        }
}
        if ($diff_time_in_mins <= $config["ElasticSearch_options"]["es_maximum_work_time_per_start_in_minutes"] && $total_items>0){

                $rest_documents_to_index = $total_items - ($products_indexed_ok + $products_deleted_from_index_ok);

                $subj = "ES-robot statistics";
                $body = "
		Total products to index: $total_items 
		Products indexed 'ok': $products_indexed_ok 
		Products indexed 'fail': $products_indexed_fail 
		Products deleted from index 'ok': $products_deleted_from_index_ok 
		Products deleted from index 'fail': $products_deleted_from_index_fail 
		Rest documents to index: $rest_documents_to_index 
		Working time:  $diff_time_in_mins minutes";

		func_backprocess_log("ElasticSearch updates", $body);

//                func_send_simple_mail($config["ElasticSearch_options"]["es_report_email"], $subj, $body, "xcart@s3stores.com");
            
        }


print"<br />DONE!";

?>
