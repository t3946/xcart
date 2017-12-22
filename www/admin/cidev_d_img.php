<?php

require "./auth.php";

if (empty($login))	func_header_location("error_message.php?antibot_error");

if (!empty($login))	require $xcart_dir."/include/security.php";

x_load("debug");

//func_print_r($current_storefront_info);


//id => productid
//imageid => type=D&id=imageid;


$products = func_query("
        SELECT 
                $sql_tbl[products].productid, $sql_tbl[products].product, $sql_tbl[products].provider
        FROM $sql_tbl[products] 

        LEFT JOIN $sql_tbl[products_categories] 
        ON $sql_tbl[products_categories].productid=$sql_tbl[products].productid 

        LEFT JOIN $sql_tbl[categories] 
        ON $sql_tbl[categories].categoryid=$sql_tbl[products_categories].categoryid  

        LEFT JOIN $sql_tbl[products_sf] 
        ON $sql_tbl[products_sf].productid = $sql_tbl[products].productid

        LEFT JOIN $sql_tbl[images_D] 
        ON $sql_tbl[images_D].id = $sql_tbl[products].productid

        WHERE 
                $sql_tbl[products_sf].sfid='$current_storefront_info[storefrontid]' 
		AND $sql_tbl[products].forsale='Y'
		AND $sql_tbl[images_D].image_x < '300' AND $sql_tbl[images_D].image_y < '300'
        GROUP BY $sql_tbl[products].productid
        ORDER BY $sql_tbl[products].provider ASC
");


//func_print_r($products);

//print("<B>" . $current_storefront_info["domain"]. "</B><br /><br />");

if (!empty($products) && is_array($products)){

	$provider = "";

	foreach ($products as $k => $v){

		if ($v["provider"] != $provider){

			$provider = $v["provider"];

//			print("<B>" . $provider . "</B><br />" );

		}

//		print("<a target='_blank' href='product_modify.php?productid=".$v["productid"]."'>". $v["product"] ."</a><br />");
		print($v["provider"]."\thttp://".$current_storefront_info["domain"]."/provider/product_modify.php?productid=".$v["productid"]."\r\n");

	}

}
else {
	print("All is OK!");
}

?>
