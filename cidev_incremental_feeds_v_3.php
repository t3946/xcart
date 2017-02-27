<?php
define("CIDEV_CRON_START", "CRON");
session_start();

require "./top.inc.php";
require "./init.php";

global $xcart_dir, $config, $storefronts, $aManufacturerZones;

#
##
###
$debug_requests = 'N';
$froogle_tracing_token = 'ANY78kLeWOxH4je4ZmHHsdNUGUhaxDLr2qkUcqeZ3MPGH1qjH2RdLqjUjqYTc95GthRPCu8dconorTv7DtGlvI5RDlQlVyq4xzMqr9hiS5aaTT9NlPQrsJc';
###
##
#
const LOG_CATEGORY = 'cidev_incremental_feeds_launched_v_3';

define("FROOGLE_TAIL", '...');
define("FROOGLE_TAIL_LEN", strlen(constant("FROOGLE_TAIL")));
define('FROOGLE_MAX_DESCRIPTION_LENGTH', 10 * 1024); //The content in an attribute in an item exceeds 10 KB.

define('EXCLUDE_CATEGORYID_BRANCH', 5099);
define('SUBMIT_DISABLE', 'N');
define('EXTRA_LOG', 'N');

set_time_limit(0);

$xcart_states_US = func_query(<<<SQL
SELECT stateid, state, code, country_code, base_state_zipcode, city FROM {$sql_tbl['states']}
LEFT JOIN {$sql_tbl['geo_litecity_location']} ON country = country_code AND postalCode = base_state_zipcode
 WHERE base_state_zipcode!='' AND country_code='US' GROUP BY stateid
SQL
);
/*foreach ($xcart_states_US as $k => $v) {
    $xcart_states_US[$k]["city"] = func_query_first_cell("SELECT city FROM $sql_tbl[geo_litecity_location] WHERE country='US' AND postalCode='$v[base_state_zipcode]'");
}*/

 if ($config[LOG_CATEGORY] == "Y") {
    func_backprocess_log('incremental feeds', 'Already launched');
    Xcart\Mail::model()->
    setTo('team@s3stores.com')->
    setFrom('team@s3stores.com')->
    setBody(LOG_CATEGORY. ' already launched')->
    setSubject(sprintf('Attention! Xcart cron %s Already launched', LOG_CATEGORY))->sendEmail();
    die("Already launched");
}

db_query("REPLACE $sql_tbl[config] SET value='Y', name='".LOG_CATEGORY."'");

$started_at = $start_time = time();

func_backprocess_log("incremental feeds", " ");
$log_text = " * * *  Cron started  * * * SUBMIT_DISABLE = '" . SUBMIT_DISABLE . "', EXTRA_LOG = '" . EXTRA_LOG . "'";
func_backprocess_log("incremental feeds", $log_text);


#
##
###
$current_hour = date("G", $started_at);
if ($current_hour == "0") {

    $cur_day_str = date("m-d-Y", $started_at);

    $products = db_query("SELECT eta_date_mm_dd_yyyy, productid FROM xcart_products WHERE eta_date_mm_dd_yyyy!='' AND forsale = 'Y'");

    $counter = 0;
    while ($product = db_fetch_array($products)) {

        $counter++;
        if ($counter % 10 == 0) {
            func_flush(".");
            if ($counter % 500 == 0) {
                func_flush("<br />\n");
            }
            func_flush();
        }

        $productid = $product["productid"];
        $eta_date_mm_dd_yyyy = $product["eta_date_mm_dd_yyyy"];

        $eta_date_mm_dd_yyyy_str = date("m-d-Y", $eta_date_mm_dd_yyyy);

        if ($eta_date_mm_dd_yyyy_str == $cur_day_str) {
            db_query($qqq = "INSERT IGNORE INTO xcart_cidev_updated_products (resourceid, type, time_stamp, source) VALUES ('$productid', '2', '" . time() . "', 'eta_end')");
        }
    }
    db_free_result($product);
}

$two_shippings = func_query_hash("SELECT shippingid, shipping, vol_threshold, dim_factor FROM $sql_tbl[shipping] WHERE shippingid='1' OR shippingid='65'", "shippingid", false);

$all_froogle_options = func_query_hash(" SELECT storefrontid, MerchantID, ClientID, BingMerchantID, BingCatalogID, enable_incremental_feed_updates FROM $sql_tbl[froogle_options]", 'storefrontid', false);


if (!empty($all_froogle_options) && is_array($all_froogle_options)) {
    foreach ($all_froogle_options as $k => $v) {
        $all_froogle_options[$k]["ClientID"] = text_decrypt($v["ClientID"]);
    }
}

