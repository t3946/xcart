<?php
/** 
 *  PHP Version 5
 *
 *  @category    Amazon
 *  @package     MarketplaceWebService
 *  @copyright   Copyright 2009 Amazon Technologies, Inc.
 *  @link        http://aws.amazon.com
 *  @license     http://aws.amazon.com/apache2.0  Apache License, Version 2.0
 *  @version     2009-01-01
 */
/******************************************************************************* 

 *  Marketplace Web Service PHP5 Library
 *  Generated: Thu May 07 13:07:36 PDT 2009
 * 
 */

/**
 * Submit Feed  Sample
 */

include_once ('.config.inc.php'); 


/************************************************************************
* Uncomment to configure the client instance. Configuration settings
* are:
*
* - MWS endpoint URL
* - Proxy host and port.
* - MaxErrorRetry.
***********************************************************************/
// IMPORTANT: Uncomment the approiate line for the country you wish to
// sell in:
// United States:
$serviceUrl = "https://mws.amazonservices.com";
// United Kingdom
//$serviceUrl = "https://mws.amazonservices.co.uk";
// Germany
//$serviceUrl = "https://mws.amazonservices.de";
// France
//$serviceUrl = "https://mws.amazonservices.fr";
// Italy
//$serviceUrl = "https://mws.amazonservices.it";
// Japan
//$serviceUrl = "https://mws.amazonservices.jp";
// China
//$serviceUrl = "https://mws.amazonservices.com.cn";
// Canada
//$serviceUrl = "https://mws.amazonservices.ca";
// India
//$serviceUrl = "https://mws.amazonservices.in";

$cidev_config = array (
  'ServiceURL' => $serviceUrl,
  'ProxyHost' => null,
  'ProxyPort' => -1,
  'MaxErrorRetry' => 3,
);

/************************************************************************
 * Instantiate Implementation of MarketplaceWebService
 * 
 * AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY constants 
 * are defined in the .config.inc.php located in the same 
 * directory as this sample
 ***********************************************************************/
 $service = new MarketplaceWebService_Client(
     AWS_ACCESS_KEY_ID, 
     AWS_SECRET_ACCESS_KEY, 
     $cidev_config,
     APPLICATION_NAME,
     APPLICATION_VERSION);
 
/************************************************************************
 * Uncomment to try out Mock Service that simulates MarketplaceWebService
 * responses without calling MarketplaceWebService service.
 *
 * Responses are loaded from local XML files. You can tweak XML files to
 * experiment with various outputs during development
 *
 * XML files available under MarketplaceWebService/Mock tree
 *
 ***********************************************************************/
 // $service = new MarketplaceWebService_Mock();

/************************************************************************
 * Setup request parameters and uncomment invoke to try out 
 * sample for Submit Feed Action
 ***********************************************************************/
 // @TODO: set request. Action can be passed as MarketplaceWebService_Model_SubmitFeedRequest
 // object or array of parameters

// Note that PHP memory streams have a default limit of 2M before switching to disk. While you
// can set the limit higher to accomidate your feed in memory, it's recommended that you store
// your feed on disk and use traditional file streams to submit your feeds. For conciseness, this
// examples uses a memory stream.

/*
$feed = <<<EOD
<?xml version="1.0" encoding="UTF-8"?>
<AmazonEnvelope xsi:noNamespaceSchemaLocation="amzn-envelope.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <Header>
        <DocumentVersion>1.01</DocumentVersion>
        <MerchantIdentifier>M_MWSTEST_49045593</MerchantIdentifier>
    </Header>
    <MessageType>OrderFulfillment</MessageType>
    <Message>
        <MessageID>1</MessageID>
        <OperationType>Update</OperationType>
        <OrderFulfillment>
            <AmazonOrderID>002-3275191-2204215</AmazonOrderID>
            <FulfillmentDate>2009-07-22T23:59:59-07:00</FulfillmentDate>
            <FulfillmentData>
                <CarrierName>Contact Us for Details</CarrierName>
                <ShippingMethod>Standard</ShippingMethod>
            </FulfillmentData>
            <Item>
                <AmazonOrderItemCode>42197908407194</AmazonOrderItemCode>
                <Quantity>1</Quantity>
            </Item>
        </OrderFulfillment>
    </Message>
</AmazonEnvelope>
EOD;
*/

