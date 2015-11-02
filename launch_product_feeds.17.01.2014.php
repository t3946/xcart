<?php
require "./auth.php";

x_load("product_feeds");
set_time_limit(0);
ini_set('memory_limit', '512M');

$all_distributors = func_query("SELECT manufacturerid, d_feed_procedure_id, d_most_recent_feed_updation_date, d_feed_updation_frequency FROM $sql_tbl[manufacturers] WHERE d_enable_feed = 'Y'");

if (!empty($all_distributors) && is_array($all_distributors)){
	foreach ($all_distributors as $k => $v){

		$time_to_launch = false;
		$time_diff_in_hours = (time() - $v["d_most_recent_feed_updation_date"])/(60*60);
		if ($time_diff_in_hours > $v["d_feed_updation_frequency"]){
			$time_to_launch = true;
		}

		$func_name = "func_".$v["d_feed_procedure_id"];

		if (function_exists($func_name) && $time_to_launch) {
			$func_name($v["manufacturerid"]);
		}
	}
}

print"<br />Done!";
?>
