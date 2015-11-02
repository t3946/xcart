<?php
define("CIDEV_CRON_START", "CRON");
session_start();

require "./top.inc.php";
require "./init.php";


### Amazon ###
include_once "MarketplaceWebService/Samples/.config.inc.php";
require_once "MarketplaceWebService/Client.php";
require_once "MarketplaceWebService/Exception.php";
require_once "MarketplaceWebService/Model/CancelFeedSubmissionsRequest.php";
require_once "MarketplaceWebService/Model/GetReportCountResult.php";
require_once "MarketplaceWebService/Model/GetReportScheduleListByNextTokenResult.php";
require_once "MarketplaceWebService/Model/CancelFeedSubmissionsResponse.php";
require_once "MarketplaceWebService/Model/GetReportListByNextTokenRequest.php";
require_once "MarketplaceWebService/Model/GetReportScheduleListRequest.php";
require_once "MarketplaceWebService/Model/CancelFeedSubmissionsResult.php";
require_once "MarketplaceWebService/Model/GetReportListByNextTokenResponse.php";
require_once "MarketplaceWebService/Model/GetReportScheduleListResponse.php";
require_once "MarketplaceWebService/Model/CancelReportRequestsRequest.php";
require_once "MarketplaceWebService/Model/GetReportListByNextTokenResult.php";
require_once "MarketplaceWebService/Model/GetReportScheduleListResult.php";
require_once "MarketplaceWebService/Model/CancelReportRequestsResponse.php";
require_once "MarketplaceWebService/Model/GetReportListRequest.php";
require_once "MarketplaceWebService/Model/IdList.php";
require_once "MarketplaceWebService/Model/CancelReportRequestsResult.php";
require_once "MarketplaceWebService/Model/GetReportListResponse.php";
require_once "MarketplaceWebService/Model/ManageReportScheduleRequest.php";
require_once "MarketplaceWebService/Model/ContentType.php";
require_once "MarketplaceWebService/Model/GetReportListResult.php";
require_once "MarketplaceWebService/Model/ManageReportScheduleResponse.php";
require_once "MarketplaceWebService/Model/Error.php";
require_once "MarketplaceWebService/Model/GetReportRequestCountRequest.php";
require_once "MarketplaceWebService/Model/ManageReportScheduleResult.php";
require_once "MarketplaceWebService/Model/ErrorResponse.php";
require_once "MarketplaceWebService/Model/GetReportRequestCountResponse.php";
require_once "MarketplaceWebService/Model/ReportInfo.php";
require_once "MarketplaceWebService/Model/FeedSubmissionInfo.php";
require_once "MarketplaceWebService/Model/GetReportRequestCountResult.php";
require_once "MarketplaceWebService/Model/ReportRequestInfo.php";
require_once "MarketplaceWebService/Model/GetFeedSubmissionCountRequest.php";
require_once "MarketplaceWebService/Model/GetReportRequestListByNextTokenRequest.php";
require_once "MarketplaceWebService/Model/ReportSchedule.php";
require_once "MarketplaceWebService/Model/GetFeedSubmissionCountResponse.php";
require_once "MarketplaceWebService/Model/GetReportRequestListByNextTokenResponse.php";
require_once "MarketplaceWebService/Model/RequestReportRequest.php";
require_once "MarketplaceWebService/Model/GetFeedSubmissionCountResult.php";
require_once "MarketplaceWebService/Model/GetReportRequestListByNextTokenResult.php";
require_once "MarketplaceWebService/Model/RequestReportResponse.php";
require_once "MarketplaceWebService/Model/GetFeedSubmissionListByNextTokenRequest.php";
require_once "MarketplaceWebService/Model/GetReportRequestListRequest.php";
require_once "MarketplaceWebService/Model/RequestReportResult.php";
require_once "MarketplaceWebService/Model/GetFeedSubmissionListByNextTokenResponse.php";
require_once "MarketplaceWebService/Model/GetReportRequestListResponse.php";
require_once "MarketplaceWebService/Model/ResponseHeaderMetadata.php";
require_once "MarketplaceWebService/Model/GetFeedSubmissionListByNextTokenResult.php";
require_once "MarketplaceWebService/Model/GetReportRequestListResult.php";
require_once "MarketplaceWebService/Model/ResponseMetadata.php";
require_once "MarketplaceWebService/Model/GetFeedSubmissionListRequest.php";
require_once "MarketplaceWebService/Model/GetReportRequest.php";
require_once "MarketplaceWebService/Model/StatusList.php";
require_once "MarketplaceWebService/Model/GetFeedSubmissionListResponse.php";
require_once "MarketplaceWebService/Model/GetReportResponse.php";
require_once "MarketplaceWebService/Model/SubmitFeedRequest.php";
require_once "MarketplaceWebService/Model/GetFeedSubmissionListResult.php";
require_once "MarketplaceWebService/Model/GetReportResult.php";
require_once "MarketplaceWebService/Model/SubmitFeedResponse.php";
require_once "MarketplaceWebService/Model/GetFeedSubmissionResultRequest.php";
require_once "MarketplaceWebService/Model/GetReportScheduleCountRequest.php";
require_once "MarketplaceWebService/Model/SubmitFeedResult.php";
require_once "MarketplaceWebService/Model/GetFeedSubmissionResultResponse.php";
require_once "MarketplaceWebService/Model/GetReportScheduleCountResponse.php";
require_once "MarketplaceWebService/Model/TypeList.php";
require_once "MarketplaceWebService/Model/GetFeedSubmissionResultResult.php";
require_once "MarketplaceWebService/Model/GetReportScheduleCountResult.php";
require_once "MarketplaceWebService/Model/UpdateReportAcknowledgementsRequest.php";
require_once "MarketplaceWebService/Model/GetReportCountRequest.php";
require_once "MarketplaceWebService/Model/GetReportScheduleListByNextTokenRequest.php";
require_once "MarketplaceWebService/Model/UpdateReportAcknowledgementsResponse.php";
require_once "MarketplaceWebService/Model/GetReportCountResponse.php";
require_once "MarketplaceWebService/Model/GetReportScheduleListByNextTokenResponse.php";
require_once "MarketplaceWebService/Model/UpdateReportAcknowledgementsResult.php";

