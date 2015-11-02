<?php
define("CIDEV_CRON_START", "CRON");

require "./top.inc.php";
require "./init.php";

ini_set('memory_limit', '512M');
set_time_limit(0);

if ($config["cron_pc_launched"] == "Y"){
	die("Already launched"); // ################################
}

$start_time = time();

#
##
###
//$storefrontid = 38;
$storefrontid = $config["cron_pc_launched_storefrontid"];
###
##
#

if ($storefrontid == ""){
	die("Error. Storefrontid=''.");
}



db_query("UPDATE $sql_tbl[config] SET value='Y' WHERE name='cron_pc_launched'");


#
## DEL
###
$to_del_termid = func_query("SELECT termid FROM $sql_tbl[pc_terms] WHERE storefrontid='$storefrontid'");
db_query("DELETE FROM $sql_tbl[pc_terms] WHERE storefrontid='$storefrontid'");
if (!empty($to_del_termid)){
	foreach ($to_del_termid as $k => $v){
		db_query("DELETE FROM $sql_tbl[pc_category_terms] WHERE termid='$v[termid]'");
	}
}
###
##
#

$pc_options = func_query_hash("SELECT * FROM $sql_tbl[pc_options]", 'storefrontid', false);

$categories = db_query($query="SELECT categoryid FROM $sql_tbl[categories] WHERE pc_ready_to_classify='Y' AND avail='Y' AND storefrontid='$storefrontid'");

$counter = 0;

while ($category = db_fetch_array($categories)) {

	$categoryid = $category["categoryid"];

//func_print_r($categoryid);

	$products = db_query("SELECT $sql_tbl[products].productid, $sql_tbl[products].product, $sql_tbl[products].fulldescr FROM $sql_tbl[products] LEFT JOIN $sql_tbl[products_categories] ON $sql_tbl[products_categories].productid = $sql_tbl[products].productid WHERE ($sql_tbl[products].pc_classify_status='MC' OR $sql_tbl[products].pc_classify_status='ACC') AND $sql_tbl[products].forsale='Y' AND $sql_tbl[products_categories].categoryid='$categoryid'");

	while ($product = db_fetch_array($products)){

// func_print_r($product);

		$sfid = func_query_first_cell("SELECT sfid FROM $sql_tbl[products_sf] WHERE productid='$product[productid]'");

		$text = $product["product"] . " " . $product["product"] . " " . $product["fulldescr"];
		$text = func_del_excluded_char_sequences($text, $pc_options[$sfid]["excluded_char_sequences"]);
		$text = func_del_stop_words($text, $pc_options[$sfid]["stop_words"]);

		if (!empty($text)){
			$text_arr = explode(" ", $text);

			foreach ($text_arr as $term){
				$termid = func_query_first_cell("SELECT termid FROM $sql_tbl[pc_terms] WHERE term='$term' AND storefrontid='$storefrontid'");

				if (empty($termid)){
					$insert_data["term"] = $term;
					$insert_data["storefrontid"] = $storefrontid;
					$termid = func_array2insert('pc_terms', $insert_data);
				}

				db_query("INSERT INTO $sql_tbl[pc_category_terms] (categoryid, termid, bayes_weight) VALUES ('$categoryid', '$termid', '0')");
			}
		}

// func_print_r($text);

		$counter++;
		if ($counter % 10 == 0) {
			func_flush(".");
			if($counter % 500 == 0) {
				func_flush("<br />\n");
			}
			func_flush();
		}
	}
}

#
##
#

$query_bayesian_weight = "Select
                C.categoryid As CategoryID, 
                LOG((Select Count(P1.productid) 
                 From xcart_products P1
                                left join xcart_products_categories PC1 ON PC1.productid = P1.productid
                 where P1.forsale = 'Y' and PC1.categoryid = C.categoryid and P1.pc_classify_status IN ('ACC','MC')
                ) /
                (Select COUNT(P2.productid) From xcart_products P2
                                left join xcart_products_sf PSF ON PSF.productid = P2.productid
                 where P2.forsale='Y' and PSF.sfid = '$storefrontid' and P2.pc_classify_status IN ('ACC','MC')
                )) As bayesian_weight
	from xcart_categories C
	where C.pc_ready_to_classify = 'Y' and C.storefrontid = '$storefrontid'";

$bayesian_weight_arr = func_query($query_bayesian_weight);

if (!empty($bayesian_weight_arr)){
	foreach ($bayesian_weight_arr as $k => $v){
		if (!empty($v["bayesian_weight"])){
//			db_query("UPDATE $sql_tbl[pc_category_terms] SET bayes_weight='$v[bayesian_weight]' WHERE categoryid='$v[CategoryID]'");
			db_query("UPDATE $sql_tbl[categories] SET pc_category_weight='$v[bayesian_weight]' WHERE categoryid='$v[CategoryID]'");
		}
	}
}

#
##
#

$query_z = "Select
                        C.categoryid As CategoryID,
                        (Count(distinct CT.termid)) + Count(T.Termid) As Z
	from xcart_categories C
                        left join xcart_pc_terms T ON 1=1
                        left join xcart_pc_category_terms CT ON CT.categoryid = C.categoryid and CT.termid = T.termid
	where C.pc_ready_to_classify = 'Y' and C.storefrontid = '$storefrontid'
	Group By C.categoryid";

$z_arr = func_query($query_z);

if (!empty($z_arr)){
	foreach ($z_arr as $k => $v){
		db_query("UPDATE $sql_tbl[categories] SET pc_z='$v[Z]' WHERE categoryid='$v[CategoryID]'");
	}
}

#
##
#



db_query("DROP TEMPORARY TABLE IF EXISTS temp_table");
db_query("
CREATE TEMPORARY TABLE temp_table
Select 
                        C.categoryid As categoryid,
                        T.termid,
                        LOG((Count(CT.termid)+1)/C.pc_z) As bayes_weight
from xcart_categories C
                        left join xcart_pc_terms T ON T.storefrontid = '$storefrontid'
                        left join xcart_pc_category_terms CT ON CT.categoryid = C.categoryid and CT.termid = T.termid
where C.pc_ready_to_classify = 'Y' and C.storefrontid = '$storefrontid'
Group By C.categoryid,T.termid
");
db_query("Delete C From xcart_pc_category_terms As C left join xcart_categories XC ON XC.categoryid = C.categoryid where XC.pc_ready_to_classify = 'Y' and XC.storefrontid = '$storefrontid'");
db_query("INSERT IGNORE INTO xcart_pc_category_terms (categoryid,termid,bayes_weight) Select * from temp_table");


#
##
#

$limit = $pc_options[$sfid]["minimum_number_of_autoclassify_product_per_turn"] * 10;

$products = func_query($query = "SELECT $sql_tbl[products].productid FROM $sql_tbl[products] LEFT JOIN $sql_tbl[products_sf] ON $sql_tbl[products_sf].productid = $sql_tbl[products].productid WHERE pc_classify_status='NC' AND $sql_tbl[products].forsale='Y' AND $sql_tbl[products_sf].sfid='$storefrontid' ORDER BY RAND() LIMIT $limit");

//func_print_r($query, $products);

if (!empty($products)){
	foreach ($products as $product){
		$productid = $product["productid"];
		func_pc_find_new_categoryid($productid);
	}
}


db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='cron_pc_launched'");
db_query("UPDATE $sql_tbl[config] SET value='' WHERE name='cron_pc_launched_storefrontid'");

print"<br />DONE!";


/*
$productid = "34785";
$new_cat = func_pc_find_new_categoryid($productid);
func_print_r($new_cat);
*/


?>
