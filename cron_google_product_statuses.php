<?php
define("CIDEV_CRON_START", "CRON");
session_start();

require "./top.inc.php";
require "./init.php";

global $xcart_dir, $config;


ini_set('memory_limit', '512M');
set_time_limit(0);

const LOG_CATEGORY = 'cron_google_product_statuses';
const BACK_PROCESS_LOG_NAME = 'google_product_statuses';

if ($config[LOG_CATEGORY] == "Y") {
    func_backprocess_log(BACK_PROCESS_LOG_NAME, 'Already launched');
    die("Already launched"); // ################################
}
db_query("REPLACE $sql_tbl[config] SET value='Y', name='" . LOG_CATEGORY . "'");
$start_time = time();

$log_text = " * * *  Cron started  * * * ";
func_backprocess_log(BACK_PROCESS_LOG_NAME, $log_text);

$oStoreFronts = new Xcart\StoreFronts();
$aStoreFronts = $oStoreFronts->getStoreFronts();
if (!empty($aStoreFronts)) {
    foreach ($aStoreFronts as $aStoreFront) {
        $aMarketPlaces = Xcart\External_MarketPlace\StoreFrontMarketPlace::getMarketPlacesByStoreFront($aStoreFront->getStoreFrontId());
        if (!empty($aMarketPlaces)) {
            foreach ($aMarketPlaces as $oMarketPlace) {
                if ($oMarketPlace instanceof Xcart\External_MarketPlace\GMC) {
                    func_backprocess_log(BACK_PROCESS_LOG_NAME, sprintf('---Storefront %d---',$aStoreFront->getStoreFrontId()));
                    $oMarketPlace->getProductStatuses();
                }
            }
        }
    }
}

db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='" . LOG_CATEGORY . "'");

$current_time = time();

$pid_diff = $current_time - $start_time;
$hour = intval($pid_diff / (60 * 60));
$minutes = intval(($pid_diff - $hour * 60 * 60) / 60);
$seconds = ($pid_diff - $hour * 60 * 60 - $minutes * 60);

$str_time = sprintf("%02d:%02d:%02d", $hour, $minutes, $seconds);

$log_text = "Cron completed. ";
$log_text .= "Processing time: $str_time";
func_backprocess_log(BACK_PROCESS_LOG_NAME, $log_text);

die("DONE!");