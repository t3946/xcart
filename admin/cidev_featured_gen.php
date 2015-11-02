<?php
require "./auth.php";
require $xcart_dir."/include/security.php";

x_load("debug", "product");

if ($mode != "start"){
	die("'mode' is incorrect");
}

$current_storefront_arr = func_get_storefront_info($storefrontid);

//func_print_r($current_storefront_arr);

if (empty($current_storefront_arr)){
	die("Check 'storefrontid'");
}

print($current_storefront_arr["domain"] . "<br />");

$months_time = time() - 60*60*24*90; // <------ 90 days (3 months)

$query = "
	SELECT 
		$sql_tbl[order_details].productid, $sql_tbl[products].product
	FROM 
		$sql_tbl[order_details] 

	LEFT JOIN $sql_tbl[orders] 
	ON  $sql_tbl[order_details].orderid = $sql_tbl[orders].orderid 

	LEFT JOIN $sql_tbl[products] 
	ON $sql_tbl[order_details].productid = $sql_tbl[products].productid

        LEFT JOIN $sql_tbl[products_sf] 
        ON $sql_tbl[products_sf].productid = $sql_tbl[products].productid

        LEFT JOIN $sql_tbl[images_T] 
        ON $sql_tbl[images_T].id = $sql_tbl[products].productid

	WHERE 
		$sql_tbl[orders].date >= '$months_time'
		AND $sql_tbl[products].forsale='Y'
		AND $sql_tbl[images_T].id != ''
		AND ($sql_tbl[orders].cb_status='P' OR $sql_tbl[orders].cb_status='O')
                AND $sql_tbl[products_sf].sfid='$current_storefront_arr[storefrontid]' 
";

$productids_last_months = func_query($query);

//func_print_r($query);

$select_product_count = 60;

//func_print_r($productids_last_months);

$product_number = 0;
$product_ids = array();
$short_name_arr = array();

if (!empty($productids_last_months) && is_array($productids_last_months)){

	foreach ($productids_last_months as $k => $v){

		if (strpos($v["product"], ":") !== false){
			$short_name_explode = explode(":", $v["product"]);
			$short_name = array_shift($short_name_explode);
		}
		else {
			$short_name = $v["product"];
		}

		$productids_last_months[$k]["short_name"] = $short_name;
	}

//func_print_r($productids_last_months);

        foreach ($productids_last_months as $k => $v){
		$short_name = $v["short_name"];
		$productids_last_months[$k]["checked_short_name"] = "Y";

		foreach ($productids_last_months as $kk => $vv){
			if ($productids_last_months[$kk]["checked_short_name"] != "Y" && ($short_name == $productids_last_months[$kk]["short_name"])){
				unset($productids_last_months[$kk]);
			}
		}


        }

        foreach ($productids_last_months as $k => $v){

		if (!empty($v["short_name"])){
			$short_name_arr[] = addslashes($v["short_name"]);
		}

                if (empty($v["product"])){
                        unset($productids_last_months[$k]);
                }
        }


//func_print_r($productids_last_months, $short_name_arr);

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

	$used_cats = array();

	if (!empty($productids_last_months) && is_array($productids_last_months)){
		foreach ($productids_last_months as $k => $v){
			$used_cats[] = func_query_first_cell("SELECT categoryid FROM $sql_tbl[products_categories] WHERE productid='$v[productid]'");
		}
	}

	if (!empty($used_cats)){
		$used_cats = array_unique($used_cats);
	}

	$current_storefront = $current_storefront_arr["storefrontid"];
	require $xcart_dir."/include/categories.php";

	if (!empty($all_categories) && is_array($all_categories)){
		foreach ($all_categories as $k => $v){
			if (!in_array($v["categoryid"], $used_cats)){

				$query = "
					SELECT 
						$sql_tbl[products].productid, $sql_tbl[products].product
					FROM $sql_tbl[products]
					
					LEFT JOIN $sql_tbl[products_categories]
					ON $sql_tbl[products_categories].productid=$sql_tbl[products].productid

				        LEFT JOIN $sql_tbl[categories] 
				        ON $sql_tbl[categories].categoryid=$sql_tbl[products_categories].categoryid  

				        LEFT JOIN $sql_tbl[pricing] 
				        ON $sql_tbl[products].productid = $sql_tbl[pricing].productid

				        LEFT JOIN $sql_tbl[quick_flags] 
				        ON $sql_tbl[products].productid = $sql_tbl[quick_flags].productid

				        LEFT JOIN $sql_tbl[products_sf] 
				        ON $sql_tbl[products_sf].productid = $sql_tbl[products].productid

				        LEFT JOIN $sql_tbl[images_T] 
				        ON $sql_tbl[images_T].id = $sql_tbl[products].productid

				        WHERE 
				                $sql_tbl[products].forsale='Y' 
				                AND $sql_tbl[images_T].id != ''
				                AND $sql_tbl[categories].avail = 'Y'";


					if (!empty($short_name_arr) && is_array($short_name_arr)){
						foreach ($short_name_arr as $kk => $vv){
					                $query .= "AND $sql_tbl[products].product NOT LIKE '".$vv.'%'."'";
						}
					}

					$query .= "
				                AND $sql_tbl[products].productid != ''
				                AND $sql_tbl[products].productid NOT IN ('".implode("','",$product_ids)."') 
				                AND $sql_tbl[products_sf].sfid='$current_storefront' 
				        GROUP BY $sql_tbl[products].productid
					LIMIT 1
				";
//func_print_r($query);

				$additioanl_product = func_query_first($query);

				if (!empty($additioanl_product) && is_array($additioanl_product)){

			                if (strpos($additioanl_product["product"], ":") !== false){
        	        		        $short_name_explode = explode(":", $additioanl_product["product"]);
			                        $short_name = array_shift($short_name_explode);
		        	        }
                			else {
		                        	$short_name = $additioanl_product["product"];
			                }

					if ($short_name != ""){
						$short_name_arr[] = addslashes($short_name);
					}
					$product_ids[] = $additioanl_product["productid"];
					$used_cats[] = $v["categoryid"];
					$product_number++;

			                if ($product_number == $select_product_count){
			                        break;
			                }
//func_print_r("additioanl_product:", $additioanl_product);
				}
			}
		}
	}
//func_print_r($used_cats, $all_categories);
//func_print_r($used_cats, $cats);
}

