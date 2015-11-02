<?php
define("CIDEV_CRON_START", "CRON");

session_start();
include_once "google-api-php-client/examples/templates/base.php";
require_once "./google-api-php-client/autoload.php";


require "./top.inc.php";
require "./init.php";
//require "./auth.php";

define("FROOGLE_TAIL", '...');
define("FROOGLE_TAIL_LEN", strlen(constant("FROOGLE_TAIL")));
define('FROOGLE_MAX_DESCRIPTION_LENGTH', 10 * 1024); //The content in an attribute in an item exceeds 10 KB.

define('EXCLUDE_CATEGORYID_BRANCH', 5099);

ini_set('memory_limit', '512M');
set_time_limit(0);

x_load('backoffice','files','taxes', 'froogle', 'product', 'crypt');


if ($config["cidev_incremental_feeds_launched"] == "Y"){
        die("Already launched"); // ################################
}
db_query("UPDATE $sql_tbl[config] SET value='Y' WHERE name='cidev_incremental_feeds_launched'");
//db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='cidev_incremental_feeds_launched'");

$started_at = time();


/*
$subj = "Start googlebase2 process";
$body = "Started at: ".date("Y-m-d H:i:s", $started_at)."\n";
$to = $config["Froogle"]["froogle_cron_email"];
$from = "orders@s3stores.com";
func_send_simple_mail($to, $subj, $body, $from);
*/


$all_manufacturer_info = func_query_hash("SELECT manufacturerid, manufacturer, m_city, m_country, m_state, m_zipcode FROM $sql_tbl[manufacturers]", 'manufacturerid', false);

$all_approximation_shipping_rates_tmp = func_query("SELECT * FROM $sql_tbl[approximation_shipping_rates]");

if (!empty($all_manufacturer_info) && !empty($all_approximation_shipping_rates_tmp)){
	foreach ($all_manufacturer_info as $manufacturerid => $v){
		$counter = 0;
		foreach ($all_approximation_shipping_rates_tmp as $kk => $vv){
			if ($manufacturerid == $vv["manufacturerid"]){
				$all_approximation_shipping_rates[$manufacturerid][$counter] = $vv;
				$counter++;
			}
		}
	}
}

$two_shippings = func_query_hash("SELECT shippingid, shipping, vol_threshold, dim_factor FROM $sql_tbl[shipping] WHERE shippingid='1' OR shippingid='65'", "shippingid", false);


$all_froogle_options = func_query_hash(" SELECT storefrontid, MerchantID, ClientID, enable_incremental_feed_updates FROM $sql_tbl[froogle_options]", 'storefrontid', false);
if (!empty($all_froogle_options) && is_array($all_froogle_options)){
	foreach ($all_froogle_options as $k => $v){
		$all_froogle_options[$k]["ClientID"] = text_decrypt($v["ClientID"]);
	}
}


$cidev_storefronts = $storefronts;

