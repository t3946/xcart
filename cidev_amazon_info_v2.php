<?php
define("CIDEV_CRON_START", "CRON");
session_start();

require "./top.inc.php";
require "./init.php";

global $xcart_dir, $config;
require_once $xcart_dir . "/include/class/classAmazonMWS.php";

ini_set('memory_limit', '2048M');
set_time_limit(0);

const LOG_CATEGORY = 'cidev_amazon_info_v2';

if ($config[LOG_CATEGORY] == "Y") {
    func_backprocess_log(classAmazonMWS::BACK_PROCESS_LOG_NAME_ORDER_INFO, 'Already launched');
    die("Already launched"); // ################################
}
db_query("REPLACE $sql_tbl[config] SET value='Y', name='" . LOG_CATEGORY . "'");
$start_time = time();

$log_text = " * * *  Cron started  * * * ";

$classAmazonMWS = new classAmazonMWS();
func_backprocess_log($classAmazonMWS::BACK_PROCESS_LOG_NAME_ORDER_INFO, $log_text);


$classAmazonMWS->setReportType('_GET_RESERVED_INVENTORY_DATA_')->setBackProcessName($classAmazonMWS::BACK_PROCESS_LOG_NAME_ORDER_INFO)
    ->_Request('RequestReport')
    ->_Request('GetReportRequestList')
    ->_Request('GetReportList')
    ->_Request('GetReport')
    ->_Request('UpdateReportAcknowledgements')
    ->processReportReservedInventory();



function invokeListInventorySupply(FBAInventoryServiceMWS_Interface $service, $request)
{
    try {
        $response = $service->ListInventorySupply($request);

        $dom = new DOMDocument();
        $dom->loadXML($response->toXML());
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        return $dom->saveXML();

    } catch (FBAInventoryServiceMWS_Exception $ex) {
        $return_echo["function"] = "invokeListInventorySupply";
        $return_echo["Caught_Exception"] = $ex->getMessage();
        $return_echo["Response_Status_Code"] = $ex->getStatusCode();
        $return_echo["Error_Code"] = $ex->getErrorCode();
        $return_echo["Error_Type"] = $ex->getErrorType();
        $return_echo["Request_ID"] = $ex->getRequestId();
        $return_echo["XML"] = $ex->getXML();
        $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
        $return_echo["message"] = "Delay 2 minutes and trying the same Request";
        func_print_r($return_echo);
        return $return_echo;
    }
}

function invokeGetCompetitivePricingForSKU(MarketplaceWebServiceProducts_Interface $service, $request)
{
    try {
        $response = $service->GetCompetitivePricingForSKU($request);

        $dom = new DOMDocument();
        $dom->loadXML($response->toXML());
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        return $dom->saveXML();

    } catch (MarketplaceWebServiceProducts_Exception $ex) {
        $return_echo["function"] = "invokeGetCompetitivePricingForSKU";
        $return_echo["Caught_Exception"] = $ex->getMessage();
        $return_echo["Response_Status_Code"] = $ex->getStatusCode();
        $return_echo["Error_Code"] = $ex->getErrorCode();
        $return_echo["Error_Type"] = $ex->getErrorType();
        $return_echo["Request_ID"] = $ex->getRequestId();
        $return_echo["XML"] = $ex->getXML();
        $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
        $return_echo["message"] = "Delay 2 minutes and trying the same Request";
        func_print_r($return_echo);
        return $return_echo;
    }
}



function invokeListRecommendations(MWSRecommendationsSectionService_Interface $service, $request)
{
    try {
        $response = $service->ListRecommendations($request);

        $dom = new DOMDocument();
        $dom->loadXML($response->toXML());
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        return $dom->saveXML();

    } catch (MWSRecommendationsSectionService_Exception $ex) {
        $return_echo["function"] = "invokeListRecommendations";
        $return_echo["Caught_Exception"] = $ex->getMessage();
        $return_echo["Response_Status_Code"] = $ex->getStatusCode();
        $return_echo["Error_Code"] = $ex->getErrorCode();
        $return_echo["Error_Type"] = $ex->getErrorType();
        $return_echo["Request_ID"] = $ex->getRequestId();
        $return_echo["XML"] = $ex->getXML();
        $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
        $return_echo["message"] = "Delay 2 minutes and trying the same Request";
        func_print_r($return_echo);
        return $return_echo;
    }
}

function invokeListRecommendationsByNextToken(MWSRecommendationsSectionService_Interface $service, $request)
{
    try {
        $response = $service->ListRecommendationsByNextToken($request);

        $dom = new DOMDocument();
        $dom->loadXML($response->toXML());
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        return $dom->saveXML();

    } catch (MWSRecommendationsSectionService_Exception $ex) {
        $return_echo["function"] = "invokeListRecommendationsByNextToken";
        $return_echo["Caught_Exception"] = $ex->getMessage();
        $return_echo["Response_Status_Code"] = $ex->getStatusCode();
        $return_echo["Error_Code"] = $ex->getErrorCode();
        $return_echo["Error_Type"] = $ex->getErrorType();
        $return_echo["Request_ID"] = $ex->getRequestId();
        $return_echo["XML"] = $ex->getXML();
        $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
        func_print_r($return_echo);
        return $return_echo;
    }
}

function invokeGetLowestOfferListingsForSKU(MarketplaceWebServiceProducts_Interface $service, $request)
{
    try {
        $response = $service->GetLowestOfferListingsForSKU($request);

        $dom = new DOMDocument();
        $dom->loadXML($response->toXML());
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        return $dom->saveXML();

    } catch (MarketplaceWebServiceProducts_Exception $ex) {
        $return_echo["function"] = "invokeGetLowestOfferListingsForSKU";
        $return_echo["Caught_Exception"] = $ex->getMessage();
        $return_echo["Response_Status_Code"] = $ex->getStatusCode();
        $return_echo["Error_Code"] = $ex->getErrorCode();
        $return_echo["Error_Type"] = $ex->getErrorType();
        $return_echo["Request_ID"] = $ex->getRequestId();
        $return_echo["XML"] = $ex->getXML();
        $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
        func_print_r($return_echo);
        return $return_echo;
    }
}

