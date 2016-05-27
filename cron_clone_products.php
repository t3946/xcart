<?php
define("CIDEV_CRON_START", "CRON");

require "./top.inc.php";
require "./init.php";

ini_set('memory_limit', '512M');
set_time_limit(0);

$log_category = 'clone_products_cron';

if ($config[$log_category] == "Y"){
    die("Already launched"); // ################################
}

global $xcart_dir;

include_once $xcart_dir."/include/class/classProducts.php";
$classProducts = new classProducts();

db_query("REPLACE $sql_tbl[config] SET value='Y', name='$log_category'");

$start_time = time();

$iProductsCount = $classProducts->getProductQueueCount();

if ($iProductsCount) {

    $log_text = sprintf(" Cron started. Trying to process %d records...", $iProductsCount);

    func_backprocess_log($log_category, $log_text);

    $classProducts->cloneProductFunction(200000); //0.2 sec

    $current_time = time();

    $pid_diff = $current_time - $start_time;

    $hour = intval($pid_diff / (60 * 60));
    $minutes = intval(($pid_diff - $hour * 60 * 60) / 60);
    $seconds = ($pid_diff - $hour * 60 * 60 - $minutes * 60 );

    $str_time = sprintf( "%02d:%02d:%02d", $hour, $minutes, $seconds );

    $sDoneMessage = "Cron finished. \n";
    $sDoneMessage .= "To update: [".($classProducts->updateCounter+$classProducts->updateFailCounter)."]\n";
    $sDoneMessage .= "ok: [".$classProducts->updateCounter."]\n";
    $sDoneMessage .= "failed: [".$classProducts->updateFailCounter."]\n";
    $sDoneMessage .= "To clone: [".($classProducts->addCounter+$classProducts->addFailCounter)."]\n";
    $sDoneMessage .= "ok: [".$classProducts->addCounter."]\n";
    $sDoneMessage .= "failed: [".$classProducts->addFailCounter."]\n";
    $sDoneMessage .= "processing time: $str_time";

    func_backprocess_log($log_category, $sDoneMessage);

}

db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='$log_category'");

die("DONE!");