$cidev_storefronts = $storefronts;
ksort($cidev_storefronts);

if (!empty($cidev_storefronts) && is_array($cidev_storefronts)) {

    foreach ($cidev_storefronts as $storefrontid => $sf_info) {
        $cidev_storefronts[$storefrontid] = func_get_storefront_info($storefrontid);
    }

    $cidev_storefronts[0] = func_get_storefront_info(0);


    $UpdateProductsOverview = func_query_first_cell("Select count(*) As saleable_count
From
(Select 
                UP.resourceid As productid,
                UP.time_stamp As ts,
                MIN(UP2.`type`) As utype
from xcart_cidev_updated_products UP
                left join xcart_cidev_updated_products UP2 ON UP2.resourceid = UP.resourceid and UP2.`type` <= 2
                inner join xcart_products_sf PS ON PS.productid = UP.resourceid
                left join xcart_products P ON P.productid = UP.resourceid
where UP.`type` <= 2 and /*P.min_amount=1 and*/ P.forsale = 'Y'
group by UP.resourceid
UNION
Select 
                P2.productid As productid,
                UPM.time_stamp As ts,
                1 As utype
 From xcart_cidev_updated_products UPM
                left join xcart_products P2 ON P2.manufacturerid = UPM.resourceid 
                inner join xcart_products_sf PS ON PS.productid = P2.productid
 where UPM.`type` = 3 and P2.forsale = 'Y' /*and P2.min_amount=1*/) As T
 where T.productid > 0");

    if ($UpdateProductsOverview > 0) {
        $paramYN = 'Y';
        $PARAMLIMIT = 'LIMIT 3000';
    } else {
        $paramYN = 'N';
        $PARAMLIMIT = 'LIMIT 1000';
        $log_text = "//// processing DISCONTINUED ITEMS ";
        func_backprocess_log("incremental feeds", $log_text);
    }

    $amazon_inventory_batch_count = 0;
    $amazon_products_batch_count = 0;
    $ainventory = array();
    $aproducts = array();

    $BingMerchantID = '';
    $BingCatalogID = '';

    foreach ($cidev_storefronts as $storefrontid => $sf_info) {

        print("\n " . strftime("%X") . " --- storefront: " . $storefrontid . " --- \n");

        /** @var Xcart\External_Marketplaces\StoreFrontMarketPlace[] $aExternalMarketPlaces */
        $aExternalMarketPlaces = Xcart\External_Marketplaces\StoreFrontMarketPlace::getMarketPlacesByStoreFront($storefrontid);

        $cnt = 0;

        $enable_incremental_feed_updates = func_query_first_cell("SELECT enable_incremental_feed_updates FROM $sql_tbl[froogle_options] WHERE storefrontid='$storefrontid'");


        $query_products_count = func_query_first_cell("
        Select COUNT(*)
        From
        (Select 
                        UP.resourceid As productid,
                        UP.time_stamp As ts,
                        P.forsale As forsale,
                        P.amazon_enabled As amazon_enabled,
                        GROUP_CONCAT(Distinct UP2.`type` ORDER BY UP2.`type`) As utype,
                        max(PS2.sfid) as maxsf
        from xcart_cidev_updated_products UP
                        left join xcart_cidev_updated_products UP2 ON UP2.resourceid = UP.resourceid and UP2.`type` <= 2
                        inner join xcart_products_sf PS ON PS.productid = UP.resourceid and PS.sfid = '$storefrontid'
                        left join xcart_products_sf PS2 ON PS2.productid = UP.resourceid
                        left join xcart_products P ON P.productid = UP.resourceid
        where UP.`type` <= 2  and P.forsale = '$paramYN'
        group by UP.resourceid
        HAVING utype = '2')
        As T
         where T.productid > 0");

        if (!empty($query_products_count)) {
            $query_products = "
                Select *
                From
                (Select 
                                UP.resourceid As productid,
                                UP.time_stamp As ts,
                                P.forsale As forsale,
                                P.amazon_enabled As amazon_enabled,
                                GROUP_CONCAT(Distinct UP2.`type` ORDER BY UP2.`type`) As utype,
                                max(PS2.sfid) as maxsf
                from xcart_cidev_updated_products UP
                                left join xcart_cidev_updated_products UP2 ON UP2.resourceid = UP.resourceid and UP2.`type` <= 2
                                inner join xcart_products_sf PS ON PS.productid = UP.resourceid and PS.sfid = '$storefrontid'
                                left join xcart_products_sf PS2 ON PS2.productid = UP.resourceid
                                left join xcart_products P ON P.productid = UP.resourceid
                where UP.`type` <= 2  and P.forsale = '$paramYN'
                group by UP.resourceid
                HAVING utype = '2')
                As T
                 where T.productid > 0";
        } else {

            $query_products_count = func_query_first_cell("
            Select COUNT(*)
            From
            (Select 
                            UP.resourceid As productid,
                            UP.time_stamp As ts,
                            P.forsale As forsale,
                            P.amazon_enabled As amazon_enabled,
                            GROUP_CONCAT(Distinct UP2.`type` ORDER BY UP2.`type`) As utype,
                            max(PS2.sfid) as maxsf
            from xcart_cidev_updated_products UP
                            left join xcart_cidev_updated_products UP2 ON UP2.resourceid = UP.resourceid and UP2.`type` <= 2
                            inner join xcart_products_sf PS ON PS.productid = UP.resourceid and PS.sfid = '$storefrontid'
                            left join xcart_products_sf PS2 ON PS2.productid = UP.resourceid
                            left join xcart_products P ON P.productid = UP.resourceid
            where UP.`type` <= 2 and P.forsale = '$paramYN'
            group by UP.resourceid
            $PARAMLIMIT
            UNION
            Select 
                            P2.productid As productid,
                            UPM.time_stamp As ts,
                            P2.forsale As forsale,
                            P2.amazon_enabled As amazon_enabled,
                            1 As utype,
                            max(PS2.sfid) as maxsf
             From xcart_cidev_updated_products UPM
                            left join xcart_products P2 ON P2.manufacturerid = UPM.resourceid
                            inner join xcart_products_sf PS ON PS.productid = P2.productid and PS.sfid = '$storefrontid'
                            left join xcart_products_sf PS2 ON PS.productid = PS2.productid
             where UPM.`type` = 3 and P2.forsale='$paramYN') As T
             where T.productid > 0");

            if (!empty($query_products_count)) {
                $query_products = "
                        Select *
                        From
                        (Select 
                                        UP.resourceid As productid,
                                        UP.time_stamp As ts,
                                        P.forsale As forsale,
                                        P.amazon_enabled As amazon_enabled,
                                        GROUP_CONCAT(Distinct UP2.`type` ORDER BY UP2.`type`) As utype,
                                        max(PS2.sfid) as maxsf
                        from xcart_cidev_updated_products UP
                                        left join xcart_cidev_updated_products UP2 ON UP2.resourceid = UP.resourceid and UP2.`type` <= 2
                                        inner join xcart_products_sf PS ON PS.productid = UP.resourceid and PS.sfid = '$storefrontid'
                                        left join xcart_products_sf PS2 ON PS2.productid = UP.resourceid
                                        left join xcart_products P ON P.productid = UP.resourceid
                        where UP.`type` <= 2 and P.forsale = '$paramYN'
                        group by UP.resourceid
                        $PARAMLIMIT
                        UNION
                        Select 
                                        P2.productid As productid,
                                        UPM.time_stamp As ts,
                                        P2.forsale As forsale,
                                        P2.amazon_enabled As amazon_enabled,
                                        1 As utype,
                                        max(PS2.sfid) as maxsf
                         From xcart_cidev_updated_products UPM
                                        left join xcart_products P2 ON P2.manufacturerid = UPM.resourceid 
                                        inner join xcart_products_sf PS ON PS.productid = P2.productid and PS.sfid = '$storefrontid'
                                        left join xcart_products_sf PS2 ON PS.productid = PS2.productid
                         where UPM.`type` = 3 and P2.forsale='$paramYN') As T
                         where T.productid > 0";
            } else {
                $tparamYN = $paramYN;
                $tPARAMLIMIT = $PARAMLIMIT;
                $paramYN = 'N';
                $PARAMLIMIT = 'LIMIT 130';

                $query_products = "
                        Select *
                        From
                        (Select 
                                        UP.resourceid As productid,
                                        UP.time_stamp As ts,
                                        P.forsale As forsale,
                                        P.amazon_enabled As amazon_enabled,
                                        1 As utype,
                                        max(PS2.sfid) as maxsf
                        from xcart_cidev_updated_products UP
                                        left join xcart_cidev_updated_products UP2 ON UP2.resourceid = UP.resourceid and UP2.`type` <= 2
                                        inner join xcart_products_sf PS ON PS.productid = UP.resourceid and PS.sfid = '$storefrontid'
                                        left join xcart_products_sf PS2 ON PS2.productid = PS.productid
                                        left join xcart_products P ON P.productid = UP.resourceid
                        where UP.`type` <= 2 and P.forsale = '$paramYN'
                        group by UP.resourceid
                        UNION
                        Select 
                                        P2.productid As productid,
                                        UPM.time_stamp As ts,
                                        P2.forsale As forsale,
                                        P2.amazon_enabled As amazon_enabled,
                                        1 As utype,
                                        max(PS2.sfid) as maxsf
                         From xcart_cidev_updated_products UPM
                                        left join xcart_products P2 ON P2.manufacturerid = UPM.resourceid 
                                        inner join xcart_products_sf PS ON PS.productid = P2.productid and PS.sfid = '$storefrontid'
                                        left join xcart_products_sf PS2 ON PS2.productid = PS.productid
                         where UPM.`type` = 3 and P2.forsale='$paramYN') As T
                         where T.productid > 0
                         $PARAMLIMIT";
                $paramYN = $tparamYN;
                $PARAMLIMIT = $tPARAMLIMIT;
            }
        }

        $products = db_query($query_products);


        while ($product = db_fetch_array($products)) {
            $oProduct = new Xcart\Product(['productid'=>$product['productid']]);
            if ($storefrontid == $product["maxsf"])
                db_query("DELETE FROM xcart_cidev_updated_products WHERE resourceid='$product[productid]' AND time_stamp <= '$started_at' AND (type='2' || type='1')");

                db_query("UPDATE $sql_tbl[products] SET last_incremental_update='" . time() . "' WHERE productid='" . $product["productid"] . "'");
                $googleOneRow = null;
                foreach ($aExternalMarketPlaces as $oExternalMarketPlace) {
                    if (is_null($googleOneRow) && in_array($product["utype"], ['1', '1,2', '2,1'])) {
                        $googleOneRow = $oExternalMarketPlace->getGoogleOneRow($oProduct, EXTRA_LOG);
                    }
                    if ($oExternalMarketPlace->getExternalMarketPlaceEntity()->getMarketPlaceStatus() == 'Y') {
                        $oExternalMarketPlace->addProductToBatch($oProduct, $product["utype"], $googleOneRow, EXTRA_LOG);
                    }

                    if ($oExternalMarketPlace->getCurrentInventoryBatchCount() == $oExternalMarketPlace->getInventoryBatchCount()) {
                        $oExternalMarketPlace->submitInventoryBatch(SUBMIT_DISABLE, EXTRA_LOG);
                    }
                    if ($oExternalMarketPlace->getCurrentProductsBatchCount() == $oExternalMarketPlace->getProductsBatchCount()) {
                        $oExternalMarketPlace->submitProductsBatch(SUBMIT_DISABLE, EXTRA_LOG);
                    }

                }
            $cnt++;
        }
        db_free_result($products);

        foreach ($aExternalMarketPlaces as $oExternalMarketPlace) {
            $aInventory = $oExternalMarketPlace->getInventory();
            if ($oExternalMarketPlace->getCurrentInventoryBatchCount() > 0 && !empty($aInventory) && is_array($aInventory)) {
                $oExternalMarketPlace->submitInventoryBatch(SUBMIT_DISABLE, EXTRA_LOG);
            }
            $aProducts = $oExternalMarketPlace->getProducts();
            if ($oExternalMarketPlace->getCurrentProductsBatchCount() > 0 && !empty($aProducts) && is_array($aProducts)) {
                $oExternalMarketPlace->submitProductsBatch(SUBMIT_DISABLE, EXTRA_LOG);
            }
        }

        print ("processed: " . $cnt . " items !!>\n");

        if ($cnt > 0) {
            $log_text = "Storefront: " . $sf_info["domain"] . " Storefrontid: " . $sf_info["storefrontid"];
            func_backprocess_log("incremental feeds", $log_text);
            $log_text = "processed: " . $cnt . " items";
            func_backprocess_log("incremental feeds", $log_text);
        }
    }

    db_query("DELETE FROM xcart_cidev_updated_products WHERE type='3' AND time_stamp <= '$started_at'");
}


$current_time = time();

$pid_diff = $current_time - $start_time;
$hour = intval($pid_diff / (60 * 60));
$minutes = intval(($pid_diff - $hour * 60 * 60) / 60);
$seconds = ($pid_diff - $hour * 60 * 60 - $minutes * 60);


//        print ("Why we dont update params ?");
db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='cidev_incremental_feeds_launched_v_3'");
//        print ("We done correctly ?");

$log_text = "Cron completed. Duration: " . sprintf("%02d:%02d:%02d", $hour, $minutes, $seconds) . " sec.";
func_backprocess_log("incremental feeds", $log_text);

die("DONE!");
