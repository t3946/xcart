<?php
/**
 * Created by PhpStorm.
 * User: Vyacheslav Zababurin
 * Date: 20.10.2015
 * Time: 11:48
 */

define("CIDEV_CRON_START", "CRON");
session_start();

require_once "./google-api-php-client/autoload.php";

require "./top.inc.php";
require "./init.php";

ini_set('memory_limit', '512M');
set_time_limit(0);

x_load('backoffice','files','taxes', 'froogle', 'product', 'crypt');

$started_at = time();

$log_text = "Full update started";
func_backprocess_log("full feeds update", $log_text);

$all_froogle_options = func_query_hash(" SELECT storefrontid, MerchantID, ClientID, enable_incremental_feed_updates FROM $sql_tbl[froogle_options]", 'storefrontid', false);

if (!empty($all_froogle_options) && is_array($all_froogle_options)){
    foreach ($all_froogle_options as $k => $v){
        $all_froogle_options[$k]["ClientID"] = text_decrypt($v["ClientID"]);
    }
}

$cidev_storefronts = $storefronts;

if (!empty($cidev_storefronts) && is_array($cidev_storefronts))
{
    foreach ($cidev_storefronts as $storefrontid => $sf_info) {
        $cidev_storefronts[$storefrontid] = func_get_storefront_info($storefrontid);
    }

    $cidev_storefronts[0] = func_get_storefront_info(0);

    foreach ($cidev_storefronts as $storefrontid => $sf_info)
    {
        print( strftime("%X")." --- storefront: ".$storefrontid." --- \n");

        $merchantId = $all_froogle_options[$storefrontid]["MerchantID"];
        $client_id = $all_froogle_options[$storefrontid]["ClientID"]; //Client ID
        $service_account_name = 'account-2@careful-triumph-774.iam.gserviceaccount.com'; //Email Address
        $key_file_location = '/var/www/stores/google-api-php-client/examples/key2.p12';
		/*
        $service_account_name = '544879562678-602vuj5s9jo0hppg9tb3p07chk4g3mr3@developer.gserviceaccount.com'; //Email Address
        $key_file_location = '/var/www/stores/google-api-php-client/examples/key.p12';
		*/
        //$key_file_location = '/vagrant/xcart/xcart/google-api-php-client/examples/key.p12';

        if(!empty($merchantId))
        {
            $client = new Google_Client();
            $client->setApplicationName("Client_Library_Feeds");

            $service = new Google_Service_ShoppingContent($client);

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

            $update = [];

            $next_page_token = '';
            do  {
                $optParams = array('maxResults' => 50, 'pageToken' => $next_page_token );

                if ( $next_page_token == '' )
                    unset( $optParams['pageToken'] );

                $params = array('merchantId' => $merchantId);
                $params = array_merge($params, $optParams);

                $result = $service->products->call('list', array($params), "Google_Service_ShoppingContent_ProductsListResponse");

                if( !isset( $result['nextPageToken'] ) )
                    $next_page_token = '';
                else
                    $next_page_token = $result['nextPageToken'];

                foreach( $result['resources'] as $item )
                {
                    $fsale = func_query_first_cell("SELECT SQL_NO_CACHE $sql_tbl[products].forsale FROM $sql_tbl[products] WHERE $sql_tbl[products].productid = '" . $item['offerId'] . "'");
                    if ( $fsale == 'N' )
                    {
                        $update[] = "'" . $item['offerId'] . "'";
                    }
                }
            } while( $next_page_token != '' );

            $items_for_delete = implode( ",", $update );

            $query_delete = '';

            if ( $items_for_delete != '' )
                $query_delete = " OR P.productid IN (" . $items_for_delete . ")";

            $query = "INSERT IGNORE INTO xcart_k.xcart_cidev_updated_products (resourceid, `type`, time_stamp, source)
                        SELECT P.productid,1,1,'FULL UPDATE' FROM xcart_k.xcart_products P INNER JOIN xcart_k.xcart_products_sf PS ON PS.productid = P.productid and PS.sfid = " .$storefrontid . "
                        WHERE P.forsale = 'Y'" . $query_delete;

            db_query($query);

            $count = func_query_first_cell("SELECT COUNT(distinct P.productid) FROM xcart_k.xcart_products P
                                              INNER JOIN xcart_k.xcart_products_sf PS ON PS.productid = P.productid and PS.sfid = " . $storefrontid . "
                                              WHERE P.forsale = 'Y'" . $query_delete );

            $log_text = $sf_info['domain'] . " - queued items to delete: {" . $items_for_delete . "} . All queued items count: " . $count;
            echo $log_text . "\n";
	    func_backprocess_log("full feeds update", $log_text);
        }
    }

    $finished_at = time();

    $duration = $finished_at - $started_at;

    $hour = intval($duration / (60 * 60));
    $minutes = intval(($duration - $hour * 60 * 60) / 60);
    $seconds = ($duration - $hour * 60 * 60 - $minutes * 60 );

    $str_time = sprintf( "%02d:%02d:%02d", $hour, $minutes, $seconds );

    $log_text = "Full update finished. Duration " . $str_time;
    echo $log_text . "\n";
    func_backprocess_log("full feeds update", $log_text);
}
