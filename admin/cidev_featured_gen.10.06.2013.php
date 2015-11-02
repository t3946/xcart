<?php
require "./auth.php";
require $xcart_dir."/include/security.php";

x_load("debug", "product");

if ($mode != "start"){
	die("'mode' is incorrect");
}

$current_storefront = func_get_storefront_info($storefrontid);

//func_print_r($current_storefront);

if (empty($current_storefront)){
	die("Check 'storefrontid'");
}

print($current_storefront["domain"] . "<br />");

$months_time = time() - 60*60*24*90; // <------ 90 days (3 months)

$query = "
	SELECT 
		$sql_tbl[order_details].productid 
	FROM 
		$sql_tbl[order_details] 

	LEFT JOIN $sql_tbl[orders] 
	ON  $sql_tbl[order_details].orderid = $sql_tbl[orders].orderid 

	LEFT JOIN $sql_tbl[products] 
	ON $sql_tbl[order_details].productid = $sql_tbl[products].productid

        LEFT JOIN $sql_tbl[products_sf] 
        ON $sql_tbl[products_sf].productid = $sql_tbl[products].productid

	WHERE 
		$sql_tbl[orders].date >= '$months_time'
		AND $sql_tbl[products].forsale='Y'
                AND $sql_tbl[products_sf].sfid='$current_storefront[storefrontid]' 
";

$productids_last_months = func_query($query);

//func_print_r($query);

$select_product_count = 60;

//func_print_r($productids_last_months);

$product_number = 0;
$product_ids = array();

if (!empty($productids_last_months) && is_array($productids_last_months)){

	$productids_last_months_ids = array();
	foreach($productids_last_months as $k => $v){
        	$productids_last_months_ids[] = $v["productid"];
	}

	$productids_last_months_ids = array_count_values($productids_last_months_ids);
	arsort($productids_last_months_ids);

//func_print_r($productids_last_months_ids);

        foreach($productids_last_months_ids as $k => $v){
                $product_ids[] = $k;
                $product_number++;

                if ($product_number == $select_product_count){
                	break;
                }
        }

//func_print_r($product_ids);

}


$add_products_count = $select_product_count - $product_number;

if ($add_products_count > 0){

//func_print_r($add_products_count);

	$query = "
        SELECT 
                $sql_tbl[products].productid 
        FROM 
                $sql_tbl[products] 

        LEFT JOIN $sql_tbl[products_sf] 
        ON $sql_tbl[products_sf].productid = $sql_tbl[products].productid

        LEFT JOIN $sql_tbl[products_categories] 
        ON $sql_tbl[products_categories].productid=$sql_tbl[products].productid 

        LEFT JOIN $sql_tbl[categories] 
        ON $sql_tbl[categories].categoryid=$sql_tbl[products_categories].categoryid  

        LEFT JOIN $sql_tbl[pricing] 
        ON $sql_tbl[products].productid = $sql_tbl[pricing].productid

        LEFT JOIN $sql_tbl[quick_flags] 
        ON $sql_tbl[products].productid = $sql_tbl[quick_flags].productid

        LEFT JOIN $sql_tbl[images_T] 
        ON $sql_tbl[images_T].id = $sql_tbl[products].productid

        WHERE 
                $sql_tbl[products].forsale='Y'
                AND $sql_tbl[products_sf].sfid='$current_storefront[storefrontid]' 
                AND $sql_tbl[images_T].id != ''
                AND $sql_tbl[products].productid != ''
		AND $sql_tbl[products].productid NOT IN ('".implode("','", $product_ids)."')
        GROUP BY 
		$sql_tbl[products].productid
	LIMIT 
		$add_products_count
	";

	$add_product_ids = func_query($query);

//func_print_r($query, $add_product_ids);

	if (!empty($add_product_ids) && is_array($add_product_ids)){
		foreach ($add_product_ids as $k => $v){
			$product_ids[] = $v["productid"];
		}
	}
}

if (!empty($product_ids) && is_array($product_ids)){

//func_print_r($product_ids);

	db_query("DELETE FROM $sql_tbl[featured_products] WHERE storefrontid='$current_storefront[storefrontid]'");

	foreach ($product_ids as $k => $v){
		db_query("INSERT INTO $sql_tbl[featured_products] (productid, storefrontid) VALUES ('$v', '$current_storefront[storefrontid]')");
	}

	print"Done!";
}


print"<br />End of script.";
?>
