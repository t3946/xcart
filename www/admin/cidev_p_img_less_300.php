<?php

require "./auth.php";

if (empty($login))	func_header_location("error_message.php?antibot_error");

if (!empty($login))	require $xcart_dir."/include/security.php";

x_load("debug");

$products = db_query("
        SELECT 
                $sql_tbl[products].productid, $sql_tbl[products].product, $sql_tbl[products].provider
        FROM $sql_tbl[products] 

        LEFT JOIN $sql_tbl[products_categories] 
        ON $sql_tbl[products_categories].productid=$sql_tbl[products].productid 

        LEFT JOIN $sql_tbl[categories] 
        ON $sql_tbl[categories].categoryid=$sql_tbl[products_categories].categoryid  

        LEFT JOIN $sql_tbl[products_sf] 
        ON $sql_tbl[products_sf].productid = $sql_tbl[products].productid

        LEFT JOIN $sql_tbl[images_P] 
        ON $sql_tbl[images_P].id = $sql_tbl[products].productid

        WHERE 
                $sql_tbl[products_sf].sfid='$current_storefront_info[storefrontid]' 
		AND $sql_tbl[products].forsale='Y'
		AND ($sql_tbl[images_P].image_x < '300' OR $sql_tbl[images_P].image_y < '300')
        GROUP BY $sql_tbl[products].productid
        ORDER BY $sql_tbl[products].provider ASC
");


while ($product = db_fetch_array($products)) {
	$provider = $product["provider"];
	print($provider."\thttp://".$current_storefront_info["domain"]."/provider/product_modify.php?productid=".$product["productid"]."\r\n");
}

?>