#
# Step 2 start
#


### Amazon ###
//include_once "MarketplaceWebServiceProducts/Samples/.config.inc.php";
require_once "MarketplaceWebServiceProducts/Client.php";
require_once "MarketplaceWebServiceProducts/Exception.php";
require_once "MarketplaceWebServiceProducts/Model/GetCompetitivePricingForSKURequest.php";
require_once "MarketplaceWebServiceProducts/Model/SellerSKUIdentifier.php";
require_once "MarketplaceWebServiceProducts/Model/SellerSKUListType.php";
require_once "MarketplaceWebServiceProducts/Model/ShippingTimeType.php";
require_once "MarketplaceWebServiceProducts/Model/SalesRankType.php";
require_once "MarketplaceWebServiceProducts/Model/SalesRankList.php";
require_once "MarketplaceWebServiceProducts/Model/ResponseMetadata.php";
require_once "MarketplaceWebServiceProducts/Model/ResponseHeaderMetadata.php";
require_once "MarketplaceWebServiceProducts/Model/RelationshipList.php";
require_once "MarketplaceWebServiceProducts/Model/QualifiersType.php";
require_once "MarketplaceWebServiceProducts/Model/Product.php";
require_once "MarketplaceWebServiceProducts/Model/ProductList.php";
require_once "MarketplaceWebServiceProducts/Model/PriceType.php";
require_once "MarketplaceWebServiceProducts/Model/Points.php";
require_once "MarketplaceWebServiceProducts/Model/OfferType.php";
require_once "MarketplaceWebServiceProducts/Model/OffersList.php";
require_once "MarketplaceWebServiceProducts/Model/OfferListingCountType.php";
require_once "MarketplaceWebServiceProducts/Model/NumberOfOfferListingsList.php";
require_once "MarketplaceWebServiceProducts/Model/MoneyType.php";
require_once "MarketplaceWebServiceProducts/Model/Message.php";
require_once "MarketplaceWebServiceProducts/Model/MessageList.php";
require_once "MarketplaceWebServiceProducts/Model/LowestOfferListingType.php";
require_once "MarketplaceWebServiceProducts/Model/LowestOfferListingList.php";
require_once "MarketplaceWebServiceProducts/Model/ListMatchingProductsResult.php";
require_once "MarketplaceWebServiceProducts/Model/ListMatchingProductsResponse.php";
require_once "MarketplaceWebServiceProducts/Model/ListMatchingProductsRequest.php";
require_once "MarketplaceWebServiceProducts/Model/IdListType.php";
require_once "MarketplaceWebServiceProducts/Model/IdentifierType.php";
require_once "MarketplaceWebServiceProducts/Model/GetServiceStatusResult.php";
require_once "MarketplaceWebServiceProducts/Model/GetServiceStatusResponse.php";
require_once "MarketplaceWebServiceProducts/Model/GetServiceStatusRequest.php";
require_once "MarketplaceWebServiceProducts/Model/GetProductCategoriesForSKUResult.php";
require_once "MarketplaceWebServiceProducts/Model/GetProductCategoriesForSKUResponse.php";
require_once "MarketplaceWebServiceProducts/Model/GetProductCategoriesForSKURequest.php";
require_once "MarketplaceWebServiceProducts/Model/GetProductCategoriesForASINResult.php";
require_once "MarketplaceWebServiceProducts/Model/GetProductCategoriesForASINResponse.php";
require_once "MarketplaceWebServiceProducts/Model/GetProductCategoriesForASINRequest.php";
require_once "MarketplaceWebServiceProducts/Model/GetMyPriceForSKUResult.php";
require_once "MarketplaceWebServiceProducts/Model/GetMyPriceForSKUResponse.php";
require_once "MarketplaceWebServiceProducts/Model/GetMyPriceForSKURequest.php";
require_once "MarketplaceWebServiceProducts/Model/GetMyPriceForASINResult.php";
require_once "MarketplaceWebServiceProducts/Model/GetMyPriceForASINResponse.php";
require_once "MarketplaceWebServiceProducts/Model/GetMyPriceForASINRequest.php";
require_once "MarketplaceWebServiceProducts/Model/GetMatchingProductResult.php";
require_once "MarketplaceWebServiceProducts/Model/GetMatchingProductResponse.php";
require_once "MarketplaceWebServiceProducts/Model/GetMatchingProductRequest.php";
require_once "MarketplaceWebServiceProducts/Model/GetMatchingProductForIdResult.php";
require_once "MarketplaceWebServiceProducts/Model/GetMatchingProductForIdResponse.php";
require_once "MarketplaceWebServiceProducts/Model/GetMatchingProductForIdRequest.php";
require_once "MarketplaceWebServiceProducts/Model/GetLowestOfferListingsForSKUResult.php";
require_once "MarketplaceWebServiceProducts/Model/GetLowestOfferListingsForSKUResponse.php";
require_once "MarketplaceWebServiceProducts/Model/GetLowestOfferListingsForSKURequest.php";
require_once "MarketplaceWebServiceProducts/Model/GetLowestOfferListingsForASINResult.php";
require_once "MarketplaceWebServiceProducts/Model/GetLowestOfferListingsForASINResponse.php";
require_once "MarketplaceWebServiceProducts/Model/GetLowestOfferListingsForASINRequest.php";
require_once "MarketplaceWebServiceProducts/Model/GetCompetitivePricingForSKUResult.php";
require_once "MarketplaceWebServiceProducts/Model/GetCompetitivePricingForSKUResponse.php";
require_once "MarketplaceWebServiceProducts/Model/GetCompetitivePricingForASINResult.php";
require_once "MarketplaceWebServiceProducts/Model/GetCompetitivePricingForASINResponse.php";
require_once "MarketplaceWebServiceProducts/Model/GetCompetitivePricingForASINRequest.php";
require_once "MarketplaceWebServiceProducts/Model/Error.php";
require_once "MarketplaceWebServiceProducts/Model/ErrorDetail.php";
require_once "MarketplaceWebServiceProducts/Model/CompetitivePricingType.php";
require_once "MarketplaceWebServiceProducts/Model/CompetitivePriceType.php";
require_once "MarketplaceWebServiceProducts/Model/CompetitivePriceList.php";
require_once "MarketplaceWebServiceProducts/Model/Categories.php";
require_once "MarketplaceWebServiceProducts/Model/AttributeSetList.php";
require_once "MarketplaceWebServiceProducts/Model/ASINListType.php";
require_once "MarketplaceWebServiceProducts/Model/ASINIdentifier.php";
### ###