/*
$feed = <<<EOD
"TemplateType=Office"   "Version=2013.0708"     "The top 3 rows are for Amazon.com use only. Do not modify or delete the top 3 rows."                                                                   "Offer - These attributes are required to make your item buyable for customers on the site"                                                                                                                                                                                                                     "Dimension - These attributes specify the size and weight of a product"                                                         "Discovery - These attributes have an effect on how customers can find your product on the site using browse or search"                                                                                                                                                                                                                                                                         "Image - These attributes provide links to images for a product"                                                                        "Fulfillment - Use these columns to provide fulfillment-related information for either Amazon-fulfilled (FBA) or seller-fulfilled orders."      "Variation - Populate these attributes if your product is available in different variations (for example color or wattage)"                             "Compliance - Attributes used to comply with consumer laws in the country or region where the item is sold"                                             "Ungrouped - These attributes create rich product listings for your buyers."
"SKU"   "Product ID"            "Product ID Type"       "Product Name"  "Manufacturer"  "Manufacturer Part Number"      "Product Type"  "Item Type"     "Product Description"   "Brand" "Update Delete" "Launch Date"   "Currency"      "Standard Price"        "Package Quantity"      "Product Tax Code"      "Manufacturer's Suggested Retail Price" "Quantity"      "Item Weight Unit Of Measure"   "Item Weight"   "Item Length Unit Of Measure"   "Item Length"   "Item Width"    "Item Height"           "Key Product Features1" "Key Product Features2" "Key Product Features3" "Key Product Features4" "Key Product Features5" "Main Image URL"                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
"item_sku"      "external_product_id"   "standard_product_id"   "external_product_id_type"      "item_name"     "manufacturer"  "part_number"   "feed_product_type"     "item_type"     "product_description"   "brand_name"    "update_delete" "product_site_launch_date"      "currency"      "standard_price"        "item_package_quantity" "product_tax_code"      "list_price"    "quantity"      "item_weight_unit_of_measure"   "item_weight"   "item_length_unit_of_measure"   "item_length"   "item_height"   "item_width"    "fulfillment_latency"   "bullet_point1" "bullet_point2" "bullet_point3" "bullet_point4" "bullet_point5" "main_image_url"                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
"ARG-200-572"   "088612255723"  "088612255723"  "UPC"   "Artograph 1520 Replacement Spray Guard Hood"   "Artograph"     "ARG-200-572"   "OfficeProducts"        "artists-paintbrush-sets"       "Artograph 1520 Replacement Spray Guard Hood"   "Artograph"             "2014-12-03"    "USD"   "40.10" "1"             "24.99" "999995"        "LB"    "2.00"  "IN"    "0"     "0"     "0"     "5"                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
"ELE-SK-40"     "756619002668"  "756619002668"  "UPC"   "NEW Elenco Solar Deluxe Educational Kit NEW"   "Elenco Electronics, Inc."      "ELE-SK-40"     "OfficeProducts"        "artists-paintbrush-sets"       "By solar power, harness the power of the sun with environmental-friendly D.I.Y. kit!   Children can do a series of do-it-yourself experiments to acquire the basic knowledge of solar energy.   Children can learn how to make an electrical circuit, make a solar circuit, how to increase   voltage and current, and how to use solar power to produce energy for a radio, calculator, battery charger, a cassette player and more!" "Elenco"                "2014-12-03"    "USD"   "37.55" "1"             "22.95" "999970"        "LB"    "0.20"  "IN"    "0"     "0"     "0"     "5"             "http://www.artistsupplysource.com/images/D/ENC-SK-40_1.jpg"                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          
"ARG-225-190"   "088612251904"  "088612251904"  "UPC"   "Artograph 225-190 Super Prism Projector"       "Artograph"     "ARG-225-190"   "OfficeProducts"        "artists-paintbrush-sets"       "The Super Prism™ is designed for the artist or designer who needs the best in image clarity and accuracy<br>   Ideal for working from a variety of challenging originals like photographs, half tones, and highly detailed drawings<br>   A 3-element, color corrected lens for extra sharp images and increased brightness<br>   This Super Lens enlarges originals from 3 to 20 times<br>   Includes extra accessory lens to extend the range from 3 times enlargement down to 80% reduction<br>   Includes 500-watts of photo quality lighting for a whiter and brighter image<br>   Magnetic door latches and a spring clip keep copy securely in place<br>   Generous 7"" x 7"" (18 x 18 cm) top-loading, glass covered copy area makes for easy placement of large copy, books, or small 3-D objects<br>   A dual cooling channel system and thermal overload circuit protect originals from overheating<br>   Top-loading 7"" x 7"" (18 x 18 cm) glass-covered copy area with adjustable cover to keep artwork flat and secure<br>   Enlarges artwork and 3-D objects up to 20 times the original size<br>   Included accessory lens extends range down to 80% reduction<br>   Vertical projection of 80% reduction up to 3Â½ times enlargement onto a tabletop with accessory Prism Table Stand, #225-206, (accessory lens included with projector must be used with this stand)<br>   Super Lens: 240mm f/4.5, 3-element color-corrected, precision-ground lens for extra sharp images<br>   Illumination: 500-watts photo quality lighting for a brighter image<br>   Dual cooling system and safety overload circuit keep copy cooler<br>   5-year warranty"        "Artograph"             "2014-12-03"    "USD"   "343.58"        "1"             "408.99"        "1000000"       "LB"    "13.00" "IN"    "16"    "9"     "16"    "5"     " Vertical projection of 80% reduction up to 3Â½ times enlargement onto a tabletop with accessory"      "http://www.artistsupplysource.com/images/D/Large-2473.jpg"                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
EOD;
*/

