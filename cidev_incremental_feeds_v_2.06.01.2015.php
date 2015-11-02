<?php
define("CIDEV_CRON_START", "CRON");
session_start();


include_once "google-api-php-client/examples/templates/base.php";
require_once "./google-api-php-client/autoload.php";


require "./top.inc.php";
require "./init.php";


### Amazon ###
include_once "MarketplaceWebService/Samples/.config.inc.php";
require_once "MarketplaceWebService/Client.php";
require_once "MarketplaceWebService/Exception.php";
require_once "MarketplaceWebService/Model/SubmitFeedRequest.php";

$a_config = array (
  'ServiceURL' => "https://mws.amazonservices.com",
  'ProxyHost' => null,
  'ProxyPort' => -1,
  'MaxErrorRetry' => 3,
);

$marketplaceIdArray = array("Id" => array('ATVPDKIKX0DER'));
### ###



define("FROOGLE_TAIL", '...');
define("FROOGLE_TAIL_LEN", strlen(constant("FROOGLE_TAIL")));
define('FROOGLE_MAX_DESCRIPTION_LENGTH', 10 * 1024); //The content in an attribute in an item exceeds 10 KB.

define('EXCLUDE_CATEGORYID_BRANCH', 5099);

ini_set('memory_limit', '512M');
set_time_limit(0);

x_load('backoffice','files','taxes', 'froogle', 'product', 'crypt');

if ($config["cidev_incremental_feeds_launched_v_2"] == "Y"){
        die("Already launched"); // ################################
}
db_query("UPDATE $sql_tbl[config] SET value='Y' WHERE name='cidev_incremental_feeds_launched_v_2'");
//db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='cidev_incremental_feeds_launched_v_2'");

$started_at = time();

$log_text = " * * *  Cron started  * * * ";
func_backprocess_log("incremental feeds", $log_text);

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

print("\n ".strftime("%X")." --- storefront: ".$storefrontid." --- \n");



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


$google_inventory_batch_count = 0;
$google_products_batch_count = 0;
$ginventory = array(); // или new Google_Service_ShoppingContent_InventoryCustomBatchRequest()
$gproducts = array(); // или new Google_Service_ShoppingContent_ProductsCustomBatchRequest()
$max_google_batch = 299;
$amazon_inventory_batch_count = 0;
$amazon_products_batch_count = 0;
$ainventory = array();
$aproducts = array();
$max_amazon_batch = 3000;

$cnt = 0;

$enable_incremental_feed_updates = func_query_first_cell("SELECT enable_incremental_feed_updates FROM $sql_tbl[froogle_options] WHERE storefrontid='$storefrontid'");

/*
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
*/

$query_products = "
Select *
from
(Select 
                UP.resourceid As productid,
                UP.time_stamp As ts,
                P.forsale As forsale,
                P.amazon_enabled As amazon_enabled,
                MIN(UP2.`type`) As utype
from xcart_cidev_updated_products UP
                left join xcart_cidev_updated_products UP2 ON UP2.resourceid = UP.resourceid and UP2.`type` <= 2
                inner join xcart_products_sf PS ON PS.productid = UP.resourceid and PS.sfid = '$storefrontid'
                left join xcart_products P ON P.productid = UP.resourceid
where UP.`type` <= 2 and P.min_amount=1 and UP.`time_stamp`<'$started_at'
group by UP.resourceid
UNION
Select 
                P2.productid As productid,
                UPM.time_stamp As ts,
                P2.forsale As forsale,
                P2.amazon_enabled As amazon_enabled,
                1 As utype
 From xcart_cidev_updated_products UPM
                left join xcart_products P2 ON P2.manufacturerid = UPM.resourceid 
                inner join xcart_products_sf PS ON PS.productid = P2.productid and PS.sfid = '$storefrontid'
 where UPM.`type` = 3 and P2.forsale = 'Y' and P2.min_amount=1 and UPM.`time_stamp`< '$started_at') as T";
