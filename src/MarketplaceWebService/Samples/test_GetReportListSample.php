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
 * Get Report List  Sample
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
 $service = new MarketplaceWebService_Mock();

/************************************************************************
 * Setup request parameters and uncomment invoke to try out 
 * sample for Get Report List Action
 ***********************************************************************/
 // @TODO: set request. Action can be passed as MarketplaceWebService_Model_GetReportListRequest
 // object or array of parameters
 $parameters = array (
   'Merchant' => MERCHANT_ID,
   'AvailableToDate' => new DateTime('now', new DateTimeZone('UTC')),
   'AvailableFromDate' => new DateTime('-6 months', new DateTimeZone('UTC')),
   'Acknowledged' => false, 
//   'MWSAuthToken' => '<MWS Auth Token>', // Optional
 );
 
 $request = new MarketplaceWebService_Model_GetReportListRequest($parameters);
 
 $request = new MarketplaceWebService_Model_GetReportListRequest();
 $request->setMerchant(MERCHANT_ID);
 $request->setAvailableToDate(new DateTime('now', new DateTimeZone('UTC')));
 $request->setAvailableFromDate(new DateTime('-3 months', new DateTimeZone('UTC')));
 $request->setAcknowledged(false);
// $request->setMWSAuthToken('<MWS Auth Token>'); // Optional
 
 invokeGetReportList($service, $request);
                                                                    
/**
  * Get Report List Action Sample
  * returns a list of reports; by default the most recent ten reports,
  * regardless of their acknowledgement status
  *   
  * @param MarketplaceWebService_Interface $service instance of MarketplaceWebService_Interface
  * @param mixed $request MarketplaceWebService_Model_GetReportList or array of parameters
  */
  function invokeGetReportList(MarketplaceWebService_Interface $service, $request) 
  {
      try {
              $response = $service->getReportList($request);
              
                echo ("Service Response\n<br />");
                echo ("=============================================================================\n<br />");

                echo("        GetReportListResponse\n<br />");
                if ($response->isSetGetReportListResult()) { 
                    echo("            GetReportListResult\n<br />");
                    $getReportListResult = $response->getGetReportListResult();
                    if ($getReportListResult->isSetNextToken()) 
                    {
                        echo("                NextToken\n<br />");
                        echo("                    " . $getReportListResult->getNextToken() . "\n<br />");
                    }
                    if ($getReportListResult->isSetHasNext()) 
                    {
                        echo("                HasNext\n<br />");
                        echo("                    " . $getReportListResult->getHasNext() . "\n<br />");
                    }
                    $reportInfoList = $getReportListResult->getReportInfoList();
                    foreach ($reportInfoList as $reportInfo) {
                        echo("                ReportInfo\n<br />");
                        if ($reportInfo->isSetReportId()) 
                        {
                            echo("                    ReportId\n<br />");
                            echo("                        " . $reportInfo->getReportId() . "\n<br />");
                        }
                        if ($reportInfo->isSetReportType()) 
                        {
                            echo("                    ReportType\n<br />");
                            echo("                        " . $reportInfo->getReportType() . "\n<br />");
                        }
                        if ($reportInfo->isSetReportRequestId()) 
                        {
                            echo("                    ReportRequestId\n<br />");
                            echo("                        " . $reportInfo->getReportRequestId() . "\n<br />");
                        }
                        if ($reportInfo->isSetAvailableDate()) 
                        {
                            echo("                    AvailableDate\n<br />");
                            echo("                        " . $reportInfo->getAvailableDate()->format(DATE_FORMAT) . "\n<br />");
                        }
                        if ($reportInfo->isSetAcknowledged()) 
                        {
                            echo("                    Acknowledged\n<br />");
                            echo("                        " . $reportInfo->getAcknowledged() . "\n<br />");
                        }
                        if ($reportInfo->isSetAcknowledgedDate()) 
                        {
                            echo("                    AcknowledgedDate\n<br />");
                            echo("                        " . $reportInfo->getAcknowledgedDate()->format(DATE_FORMAT) . "\n<br />");
                        }
                    }
                } 
                if ($response->isSetResponseMetadata()) { 
                    echo("            ResponseMetadata\n<br />");
                    $responseMetadata = $response->getResponseMetadata();
                    if ($responseMetadata->isSetRequestId()) 
                    {
                        echo("                RequestId\n<br />");
                        echo("                    " . $responseMetadata->getRequestId() . "\n<br />");
                    }
                } 

                echo("            ResponseHeaderMetadata: " . $response->getResponseHeaderMetadata() . "\n<br />");
     } catch (MarketplaceWebService_Exception $ex) {
         echo("Caught Exception: " . $ex->getMessage() . "\n<br />");
         echo("Response Status Code: " . $ex->getStatusCode() . "\n<br />");
         echo("Error Code: " . $ex->getErrorCode() . "\n<br />");
         echo("Error Type: " . $ex->getErrorType() . "\n<br />");
         echo("Request ID: " . $ex->getRequestId() . "\n<br />");
         echo("XML: " . $ex->getXML() . "\n<br />");
         echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n<br />");
     }
 }
?>
