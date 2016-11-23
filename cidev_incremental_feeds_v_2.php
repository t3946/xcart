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





#
##
###
$debug_requests = 'N';
$froogle_tracing_token = 'ANY78kLeWOxH4je4ZmHHsdNUGUhaxDLr2qkUcqeZ3MPGH1qjH2RdLqjUjqYTc95GthRPCu8dconorTv7DtGlvI5RDlQlVyq4xzMqr9hiS5aaTT9NlPQrsJc';
###
##
#

define("FROOGLE_TAIL", '...');
define("FROOGLE_TAIL_LEN", strlen(constant("FROOGLE_TAIL")));
define('FROOGLE_MAX_DESCRIPTION_LENGTH', 10 * 1024); //The content in an attribute in an item exceeds 10 KB.

define('EXCLUDE_CATEGORYID_BRANCH', 5099);
define('SUBMIT_DISABLE', 'N');
define('EXTRA_LOG', 'N');

ini_set('memory_limit', '512M');
set_time_limit(0);

x_load('backoffice','files','taxes', 'froogle', 'product', 'crypt');


$xcart_states_US = func_query("SELECT state, code, country_code, base_state_zipcode FROM $sql_tbl[states] WHERE base_state_zipcode!='' AND country_code='US'");
foreach ($xcart_states_US as $k => $v){
	$xcart_states_US[$k]["city"] = func_query_first_cell("SELECT city FROM $sql_tbl[geo_litecity_location] WHERE country='US' AND postalCode='$v[base_state_zipcode]'");
}


########################## for test purpose ##########################
# $GetGoogleBaseOneRow = GetGoogleBaseOneRow('281820');
# func_print_r($GetGoogleBaseOneRow);
# die("++++++++++++++++++++++++++++++++++++++++++++++++++++");
########################## ##########################





//db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='cidev_incremental_feeds_launched_v_2'");

if ($config["cidev_incremental_feeds_launched_v_2"] == "Y"){
//        die("Already launched"); // ################################
}

db_query("UPDATE $sql_tbl[config] SET value='Y' WHERE name='cidev_incremental_feeds_launched_v_2'");
//db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='cidev_incremental_feeds_launched_v_2'");




$started_at = time();

func_backprocess_log("incremental feeds", " ");
$log_text = " * * *  Cron started  * * * SUBMIT_DISABLE = '".SUBMIT_DISABLE."', EXTRA_LOG = '".EXTRA_LOG."'";
func_backprocess_log("incremental feeds", $log_text);


#
##
###
$current_hour = date("G", $started_at);
if ($current_hour == "0"){

	$cur_day_str = date("m-d-Y", $started_at);
	
	$products = db_query("SELECT eta_date_mm_dd_yyyy, productid FROM xcart_products WHERE eta_date_mm_dd_yyyy!='' AND forsale = 'Y'");

	$counter = 0;
	while ($product = db_fetch_array($products)) {

                                $counter++;
                                if ($counter % 10 == 0) {
                                        func_flush(".");
                                        if($counter % 500 == 0) {
                                                func_flush("<br />\n");
                                        }
                                        func_flush();
                                }

        	$productid = $product["productid"];
	        $eta_date_mm_dd_yyyy = $product["eta_date_mm_dd_yyyy"];

		$eta_date_mm_dd_yyyy_str = date("m-d-Y", $eta_date_mm_dd_yyyy);

		if ($eta_date_mm_dd_yyyy_str == $cur_day_str){
			db_query($qqq="INSERT IGNORE INTO xcart_cidev_updated_products (resourceid, type, time_stamp, source) VALUES ('$productid', '2', '".time()."', 'eta_end')");
	        }
	}
	db_free_result($product);
}
//die("==========TEST=======");
###
##
#