$a_config = array (
    'ServiceURL' => "https://mws.amazonservices.com/Products/2011-10-01",
    'ProxyHost' => null,
    'ProxyPort' => -1,
    'ProxyUsername' => null,
    'ProxyPassword' => null,
    'MaxErrorRetry' => 3,
);

$service = new MarketplaceWebServiceProducts_Client(
    AWS_ACCESS_KEY_ID,
    AWS_SECRET_ACCESS_KEY,
    APPLICATION_NAME,
    APPLICATION_VERSION,
    $a_config);



################################################################!!!!!!!!!!!!!!!!!!!!!!!
$where = "";
//$where = " OR  P.productcode IN ('ALV-610E', 'ALV-WS00335', 'ALV-3010-1', 'ALV-310A', 'ALV-610A', 'ALV-3050L-20/0', 'ALV-SN46036', 'ALV-TD435', 'ALV-TX320', 'ALV-85T', 'ALV-5608-04', 'ALV-E2', 'ALV-2150B-2/0', 'ALV-ST457-3', 'ALV-WS00336', 'ALV-BHS605', 'ALV-MC0461SP', 'ALV-B90', 'ALV-PM118', 'ALV-P260', 'ALV-G800BP', 'ALV-HM0305', 'ALV-SN44011', 'ALV-SN46030')";
$where = " or ((P.amazon_fba = 'Y' or P.amazon_enabled = 'Y') and P.forsale = 'Y')";
//$where = " or ((P.amazon_fba = 'Y' or P.amazon_enabled = 'Y') and P.forsale = 'Y' and P.productcode like 'PPF-%')";
################################################################



/*
$xcart_query = "Select P.productcode, P.productid
From xcart_orders O
        left join xcart_order_details OD ON OD.orderid = O.orderid
        left join xcart_products P ON P.productid = OD.productid
Where O.amazon_fulfillment_channel = 'AFN' $where GROUP BY P.productcode";
*/

$xcart_query = "Select P.productcode, P.productid
From xcart_k.xcart_products P
where P.forsale = 'Y' and (P.amazon_enabled = 'Y' or P.amazon_fba = 'Y')";

$products = db_query($xcart_query);

//$tmp_products = func_query($xcart_query);
//func_print_r($tmp_products);


$max_products = 20;
$products_arr = array();
$tmp_counter = 0;
$products_arr_key = 0;

$max_products2 = 50;
$products_arr2 = array();
$tmp_counter2 = 0;
$products_arr_key2 = 0;

while($product = db_fetch_array($products)) {

    $products_arr[$products_arr_key][$tmp_counter]["productcode"] = $product["productcode"];
    $products_arr[$products_arr_key][$tmp_counter]["productid"] = $product["productid"];

    $tmp_counter++;

    if ($tmp_counter == $max_products){
        $products_arr_key++;
        $tmp_counter = 0;
    }


    $products_arr2[$products_arr_key2][$tmp_counter2]["productcode"] = $product["productcode"];
    $products_arr2[$products_arr_key2][$tmp_counter2]["productid"] = $product["productid"];

    $tmp_counter2++;

    if ($tmp_counter2 == $max_products2){
        $products_arr_key2++;
        $tmp_counter2 = 0;
    }
}


//func_print_r($products_arr);