//where T.productid not in (320764,320761,320762,320764,320765,320766)";

			$products = db_query($query_products);

			while ($product = db_fetch_array($products)){


//if (!($product["productid"] == "198351" || $product["productid"] == "44058"))
//continue;

        		        if ($enable_incremental_feed_updates == "Y" && !empty($MerchantID) && !empty($client_id)){

//					SubmitProductToGBFeed($product["productid"], $MerchantID, $client_id, $key_file_location, $product["utype"], $service, $product["forsale"]);

					$AddProductToGoogleBaseBatch_arr = AddProductToGoogleBaseBatch($product["productid"], $MerchantID, $product["utype"], $service, $product["forsale"], $google_products_batch_count, $gproducts, $google_inventory_batch_count, $ginventory);

					if (!empty($AddProductToGoogleBaseBatch_arr) && is_array($AddProductToGoogleBaseBatch_arr)){
						$google_products_batch_count = $AddProductToGoogleBaseBatch_arr["google_products_batch_count"];
						$gproducts = $AddProductToGoogleBaseBatch_arr["gproducts"];
						$google_inventory_batch_count = $AddProductToGoogleBaseBatch_arr["google_inventory_batch_count"];
						$ginventory = $AddProductToGoogleBaseBatch_arr["ginventory"];
					}
				}

//$tmp_products = func_query($query_products);
//func_print_r($tmp_products, $storefrontid);
//die("E");


//func_print_r($AddProductToGoogleBaseBatch_arr);
//die("A");

				if ($product["amazon_enabled"] == "Y" && $product["forsale"] == "Y"){
					$AddProductToAmazonBatch_arr = AddProductToAmazonBatch($product["productid"], $product["utype"], $amazon_inventory_batch_count, $ainventory);

					if (!empty($AddProductToAmazonBatch_arr) && is_array($AddProductToAmazonBatch_arr)){
						$ainventory = $AddProductToAmazonBatch_arr["ainventory"];
						$amazon_inventory_batch_count = $AddProductToAmazonBatch_arr["amazon_inventory_batch_count"];

					}
				}

				db_query("DELETE FROM xcart_cidev_updated_products WHERE resourceid='$product[productid]' AND time_stamp <= '$started_at' AND (type='2' || type='1')");
				db_query("UPDATE $sql_tbl[products] SET last_incremental_update='".time()."' WHERE productid='".$product["productid"]."'");


				if ($google_inventory_batch_count == 3 * $max_google_batch){
					$SubmitGoogleInventoryBatch_arr = SubmitGoogleInventoryBatch($ginventory, $service, $MerchantID);

					$google_inventory_batch_count = 0;
					$ginventory = array();
				}

				if ($google_products_batch_count == $max_google_batch){
					$SubmitGoogleProductsBatch_arr = SubmitGoogleProductsBatch($gproducts, $service, $MerchantID);

					$google_products_batch_count = 0;
					$gproducts = array();
				}


				if ($amazon_inventory_batch_count == $max_amazon_batch){
					$SubmitAmazonInventoryBatch_arr = SubmitAmazonInventoryBatch($ainventory, $a_config, $marketplaceIdArray);

					$amazon_inventory_batch_count = 0;
					$ainventory = array();
				}

				if ($amazon_products_batch_count == $max_amazon_batch){
					$SubmitAmazonProductsBatch_arr = SubmitAmazonProductsBatch();
					$amazon_products_batch_count = 0;
					$aproducts = array();
				}

			        $cnt++;
			        if ($cnt % 10 == 0) {
			                func_flush(".");
			                if($cnt % 500 == 0) {
			                        func_flush("");
			                }
			                func_flush();
			        }
			}
			db_free_result($products);
			print ("processed: ".$cnt." items !!>\n");

//func_print_r($ginventory, $gproducts, $google_inventory_batch_count, $google_products_batch_count, $amazon_inventory_batch_count, $amazon_products_batch_count);
//die();

			if ($google_inventory_batch_count >= 1 && !empty($ginventory) && is_array($ginventory)){
				$SubmitGoogleInventoryBatch_arr = SubmitGoogleInventoryBatch($ginventory, $service, $MerchantID);
			}
			if ($google_products_batch_count >= 1 && !empty($gproducts) && is_array($gproducts)){
				$SubmitGoogleProductsBatch_arr = SubmitGoogleProductsBatch($gproducts, $service, $MerchantID);
			}
			if ($amazon_inventory_batch_count >= 1){
				$SubmitAmazonInventoryBatch_arr = SubmitAmazonInventoryBatch($ainventory, $a_config, $marketplaceIdArray);
			}
			if ($amazon_products_batch_count >= 1){
				$SubmitAmazonProductsBatch_arr = SubmitAmazonProductsBatch();
			}
			
			if ($cnt > 0) 
			    {
				$log_text = "Storefront: ".$sf_info["domain"]." Storefrontid: ".$sf_info["storefrontid"];
				func_backprocess_log("incremental feeds", $log_text);
				$log_text = "processed: ".$cnt." items";
				func_backprocess_log("incremental feeds", $log_text);
			    }
	}
//        print ("Why we dont delete utype 3 ?");
	db_query("DELETE FROM xcart_cidev_updated_products WHERE type='3' AND time_stamp <= '$started_at'");
}


$finished_at = time();

$duration = $started_at - $finished_at;
$duration = $duration/(60*60);
$duration = round($duration,1);

/*
$subj = "Finish googlebase2 process";
$body = "Started at: ".date("Y-m-d H:i:s", $started_at)."\n";
$body .= "Finished at: ".date("Y-m-d H:i:s", $finished_at)."\n";
$body .= "Duration: ".$duration." Hours\n";
func_send_simple_mail($to, $subj, $body, $from);
*/
//        print ("Why we dont update params ?");
	db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='cidev_incremental_feeds_launched_v_2'");
//        print ("We done correctly ?");

$log_text = "Cron completed. Duration: ".$duration." hours";
func_backprocess_log("incremental feeds", $log_text);

die("DONE!");
?>
