<?php
define("CIDEV_CRON_START", "CRON");

require "./top.inc.php";
require "./init.php";


require_once './Excel/OLERead.php';
require_once './Excel/reader.php';

require_once('ShareFile.php');

//require "./auth.php";

x_load("product_feeds", "category");
set_time_limit(0);
ini_set('memory_limit', '512M');

//db_query("UPDATE $sql_tbl[supplier_product_feeds] SET is_launched='N'");

$supplier_product_feeds = func_query("SELECT $sql_tbl[manufacturers].manufacturer, $sql_tbl[manufacturers].code, $sql_tbl[supplier_product_feeds].* FROM $sql_tbl[supplier_product_feeds] LEFT JOIN $sql_tbl[manufacturers] ON $sql_tbl[manufacturers].manufacturerid=$sql_tbl[supplier_product_feeds].manufacturerid WHERE $sql_tbl[supplier_product_feeds].enabled_feed = 'Y' AND $sql_tbl[manufacturers].manufacturerid!='' AND $sql_tbl[supplier_product_feeds].is_launched='N'");

//func_print_r($supplier_product_feeds);
//die();

if (!empty($supplier_product_feeds) && is_array($supplier_product_feeds)){

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

	foreach ($supplier_product_feeds as $k => $v){

		$time_to_launch = false;
		$time_diff_in_days = (time() - $v["last_import_date"])/(60*60*24);
		if ($time_diff_in_days >= $v["updation_frequency"]){
			$time_to_launch = true;
		}

		$func_name = "func_".$v["feed_procedure_id"];

		if (function_exists($func_name) && $time_to_launch) {
//func_print_r($v["manufacturerid"]);
			$function_launch_time = time();
			db_query("UPDATE $sql_tbl[supplier_product_feeds] SET is_launched='Y' WHERE manufacturerid='$v[manufacturerid]'");
			$func_name($v);
			db_query("UPDATE $sql_tbl[supplier_product_feeds] SET is_launched='N' WHERE manufacturerid='$v[manufacturerid]'");
		}
	}
}

print"<br />Done!";
?>