/*
$subj = "Start googlebase2 process";
$body = "Started at: ".date("Y-m-d H:i:s", $started_at)."\n";
$to = $config["Froogle"]["froogle_cron_email"];
$from = "orders@s3stores.com";
func_send_simple_mail($to, $subj, $body, $from);
*/


/*$all_manufacturer_info = func_query_hash("SELECT manufacturerid, manufacturer, m_city, m_country, m_state, m_zipcode FROM $sql_tbl[manufacturers]", 'manufacturerid', false);


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
}*/

$two_shippings = func_query_hash("SELECT shippingid, shipping, vol_threshold, dim_factor FROM $sql_tbl[shipping] WHERE shippingid='1' OR shippingid='65'", "shippingid", false);

$all_froogle_options = func_query_hash(" SELECT storefrontid, MerchantID, ClientID, BingMerchantID, BingCatalogID, enable_incremental_feed_updates FROM $sql_tbl[froogle_options]", 'storefrontid', false);


if (!empty($all_froogle_options) && is_array($all_froogle_options)){
	foreach ($all_froogle_options as $k => $v){
		$all_froogle_options[$k]["ClientID"] = text_decrypt($v["ClientID"]);
	}
}

$cidev_storefronts = $storefronts;
ksort($cidev_storefronts);