if (!empty($products_arr)){
    foreach ($products_arr as $k_p => $v_arr){
        if (!empty($v_arr) && is_array($v_arr)){

            $productcode_productid_arr = array();
            $sku_arr = array();

            foreach ($v_arr as $kk_p => $vv_p){
                $productcode_productid_arr[$vv_p["productcode"]] = $vv_p["productid"];
                $sku_arr[] = $vv_p["productcode"];
            }


//func_print_r($productcode_productid_arr, $sku_arr);
//die("---");


            $request = new MarketplaceWebServiceProducts_Model_GetCompetitivePricingForSKURequest();
            $request->setSellerId(MERCHANT_ID);
            $request->setMarketplaceId(MARKETPLACE_ID);

            $SellerSKUList = new MarketplaceWebServiceProducts_Model_SellerSKUListType();
            $SellerSKUList->setSellerSKU($sku_arr);
            func_print_r($sku_arr);
            $request->setSellerSKUList($SellerSKUList);

            // object or array of parameters
            $dom_xml = invokeGetCompetitivePricingForSKU($service, $request);


            while (!empty($dom_xml["Caught_Exception"]) && $dom_xml["Caught_Exception"] == "Request is throttled" && $dom_xml["Response_Status_Code"] == "503"){
                func_flush("sleeping...");
                func_flush();
                sleep('123');
                func_flush("Unsleeped");
                func_flush();

                $request = new MarketplaceWebServiceProducts_Model_GetCompetitivePricingForSKURequest();
                $request->setSellerId(MERCHANT_ID);
                $request->setMarketplaceId(MARKETPLACE_ID);

                $SellerSKUList = new MarketplaceWebServiceProducts_Model_SellerSKUListType();
                $SellerSKUList->setSellerSKU($sku_arr);
                $request->setSellerSKUList($SellerSKUList);

                // object or array of parameters
                $dom_xml = invokeGetCompetitivePricingForSKU($service, $request);
            }

            if (!empty($dom_xml) && !is_array($dom_xml)){
                ##################################################################################

                $dom_xml_arr = explode("\n",$dom_xml);
                $tmp_arr_counter = 0;
                $found_for_replace = "N";
                $current_k_d = 0;
                foreach($dom_xml_arr as $k_d => $v_d){

                    if (strpos($v_d, "GetCompetitivePricingForSKUResult")!==false && $found_for_replace == "N"){
                        $found_for_replace = "Y";
                        $v_d = str_replace("GetCompetitivePricingForSKUResult", "GetCompetitivePricingForSKUResult".$tmp_arr_counter, $v_d);

                        if (strpos($v_d, "status")!==false){
                            $SellerSKU_status_arr = preg_match("|status=\"([^\"]*)\"|sei", $v_d, $arr_s);
                            $SellerSKU_status = $arr_s["1"];
                            $v_d .= "\n<SellerSKU_status>$SellerSKU_status</SellerSKU_status>";
                        }

                        $SellerSKU_arr = preg_match("|SellerSKU=\"([^\"]*)\"|sei", $v_d, $arr);
                        $SellerSKU = $arr[1];
                        $v_d .= "\n<SellerSKU>$SellerSKU</SellerSKU>";
                        $v_d .= "\n<productid>".$productcode_productid_arr[$SellerSKU]."</productid>";

                        $current_k_d = $k_d;
                    }

                    if (strpos($v_d, "GetCompetitivePricingForSKUResult")!==false && $found_for_replace == "Y" && $k_d != $current_k_d){
                        $found_for_replace = "N";
                        $v_d = str_replace("GetCompetitivePricingForSKUResult", "GetCompetitivePricingForSKUResult".$tmp_arr_counter, $v_d);
                        $tmp_arr_counter++;
                    }

                    if (strpos($v_d, "<CompetitivePrice ")!==false){

                        $CompetitivePrice_condition_arr = preg_match("|condition=\"([^\"]*)\"|sei", $v_d, $arr1);
                        $v_d .= "\n<CompetitivePrice_condition>".$arr1[1]."</CompetitivePrice_condition>";

                        $CompetitivePrice_subcondition_arr = preg_match("|subcondition=\"([^\"]*)\"|sei", $v_d, $arr2);
                        $v_d .= "\n<CompetitivePrice_subcondition>".$arr2[1]."</CompetitivePrice_subcondition>";


                        $CompetitivePrice_belongsToRequester_arr = preg_match("|belongsToRequester=\"([^\"]*)\"|sei", $v_d, $arr3);
                        $v_d .= "\n<CompetitivePrice_belongsToRequester>".$arr3[1]."</CompetitivePrice_belongsToRequester>";

                    }

                    $dom_xml_arr[$k_d] = $v_d;
                }
                $dom_xml = implode("\n",$dom_xml_arr);

                $dom_xml_arr = func_xml2hash($dom_xml, "UTF-8");

//			        func_print_r($dom_xml_arr);

                if (!empty($dom_xml_arr["GetCompetitivePricingForSKUResponse"]) && is_array($dom_xml_arr["GetCompetitivePricingForSKUResponse"])){
##############################################################################################################################
##############################################################################################################################
##############################################################################################################################

//func_print_r($dom_xml_arr);

                    foreach ($dom_xml_arr["GetCompetitivePricingForSKUResponse"] as $k_cpr => $v_cpr_arr){

                        if (
                            !empty($v_cpr_arr["Product"]["CompetitivePricing"]["CompetitivePrices"]["CompetitivePrice"]) &&
                            is_array($v_cpr_arr["Product"]["CompetitivePricing"]["CompetitivePrices"]["CompetitivePrice"]) &&
                            $v_cpr_arr["Product"]["CompetitivePricing"]["CompetitivePrices"]["CompetitivePrice"]["CompetitivePrice_condition"] == "New" &&
                            $v_cpr_arr["Product"]["CompetitivePricing"]["CompetitivePrices"]["CompetitivePrice"]["CompetitivePrice_subcondition"] == "New"
                        ){

###
                            if (!isset($v_cpr_arr["Product"]["SalesRankings"]["SalesRank"])){
                                $amazon_fba_products[$v_cpr_arr["productid"]]["cpr_SalesRank"] = 0;
                            } else {
                                $amazon_fba_products[$v_cpr_arr["productid"]]["cpr_SalesRank"] = $v_cpr_arr["Product"]["SalesRankings"]["SalesRank"]["Rank"];
                            }
###

                            if ($v_cpr_arr["Product"]["CompetitivePricing"]["CompetitivePrices"]["CompetitivePrice"]["CompetitivePrice_belongsToRequester"] == "true"){

                                $LandedPrice = "";

                                if (isset($v_cpr_arr["Product"]["CompetitivePricing"]["CompetitivePrices"]["CompetitivePrice"]["Price"]["LandedPrice"]["Amount"])){
                                    $LandedPrice = $v_cpr_arr["Product"]["CompetitivePricing"]["CompetitivePrices"]["CompetitivePrice"]["Price"]["LandedPrice"]["Amount"];
                                } elseif (isset($v_cpr_arr["Product"]["CompetitivePricing"]["CompetitivePrices"]["CompetitivePrice"]["Price"]["ListingPrice"]["Amount"])){
                                    $LandedPrice = $v_cpr_arr["Product"]["CompetitivePricing"]["CompetitivePrices"]["CompetitivePrice"]["Price"]["ListingPrice"]["Amount"];
                                }

                                $amazon_fba_products[$v_cpr_arr["productid"]]["cpr_belongs_LandedPrice"] = $LandedPrice;
                            }
                            elseif ($v_cpr_arr["Product"]["CompetitivePricing"]["CompetitivePrices"]["CompetitivePrice"]["CompetitivePrice_belongsToRequester"] == "false"){

####### should be redone accorning several CompetitivePrice #######

                                $LandedPrice = "";

                                if (isset($v_cpr_arr["Product"]["CompetitivePricing"]["CompetitivePrices"]["CompetitivePrice"]["Price"]["LandedPrice"]["Amount"])){
                                    $LandedPrice = $v_cpr_arr["Product"]["CompetitivePricing"]["CompetitivePrices"]["CompetitivePrice"]["Price"]["LandedPrice"]["Amount"];
                                } elseif (isset($v_cpr_arr["Product"]["CompetitivePricing"]["CompetitivePrices"]["CompetitivePrice"]["Price"]["ListingPrice"]["Amount"])){
                                    $LandedPrice = $v_cpr_arr["Product"]["CompetitivePricing"]["CompetitivePrices"]["CompetitivePrice"]["Price"]["ListingPrice"]["Amount"];
                                }

                                $amazon_fba_products[$v_cpr_arr["productid"]]["cpr_LandedPrice"] = $LandedPrice;
####### #######
                            }

                        } //if
                    } // foreach ($dom_xml_arr["GetCompetitivePricingForSKUResponse"] as $k_cpr => $v_cpr_arr)

##############################################################################################################################
##############################################################################################################################
##############################################################################################################################
                }
                ##################################################################################
            }



### ### ###

##

            $request2 = new MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForSKURequest();
            $request2->setSellerId(MERCHANT_ID);
            $request2->setMarketplaceId(MARKETPLACE_ID);
            $request2->setSellerSKUList($SellerSKUList);
            $request2->setItemCondition("New");
            $request2->setExcludeMe(true);

            // object or array of parameters
            $dom_xml2 =  invokeGetLowestOfferListingsForSKU($service, $request2);

            while (!empty($dom_xml2["Caught_Exception"]) && $dom_xml2["Caught_Exception"] == "Request is throttled" && $dom_xml2["Response_Status_Code"] == "503"){
                func_flush("sleeping...");
                func_flush();
                sleep('123');
                func_flush("Unsleeped");
                func_flush();


                $request2 = new MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForSKURequest();
                $request2->setSellerId(MERCHANT_ID);
                $request2->setMarketplaceId(MARKETPLACE_ID);
                $request2->setSellerSKUList($SellerSKUList);
                $request2->setItemCondition("New");
                $request2->setExcludeMe(true);

                // object or array of parameters
                $dom_xml2 =  invokeGetLowestOfferListingsForSKU($service, $request2);
            }

            if (!empty($dom_xml2)){
                ##################################################################################

                $dom_xml_arr = explode("\n",$dom_xml2);

                $tmp_arr_counter = 0;
                $found_for_replace = "N";
                $current_k_d = 0;

                $tmp_arr_counter2 = 0;
                $found_for_replace2 = "N";
                $current_k_d2 = 0;


                foreach($dom_xml_arr as $k_d => $v_d){

                    if (strpos($v_d, "GetLowestOfferListingsForSKUResult")!==false && $found_for_replace == "N"){
                        $found_for_replace = "Y";
                        $v_d = str_replace("GetLowestOfferListingsForSKUResult", "GetLowestOfferListingsForSKUResult".$tmp_arr_counter, $v_d);

                        if (strpos($v_d, "status")!==false){
                            $SellerSKU_status_arr = preg_match("|status=\"([^\"]*)\"|sei", $v_d, $arr_s);
                            $SellerSKU_status = $arr_s["1"];
                            $v_d .= "\n<SellerSKU_status>$SellerSKU_status</SellerSKU_status>";
                        }

                        $SellerSKU_arr = preg_match("|SellerSKU=\"([^\"]*)\"|sei", $v_d, $arr);
                        $SellerSKU = $arr[1];
                        $v_d .= "\n<SellerSKU>$SellerSKU</SellerSKU>";
                        $v_d .= "\n<productid>".$productcode_productid_arr[$SellerSKU]."</productid>";

                        $current_k_d = $k_d;
                    }

                    if (strpos($v_d, "GetLowestOfferListingsForSKUResult")!==false && $found_for_replace == "Y" && $k_d != $current_k_d){
                        $found_for_replace = "N";
                        $v_d = str_replace("GetLowestOfferListingsForSKUResult", "GetLowestOfferListingsForSKUResult".$tmp_arr_counter, $v_d);
                        $tmp_arr_counter++;
                    }


                    if (strpos($v_d, "LowestOfferListing>")!==false && $found_for_replace2 == "N"){
                        $found_for_replace2 = "Y";
                        $v_d = str_replace("LowestOfferListing>", "LowestOfferListing".$tmp_arr_counter2.">", $v_d);
                        $current_k_d2 = $k_d;
                    }

                    if (strpos($v_d, "LowestOfferListing>")!==false && $found_for_replace2 == "Y" && $k_d != $current_k_d2){
                        $found_for_replace2 = "N";
                        $v_d = str_replace("LowestOfferListing>", "LowestOfferListing".$tmp_arr_counter2.">", $v_d);
                        $tmp_arr_counter2++;
                    }




                    $dom_xml_arr[$k_d] = $v_d;
                }
                $dom_xml = implode("\n",$dom_xml_arr);

                $dom_xml_arr = func_xml2hash($dom_xml, "UTF-8");

###
                if (!empty($dom_xml_arr["GetLowestOfferListingsForSKUResponse"]) && is_array($dom_xml_arr["GetLowestOfferListingsForSKUResponse"])){
##############################################################################################################################
##############################################################################################################################
##############################################################################################################################

                    foreach ($dom_xml_arr["GetLowestOfferListingsForSKUResponse"] as $k_lol => $v_lol_arr){

                        $amazon_fba_products[$v_lol_arr["productid"]]["ASIN"] = $v_lol_arr["Product"]["Identifiers"]["MarketplaceASIN"]["ASIN"];

                        if (
                            !empty($v_lol_arr["Product"]["LowestOfferListings"]) &&
                            is_array($v_lol_arr["Product"]["LowestOfferListings"])
                        ){

#
                            $lowest_LandedPrice = "";
                            $use_lowest_k_LowestOfferListing = "";
#

                            foreach ($v_lol_arr["Product"]["LowestOfferListings"] as $k_LowestOfferListing => $LowestOfferListing){

                                if (
                                    $LowestOfferListing["Qualifiers"]["ItemCondition"] == "New" &&
                                    $LowestOfferListing["Qualifiers"]["ItemSubcondition"] == "New" &&
                                    ($LowestOfferListing["Qualifiers"]["ShipsDomestically"] == "Unknown" || $LowestOfferListing["Qualifiers"]["ShipsDomestically"] == "True")
                                ){
#
                                    if ($lowest_LandedPrice == ""){
                                        $lowest_LandedPrice = $LowestOfferListing["Price"]["LandedPrice"]["Amount"];
                                        $use_lowest_k_LowestOfferListing = $k_LowestOfferListing;
                                    } else {

                                        if ($lowest_LandedPrice > $LowestOfferListing["Price"]["LandedPrice"]["Amount"]){
                                            $lowest_LandedPrice = $LowestOfferListing["Price"]["LandedPrice"]["Amount"];
                                            $use_lowest_k_LowestOfferListing = $k_LowestOfferListing;
                                        }
                                    }
#
                                }
                            }

                            if ($lowest_LandedPrice != "" && $use_lowest_k_LowestOfferListing != ""){
                                $amazon_fba_products[$v_lol_arr["productid"]]["lp_LandedPrice"] = $lowest_LandedPrice;

                                $amazon_fba_products[$v_lol_arr["productid"]]["lp_MultipleOfferListingsAtLowestPrice"] = $v_lol_arr["Product"]["LowestOfferListings"][$use_lowest_k_LowestOfferListing]["MultipleOffersAtLowestPrice"];
                                if ($amazon_fba_products[$v_lol_arr["productid"]]["lp_MultipleOfferListingsAtLowestPrice"] == "False"){
                                    $amazon_fba_products[$v_lol_arr["productid"]]["lp_MultipleOfferListingsAtLowestPrice"] = "N";
                                } else {
                                    $amazon_fba_products[$v_lol_arr["productid"]]["lp_MultipleOfferListingsAtLowestPrice"] = "Y";
                                }

                                $amazon_fba_products[$v_lol_arr["productid"]]["lp_AllOfferListingsConsidered"] = $v_lol_arr["AllOfferListingsConsidered"];
                                if ($amazon_fba_products[$v_lol_arr["productid"]]["lp_AllOfferListingsConsidered"] == "true"){
                                    $amazon_fba_products[$v_lol_arr["productid"]]["lp_AllOfferListingsConsidered"] = "Y";
                                } else {
                                    $amazon_fba_products[$v_lol_arr["productid"]]["lp_AllOfferListingsConsidered"] = "N";
                                }

                                $amazon_fba_products[$v_lol_arr["productid"]]["lp_NumberOfOfferListingsConsidered"] = $v_lol_arr["Product"]["LowestOfferListings"][$use_lowest_k_LowestOfferListing]["NumberOfOfferListingsConsidered"];
                                $amazon_fba_products[$v_lol_arr["productid"]]["lp_SellerFeedbackCount"] = $v_lol_arr["Product"]["LowestOfferListings"][$use_lowest_k_LowestOfferListing]["SellerFeedbackCount"];

                                $FulfillmentChannel = "";
                                if ($v_lol_arr["Product"]["LowestOfferListings"][$use_lowest_k_LowestOfferListing]["Qualifiers"]["FulfillmentChannel"] == "Merchant") $FulfillmentChannel = "MFN";
                                if ($v_lol_arr["Product"]["LowestOfferListings"][$use_lowest_k_LowestOfferListing]["Qualifiers"]["FulfillmentChannel"] == "Amazon") $FulfillmentChannel = "AFN";
                                $amazon_fba_products[$v_lol_arr["productid"]]["lp_FulfillmentChannel"] = $FulfillmentChannel;
                                $amazon_fba_products[$v_lol_arr["productid"]]["lp_ShippingTime"] = $v_lol_arr["Product"]["LowestOfferListings"][$use_lowest_k_LowestOfferListing]["Qualifiers"]["ShippingTime"]["Max"];
                                $amazon_fba_products[$v_lol_arr["productid"]]["lp_SellerPositiveFeedbackRating"] = $v_lol_arr["Product"]["LowestOfferListings"][$use_lowest_k_LowestOfferListing]["Qualifiers"]["SellerPositiveFeedbackRating"];
                            } else {
                                $amazon_fba_products[$v_lol_arr["productid"]]["lp_LandedPrice"] = 0;
                                $amazon_fba_products[$v_lol_arr["productid"]]["lp_MultipleOfferListingsAtLowestPrice"] = "";
                                $amazon_fba_products[$v_lol_arr["productid"]]["lp_AllOfferListingsConsidered"] = "";
                                $amazon_fba_products[$v_lol_arr["productid"]]["lp_NumberOfOfferListingsConsidered"] = 0;
                                $amazon_fba_products[$v_lol_arr["productid"]]["lp_SellerFeedbackCount"] = 0;
                                $amazon_fba_products[$v_lol_arr["productid"]]["lp_FulfillmentChannel"] = "";
                                $amazon_fba_products[$v_lol_arr["productid"]]["lp_ShippingTime"] = "";
                                $amazon_fba_products[$v_lol_arr["productid"]]["lp_SellerPositiveFeedbackRating"] = "";
                            }

//func_print_r($v_lol_arr["productid"],$amazon_fba_products[$v_lol_arr["productid"]]);

                        } //if
                    } // foreach ($dom_xml_arr[""] as $k_lol => $v_lol_arr)


##############################################################################################################################
##############################################################################################################################
##############################################################################################################################
                }
###

                ##################################################################################
            }




//func_print_r( $dom_xml_arr);
//die("asdad");

##

### ### ###


//die("EEE");

        } // if (!empty($v_arr) && is_array($v_arr))
    } // foreach ($products_arr as $k_p => $v_arr)
} // if (!empty($products_arr))
db_free_result($products);

