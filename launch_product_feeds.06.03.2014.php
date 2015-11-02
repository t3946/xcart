<?php

require_once './Excel/OLERead.php';
require_once './Excel/reader.php';

require_once('ShareFile.php');

require "./auth.php";

x_load("product_feeds");
set_time_limit(0);
ini_set('memory_limit', '512M');

$all_distributors = func_query("SELECT manufacturerid, d_feed_procedure_id, d_most_recent_feed_updation_date, d_feed_updation_frequency FROM $sql_tbl[manufacturers] WHERE d_enable_feed = 'Y'");

if (!empty($all_distributors) && is_array($all_distributors)){

	$launch_time = time();

//	$now = date("F j, Y, g.i:s a ", $launch_time);

	$now_minutes = date("i", $launch_time);
	$now_minutes = intval($now_minutes);

	if (0 <= $now_minutes && $now_minutes < 15) $new_minutes = 0;
	if (15 <= $now_minutes && $now_minutes < 30) $new_minutes = 15;
	if (30 <= $now_minutes && $now_minutes < 45) $new_minutes = 30;
	if (45 <= $now_minutes && $now_minutes < 60) $new_minutes = 45;

	$new_launch_time = mktime(date("G", $launch_time), $new_minutes, 0, date("m", $launch_time), date("d", $launch_time), date("Y", $launch_time));

//	$new_now = date("F j, Y, g.i:s a ", $new_launch_time);

//	func_print_r($now, $new_now);

	if ($new_launch_time != $launch_time){
		$launch_time = $new_launch_time;
	}

	foreach ($all_distributors as $k => $v){

		$time_to_launch = false;
		$time_diff_in_hours = (time() - $v["d_most_recent_feed_updation_date"])/(60*60);
		if ($time_diff_in_hours >= $v["d_feed_updation_frequency"]){
			$time_to_launch = true;
		}

		$func_name = "func_".$v["d_feed_procedure_id"];

//func_print_r($v["manufacturerid"]);
		if (function_exists($func_name) && $time_to_launch) {
			$func_name($v["manufacturerid"]);
		}
	}
}

print"<br />Done!";
?>
