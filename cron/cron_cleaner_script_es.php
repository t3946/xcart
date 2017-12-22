<?php
define("CIDEV_CRON_START", "CRON");

require __DIR__ . DIRECTORY_SEPARATOR . "../www/top.inc.php";
require __DIR__ . DIRECTORY_SEPARATOR . "../www/init.php";

ini_set('memory_limit', '512M');
set_time_limit(0);

if (empty($config["cron_cleaner_script_es_launched"])){

	db_query("UPDATE $sql_tbl[config] SET value='Y' WHERE name='cron_cleaner_script_es_launched'");

	$start_time = time();

	$storefronts[0]["storefrontid"] = 0;
	$storefronts[0]["domain"] = "www.artistsupplysource.com";

	$sitemap_bare_keywords_file = "sitemap_bare_keywords.csv";
	$sitemap_keywords_file = "sitemap_keywords.csv";
	$count_found_strs = 0;

	foreach ($storefronts as $sf_id => $v){

		$path_to_dir = $xcart_dir."/files/".$v["domain"]."/";
		$full_path_to_sitemap_bare_keywords_file = $path_to_dir.$sitemap_bare_keywords_file;
		$full_path_to_sitemap_keywords_file = $path_to_dir.$sitemap_keywords_file;

		if (is_file($full_path_to_sitemap_bare_keywords_file) && !is_file($full_path_to_sitemap_keywords_file)){

			$handle = @fopen($full_path_to_sitemap_bare_keywords_file, "r");
			if ($handle) {

			    $found_strs = "";

			    while (($str = fgets($handle, 4096)) !== false) {

				$str = preg_replace("/[^0-9a-zA-Z\.\'\-]/S", " ", $str);
				$str = trim($str);
				$str = strtolower($str);

			        $data_arr["_source"] = "*._id";
			        $data_arr["query"]["dis_max"]["queries"][0]["query_string"]["query"] = $str;
			        $data_arr["query"]["dis_max"]["queries"][0]["query_string"]["fields"] = array("productname.productname_original^1.5","sku","upc","brand.brand_original^0.5","description.description_original");
			        $data_arr["query"]["dis_max"]["queries"][1]["query_string"]["query"] = $str;
			        $data_arr["query"]["dis_max"]["queries"][1]["query_string"]["analyzer"] = "snowball";
			        $data_arr["query"]["dis_max"]["queries"][1]["query_string"]["fields"] = array("productname.productname","sku","upc","brand.brand","description.description");
			        $data_arr["query"]["dis_max"]["queries"][1]["query_string"]["fields"] = array("productname.productname^1.5","sku","upc","brand.brand^0.5","description.description");

			        $data_json = json_encode($data_arr);
			
				$url = $config["ElasticSearch_options"]["es_url"].$v["domain"]."/product/_search";
			        $ch = curl_init($url);
			        curl_setopt($ch, CURLOPT_HTTPHEADER, array ("Accept: application/json"));
			        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
			        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			        $result_json = curl_exec($ch);
			        curl_close($ch);
			        $result = json_decode($result_json, true);

			        if ($result["hits"]["total"] > 0){

					$found_strs .= $str."\r\n";

		                        $count_found_strs++;
                               		func_flush(".");
	                                if($count_found_strs % 5000 == 0) {
               		                        func_flush("<br />\n");
                               		}

	                                func_flush();
				}
			    }

			    if (!feof($handle)) {
			        echo "Error: unexpected fgets() fail\n";
			    }
			    fclose($handle);

			    if (!empty($found_strs)){
				$fp = fopen($full_path_to_sitemap_keywords_file, 'w+');
				fwrite($fp, $found_strs);
				fclose($fp);
			    }
			}
		}
	}

	db_query("UPDATE $sql_tbl[config] SET value='' WHERE name='cron_cleaner_script_es_launched'");
	print"<br />DONE!";
} else {
	print"<br />It is working already!";
}
?>
