<?php
require "./auth.php";
require $xcart_dir."/include/security.php";

x_load('backoffice','files','category');

set_time_limit(0);
ini_set('memory_limit', '512M');

x_session_register("category_not_ready_to_classification");

//func_print_r($current_storefront_info["storefrontid"]);

$pc_options = func_query_first("SELECT * FROM $sql_tbl[pc_options] WHERE storefrontid='".$current_storefront_info["storefrontid"]."'");

if (empty($pc_options)){
        db_query("INSERT INTO $sql_tbl[pc_options] (storefrontid, maximum_number_of_autoclassify_product_per_turn, minimum_number_of_autoclassify_product_per_turn, stop_words, excluded_char_sequences) VALUES ('".$current_storefront_info["storefrontid"]."', '50', '3', '- with for not as by this when x you your the a on and feature will would can to in must do or nor if of me is', '+#13+ +#10+')");

        $pc_options = func_query_first("SELECT * FROM $sql_tbl[pc_options] WHERE storefrontid='".$current_storefront_info["storefrontid"]."'");
}


if ($REQUEST_METHOD == "POST") {

	if ($mode == "submit_pc_form1" && !empty($posted_data) && is_array($posted_data)){

//func_print_r($_POST);
//die();

		$category_not_ready_to_classification = array();

		$correct_categoryid_counter = 0;
		$products_incorrect_assigned = 0;
		$count_approved_products = 0;
		$count_skipped_products = 0;


		foreach ($posted_data as $k => $v){

			$correct_categoryid = isset($v["correct_categoryid"]) ? abs(intval($v["correct_categoryid"])) : 0;

			if ($correct_categoryid > 0){
				// MC

				$is_such_cat = func_query_first_cell("SELECT categoryid FROM $sql_tbl[categories] WHERE categoryid='$correct_categoryid' AND pc_ready_to_classify='Y'");
				if (!empty($is_such_cat)){

					db_query("UPDATE $sql_tbl[products_categories] SET categoryid='$correct_categoryid' WHERE productid='$v[productid]' AND main='Y'");
					db_query("UPDATE $sql_tbl[products] SET pc_classify_status='MC', pc_mc_operator='$login' WHERE productid='$v[productid]'");
					$correct_categoryid_counter++;

				} else {
					$category_not_ready_to_classification[] = $correct_categoryid;
					$products_incorrect_assigned++;
				}

			} else {

				if ($v["skip"] == "Y"){
					db_query("UPDATE $sql_tbl[products] SET pc_classify_status='NC', pc_acc_operator='$login' WHERE productid='$v[productid]'");
					$count_skipped_products++;
				}
				else {
					//Approve ACC
					$count_approved_products++;
					db_query("UPDATE $sql_tbl[products] SET pc_classify_status='ACC', pc_acc_operator='$login' WHERE productid='$v[productid]'");
				}
			}
		}

		x_session_save("category_not_ready_to_classification");

		$classification_approval_rate = $pc_options["classification_approval_rate"];

		$count_products = count($posted_data);
		$classification_approval_rate += $count_approved_products/$count_products;

		db_query("UPDATE $sql_tbl[pc_options] SET classification_approval_rate='$classification_approval_rate' WHERE storefrontid='".$current_storefront_info["storefrontid"]."'");


#
##
		$run = func_query_first_cell("SELECT MAX(run) FROM $sql_tbl[pc_runs_log]");
		$run += 1;
		
		db_query("INSERT INTO $sql_tbl[pc_runs_log] (run, login, date_time_start, date_time_end, products_assigned, products_skipped, products_approved, storefrontid, products_incorrect_assigned) VALUES ('$run', '$login', '$date_time_start', '".time()."', '$correct_categoryid_counter', '$count_skipped_products', '$count_approved_products', '".$current_storefront_info["storefrontid"]."', '$products_incorrect_assigned') ");
##
#

                $top_message["content"] = "Done.";
                $top_message["type"] = "I";

	        func_header_location("classification.php");
	}
	elseif ($mode == "recalc_bayes"){

		if ($config["cron_pc_launched"] == "Y"){
        	        $top_message["content"] = "CRON is working. Please try again later.";
	                $top_message["type"] = "I";
	                func_header_location("classification.php");
		}

		if ($config["cron_pc_launched_storefrontid"] != ""){
                        $top_message["content"] = "CRON is NOT launched yet, but storefrontid already is marked in DB, and when CRON start to work it will start work with this storefrontid: ".$config["cron_pc_launched_storefrontid"].". Please try again later.";
                        $top_message["type"] = "I";
                        func_header_location("classification.php");
		}

		db_query("UPDATE $sql_tbl[config] SET value='".$current_storefront_info["storefrontid"]."' WHERE name='cron_pc_launched_storefrontid'");

                $top_message["content"] = "Done. The necessary info is added to DB. CRON will check products soon. You can click the 'Check status' button to check if CRON complete his work already.";
                $top_message["type"] = "I";
                func_header_location("classification.php");
	}
        elseif ($mode == "check_status"){

                if ($config["cron_pc_launched"] == "Y"){
                        $top_message["content"] = "CRON is working. Please try again later.";
                        $top_message["type"] = "I";
                        func_header_location("classification.php");
                }

                if ($config["cron_pc_launched_storefrontid"] != ""){
                        $top_message["content"] = "CRON is NOT launched yet, but storefrontid already is marked in DB, and when CRON start to work it will start work with this storefrontid: ".$config["cron_pc_launched_storefrontid"].". Please try again later.";
                        $top_message["type"] = "I";
                        func_header_location("classification.php");
                }

                $top_message["content"] = "CRON is NOT launched. The storefrontid marked as '<empty>' in DB for CRON.";
                $top_message["type"] = "I";
                func_header_location("classification.php");
	}
}