if (!empty($cidev_storefronts) && is_array($cidev_storefronts)){

	foreach ($cidev_storefronts as $storefrontid => $sf_info){
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
 where T.productid not in (320764,320761,320762,320764,320765,320766)");

	if ($UpdateProductsOverview > 0){
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

/*
7 Sporting Goods			55	51722 "www.7sportinggoods.com"
ACU Healthcare				42	51787 "www.acuhealthcare.com"
Artist Supply Source		0	51788 "www.artistsupplysource.com"
Astro Jewelry				12	51789 "www.astrojewelry.com"
Business Supply Source		35	51790 "www.businesssupplysource.com"
Electronic Toolbox			57	51791 "www.electronictoolbox.com"
Furnishings Mart			52	51792 "www.furnishingsmart.com"
Hunter Supply Source		50	51793 "www.huntersupplysource.com"
Just Poker Supplies			63	51794 "www.justpokersupplies.com"
Kid Stuff Station			41	51795 "www.kidstuffstation.com"
Light Kits and More			37	51796 "www.lightkitsandmore.com"
Musical Instrument Shoppe	62	51797 "www.musicalinstrumentshoppe.com"
Organic Life				56	51798 "www.organiclifesource.com"
Pet Supplies Place			59	51799 "www.petsuppliesplace.com"
RFID Locks and More			34	51800 "www.rfidlocksandmore.com"
Sincere Wedding				60	51801 "www.sincerewedding.com"
Teacher Supply Source		10	51802 "www.teachersupplysource.com"
Tradeshow Exhibitor Supply	38	51803 "www.tradeshowexhibitorsupply.com"
 */

/*
$bing_catalog = array(	55=>array( 'store' => 51722, 'catalog' =>46227 ),
						42=>array( 'store' => 51787, 'catalog' =>46276 ),
						0=>array( 'store' => 51788, 'catalog' =>46105 ),
						12=>array( 'store' => 51789, 'catalog' =>46225 ),
						35=>array( 'store' => 51790, 'catalog' =>46223 ), 
						57=>array( 'store' => 51791, 'catalog' =>42673 ),
						52=>array( 'store' => 51792, 'catalog' =>46210 ), 
						50=>array( 'store' => 51793, 'catalog' =>45947 ),
						63=>array( 'store' => 51794, 'catalog' =>46212 ), 
						41=>array( 'store' => 51795, 'catalog' =>46214 ),
						37=>array( 'store' => 51796, 'catalog' =>46216 ), 
						62=>array( 'store' => 51797, 'catalog' =>46218 ), 
						56=>array( 'store' => 51798, 'catalog' =>46220 ),
						59=>array( 'store' => 51799, 'catalog' =>46208 ), 
						34=>array( 'store' => 51800, 'catalog' =>46202 ),
						60=>array( 'store' => 51801, 'catalog' =>46204 ),
						10=>array( 'store' => 51802, 'catalog' =>46148 ),
						38=>array( 'store' => 51803, 'catalog' =>46206 ) );
*/
	foreach ($cidev_storefronts as $storefrontid => $sf_info)
	{

print("\n ".strftime("%X")." --- storefront: ".$storefrontid." --- \n");


#####################################################################################################################
#####################################################################################################################
#####################################################################################################################

if (!isset($all_froogle_options[$storefrontid]))
{
	$BingMerchantID = '';
	$BingCatalogID = '';
}
else
{
	$BingMerchantID = $all_froogle_options[$storefrontid]['BingMerchantID'];
	$BingCatalogID = $all_froogle_options[$storefrontid]['BingCatalogID'];
}

$bing_username = "API_s3stores";
$bing_password = "3QpmZz3V4xELHwGf";
$bing_token = "01122QXWZ9646473";

$MerchantID = $all_froogle_options[$storefrontid]["MerchantID"];
$client_id = $all_froogle_options[$storefrontid]["ClientID"]; //Client ID
$service_account_name = 'account-2@careful-triumph-774.iam.gserviceaccount.com'; //Email Address
$key_file_location = '/var/www/stores/google-api-php-client/examples/key2.p12'; //key.p12
/*
$service_account_name = '544879562678-602vuj5s9jo0hppg9tb3p07chk4g3mr3@developer.gserviceaccount.com'; //Email Address
$key_file_location = '/var/www/stores/google-api-php-client/examples/key.p12'; //key.p12
*/
//$key_file_location = '/vagrant/xcart/xcart/google-api-php-client/examples/key.p12'; //key.p12

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
if (SUBMIT_DISABLE!="Y") {
	if ($client->getAuth()->isAccessTokenExpired()) {
		$client->getAuth()->refreshTokenWithAssertion($cred);
	}
}
$_SESSION['service_token'] = $client->getAccessToken();
#####################################################################################################################
#####################################################################################################################
#####################################################################################################################


$google_inventory_batch_count = 0;
$google_products_batch_count = 0;
$ginventory = array(); // или new Google_Service_ShoppingContent_InventoryCustomBatchRequest()
$gproducts = array(); // или new Google_Service_ShoppingContent_ProductsCustomBatchRequest()

$bing_inventory_batch_count = 0;
$bing_products_batch_count = 0;
$binventory = array();
$bproducts = array();

$max_google_batch = 140;
$max_amazon_batch = 2000;
$max_bing_batch = 140;

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
         where T.productid not in (320764,320761,320762,320764,320765,320766)");

if (!empty($query_products_count)){
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
                 where T.productid not in (320764,320761,320762,320764,320765,320766)";
    }
else {

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
             where T.productid not in (320764,320761,320762,320764,320765,320766)");
     
    if (!empty($query_products_count)){
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
                         where T.productid not in (320764,320761,320762,320764,320765,320766)";
            }    
    else
            {
                $tparamYN = $paramYN;
                $tPARAMLIMIT = $PARAMLIMIT;
                $paramYN = 'N';
                $PARAMLIMIT = 'LIMIT 130';
                //$log_text = "//// processing SF DISCONTINUED ITEMS ";
                //func_backprocess_log("incremental feeds", $log_text);
                
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
                         where T.productid not in (320764,320761,320762,320764,320765,320766)
                         $PARAMLIMIT";
                $paramYN = $tparamYN;
                $PARAMLIMIT = $tPARAMLIMIT;
            }
    }
 

			$products = db_query($query_products);

			while ($product = db_fetch_array($products))
			{

				if ( $enable_incremental_feed_updates == "Y" && $BingMerchantID != '' && $BingCatalogID != '' )
				{
					$AddProductToBingBaseBatch_arr = AddProductToBingBaseBatch($product["productid"],$product["utype"],$product["forsale"],$bing_products_batch_count,$bproducts,$bing_inventory_batch_count,$binventory);

					if (!empty($AddProductToBingBaseBatch_arr) && is_array($AddProductToBingBaseBatch_arr))
					{
						$bing_products_batch_count = $AddProductToBingBaseBatch_arr["bing_products_batch_count"];
						$bproducts = $AddProductToBingBaseBatch_arr["bproducts"];
						$bing_inventory_batch_count = $AddProductToBingBaseBatch_arr["bing_inventory_batch_count"];
						$binventory = $AddProductToBingBaseBatch_arr["binventory"];
					}
				}

				if ($enable_incremental_feed_updates == "Y" && !empty($MerchantID) && !empty($client_id))
				{

					$AddProductToGoogleBaseBatch_arr = AddProductToGoogleBaseBatch($product["productid"], $MerchantID, $product["utype"], $service, $product["forsale"], $google_products_batch_count, $gproducts, $google_inventory_batch_count, $ginventory, EXTRA_LOG);

					if (!empty($AddProductToGoogleBaseBatch_arr) && is_array($AddProductToGoogleBaseBatch_arr)){
						$google_products_batch_count = $AddProductToGoogleBaseBatch_arr["google_products_batch_count"];
						$gproducts = $AddProductToGoogleBaseBatch_arr["gproducts"];
						$google_inventory_batch_count = $AddProductToGoogleBaseBatch_arr["google_inventory_batch_count"];
						$ginventory = $AddProductToGoogleBaseBatch_arr["ginventory"];
					}
				}

				if ($product["amazon_enabled"] == "Y" /*&& $product["forsale"] == "Y"*/){
					$AddProductToAmazonBatch_arr = AddProductToAmazonBatch($product["productid"], $product["utype"], $amazon_inventory_batch_count, $ainventory);

					if (!empty($AddProductToAmazonBatch_arr) && is_array($AddProductToAmazonBatch_arr)){
						$ainventory = $AddProductToAmazonBatch_arr["ainventory"];
						$amazon_inventory_batch_count = $AddProductToAmazonBatch_arr["amazon_inventory_batch_count"];

					}
				}

###
				if ($storefrontid == $product["maxsf"])
					db_query("DELETE FROM xcart_cidev_updated_products WHERE resourceid='$product[productid]' AND time_stamp <= '$started_at' AND (type='2' || type='1')");

				db_query("UPDATE $sql_tbl[products] SET last_incremental_update='".time()."' WHERE productid='".$product["productid"]."'");
###

					//Bing Batch Array = Google Batch Array
					$bproducts = $gproducts;

					if ($bing_inventory_batch_count == $max_bing_batch)
					{
						$error = SubmitBingInventoryBatch($binventory, $BingMerchantID, $BingCatalogID, $bing_username, $bing_password, $bing_token, SUBMIT_DISABLE);
						//$error = SubmitBingProductsBatch($binventory, $BingMerchantID, $BingCatalogID, $bing_username, $bing_password, $bing_token);
						if ( $error == 500 )
							restore_queue( $binventory, 2 );

						$bing_inventory_batch_count = 0;
						$binventory = array();
					}

					if ($bing_products_batch_count == $max_bing_batch)
					{
						$error = SubmitBingProductsBatch($bproducts, $BingMerchantID, $BingCatalogID, $bing_username, $bing_password, $bing_token, SUBMIT_DISABLE);
						if ( $error == 500 )
							restore_queue( $bproducts, 1 );

						$bing_products_batch_count = 0;
						$bproducts = array();
					}

					if ($google_inventory_batch_count == $max_google_batch){
						$error = SubmitGoogleInventoryBatch($ginventory, $service, $MerchantID, SUBMIT_DISABLE, EXTRA_LOG);
						if ( $error == 500 )
							restore_queue( $ginventory, 2 );

						$google_inventory_batch_count = 0;
						$ginventory = array();
					}

					if ($google_products_batch_count == $max_google_batch){
						$error = SubmitGoogleProductsBatch($gproducts, $service, $MerchantID, SUBMIT_DISABLE);
						if ( $error == 500 )
							restore_queue( $gproducts, 1 );

						$google_products_batch_count = 0;
						$gproducts = array();
					}

					if ($amazon_inventory_batch_count == $max_amazon_batch){
						SubmitAmazonInventoryBatch($ainventory, $a_config, $marketplaceIdArray);

						$amazon_inventory_batch_count = 0;
						$ainventory = array();
					}

					if ($amazon_products_batch_count == $max_amazon_batch){
						SubmitAmazonProductsBatch();

						$amazon_products_batch_count = 0;
						$aproducts = array();
					}


				$cnt++;
			}
			db_free_result($products);


			if ($bing_inventory_batch_count >= 1 && !empty($binventory) && is_array($binventory))
			{
                $error = SubmitBingInventoryBatch($binventory, $BingMerchantID, $BingCatalogID, $bing_username, $bing_password, $bing_token, SUBMIT_DISABLE);
				//$error = SubmitBingProductsBatch($binventory, $BingMerchantID, $BingCatalogID, $bing_username, $bing_password, $bing_token);
				if ( $error == 500 )
					restore_queue( $binventory, 2 );
			}
			if ($bing_products_batch_count >= 1 && !empty($bproducts) && is_array($bproducts))
			{
				$error = SubmitBingProductsBatch($bproducts, $BingMerchantID, $BingCatalogID, $bing_username, $bing_password, $bing_token, SUBMIT_DISABLE);
				if ( $error == 500 )
					restore_queue( $bproducts, 1 );
			}

			if ($google_inventory_batch_count >= 1 && !empty($ginventory) && is_array($ginventory))
			{
				$error = SubmitGoogleInventoryBatch($ginventory, $service, $MerchantID, SUBMIT_DISABLE, EXTRA_LOG);
				if ( $error == 500 )
					restore_queue( $ginventory, 2 );
			}
			if ($google_products_batch_count >= 1 && !empty($gproducts) && is_array($gproducts))
			{
				$error = SubmitGoogleProductsBatch($gproducts, $service, $MerchantID, SUBMIT_DISABLE);
				if ( $error == 500 )
					restore_queue( $gproducts, 1 );
			}

			print ("processed: ".$cnt." items !!>\n");

			if ($cnt > 0) 
			{
				$log_text = "Storefront: ".$sf_info["domain"]." Storefrontid: ".$sf_info["storefrontid"];
				func_backprocess_log("incremental feeds", $log_text);
				$log_text = "processed: ".$cnt." items";
				func_backprocess_log("incremental feeds", $log_text);
			}
	}

	if ($amazon_inventory_batch_count >= 1)
			{
				SubmitAmazonInventoryBatch($ainventory, $a_config, $marketplaceIdArray);
			}
	if ($amazon_products_batch_count >= 1)
			{
				SubmitAmazonProductsBatch();
			}
//        print ("Why we dont delete utype 3 ?");
###
	db_query("DELETE FROM xcart_cidev_updated_products WHERE type='3' AND time_stamp <= '$started_at'");
###	


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

function restore_queue( $products, $mode )
{
	foreach( $products as $item )
	{
		$count = func_query_first_cell("SELECT COUNT(*) as count FROM xcart_cidev_updated_products WHERE resourceid='" . $item['productid'] . "' AND type=" . $mode . ";");
		if ( $count == 0 )
			db_query("INSERT INTO xcart_cidev_updated_products (`resourceid`,`type`,`time_stamp`,`source`) VALUES( '" . $item['productid'] . "', ". $mode .", " . time() . ", 're-queue' )");
	}
}
?>