if (!empty($cidev_storefronts) && is_array($cidev_storefronts)){

	foreach ($cidev_storefronts as $storefrontid => $sf_info){
		$cidev_storefronts[$storefrontid] = func_get_storefront_info($storefrontid);
	}

	$cidev_storefronts[0] = func_get_storefront_info(0);








	foreach ($cidev_storefronts as $storefrontid => $sf_info){

		$enable_incremental_feed_updates = func_query_first_cell("SELECT enable_incremental_feed_updates FROM $sql_tbl[froogle_options] WHERE storefrontid='$storefrontid'");
		if ($enable_incremental_feed_updates != "Y"){
			continue;
		}


#####################################################################################################################
#####################################################################################################################
#####################################################################################################################
$MerchantID = $all_froogle_options[$storefrontid]["MerchantID"];
$client_id = $all_froogle_options[$storefrontid]["ClientID"]; //Client ID
$service_account_name = '544879562678-602vuj5s9jo0hppg9tb3p07chk4g3mr3@developer.gserviceaccount.com'; //Email Address
$key_file_location = '/var/www/stores/google-api-php-client/examples/key.p12'; //key.p12

$client = new Google_Client();
$client->setApplicationName("Client_Library_Examples");
$service = new Google_Service_ShoppingContent($client);

if (isset($_SESSION['service_token'])) {
	$client->setAccessToken($_SESSION['service_token']);
}

$key = file_get_contents($key_file_location);
$cred = new Google_Auth_AssertionCredentials(
	$service_account_name,
	array('https://www.googleapis.com/auth/content'),
	$key
);
$client->setAssertionCredentials($cred);
if ($client->getAuth()->isAccessTokenExpired()) {
	$client->getAuth()->refreshTokenWithAssertion($cred);
}
$_SESSION['service_token'] = $client->getAccessToken();
#####################################################################################################################
#####################################################################################################################
#####################################################################################################################

	if (empty($MerchantID) || empty($client_id)){
		continue;
	}


/*
			$query_products = "
Select SQL_NO_CACHE
                UP.resourceid As productid,
                UP.time_stamp As ts,
                MIN(UP2.`type`) As utype
from xcart_cidev_updated_products UP
                left join xcart_cidev_updated_products UP2 ON UP2.resourceid = UP.resourceid and UP2.`type` <= 2
                inner join xcart_products_sf PS ON PS.productid = UP.resourceid and PS.sfid = '$storefrontid'
where UP.`type` <= 2
group by UP.resourceid
UNION
Select 
                P.productid As productid,
                UPM.time_stamp As ts,
                1 As utype
 From xcart_cidev_updated_products UPM
                left join xcart_products P ON P.manufacturerid = UPM.resourceid and P.forsale = 'Y'
                inner join xcart_products_sf PS ON PS.productid = P.productid and PS.sfid = '$storefrontid'
 where UPM.`type` = 3";
*/
print (strftime("%X").":               logged in, before query for ".$storefrontid."\n");

$query_products = "
Select * From
(Select 
                UP.resourceid As productid,
                UP.time_stamp As ts,
                P.forsale As forsale,
                MIN(UP2.`type`) As utype
from xcart_cidev_updated_products UP
                left join xcart_cidev_updated_products UP2 ON UP2.resourceid = UP.resourceid and UP2.`type` <= 2
                inner join xcart_products_sf PS ON PS.productid = UP.resourceid and PS.sfid = '$storefrontid'
                left join xcart_products P ON P.productid = UP.resourceid
where UP.`type` <= 2 and UP.`time_stamp`<'$started_at'
group by UP.resourceid
UNION
Select 
                P2.productid As productid,
                UPM.time_stamp As ts,
                P2.forsale As forsale,
                1 As utype
 From xcart_cidev_updated_products UPM
                left join xcart_products P2 ON P2.manufacturerid = UPM.resourceid 
                inner join xcart_products_sf PS2 ON PS2.productid = P2.productid and PS2.sfid = '$storefrontid'
 where UPM.`type` = 3 and UPM.`time_stamp`< '$started_at' and P2.forsale = 'Y') As T
 Order by T.utype desc";

			$products = db_query($query_products);

			$cnt = 0;
			
			while ($product = db_fetch_array($products)){


//$tmp_products = func_query($query_products);
//func_print_r($tmp_products, $storefrontid);
//die("E");

				
				db_query("UPDATE $sql_tbl[products] SET last_incremental_update='".time()."' WHERE productid='".$product["productid"]."'");

				print (strftime("%X").": cron script , try ".$product["productid"]);
				SubmitProductToGBFeed($product["productid"], $MerchantID, $client_id, $key_file_location, $product["utype"], $service, $product["forsale"]);




			        $cnt++;
			        if ($cnt % 10 == 0) {
			                func_flush(".");
			                if($cnt % 500 == 0) {
			                        func_flush("<br />\n");
			                }
			                func_flush();
			        }
			}
			db_free_result($products);
			print ("!!>\n");
	}
        print ("Why we dont delete utype 3 ?");
	db_query("DELETE FROM xcart_cidev_updated_products WHERE type='3' AND time_stamp <= '$started_at'");
}

/*
$finished_at = time();

$duration = $started_at - $finished_at;
$duration = $duration/(60*60);
$duration = round($duration,1);

$subj = "Finish googlebase2 process";
$body = "Started at: ".date("Y-m-d H:i:s", $started_at)."\n";
$body .= "Finished at: ".date("Y-m-d H:i:s", $finished_at)."\n";
$body .= "Duration: ".$duration." Hours\n";
func_send_simple_mail($to, $subj, $body, $from);
*/
        print ("Why we dont update params ?");
	db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='cidev_incremental_feeds_launched'");
        print ("We done correctly ?");

die("DONE!");
?>