#
# Step 2 End
#


#
# Step 3 Start
#

//include_once "FBAInventoryServiceMWS/Samples/.config.inc.php";
require_once "FBAInventoryServiceMWS/Client.php";
require_once "FBAInventoryServiceMWS/Exception.php";
require_once "FBAInventoryServiceMWS/Model/ListInventorySupplyRequest.php";
require_once "FBAInventoryServiceMWS/Model/ListInventorySupplyByNextTokenRequest.php";
require_once "FBAInventoryServiceMWS/Model/GetServiceStatusRequest.php";
require_once "FBAInventoryServiceMWS/Model/GetServiceStatusResponse.php";
require_once "FBAInventoryServiceMWS/Model/GetServiceStatusResult.php";
require_once "FBAInventoryServiceMWS/Model/InventorySupplyDetailList.php";
require_once "FBAInventoryServiceMWS/Model/InventorySupplyDetail.php";
require_once "FBAInventoryServiceMWS/Model/InventorySupplyList.php";
require_once "FBAInventoryServiceMWS/Model/InventorySupply.php";
require_once "FBAInventoryServiceMWS/Model/ListInventorySupplyByNextTokenResponse.php";
require_once "FBAInventoryServiceMWS/Model/ListInventorySupplyByNextTokenResult.php";
require_once "FBAInventoryServiceMWS/Model/ListInventorySupplyResponse.php";
require_once "FBAInventoryServiceMWS/Model/ListInventorySupplyResult.php";
require_once "FBAInventoryServiceMWS/Model/ResponseHeaderMetadata.php";
require_once "FBAInventoryServiceMWS/Model/ResponseMetadata.php";
require_once "FBAInventoryServiceMWS/Model/SellerSkuList.php";
require_once "FBAInventoryServiceMWS/Model/Timepoint.php";


