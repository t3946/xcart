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


/*
if ($config["cidev_incremental_feeds_launched"] == "Y"){
        die("Already launched"); // ################################
}
db_query("UPDATE $sql_tbl[config] SET value='Y' WHERE name='cidev_incremental_feeds_launched'");
//db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='cidev_incremental_feeds_launched'");
*/

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




/*
$tmp_cidev_storefronts[10] = $cidev_storefronts[10];
unset($cidev_storefronts);
$cidev_storefronts = $tmp_cidev_storefronts;
*/



	$usleep_time1 = $config["Froogle"]["froogle_interval_queries"] * 1000;
	$usleep_time2 = $config["Froogle"]["froogle_interval_block_queries"] * 1000;


	foreach ($cidev_storefronts as $storefrontid => $sf_info){

		$enable_incremental_feed_updates = func_query_first_cell("SELECT enable_incremental_feed_updates FROM $sql_tbl[froogle_options] WHERE storefrontid='$storefrontid'");
		if ($enable_incremental_feed_updates != "Y"){
			continue;
		}
		
		print ("\nsf ".$storefrontid."\n");


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


		$product_min_max = func_query_first("
		Select 
			MAX(P.productid) as max, MIN(P.productid) as min
		From xcart_products P
			left join xcart_products_sf PS ON PS.productid = P.productid
		Where P.forsale = 'Y' and PS.sfid = '$storefrontid'");


		$min_productid_for_sf = $product_min_max["min"];
		$max_productid_for_sf = $product_min_max["max"];

		$test= "";
//$test = " or P.productid='74864'";

		$products_query = "	Select 
					    P.productid
					From xcart_k.xcart_products P
						left join xcart_k.xcart_products_sf PS ON PS.productid = P.productid
					Where P.forsale = 'Y' and PS.sfid = $storefrontid and (
					(
					  (MOD(TO_DAYS(CURDATE()),29)+1 =  Round(29 * (P.productid - $min_productid_for_sf)/($max_productid_for_sf - $min_productid_for_sf)+1))
					   and
					  (DATEDIFF(CURDATE(), FROM_UNIXTIME(P.last_incremental_update) )>29)
					) 
					)
			$test
			";

		$products = db_query($products_query);


		while ($product = db_fetch_array($products)){

//$tmp_products = func_query($products_query);
//func_print_r($tmp_products);
//die("E");

			Submit_expirationDate_ToGBFeed($product["productid"], $MerchantID, $client_id, $key_file_location, $service);

		        $cnt++;
		        if ($cnt % 10 == 0) {
		                func_flush(".");
		                if($cnt % 500 == 0) {
		                        func_flush("<br />\n");
		                }
		                func_flush();
		        }
//			usleep($usleep_time1); 
		}
		db_free_result($products);
//		usleep($usleep_time2);
	}
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

//db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='cidev_incremental_feeds_launched'");

die("DONE!");
?>