$limit = $pc_options["minimum_number_of_autoclassify_product_per_turn"];
if ($config["cron_pc_launched"] == "Y" && $config["cron_pc_launched_storefrontid"] != ""){
//	$limit *= 10;
}

$where1 = "";
#
##

db_query("DELETE $sql_tbl[pc_locks] FROM $sql_tbl[pc_locks] LEFT JOIN $sql_tbl[products_sf] ON $sql_tbl[pc_locks].productid=$sql_tbl[products_sf].productid WHERE $sql_tbl[pc_locks].login='$login' AND $sql_tbl[products_sf].sfid='".$current_storefront_info["storefrontid"]."'");

$time_min_2days = time() - 60*60*24*2;
db_query("DELETE $sql_tbl[pc_locks] FROM $sql_tbl[pc_locks] LEFT JOIN $sql_tbl[products_sf] ON $sql_tbl[pc_locks].productid=$sql_tbl[products_sf].productid WHERE $sql_tbl[pc_locks].lock_date < '$time_min_2days' AND $sql_tbl[products_sf].sfid='".$current_storefront_info["storefrontid"]."'");

$productids_in_pc_locks_arr = func_query("SELECT productid FROM $sql_tbl[pc_locks]");
if (!empty($productids_in_pc_locks_arr)){
	foreach ($productids_in_pc_locks_arr as $k => $v){
		$productids_in_pc_locks[] = $v["productid"];
	}

	$where1 = " AND $sql_tbl[products].productid NOT IN ('".implode("','", $productids_in_pc_locks)."')";
}
##
#

$products_minimum_number_of_autoclassify_product_per_turn = func_query($query="SELECT $sql_tbl[products].productid, $sql_tbl[products].product, $sql_tbl[products].pc_classify_status, $sql_tbl[categories].categoryid, $sql_tbl[categories].categoryid_path FROM $sql_tbl[products]  LEFT JOIN $sql_tbl[products_sf] ON $sql_tbl[products_sf].productid=$sql_tbl[products].productid LEFT JOIN $sql_tbl[products_categories] ON $sql_tbl[products_categories].productid=$sql_tbl[products].productid LEFT JOIN $sql_tbl[categories] ON $sql_tbl[categories].categoryid=$sql_tbl[products_categories].categoryid WHERE $sql_tbl[products_sf].sfid='".$current_storefront_info["storefrontid"]."' AND $sql_tbl[categories].pc_ready_to_classify='Y' AND $sql_tbl[products].pc_classify_status='AC' AND $sql_tbl[products].forsale='Y' $where1 GROUP BY $sql_tbl[products].productid LIMIT ".$limit);

if (!empty($products_minimum_number_of_autoclassify_product_per_turn)){
	foreach ($products_minimum_number_of_autoclassify_product_per_turn as $k => $product){
	
		$categoryid_path_arr = func_categoryid_path2category_path($product["categoryid_path"]);
		$products_minimum_number_of_autoclassify_product_per_turn[$k]["categoryid_path_arr"] = $categoryid_path_arr;
		$products_minimum_number_of_autoclassify_product_per_turn[$k]["categoryid_path_arr_count"] = count($categoryid_path_arr);

		db_query("INSERT INTO $sql_tbl[pc_locks] (productid, lock_date, login) VALUES ('$product[productid]', '".time()."', '$login')");

	}

	$smarty->assign("products_minimum_number_of_autoclassify_product_per_turn", $products_minimum_number_of_autoclassify_product_per_turn);

	$count_products_minimum_number_of_autoclassify_product_per_turn = count($products_minimum_number_of_autoclassify_product_per_turn);
	$smarty->assign("count_products_minimum_number_of_autoclassify_product_per_turn", $count_products_minimum_number_of_autoclassify_product_per_turn);
}
else {
	$show_recalc_bayes_button = "Y";
	$smarty->assign("show_recalc_bayes_button", $show_recalc_bayes_button);
}