ini_set('memory_limit', '512M');
set_time_limit(0);
x_load('backoffice','files','taxes', 'froogle', 'product', 'crypt');

if ($config["cidev_amazon_settlement_report"] == "Y"){
//        die("Already launched"); // ################################
}
db_query("UPDATE $sql_tbl[config] SET value='Y' WHERE name='cidev_amazon_settlement_report'");
//db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='cidev_amazon_settlement_report'");

$started_at = time();

$log_text = " * * *  Cron started  * * * ";
func_backprocess_log("Amazon_Reports_Cron", $log_text);



  function invokeGetReport(MarketplaceWebService_Interface $service, $request)
  {
      try {
              $response = $service->getReport($request);

                echo ("Service Response\n");
                echo ("=============================================================================\n");

                echo("        GetReportResponse\n");
                if ($response->isSetGetReportResult()) {
                  $getReportResult = $response->getGetReportResult();
                  echo ("            GetReport");

                  if ($getReportResult->isSetContentMd5()) {
                    echo ("                ContentMd5");
                    echo ("                " . $getReportResult->getContentMd5() . "\n");
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

                echo ("        Report Contents\n");
                echo (stream_get_contents($request->getReport()) . "\n");

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
 }

  function invokeRequestReport(MarketplaceWebService_Interface $service, $request)
  {
      try {
              $response = $service->requestReport($request);

//                echo ("Service Response\n");
//                echo ("=============================================================================\n");

//                echo("        RequestReportResponse\n");
                if ($response->isSetRequestReportResult()) {
//                    echo("            RequestReportResult\n");
                    $requestReportResult = $response->getRequestReportResult();

                    if ($requestReportResult->isSetReportRequestInfo()) {

                        $reportRequestInfo = $requestReportResult->getReportRequestInfo();
//                          echo("                ReportRequestInfo\n");
                          if ($reportRequestInfo->isSetReportRequestId())
                          {
//                              echo("                    ReportRequestId\n");
//                              echo("                        " . $reportRequestInfo->getReportRequestId() . "\n");
                              $return_echo["ReportRequestId"] = $reportRequestInfo->getReportRequestId();
                          }
                          if ($reportRequestInfo->isSetReportType())
                          {
//                              echo("                    ReportType\n");
//                              echo("                        " . $reportRequestInfo->getReportType() . "\n");
                              $return_echo["ReportType"] = $reportRequestInfo->getReportType();
                          }
                          if ($reportRequestInfo->isSetStartDate())
                          {
//                              echo("                    StartDate\n");
//                              echo("                        " . $reportRequestInfo->getStartDate()->format(DATE_FORMAT) . "\n");
                              $return_echo["StartDate"] = $reportRequestInfo->getStartDate()->format(DATE_FORMAT);
                          }
                          if ($reportRequestInfo->isSetEndDate())
                          {
//                              echo("                    EndDate\n");
//                              echo("                        " . $reportRequestInfo->getEndDate()->format(DATE_FORMAT) . "\n");
                              $return_echo["EndDate"] = $reportRequestInfo->getEndDate()->format(DATE_FORMAT);
                          }
                          if ($reportRequestInfo->isSetSubmittedDate())
                          {
//                              echo("                    SubmittedDate\n");
//                              echo("                        " . $reportRequestInfo->getSubmittedDate()->format(DATE_FORMAT) . "\n");
                              $return_echo["SubmittedDate"] = $reportRequestInfo->getSubmittedDate()->format(DATE_FORMAT);
                          }
                          if ($reportRequestInfo->isSetReportProcessingStatus())
                          {
//                              echo("                    ReportProcessingStatus\n");
//                              echo("                        " . $reportRequestInfo->getReportProcessingStatus() . "\n");
                              $return_echo["ReportProcessingStatus"] = $reportRequestInfo->getReportProcessingStatus();
                          }
                      }
                }
                if ($response->isSetResponseMetadata()) {
//                    echo("            ResponseMetadata\n");
                    $responseMetadata = $response->getResponseMetadata();
                    if ($responseMetadata->isSetRequestId())
                    {
//                        echo("                RequestId\n");
//                        echo("                    " . $responseMetadata->getRequestId() . "\n");
                        $return_echo["RequestId"] = $responseMetadata->getRequestId();
                    }
                }

//                echo("            ResponseHeaderMetadata: " . $response->getResponseHeaderMetadata() . "\n");
                $return_echo["ResponseHeaderMetadata"] = $response->getResponseHeaderMetadata();

		return $return_echo;

     } catch (MarketplaceWebService_Exception $ex) {

/*
         echo("Caught Exception: " . $ex->getMessage() . "\n");
         echo("Response Status Code: " . $ex->getStatusCode() . "\n");
         echo("Error Code: " . $ex->getErrorCode() . "\n");
         echo("Error Type: " . $ex->getErrorType() . "\n");
         echo("Request ID: " . $ex->getRequestId() . "\n");
         echo("XML: " . $ex->getXML() . "\n");
         echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n");
*/

        $return_echo["function"] = "invokeRequestReport";
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

  function invokeGetReportRequestList(MarketplaceWebService_Interface $service, $request)
  {
      try {
              $response = $service->getReportRequestList($request);

//                echo ("Service Response\n");
//                echo ("=============================================================================\n");

//                echo("        GetReportRequestListResponse\n");
                if ($response->isSetGetReportRequestListResult()) {
//                    echo("            GetReportRequestListResult\n");
                    $getReportRequestListResult = $response->getGetReportRequestListResult();
                    if ($getReportRequestListResult->isSetNextToken())
                    {
//                        echo("                NextToken\n");
//                        echo("                    " . $getReportRequestListResult->getNextToken() . "\n");
                        $return_echo["NextToken"] = $getReportRequestListResult->getNextToken();
                    }
                    if ($getReportRequestListResult->isSetHasNext())
                    {
//                        echo("                HasNext\n");
//                        echo("                    " . $getReportRequestListResult->getHasNext() . "\n");
                        $return_echo["HasNext"] = $getReportRequestListResult->getHasNext();
                    }
                    $reportRequestInfoList = $getReportRequestListResult->getReportRequestInfoList();
                    foreach ($reportRequestInfoList as $reportRequestInfo) {
//                        echo("                ReportRequestInfo\n");
                    if ($reportRequestInfo->isSetReportRequestId())
                          {
//                              echo("                    ReportRequestId\n");
//                              echo("                        " . $reportRequestInfo->getReportRequestId() . "\n");
                              $return_echo["ReportRequestId"] = $reportRequestInfo->getReportRequestId();
                          }
                          if ($reportRequestInfo->isSetReportType())
                          {
//                              echo("                    ReportType\n");
//                              echo("                        " . $reportRequestInfo->getReportType() . "\n");
                              $return_echo["ReportType"] = $reportRequestInfo->getReportType();
                          }
                          if ($reportRequestInfo->isSetStartDate())
                          {
//                              echo("                    StartDate\n");
//                              echo("                        " . $reportRequestInfo->getStartDate()->format(DATE_FORMAT) . "\n");
                              $return_echo["StartDate"] = $reportRequestInfo->getStartDate()->format(DATE_FORMAT);
                          }
                          if ($reportRequestInfo->isSetEndDate())
                          {
//                              echo("                    EndDate\n");
//                              echo("                        " . $reportRequestInfo->getEndDate()->format(DATE_FORMAT) . "\n");
                              $return_echo["EndDate"] = $reportRequestInfo->getEndDate()->format(DATE_FORMAT);
                          }
                          // add start
                          if ($reportRequestInfo->isSetScheduled())
                          {
//                              echo("                    Scheduled\n");
//                              echo("                        " . $reportRequestInfo->getScheduled() . "\n");
                              $return_echo["Scheduled"] = $reportRequestInfo->getScheduled();
                          }
                          // add end
                          if ($reportRequestInfo->isSetSubmittedDate())
                          {
//                              echo("                    SubmittedDate\n");
//                              echo("                        " . $reportRequestInfo->getSubmittedDate()->format(DATE_FORMAT) . "\n");
                              $return_echo["SubmittedDate"] = $reportRequestInfo->getSubmittedDate()->format(DATE_FORMAT);
                          }
                          if ($reportRequestInfo->isSetReportProcessingStatus())
                          {
//                              echo("                    ReportProcessingStatus\n");
//                              echo("                        " . $reportRequestInfo->getReportProcessingStatus() . "\n");
                              $return_echo["ReportProcessingStatus"] = $reportRequestInfo->getReportProcessingStatus();
                          }
                          // add start
                          if ($reportRequestInfo->isSetGeneratedReportId())
                          {
//                              echo("                    GeneratedReportId\n");
//                              echo("                        " . $reportRequestInfo->getGeneratedReportId() . "\n");
                              $return_echo["GeneratedReportId"] = $reportRequestInfo->getGeneratedReportId();
                          }
                          if ($reportRequestInfo->isSetStartedProcessingDate())
                          {
//                              echo("                    StartedProcessingDate\n");
//                              echo("                        " . $reportRequestInfo->getStartedProcessingDate()->format(DATE_FORMAT) . "\n");
                              $return_echo["StartedProcessingDate"] = $reportRequestInfo->getStartedProcessingDate()->format(DATE_FORMAT);
                          }
                          if ($reportRequestInfo->isSetCompletedDate())
                          {
//                              echo("                    CompletedDate\n");
//                              echo("                        " . $reportRequestInfo->getCompletedDate()->format(DATE_FORMAT) . "\n");
                              $return_echo["CompletedDate"] = $reportRequestInfo->getCompletedDate()->format(DATE_FORMAT);
                          }
                          // add end

                    }
                }
                if ($response->isSetResponseMetadata()) {
//                    echo("            ResponseMetadata\n");
                    $responseMetadata = $response->getResponseMetadata();
                    if ($responseMetadata->isSetRequestId())
                    {
//                        echo("                RequestId\n");
//                        echo("                    " . $responseMetadata->getRequestId() . "\n");
                        $return_echo["RequestId"] = $responseMetadata->getRequestId();
                    }
                }

//                echo("            ResponseHeaderMetadata: " . $response->getResponseHeaderMetadata() . "\n");
                $return_echo["ResponseHeaderMetadata"] = $response->getResponseHeaderMetadata();

		return $return_echo;

     } catch (MarketplaceWebService_Exception $ex) {
/*
         echo("Caught Exception: " . $ex->getMessage() . "\n");
         echo("Response Status Code: " . $ex->getStatusCode() . "\n");
         echo("Error Code: " . $ex->getErrorCode() . "\n");
         echo("Error Type: " . $ex->getErrorType() . "\n");
         echo("Request ID: " . $ex->getRequestId() . "\n");
         echo("XML: " . $ex->getXML() . "\n");
         echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n");
*/
        $return_echo["function"] = "invokeGetReportRequestList";
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


$a_config = array (
  'ServiceURL' => "https://mws.amazonservices.com",
  'ProxyHost' => null,
  'ProxyPort' => -1,
  'MaxErrorRetry' => 3,
);

 $service = new MarketplaceWebService_Client(
     AWS_ACCESS_KEY_ID,
     AWS_SECRET_ACCESS_KEY,
     $a_config,
     APPLICATION_NAME,
     APPLICATION_VERSION);



$marketplaceIdArray = array("Id" => array('ATVPDKIKX0DER'));




# ## ### Start Step 1 ### ## #


// @TO DO set request. Action can be passed as MarketplaceWebService_Model_ReportRequest
// object or array of parameters

// $parameters = array (
//   'Merchant' => MERCHANT_ID,
//   'MarketplaceIdList' => $marketplaceIdArray,
//   'ReportType' => '_GET_MERCHANT_LISTINGS_DATA_',
//   'ReportOptions' => 'ShowSalesChannel=true',
//   'MWSAuthToken' => '<MWS Auth Token>', // Optional
// );

// $request = new MarketplaceWebService_Model_RequestReportRequest($parameters);

 $request = new MarketplaceWebService_Model_RequestReportRequest();
 $request->setMarketplaceIdList($marketplaceIdArray);
 $request->setMerchant(MERCHANT_ID);
// $request->setReportType('_GET_MERCHANT_LISTINGS_DATA_');
 $request->setReportType('_GET_V2_SETTLEMENT_REPORT_DATA_XML_');

 $sdate = new DateTime('-14 days', new DateTimeZone('UTC'));
 $startdate = $sdate->format("Y-m-d\T00:00:00P");

//   $edate = new DateTime('-1 days', new DateTimeZone('UTC'));
//   $enddate = $edate->format("Y-m-d\T23:59:59P");

 $request->setStartDate(new DateTime($startdate, new DateTimeZone('UTC')));
//   $request->setEndDate(new DateTime($enddate, new DateTimeZone('UTC')));

// $request->setMWSAuthToken('<MWS Auth Token>'); // Optional

// Using ReportOptions
// $request->setReportOptions('ShowSalesChannel=true');

 $dom_xml_arr = invokeRequestReport($service, $request);

  while (!empty($dom_xml_arr["Caught_Exception"]) && $dom_xml_arr["Caught_Exception"] == "Request is throttled" && $dom_xml_arr["Response_Status_Code"] == "503"){
        func_flush("sleeping...");
        func_flush();
        sleep('123');
        func_flush("Unsleeped");
        func_flush();

	$request = new MarketplaceWebService_Model_RequestReportRequest();
	$request->setMarketplaceIdList($marketplaceIdArray);
	$request->setMerchant(MERCHANT_ID);
	// $request->setReportType('_GET_MERCHANT_LISTINGS_DATA_');
	$request->setReportType('_GET_V2_SETTLEMENT_REPORT_DATA_XML_');

	$sdate = new DateTime('-14 days', new DateTimeZone('UTC'));
	$startdate = $sdate->format("Y-m-d\T00:00:00P");

	//   $edate = new DateTime('-1 days', new DateTimeZone('UTC'));
	//   $enddate = $edate->format("Y-m-d\T23:59:59P");

	$request->setStartDate(new DateTime($startdate, new DateTimeZone('UTC')));
	//   $request->setEndDate(new DateTime($enddate, new DateTimeZone('UTC')));

	// $request->setMWSAuthToken('<MWS Auth Token>'); // Optional

	// Using ReportOptions
	// $request->setReportOptions('ShowSalesChannel=true');

	$dom_xml_arr = invokeRequestReport($service, $request);
  }


 func_print_r($dom_xml_arr);

 $log_text = "ReportRequestId = ".$dom_xml_arr["ReportRequestId"];
 func_backprocess_log("Amazon_Reports_Cron", $log_text);

 if (empty($dom_xml_arr["ReportRequestId"])){
	$log_text = "ReportRequestId was empty. Script Stopped.";
	func_backprocess_log("Amazon_Reports_Cron", $log_text);
	die("Stopped.");
 }

# ## ### End Step 1 ### ## #



# ## ### Start Step 2 ### ## #

// @TO DO: set request. Action can be passed as MarketplaceWebService_Model_GetReportListRequest
// object or array of parameters

// $parameters = array (
//   'Merchant' => MERCHANT_ID,
//   'MWSAuthToken' => '<MWS Auth Token>', // Optional
// );
// $request = new MarketplaceWebService_Model_GetReportRequestListRequest($parameters);

 $ReportRequestId = $dom_xml_arr["ReportRequestId"];

////////////////////////////////////////////////////////////////////////////////
//$ReportRequestId = "54952016588";  //<----------------------- for test purpose
////////////////////////////////////////////////////////////////////////////////

 $reportRequestIdList = new MarketplaceWebService_Model_IdList();
 $reportRequestIdList->setId($ReportRequestId);  // ReportRequestId

 $tmp_counter = 0;
 $dom_xml_arr2 = array();
 while ( (!empty($dom_xml_arr2["Caught_Exception"]) && $dom_xml_arr2["Caught_Exception"] == "Request is throttled" && $dom_xml_arr2["Response_Status_Code"] == "503") || $dom_xml_arr2["ReportProcessingStatus"] != "_DONE_"){

	$request2 = new MarketplaceWebService_Model_GetReportRequestListRequest();
	$request2->setMerchant(MERCHANT_ID);
	$request2->setReportRequestIdList($reportRequestIdList);
//$request->setMWSAuthToken('<MWS Auth Token>'); // Optional
// 
	$dom_xml_arr2 = invokeGetReportRequestList($service, $request2);

	func_print_r($dom_xml_arr2);

	if ($dom_xml_arr2["ReportProcessingStatus"] == "_DONE_"){
		break;
	}

	if ($tmp_counter > 45){
	        $log_text = "Wait report request failed after ".$tmp_counter." iterations";
	        func_backprocess_log("Amazon_Reports_Cron", $log_text);
		break;
	}

        func_flush("sleeping...");
        func_flush();
        sleep('123');
        func_flush("Unsleeped");
        func_flush();

	$tmp_counter++;
 }

 $log_text = "GeneratedReportId = ".$dom_xml_arr2["GeneratedReportId"];
 func_backprocess_log("Amazon_Reports_Cron", $log_text);

 if (empty($dom_xml_arr2["GeneratedReportId"])){
        $log_text = "GeneratedReportId was empty. Script Stopped.";
        func_backprocess_log("Amazon_Reports_Cron", $log_text);
	die("Stopped.");
 }

# ## ### End Step 2 ### ## #



# ## ### Start Step 3 ### ## #
// @TO DO: set request. Action can be passed as MarketplaceWebService_Model_GetReportRequest
// object or array of parameters
// $reportId = '<Your Report Id>';
 
 $reportId = $dom_xml_arr2["GeneratedReportId"];

// $parameters = array (
//   'Merchant' => MERCHANT_ID,
//   'Report' => @fopen('php://memory', 'rw+'),
//   'ReportId' => $reportId,
//   'MWSAuthToken' => '<MWS Auth Token>', // Optional
// );
// $request = new MarketplaceWebService_Model_GetReportRequest($parameters);

$request = new MarketplaceWebService_Model_GetReportRequest();
$request->setMerchant(MERCHANT_ID);
//$request->setReport(@fopen('php://memory', 'rw+'));
$request->setReportId($reportId);
//$request->setMWSAuthToken('<MWS Auth Token>'); // Optional

$dom_xml3 = invokeGetReport($service, $request);


# ## ### End Step 3 ### ## #





db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='cidev_amazon_settlement_report'");

$log_text = "Cron completed.";
func_backprocess_log("Amazon_Reports_Cron", $log_text);

die("DONE!");
?>