//func_print_r($product_ids);

$add_products_count = $select_product_count - $product_number;

if ($add_products_count > 0){

//func_print_r($add_products_count);

	$query = "
        SELECT 
                $sql_tbl[products].productid, $sql_tbl[products].product
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
                AND $sql_tbl[products_sf].sfid='$current_storefront_arr[storefrontid]' 
                AND $sql_tbl[images_T].id != ''
                AND $sql_tbl[categories].avail = 'Y'";

                if (!empty($short_name_arr) && is_array($short_name_arr)){
	                foreach ($short_name_arr as $kk => $vv){
        	                $query .= "AND $sql_tbl[products].product NOT LIKE '".$vv.'%'."'";
                        }
                }

	$query .= "
                AND $sql_tbl[products].productid != ''
		AND $sql_tbl[products].productid NOT IN ('".implode("','", $product_ids)."')
        GROUP BY 
		$sql_tbl[products].productid
	LIMIT 
		500
	";

	$add_product_ids = func_query($query);


	if (!empty($add_product_ids) && is_array($add_product_ids)){
	        foreach ($add_product_ids as $k => $v){

        	        if (strpos($v["product"], ":") !== false){
                	        $short_name_explode = explode(":", $v["product"]);
                        	$short_name = array_shift($short_name_explode);
	                }
        	        else {
                	        $short_name = $v["product"];
	                }

        	        $add_product_ids[$k]["short_name"] = $short_name;
	        }
	}

//func_print_r($productids_last_months);

	if (!empty($add_product_ids) && is_array($add_product_ids)){
	        foreach ($add_product_ids as $k => $v){
        	        $short_name = $v["short_name"];
                	$add_product_ids[$k]["checked_short_name"] = "Y";

	                foreach ($add_product_ids as $kk => $vv){
        	                if ($add_product_ids[$kk]["checked_short_name"] != "Y" && ($short_name == $add_product_ids[$kk]["short_name"])){
                	                unset($add_product_ids[$kk]);
                        	}
	                }
        	}
	}

	if (!empty($add_product_ids) && is_array($add_product_ids)){
	        foreach ($add_product_ids as $k => $v){

        	        if (!empty($v["short_name"])){
                	        $short_name_arr[] = addslashes($v["short_name"]);
	                }

        	        if (empty($v["product"])){
	                        unset($add_product_ids[$k]);
        	        }
	        }
	}

//func_print_r($query);
//func_print_r($add_product_ids);

	if (!empty($add_product_ids) && is_array($add_product_ids)){
		foreach ($add_product_ids as $k => $v){
			$product_ids[] = $v["productid"];
			$product_number++;

                        if ($product_number == $select_product_count){
	                        break;
                        }
		}
	}
}

$add_products_count = $select_product_count - $product_number;

if ($add_products_count > 0){

//func_print_r($add_products_count);

        $query = "
        SELECT 
                $sql_tbl[products].productid, $sql_tbl[products].product
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
                AND $sql_tbl[products_sf].sfid='$current_storefront_arr[storefrontid]' 
                AND $sql_tbl[images_T].id != ''
                AND $sql_tbl[products].productid != ''
                AND $sql_tbl[products].productid NOT IN ('".implode("','", $product_ids)."')
        GROUP BY 
                $sql_tbl[products].productid
        LIMIT 
                $add_products_count
        ";

        $add_product_ids = func_query($query);

//func_print_r($add_product_ids);

        if (!empty($add_product_ids) && is_array($add_product_ids)){
                foreach ($add_product_ids as $k => $v){
                        $product_ids[] = $v["productid"];
                }
        }
}

if (!empty($product_ids) && is_array($product_ids)){

//func_print_r($current_storefront_arr, $product_ids);
//die();

	db_query("DELETE FROM $sql_tbl[featured_products] WHERE storefrontid='$current_storefront_arr[storefrontid]'");

	foreach ($product_ids as $k => $v){

#
##
###
		db_query("DELETE FROM $sql_tbl[featured_products] WHERE productid='$v' AND categoryid='0'");
###
##
#
		db_query("INSERT INTO $sql_tbl[featured_products] (productid, storefrontid, product_order) VALUES ('$v', '$current_storefront_arr[storefrontid]', '$k')");
	}

	print"Done!";
}


print"<br />End of script.";
print"<br /><br /><a href='categories.php'><< Categories page</a>";

?>