// Constructing the MarketplaceId array which will be passed in as the the MarketplaceIdList 
// parameter to the SubmitFeedRequest object.
//$marketplaceIdArray = array("Id" => array('<Marketplace_Id_1>','<Marketplace_Id_2>'));
     
 // MWS request objects can be constructed two ways: either passing an array containing the 
 // required request parameters into the request constructor, or by individually setting the request
 // parameters via setter methods.
 // Uncomment one of the methods below.


$marketplaceIdArray = array("Id" => array('ATVPDKIKX0DER'));

/********* Begin Comment Block *********/

//$feedHandle = @fopen('php://temp', 'rw+');
//fwrite($feedHandle, $feed);

$feedHandle = @fopen('amazon_export.txt', 'r+');

if(!$feedHandle) die("Can't open device");

rewind($feedHandle);

//echo fread($feedHandle, filesize('amazon_export.txt'));
//die();


$parameters = array (
  'Merchant' => MERCHANT_ID,
  'MarketplaceIdList' => $marketplaceIdArray,
  'FeedType' => '_POST_FLAT_FILE_LISTINGS_DATA_',
  'FeedContent' => $feedHandle,
  'PurgeAndReplace' => false,
  'ContentMd5' => base64_encode(md5(stream_get_contents($feedHandle), true)),
//  'MWSAuthToken' => '<MWS Auth Token>', // Optional
);

rewind($feedHandle);

$request = new MarketplaceWebService_Model_SubmitFeedRequest($parameters);
/********* End Comment Block *********/

/********* Begin Comment Block *********/
//$feedHandle = @fopen('php://memory', 'rw+');
//fwrite($feedHandle, $feed);
//rewind($feedHandle);

//$request = new MarketplaceWebService_Model_SubmitFeedRequest();
//$request->setMerchant(MERCHANT_ID);
//$request->setMarketplaceIdList($marketplaceIdArray);
//$request->setFeedType('_POST_PRODUCT_DATA_');
//$request->setContentMd5(base64_encode(md5(stream_get_contents($feedHandle), true)));
//rewind($feedHandle);
//$request->setPurgeAndReplace(false);
//$request->setFeedContent($feedHandle);
//$request->setMWSAuthToken('<MWS Auth Token>'); // Optional