$a_config = array (
    'ServiceURL' => "https://mws.amazonservices.com/FulfillmentInventory/2010-10-01",
    'ProxyHost' => null,
    'ProxyPort' => -1,
    'ProxyUsername' => null,
    'ProxyPassword' => null,
    'MaxErrorRetry' => 3,
);

$service = new FBAInventoryServiceMWS_Client(
    AWS_ACCESS_KEY_ID,
    AWS_SECRET_ACCESS_KEY,
    APPLICATION_NAME,
    APPLICATION_VERSION,
    $a_config);



if (!empty($products_arr2)){
    foreach ($products_arr2 as $k_p => $v_arr){
        if (!empty($v_arr) && is_array($v_arr)){

            $productcode_productid_arr = array();
            $sku_arr = array();

            foreach ($v_arr as $kk_p => $vv_p){
                $productcode_productid_arr[$vv_p["productcode"]] = $vv_p["productid"];
                $sku_arr[] = $vv_p["productcode"];
            }



            $NextToken = "start";

            while (!empty($NextToken)){

                $dom_xml_arr = array();
                $dom_xml_member_arr = array();

                if ($NextToken == "start"){

                    $request = new FBAInventoryServiceMWS_Model_ListInventorySupplyRequest();
                    $request->setSellerId(MERCHANT_ID);
                    $sellerSKUs = new FBAInventoryServiceMWS_Model_SellerSkuList();
                    $sellerSKUs->setmember($sku_arr);
                    $request->setSellerSkus($sellerSKUs);
                    // object or array of parameters
                    $dom_xml = invokeListInventorySupply($service, $request);

                    while (!empty($dom_xml["Caught_Exception"]) && $dom_xml["Caught_Exception"] == "Request is throttled" && $dom_xml["Response_Status_Code"] == "503"){
                        func_flush("sleeping...");
                        func_flush();
                        sleep('123');
                        func_flush("Unsleeped");
                        func_flush();

                        $request = new FBAInventoryServiceMWS_Model_ListInventorySupplyRequest();
                        $request->setSellerId(MERCHANT_ID);
                        $sellerSKUs = new FBAInventoryServiceMWS_Model_SellerSkuList();
                        $sellerSKUs->setmember($sku_arr);
                        $request->setSellerSkus($sellerSKUs);
                        // object or array of parameters
                        $dom_xml = invokeListInventorySupply($service, $request);
                    }


                    if (!empty($dom_xml) && !is_array($dom_xml)){

                        $pos = strpos($dom_xml, "<member>");
                        if ($pos !== "false"){
                            $dom_xml_arr = explode("<member>",$dom_xml);
                            $count_dom_xml_arr = count($dom_xml_arr);
                            $dom_xml = "";
                            foreach ($dom_xml_arr as $k => $v){
                                $k_n = $k-1;
                                $v = str_replace("</member>","</member$k_n>",$v);
                                $dom_xml .= $v.($k != ($count_dom_xml_arr-1)?"<member$k>":"");
                            }
                        }

                        $dom_xml_arr = func_xml2hash($dom_xml, "UTF-8");

//func_print_r($dom_xml_arr);
//die();

                        $dom_xml_member_arr = $dom_xml_arr["ListInventorySupplyResponse"]["ListInventorySupplyResult"]["InventorySupplyList"];
                    }


                    if (!empty($dom_xml_arr) && !empty($dom_xml_arr["ListInventorySupplyResponse"]["ListInventorySupplyResult"]["NextToken"])){
                        $NextToken = $dom_xml_arr["ListInventorySupplyResponse"]["ListInventorySupplyResult"]["NextToken"];
                    }
                    else {
                        $NextToken = "";
                    }

                } // if ($NextToken == "start")
                elseif (!empty($NextToken)) {


                    $request = new FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenRequest();
                    $request->setSellerId(MERCHANT_ID);
                    $request->setNextToken($NextToken);

#    $sellerSKUs = new FBAInventoryServiceMWS_Model_SellerSkuList();
#    $sellerSKUs->setmember($sku_arr);
#    $request->setSellerSkus($sellerSKUs);

                    // object or array of parameters
                    $dom_xml = invokeListInventorySupplyByNextToken($service, $request);

                    while (!empty($dom_xml["Caught_Exception"]) && $dom_xml["Caught_Exception"] == "Request is throttled" && $dom_xml["Response_Status_Code"] == "503"){
                        func_flush("sleeping...");
                        func_flush();
                        sleep('123');
                        func_flush("Unsleeped");
                        func_flush();

                        $request = new FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenRequest();
                        $request->setSellerId(MERCHANT_ID);
                        $request->setNextToken($NextToken);

#       $sellerSKUs = new FBAInventoryServiceMWS_Model_SellerSkuList();
#       $sellerSKUs->setmember($sku_arr);
#       $request->setSellerSkus($sellerSKUs);

                        // object or array of parameters
                        $dom_xml = invokeListInventorySupplyByNextToken($service, $request);
                    }

//func_print_r($dom_xml);

                    if (!empty($dom_xml)){
                        $pos = strpos($dom_xml, "<member>");
                        if ($pos !== "false"){
                            $dom_xml_arr = explode("<member>",$dom_xml);
                            $count_dom_xml_arr = count($dom_xml_arr);
                            $dom_xml = "";
                            foreach ($dom_xml_arr as $k => $v){
                                $k_n = $k-1;
                                $v = str_replace("</member>","</member$k_n>",$v);
                                $dom_xml .= $v.($k != ($count_dom_xml_arr-1)?"<member$k>":"");
                            }
                        }

                        $dom_xml_arr = func_xml2hash($dom_xml, "UTF-8");
                        $dom_xml_member_arr = $dom_xml_arr["ListInventorySupplyByNextTokenResponse"]["ListInventorySupplyByNextTokenResult"]["InventorySupplyList"];
                    }


                    if (!empty($dom_xml_arr["ListInventorySupplyByNextTokenResponse"]["ListInventorySupplyByNextTokenResult"]["NextToken"])){
                        $NextToken = $dom_xml_arr["ListInventorySupplyByNextTokenResponse"]["ListInventorySupplyByNextTokenResult"]["NextToken"];
                    }
                    else {
                        $NextToken = "";
                    }

//die("NextToken");

                } // elseif (!empty($NextToken))


                if (empty($dom_xml_member_arr)){
                    continue;
                }

#############################################

// func_print_r($dom_xml_member_arr, $productcode_productid_arr);
//die();


                foreach ($dom_xml_member_arr as $k_member => $v_member_arr){

                    $amazon_fba_products[$productcode_productid_arr[$v_member_arr["SellerSKU"]]]["lis_TotalSupplyQuantity"] = $v_member_arr["TotalSupplyQuantity"];
                    $amazon_fba_products[$productcode_productid_arr[$v_member_arr["SellerSKU"]]]["lis_InStockSupplyQuantity"] = $v_member_arr["InStockSupplyQuantity"];

                } // foreach ($dom_xml_member_arr as $k_member => $v_member_arr)

#############################################

            } // while (!empty($NextToken))

        } // if (!empty($v_arr) && is_array($v_arr))
    } // foreach ($products_arr2 as $k_p => $v_arr)
} // if (!empty($products_arr2))