if ($config["cron_pc_launched_storefrontid"] != "") {
	$show_check_status_button = "Y";
	$smarty->assign("show_check_status_button", $show_check_status_button);
}


$count_ACC_or_MC_products = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[products] LEFT JOIN $sql_tbl[products_sf] ON $sql_tbl[products_sf].productid=$sql_tbl[products].productid LEFT JOIN $sql_tbl[products_categories] ON $sql_tbl[products_categories].productid=$sql_tbl[products].productid LEFT JOIN $sql_tbl[categories] ON $sql_tbl[categories].categoryid=$sql_tbl[products_categories].categoryid WHERE ($sql_tbl[products].pc_classify_status='ACC' OR $sql_tbl[products].pc_classify_status='MC') AND $sql_tbl[products_sf].sfid='".$current_storefront_info["storefrontid"]."' AND $sql_tbl[categories].pc_ready_to_classify='Y' AND $sql_tbl[products].forsale='Y'");

$count_products = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[products] LEFT JOIN $sql_tbl[products_sf] ON $sql_tbl[products_sf].productid=$sql_tbl[products].productid LEFT JOIN $sql_tbl[products_categories] ON $sql_tbl[products_categories].productid=$sql_tbl[products].productid LEFT JOIN $sql_tbl[categories] ON $sql_tbl[categories].categoryid=$sql_tbl[products_categories].categoryid WHERE $sql_tbl[products_sf].sfid='".$current_storefront_info["storefrontid"]."' AND $sql_tbl[products].forsale='Y'");

$smarty->assign("count_ACC_or_MC_products", $count_ACC_or_MC_products);
$smarty->assign("count_products", $count_products);


$count_all_pc_cats = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[categories] WHERE $sql_tbl[categories].storefrontid='".$current_storefront_info["storefrontid"]."' AND $sql_tbl[categories].pc_ready_to_classify='Y'");

//$count_pc_cats_with_pr = func_query_first_cell($q="SELECT COUNT(*) FROM $sql_tbl[categories] LEFT JOIN $sql_tbl[products_categories] ON $sql_tbl[products_categories].categoryid=$sql_tbl[categories].categoryid LEFT JOIN $sql_tbl[products] ON $sql_tbl[products_categories].productid=$sql_tbl[products].productid  WHERE $sql_tbl[categories].storefrontid='".$current_storefront_info["storefrontid"]."' AND $sql_tbl[categories].pc_ready_to_classify='Y' AND ($sql_tbl[products].pc_classify_status='ACC' OR $sql_tbl[products].pc_classify_status='MC') AND $sql_tbl[products].forsale='Y'");
$count_pc_cats_with_pr = func_query($q="SELECT COUNT(*) FROM $sql_tbl[categories] LEFT JOIN $sql_tbl[products_categories] ON $sql_tbl[products_categories].categoryid=$sql_tbl[categories].categoryid LEFT JOIN $sql_tbl[products] ON $sql_tbl[products_categories].productid=$sql_tbl[products].productid  WHERE $sql_tbl[categories].storefrontid='".$current_storefront_info["storefrontid"]."' AND $sql_tbl[categories].pc_ready_to_classify='Y' AND ($sql_tbl[products].pc_classify_status='ACC' OR $sql_tbl[products].pc_classify_status='MC') AND $sql_tbl[products].forsale='Y' GROUP BY $sql_tbl[categories].categoryid");
$count_pc_cats_with_pr = count($count_pc_cats_with_pr);

$smarty->assign("count_all_pc_cats", $count_all_pc_cats);
$smarty->assign("count_pc_cats_with_pr", $count_pc_cats_with_pr);

$date_time_start = time();
$smarty->assign("date_time_start", $date_time_start);

$count_cats_with_no_classified_products = $count_all_pc_cats - $count_pc_cats_with_pr;
$smarty->assign("count_cats_with_no_classified_products", $count_cats_with_no_classified_products);

//func_print_r($count_pc_cats_with_pr, $count_all_pc_cats, $count_cats_with_no_classified_products);

//print($q);

//func_print_r($products_minimum_number_of_autoclassify_product_per_turn, $query);
//func_print_r($pc_options);

//func_print_r($category_not_ready_to_classification);

$smarty->assign("category_not_ready_to_classification", $category_not_ready_to_classification);
$category_not_ready_to_classification = "";
x_session_save("category_not_ready_to_classification");

$location[] = array("Classification", "");

$smarty->assign("pc_options", $pc_options);
$smarty->assign("main", "categorization");
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
