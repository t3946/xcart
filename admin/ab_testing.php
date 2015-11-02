<?php
require "./auth.php";
require $xcart_dir."/include/security.php";

$membershipid = func_query_first_cell("SELECT membershipid FROM $sql_tbl[customers] WHERE login='$login'");

if ($membershipid != "0"){
	func_header_location("home.php");
}

if ($REQUEST_METHOD == "POST") {

	if ($mode == "add_point"){
		db_query("INSERT INTO $sql_tbl[ab_testing_points] (point_start_date, point_end_date) VALUES ('".time()."', '".time()."')");
	} 
	elseif ($mode == "add_variant" && !empty($add_variant_id_point_id)){
		db_query("INSERT INTO $sql_tbl[ab_point_variants] (point_id) VALUES ('$add_variant_id_point_id')");
	}
	elseif ($mode == "delete_variant" && !empty($delete_variant_id_id)){
		db_query("DELETE FROM $sql_tbl[ab_point_variants] WHERE id='$delete_variant_id_id'");
	}
	elseif ($mode == "update" && !empty($posted_data) && is_array($posted_data)){

		if (!empty($posted_data["ab_testing_points"]) && is_array($posted_data["ab_testing_points"])){
			foreach ($posted_data["ab_testing_points"] as $point_id => $v){

				$point_start_date = $v["point_start_date"];
				if (!empty($point_start_date)){
					$point_start_date_arr = explode("/", $point_start_date);
					$point_start_date = mktime(0, 0, 0, $point_start_date_arr[0], $point_start_date_arr[1], $point_start_date_arr[2]);
				}

                                $point_end_date = $v["point_end_date"];
                                if (!empty($point_end_date)){
                                        $point_end_date_arr = explode("/", $point_end_date);
                                        $point_end_date = mktime(0, 0, 0, $point_end_date_arr[0], $point_end_date_arr[1], $point_end_date_arr[2]);
                                }

//				db_query("UPDATE $sql_tbl[ab_testing_points] SET point_name='$v[point_name]', point_descr='$v[point_descr]', storefronts_enabled='$v[storefronts_enabled]', point_start_date='$point_start_date', point_end_date='$point_end_date', point_goal_url='$v[point_goal_url]', mod_param='$v[mod_param]', total_hits='$v[total_hits]', enabled='$v[enabled]' WHERE point_id='$point_id'");
				db_query("UPDATE $sql_tbl[ab_testing_points] SET point_name='$v[point_name]', point_descr='$v[point_descr]', storefronts_enabled='$v[storefronts_enabled]', point_start_date='$point_start_date', point_end_date='$point_end_date', point_goal_url='$v[point_goal_url]', mod_param='$v[mod_param]', enabled='$v[enabled]' WHERE point_id='$point_id'");
			}
		}

		if (!empty($posted_data["ab_point_variants"]) && is_array($posted_data["ab_point_variants"])){
			foreach ($posted_data["ab_point_variants"] as $id => $v){
//				db_query("UPDATE $sql_tbl[ab_point_variants] SET variant_id='$v[variant_id]', variant_name='$v[variant_name]', is_default='$v[is_default]', total_hits_count='$v[total_hits_count]', reach_goal_count='$v[reach_goal_count]', average_success_measure='$v[average_success_measure]', outcome='$v[outcome]', dollar_amount_of_goal_conversions='$v[dollar_amount_of_goal_conversions]', success_measure_range='$v[success_measure_range]' WHERE id='$id'");
				db_query("UPDATE $sql_tbl[ab_point_variants] SET variant_id='$v[variant_id]', variant_name='$v[variant_name]', is_default='$v[is_default]' WHERE id='$id'");
			}
		}
	}

	$top_message["content"] = "Done.";
	$top_message["type"] = "I";
	func_header_location("ab_testing.php");
}

$ab_testing_points = func_query("SELECT * FROM $sql_tbl[ab_testing_points]");
if (!empty($ab_testing_points)){
	foreach ($ab_testing_points as $k => $v){

		$point_start_date = $v["point_start_date"];
		if (!empty($point_start_date)){
			$point_start_date = date("m/d/Y", $point_start_date);
			$ab_testing_points[$k]["point_start_date"] = $point_start_date;
		}

                $point_end_date = $v["point_end_date"];
                if (!empty($point_end_date)){
                        $point_end_date = date("m/d/Y", $point_end_date);
                        $ab_testing_points[$k]["point_end_date"] = $point_end_date;
                }

	}
}

$ab_point_variants = func_query("SELECT * FROM $sql_tbl[ab_point_variants]");

$smarty->assign("ab_testing_points", $ab_testing_points);
$smarty->assign("ab_point_variants", $ab_point_variants);
$smarty->assign("main", "ab_testing");

$location[] = array("A/B testing", "");
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
