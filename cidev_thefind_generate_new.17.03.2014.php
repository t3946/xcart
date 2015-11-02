<?php
define("CIDEV_CRON_START", "CRON");

require "./top.inc.php";
require "./init.php";

define("FROOGLE_TAIL", '...');
define("FROOGLE_TAIL_LEN", strlen(constant("FROOGLE_TAIL")));
define('FROOGLE_MAX_DESCRIPTION_LENGTH', 10 * 1024); //The content in an attribute in an item exceeds 10 KB.

define('EXCLUDE_CATEGORYID_BRANCH', 5099);

ini_set('memory_limit', '512M');
set_time_limit(0);

x_load('backoffice','files','taxes', 'froogle', 'product');

$started_at = time();

$subj = "Start theFind process";
$body = "Started at: ".date("Y-m-d H:i:s", $started_at)."\n";
$to = $config["Froogle"]["froogle_cron_email"];
$from = "orders@s3stores.com";
func_send_simple_mail($to, $subj, $body, $from);

$cidev_storefronts = $storefronts;

if (!empty($cidev_storefronts) && is_array($cidev_storefronts)){

	foreach ($cidev_storefronts as $storefrontid => $sf_info){
		$cidev_storefronts[$storefrontid] = func_get_storefront_info($storefrontid);
	}

	$cidev_storefronts[0] = func_get_storefront_info(0);

	$take_products_per_cycle = $config["Froogle"]["froogle_products_per_query"];

	$usleep_time1 = $config["Froogle"]["froogle_interval_queries"] * 1000;
	$usleep_time2 = $config["Froogle"]["froogle_interval_block_queries"] * 1000;

	$froogle_file_name = "thefind.txt";

	foreach ($cidev_storefronts as $storefrontid => $sf_info){

	        $query_count = "SELECT SQL_NO_CACHE COUNT(*) 
                	FROM $sql_tbl[products] 
        	        INNER JOIN $sql_tbl[products_sf] 
	                ON $sql_tbl[products].productid= $sql_tbl[products_sf].productid
			INNER JOIN $sql_tbl[products_categories]
			ON $sql_tbl[products_categories].productid = $sql_tbl[products].productid
			INNER JOIN $sql_tbl[categories]
			ON $sql_tbl[categories].categoryid = $sql_tbl[products_categories].categoryid
	                WHERE 
	                $sql_tbl[products_sf].sfid = $storefrontid
			AND $sql_tbl[categories].avail = 'Y'
			AND $sql_tbl[products_categories].main = 'Y'
			AND $sql_tbl[products].forsale = 'Y'";

		$count_products = func_query_first_cell($query_count);

		if (empty($count_products)) continue;

	        if ($sf_info['prefix'] == "MAIN_SF_PREFIX"){
	                $froogle_file = $files_dir_name . DIRECTORY_SEPARATOR . "AR-" . $froogle_file_name;
               		$sf_info['prefix'] = "AR-";
	        } else {
               		$froogle_file = $files_dir_name . DIRECTORY_SEPARATOR . $sf_info["domain"] . DIRECTORY_SEPARATOR . $sf_info['prefix'] . $froogle_file_name;

	                $cidev_get_files_location = $files_dir_name . DIRECTORY_SEPARATOR . $sf_info["domain"];

               		if (!file_exists($cidev_get_files_location)){
	                        func_mkdir($cidev_get_files_location);
			}
	        }

		$fp = func_fopen($froogle_file, 'w', true);

		if ($fp === false) continue;

		$row = GetTheFindOneRow(0);

		if (!empty($row)){
			fputs($fp, utf8_encode($row));
		}

		$count_cycles = ceil($count_products/$take_products_per_cycle);
		$cnt = 0;

		for ($i = 0; $i < $count_cycles; $i++) {

                        $limit_from = $i * $take_products_per_cycle;
                        $limit = 'LIMIT ' . $limit_from . ', ' . $take_products_per_cycle;

			$query_products = "SELECT SQL_NO_CACHE $sql_tbl[products].productid 
	                        FROM $sql_tbl[products] 
	                        INNER JOIN $sql_tbl[products_sf] 
        	                ON $sql_tbl[products].productid= $sql_tbl[products_sf].productid
                	        INNER JOIN $sql_tbl[products_categories]
                        	ON $sql_tbl[products_categories].productid = $sql_tbl[products].productid
	                        INNER JOIN $sql_tbl[categories]
        	                ON $sql_tbl[categories].categoryid = $sql_tbl[products_categories].categoryid
	                        WHERE 
        	                $sql_tbl[products_sf].sfid = $storefrontid
                	        AND $sql_tbl[categories].avail = 'Y'
                        	AND $sql_tbl[products_categories].main = 'Y'
	                        AND $sql_tbl[products].forsale = 'Y'
				LIMIT $limit_from, $take_products_per_cycle"; 

			$products = db_query($query_products);

			while ($product = db_fetch_array($products)){

				$row = GetTheFindOneRow($product["productid"]);

				if (!empty($row)){
					fputs($fp, iconv("UTF-8", "UTF-8//IGNORE", utf8_encode($row))."\n");
				}

			        $cnt++;
			        if ($cnt % 100 == 0) {
			                func_flush(".");
			                if($cnt % 5000 == 0) {
			                        func_flush("<br />\n");
			                }
			                func_flush();
			        }

				usleep($usleep_time1); 
			}
			db_free_result($products);
		}

		fclose($fp);

		usleep($usleep_time2);
	}
}

$finished_at = time();

$duration = $started_at - $finished_at;
$duration = $duration/(60*60);
$duration = round($duration,1);

$subj = "Finish theFind process";
$body = "Started at: ".date("Y-m-d H:i:s", $started_at)."\n";
$body .= "Finished at: ".date("Y-m-d H:i:s", $finished_at)."\n";
$body .= "Duration: ".$duration." Hours\n";
func_send_simple_mail($to, $subj, $body, $from);

die("DONE!");
?>
