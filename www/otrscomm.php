<?php

define("CIDEV_CRON_START", "CRON");

require "./top.inc.php";
require "./init.php";

ini_set('memory_limit', '512M');
set_time_limit(0);

$OTRS_passphrase = func_query_first("SELECT * FROM $sql_tbl[otrs_options]");


/*
#
##
###
$otrs_query_arr["querytype"] = "new_mail_notification";
$otrs_query_arr["queryparameters"]["ordernumber"] = "AR-39878";
$otrs_query = json_encode($otrs_query_arr);
$passphrase = $OTRS_passphrase["OTRS_passphrase"];
$REQUEST_METHOD = 'POST';
###
##
#
*/

if ($REQUEST_METHOD == "POST" && !empty($passphrase) && $passphrase == $OTRS_passphrase["OTRS_passphrase"]) {

	$otrs_query = stripslashes($otrs_query);
	$otrs_query = htmlspecialchars_decode($otrs_query);

	$otrs_arr = json_decode($otrs_query, true);

//func_print_r($otrs_query, $otrs_arr);
//die();
	if (!empty($otrs_arr) && is_array($otrs_arr)){

		$func_name = "func_".$otrs_arr["querytype"];

                if (function_exists($func_name)) {
                        $func_name($otrs_arr["queryparameters"]);
                }
	}
}