if (!empty($amazon_fba_products) && is_array($amazon_fba_products))
    foreach ($amazon_fba_products as $productid => $v){

        $v["report_date"] = mktime(0, 0, 0, date("n"), date("j"), date("Y"));

        $id = func_query_first_cell("SELECT id FROM $sql_tbl[cidev_amazon_fba_products] WHERE report_date='$v[report_date]' AND productid='$productid'");

        if (!empty($id)){ // Update
            func_array2update('cidev_amazon_fba_products', $v, "id = '$id'");
        }
        else { // Insert

            $v["productcode"] = func_query_first_cell("SELECT productcode FROM $sql_tbl[products] WHERE productid='$productid'");
            $v["productid"] = $productid;

            $id = func_array2insert('cidev_amazon_fba_products', $v);
        }
    }




$classAmazonMWS->groupAmazonFBAProducts();

db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='" . LOG_CATEGORY . "'");

$current_time = time();

$pid_diff = $current_time - $start_time;
$hour = intval($pid_diff / (60 * 60));
$minutes = intval(($pid_diff - $hour * 60 * 60) / 60);
$seconds = ($pid_diff - $hour * 60 * 60 - $minutes * 60);

$str_time = sprintf("%02d:%02d:%02d", $hour, $minutes, $seconds);

$log_text = "Cron completed. ";
$log_text .= "Processing time: $str_time";
func_backprocess_log($classAmazonMWS::BACK_PROCESS_LOG_NAME_ORDER_INFO, $log_text);

die("DONE!");