//rewind($feedHandle);
/********* End Comment Block *********/

invokeSubmitFeed($service, $request);



//$SUBMITTED_status = invokeSubmitFeed($service, $request);

@fclose($feedHandle);
                        

//print_r($SUBMITTED_status);
                
/**
  * Submit Feed Action Sample
  * Uploads a file for processing together with the necessary
  * metadata to process the file, such as which type of feed it is.
  * PurgeAndReplace if true means that your existing e.g. inventory is
  * wiped out and replace with the contents of this feed - use with
  * caution (the default is false).
  *   
  * @param MarketplaceWebService_Interface $service instance of MarketplaceWebService_Interface
  * @param mixed $request MarketplaceWebService_Model_SubmitFeed or array of parameters
  */
  function invokeSubmitFeed(MarketplaceWebService_Interface $service, $request) 
  {
      try {
              $response = $service->submitFeed($request);
              
                echo ("Service Response\n");
                echo ("=============================================================================\n");

                echo("        SubmitFeedResponse\n");
                if ($response->isSetSubmitFeedResult()) { 
                    echo("            SubmitFeedResult\n");
                    $submitFeedResult = $response->getSubmitFeedResult();
                    if ($submitFeedResult->isSetFeedSubmissionInfo()) { 
                        echo("                FeedSubmissionInfo\n");
                        $feedSubmissionInfo = $submitFeedResult->getFeedSubmissionInfo();
                        if ($feedSubmissionInfo->isSetFeedSubmissionId()) 
                        {
                            echo("                    FeedSubmissionId\n");
                            echo("                        " . $feedSubmissionInfo->getFeedSubmissionId() . "\n");
                        }
                        if ($feedSubmissionInfo->isSetFeedType()) 
                        {
                            echo("                    FeedType\n");
                            echo("                        " . $feedSubmissionInfo->getFeedType() . "\n");
                        }
                        if ($feedSubmissionInfo->isSetSubmittedDate()) 
                        {
                            echo("                    SubmittedDate\n");
                            echo("                        " . $feedSubmissionInfo->getSubmittedDate()->format(DATE_FORMAT) . "\n");
                        }
                        if ($feedSubmissionInfo->isSetFeedProcessingStatus()) 
                        {
                            echo("                    FeedProcessingStatus\n");
                            echo("                        " . $feedSubmissionInfo->getFeedProcessingStatus() . "\n");
                        }
                        if ($feedSubmissionInfo->isSetStartedProcessingDate()) 
                        {
                            echo("                    StartedProcessingDate\n");
                            echo("                        " . $feedSubmissionInfo->getStartedProcessingDate()->format(DATE_FORMAT) . "\n");
                        }
                        if ($feedSubmissionInfo->isSetCompletedProcessingDate()) 
                        {
                            echo("                    CompletedProcessingDate\n");
                            echo("                        " . $feedSubmissionInfo->getCompletedProcessingDate()->format(DATE_FORMAT) . "\n");
                        }
                    } 
                } 
                if ($response->isSetResponseMetadata()) { 
                    echo("            ResponseMetadata\n");
                    $responseMetadata = $response->getResponseMetadata();
                    if ($responseMetadata->isSetRequestId()) 
                    {
                        echo("                RequestId\n");
                        echo("                    " . $responseMetadata->getRequestId() . "\n");
                    }
                } 

                echo("            ResponseHeaderMetadata: " . $response->getResponseHeaderMetadata() . "\n");
     } catch (MarketplaceWebService_Exception $ex) {
         echo("Caught Exception: " . $ex->getMessage() . "\n");
         echo("Response Status Code: " . $ex->getStatusCode() . "\n");
         echo("Error Code: " . $ex->getErrorCode() . "\n");
         echo("Error Type: " . $ex->getErrorType() . "\n");
         echo("Request ID: " . $ex->getRequestId() . "\n");
         echo("XML: " . $ex->getXML() . "\n");
         echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n");
     }

//     return $feedSubmissionInfo->getFeedProcessingStatus();

 }
                                                                
