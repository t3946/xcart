<?php

use \Mindy\QueryBuilder\QueryBuilder;
use \Xcart\Config;
use \Xcart\StoreFront;

global $config, $sql_tbl;

define("CIDEV_CRON_START", "CRON");
session_start();

require "../top.inc.php";
require "../init.php";

set_time_limit(0);
const LOG_CATEGORY = 'cron_auto_classified_products_watchdog';
$aSendLines = [];

if ($config[LOG_CATEGORY] == "Y") {
    func_backprocess_log(LOG_CATEGORY, 'Already launched');
    $oMail = \Xcart\App\Main\Xcart::app()->mail;
    $oMail->to = 'team@s3stores.com';
    $oMail->from = ('team@s3stores.com');
    $oMail->subject = LOG_CATEGORY . ' already launched';
    $oMail->body = sprintf('Attention! Xcart cron %s Already launched', LOG_CATEGORY);
    $oMail->sendEmail();
    die("Already launched"); // ################################
}

db_query("REPLACE $sql_tbl[config] SET value='Y', name='" . LOG_CATEGORY . "'");
$start_time = new DateTime('now');

$log_text = " * * *  Cron started  * * * ";
func_backprocess_log(LOG_CATEGORY, $log_text);

$connection = \Xcart\Connection::getInstance();
$aStoreFronts = (new \Xcart\StoreFronts())->getStoreFronts();
if (!empty($aStoreFronts)) {
    /** @var StoreFront $oStoreFront */
    foreach ($aStoreFronts as $oStoreFront){
        $productSql = QueryBuilder::getInstance($connection)
            ->select('t.*')
            ->from('xcart_products')
            ->setAlias('t')
            ->join('inner join', 'xcart_products_sf', ['t.productid' => 'psf.productid'], 'psf')
            ->where(['t.pc_classify_status' => 'AC', 't.forsale' => 'Y', 'psf.sfid' => $oStoreFront->getStoreFrontId()])
            ->toSQL();
        $iCount = $connection->executeQuery($productSql)->rowCount();
        if ($iCount) {
            $aSendLines[] = "{$iCount}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;classified products found at {$oStoreFront->getDomain()}";
        }
    }
}
if (!empty($aSendLines) && is_array($aSendLines)) {
    $oMail = \Xcart\App\Main\Xcart::app()->mail;
    $oMail->to = $config['Company']['product_categoryzation'];
    $oMail->from = ('team@s3stores.com');
    $oMail->body = implode("\n", $aSendLines);
    $oMail->subject = "Next storefronts have classified products which need to be confirmed";
    $oMail->sendEmail();
}

Config::model(['name' => LOG_CATEGORY])->setValue('N')->_update();
$str_time = (new DateTime('now'))->diff($start_time)->format('%H:%I:%S');
$log_text = "Cron completed. Processing time: {$str_time}";
func_backprocess_log(LOG_CATEGORY, $log_text);

die("DONE!");