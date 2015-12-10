<?php
define("CIDEV_CRON_START", "CRON");

require "./top.inc.php";
require "./init.php";

ini_set('memory_limit', '512M');
set_time_limit(0);

if ($config["cron_products_subcategories_count"] == "Y"){
        die("Already launched"); // ################################
}
db_query("UPDATE $sql_tbl[config] SET value='Y' WHERE name='cron_products_subcategories_count'");

$start_time = time();



$records = db_query($query="
Select U.resourceid, MAX(U.`type`) as max_type, C.categoryid_path
from xcart_cidev_updated_products U
            left join xcart_categories C ON C.categoryid = U.resourceid
where U.`type` IN (4,5) and FROM_UNIXTIME(U.time_stamp) < NOW()
Group By U.resourceid
order By MAX(U.`type`) desc, C.categoryid_path desc
");
//print($query);

//$records = func_query($query);
//func_print_r($records);
//die("asd");


$counter = 0;

while ($record = db_fetch_array($records)) {

/* example
        (
            [resourceid] => 57130
            [max_type] => 5
            [categoryid_path] => 57129/57130
        )
*/

	$counter++;
	if ($counter % 100 == 0) {
		func_flush(".");
		if($counter % 5000 == 0) {
			func_flush("<br />\n");
		}
		func_flush();
	}

	if ($record["max_type"] == "4"){

		if (empty($record["categoryid_path"])){
			continue;
		}

		$categoryid_path_arr = explode("/", $record["categoryid_path"]);

		$current_vals = array();
		$new_vals = array();

		foreach ($categoryid_path_arr as $cat){

			$categories_subcount_info = func_query_first("SELECT product_count, subcategory_count FROM $sql_tbl[categories_subcount] WHERE categoryid='$cat'");
			$current_vals[$cat]["product_count"] = $categories_subcount_info["product_count"];
			$current_vals[$cat]["subcategory_count"] = $categories_subcount_info["subcategory_count"];

			$new_vals[$cat] = $current_vals[$cat];
		}
		$current_product_count = $current_vals[$cat]["product_count"];
		$current_subcategory_count = $current_vals[$cat]["subcategory_count"];

		$real_product_count = func_query_first_cell("Select COUNT(distinct PC.productid) As cp_count
From xcart_products_categories PC
        left join xcart_categories C ON C.categoryid = PC.categoryid
        inner join xcart_products P ON P.productid = PC.productid and P.forsale = 'Y'
        inner join xcart_products_sf PS ON PS.productid = P.productid and PS.sfid = C.storefrontid
Where PC.categoryid = '$record[resourceid]'");

		$real_subcategory_count = func_query_first_cell("Select Count(C2.categoryid)
From xcart_categories C
        left join xcart_categories C2 ON C2.categoryid_path like CONCAT(C.categoryid_path,'/%') and C2.avail = 'Y'
where C.categoryid = '$record[resourceid]'
Order By C2.categoryid_path desc");

		if ($current_product_count != $real_product_count){

			foreach ($current_vals as $cat => $c_v){
				$new_product_count = $c_v["product_count"] - $current_product_count + $real_product_count;
				$new_vals[$cat]["product_count"] = $new_product_count;

                                $new_subcategory_count = $c_v["subcategory_count"] - $current_subcategory_count + $real_subcategory_count;
                                $new_vals[$cat]["subcategory_count"] = $new_subcategory_count;

				db_query("UPDATE $sql_tbl[categories_subcount] SET product_count='$new_product_count', subcategory_count='$new_subcategory_count' WHERE categoryid='$cat'");
			}
		}
	}
	elseif ($record["max_type"] == "5"){

                if (empty($record["categoryid_path"])){
                        continue;
                }
/*
		$p_count_in_cats_below = func_query_first_cell("Select SUM(CS.product_count) As p_count
from xcart_categories C
        left join xcart_categories_subcount CS ON CS.categoryid = C.categoryid
where C.categoryid_path like '".$record["categoryid_path"]."/%' and C.avail = 'Y'");

		$p_count_in_current_cat = func_query_first_cell("Select COUNT(distinct PC.productid) As cp_count
From xcart_products_categories PC
        inner join xcart_products P ON P.productid = PC.productid and P.forsale = 'Y'
Where PC.categoryid = '$record[resourceid]'");
*/

		$p_count_in_cats_below = func_query_first_cell("Select SUM(CS.product_count) As p_count
from xcart_categories C
        left join xcart_categories_subcount CS ON CS.categoryid = C.categoryid
where C.parentid = '$record[resourceid]' and C.avail = 'Y'");

		$p_count_in_current_cat = func_query_first_cell("Select COUNT(distinct PC.productid) As cp_count
From xcart_products_categories PC
        left join xcart_categories C ON C.categoryid = PC.categoryid
        inner join xcart_products P ON P.productid = PC.productid and P.forsale = 'Y'
        inner join xcart_products_sf PS ON PS.productid = P.productid and PS.sfid = C.storefrontid
Where PC.categoryid = '$record[resourceid]'");

		$p_count_sum = $p_count_in_cats_below + $p_count_in_current_cat;

		$c_count = func_query_first_cell("Select Count(C2.categoryid)
From xcart_categories C
        left join xcart_categories C2 ON C2.categoryid_path like CONCAT(C.categoryid_path,'/%') and C2.avail = 'Y'
where C.categoryid = '$record[resourceid]'
Order By C2.categoryid_path desc");

		db_query("UPDATE $sql_tbl[categories_subcount] SET product_count='$p_count_sum', subcategory_count='$c_count' WHERE categoryid='$record[resourceid]'");

		$parentid = func_query_first_cell("SELECT parentid FROM $sql_tbl[categories] WHERE categoryid='$record[resourceid]'");

		if ($parentid != "0"){
			 db_query("INSERT IGNORE INTO xcart_cidev_updated_products (resourceid, type, time_stamp, source) values ('$parentid', '5', '".time()."','nxt lvl')");
		}
	}

	db_query("DELETE FROM xcart_cidev_updated_products WHERE resourceid='$record[resourceid]' AND (type='4' OR type='5')");
}
db_free_result($records);


db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='cron_products_subcategories_count'");

print"<br />DONE!";

?>
