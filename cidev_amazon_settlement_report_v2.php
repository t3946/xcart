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
x_load('backoffice','files','taxes', 'froogle', 'product', 'crypt', 'xml');

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

//                echo ("Service Response\n");
//                echo ("=============================================================================\n");

//                echo("        GetReportResponse\n");
                if ($response->isSetGetReportResult()) {
                  $getReportResult = $response->getGetReportResult();
//                  echo ("            GetReport");

                  if ($getReportResult->isSetContentMd5()) {
//                    echo ("                ContentMd5");
                    $return_echo["ContentMd5"] = $getReportResult->getContentMd5();
                  }
                }
                if ($response->isSetResponseMetadata()) {
//                    echo("            ResponseMetadata\n");
                    $responseMetadata = $response->getResponseMetadata();
                    if ($responseMetadata->isSetRequestId())
                    {
//                        echo("                RequestId\n");
                        $return_echo["RequestId"] = $responseMetadata->getRequestId();
                    }
                }

//                echo ("        Report Contents\n");
//                echo (stream_get_contents($request->getReport()) . "\n");
                $return_echo["Report_Contents"] = stream_get_contents($request->getReport());

                $return_echo["ResponseHeaderMetadata"] = $response->getResponseHeaderMetadata();

	return $return_echo;
     } catch (MarketplaceWebService_Exception $ex) {
         $return_echo["Caught_Exception"] = $ex->getMessage();
         $return_echo["Response_Status_Code"] = $ex->getStatusCode();
         $return_echo["Error_Code"] = $ex->getErrorCode();
         $return_echo["Error_Type"] = $ex->getErrorType();
         $return_echo["Request_ID"] = $ex->getRequestId();
         $return_echo["XML"] = $ex->getXML();
         $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();

	return $return_echo;
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

  function invokeGetReportList(MarketplaceWebService_Interface $service, $request)
  {
      try {
              $response = $service->getReportList($request);

$response_arr["ReportId"] = array();

                echo ("Service Response\n");
                echo ("=============================================================================\n");

                echo("        GetReportListResponse\n");
                if ($response->isSetGetReportListResult()) {
                    echo("            GetReportListResult\n");
                    $getReportListResult = $response->getGetReportListResult();
                    if ($getReportListResult->isSetNextToken())
                    {
                        echo("                NextToken\n");
                        echo("                    " . $getReportListResult->getNextToken() . "\n");
                    }
                    if ($getReportListResult->isSetHasNext())
                    {
                        echo("                HasNext\n");
                        echo("                    " . $getReportListResult->getHasNext() . "\n");
                    }
                    $reportInfoList = $getReportListResult->getReportInfoList();
                    foreach ($reportInfoList as $reportInfo) {
                        echo("                ReportInfo\n");
                        if ($reportInfo->isSetReportId())
                        {
                            echo("                    ReportId\n");
                            echo("                        " . $reportInfo->getReportId() . "\n");
				$response_arr["ReportId"][] = $reportInfo->getReportId();
                        }
                        if ($reportInfo->isSetReportType())
                        {
                            echo("                    ReportType\n");
                            echo("                        " . $reportInfo->getReportType() . "\n");
                        }
                        if ($reportInfo->isSetReportRequestId())
                        {
                            echo("                    ReportRequestId\n");
                            echo("                        " . $reportInfo->getReportRequestId() . "\n");
                        }
                        if ($reportInfo->isSetAvailableDate())
                        {
                            echo("                    AvailableDate\n");
                            echo("                        " . $reportInfo->getAvailableDate()->format(DATE_FORMAT) . "\n");
                        }
                        if ($reportInfo->isSetAcknowledged())
                        {
                            echo("                    Acknowledged\n");
                            echo("                        " . $reportInfo->getAcknowledged() . "\n");
                        }
                        if ($reportInfo->isSetAcknowledgedDate())
                        {
                            echo("                    AcknowledgedDate\n");
                            echo("                        " . $reportInfo->getAcknowledgedDate()->format(DATE_FORMAT) . "\n");
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

	return $response_arr;

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

  function invokeUpdateReportAcknowledgements(MarketplaceWebService_Interface $service, $request)
  {
      try {
              $response = $service->updateReportAcknowledgements($request);

                echo ("Service Response\n");
                echo ("=============================================================================\n");

                echo("        UpdateReportAcknowledgementsResponse\n");
                if ($response->isSetUpdateReportAcknowledgementsResult()) {
                    echo("            UpdateReportAcknowledgementsResult\n");
                    $updateReportAcknowledgementsResult = $response->getUpdateReportAcknowledgementsResult();
                    if ($updateReportAcknowledgementsResult->isSetCount())
                    {
                        echo("                Count\n");
                        echo("                    " . $updateReportAcknowledgementsResult->getCount() . "\n");
                    }
                    $reportInfoList = $updateReportAcknowledgementsResult->getReportInfo();
                    foreach ($reportInfoList as $reportInfo) {
                        echo("                ReportInfo\n");
                        if ($reportInfo->isSetReportId())
                        {
                            echo("                    ReportId\n");
                            echo("                        " . $reportInfo->getReportId() . "\n");
                        }
                        if ($reportInfo->isSetReportType())
                        {
                            echo("                    ReportType\n");
                            echo("                        " . $reportInfo->getReportType() . "\n");
                        }
                        if ($reportInfo->isSetReportRequestId())
                        {
                            echo("                    ReportRequestId\n");
                            echo("                        " . $reportInfo->getReportRequestId() . "\n");
                        }
                        if ($reportInfo->isSetAvailableDate())
                        {
                            echo("                    AvailableDate\n");
                            echo("                        " . $reportInfo->getAvailableDate()->format(DATE_FORMAT) . "\n");
                        }
                        if ($reportInfo->isSetAcknowledged())
                        {
                            echo("                    Acknowledged\n");
                            echo("                        " . $reportInfo->getAcknowledged() . "\n");
                        }
                        if ($reportInfo->isSetAcknowledgedDate())
                        {
                            echo("                    AcknowledgedDate\n");
                            echo("                        " . $reportInfo->getAcknowledgedDate()->format(DATE_FORMAT) . "\n");
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



/*

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

*/



# ## ### Start Step 3 ### ## #

func_print_r("Step 3: a) START");

// @TO DO: set request. Action can be passed as MarketplaceWebService_Model_GetReportListRequest
// object or array of parameters
// $parameters = array (
//   'Merchant' => MERCHANT_ID,
//   'AvailableToDate' => new DateTime('now', new DateTimeZone('UTC')),
//   'AvailableFromDate' => new DateTime('-6 months', new DateTimeZone('UTC')),
//   'Acknowledged' => false, 
//   'MWSAuthToken' => '<MWS Auth Token>', // Optional
// );
// 
// $request = new MarketplaceWebService_Model_GetReportListRequest($parameters);


$reqt = new MarketplaceWebService_Model_TypeList;
$reqt->withType('_GET_V2_SETTLEMENT_REPORT_DATA_XML_');

 $request = new MarketplaceWebService_Model_GetReportListRequest();
 $request->setMerchant(MERCHANT_ID);
// $request->setAvailableToDate(new DateTime('now', new DateTimeZone('UTC')));
// $request->setAvailableFromDate(new DateTime('-3 months', new DateTimeZone('UTC')));

 $request->setReportTypeList($reqt);
 $request->setMaxCount("100");
 $request->setAcknowledged(false);

// $request->setAcknowledged(false);
// $request->setMWSAuthToken('<MWS Auth Token>'); // Optional

//$dom_xml3_a =  invokeGetReportList($service, $request);
$dom_xml_arr = invokeGetReportList($service, $request);

//func_print_r($dom_xml_arr);


func_print_r("Step 3: a) END");

//die("=====================");

func_flush("\r\n");
func_flush("\r\n");
func_flush("\r\n");
func_flush("\r\n");
func_print_r("Step 3: b) START");
//die();


// @TO DO: set request. Action can be passed as MarketplaceWebService_Model_GetReportRequest
// object or array of parameters
// $reportId = '<Your Report Id>';
 
// $reportId = $dom_xml_arr2["GeneratedReportId"];

   $reportId = $dom_xml_arr["ReportId"][0];


 $log_text = "ReportId = ".$reportId;
 func_backprocess_log("Amazon_Reports_Cron", $log_text);


###########
#$reportId = 24375331893;
//$reportId = 59251048223;
###########


//$reportId = 57479778633;



// $parameters = array (
//   'Merchant' => MERCHANT_ID,
//   'Report' => @fopen('php://memory', 'rw+'),
//   'ReportId' => $reportId,
//   'MWSAuthToken' => '<MWS Auth Token>', // Optional
// );
// $request = new MarketplaceWebService_Model_GetReportRequest($parameters);


//$reportId="900317198016783";


$request = new MarketplaceWebService_Model_GetReportRequest();
$request->setMerchant(MERCHANT_ID);
$request->setReport(@fopen('php://memory', 'rw+'));
$request->setReportId($reportId);
//$request->setMWSAuthToken('<MWS Auth Token>'); // Optional

$dom_xml_3 = invokeGetReport($service, $request);

//func_print_r($dom_xml_3);

$dom_xml = $dom_xml_3["Report_Contents"];

if (!empty($dom_xml)){

	$findme_arr = array("Order", "Refund", "Fee", "Component", "Item", "AdjustedItem");

	foreach ($findme_arr as $findme){
	        $pos = strpos($dom_xml, "<$findme>");
	        if ($pos !== "false"){
        	        $dom_xml_arr = explode("<$findme>",$dom_xml);
	                $count_dom_xml_arr = count($dom_xml_arr);
        	        $dom_xml = "";
	                foreach ($dom_xml_arr as $k => $v){
        	                $k_n = $k-1;
                	        $v = str_replace("</$findme>","</$findme$k_n>",$v);
                        	$dom_xml .= $v.($k != ($count_dom_xml_arr-1)?"<$findme$k>":"");
	                }
        	}
	}

        $dom_xml_arr = func_xml2hash($dom_xml, "UTF-8");
}   

//func_print_r($dom_xml_arr["AmazonEnvelope"]["Message"]["SettlementReport"]);
//die();


if (!empty($dom_xml_arr["AmazonEnvelope"]["Message"]["SettlementReport"]) && is_array($dom_xml_arr["AmazonEnvelope"]["Message"]["SettlementReport"])){

    $order_not_found = false;

    foreach ($dom_xml_arr["AmazonEnvelope"]["Message"]["SettlementReport"] as $k => $v){
	if (!empty($v["AmazonOrderID"])){

//if ($v["AmazonOrderID"] != "104-4999108-5002664") continue; ###################################


		$fields = "orderid";
		$order_info = func_query_first("SELECT $fields FROM $sql_tbl[orders] WHERE amazonorderid='$v[AmazonOrderID]'");

		if (!empty($order_info)){
			$log_text = "order processed: <AmazonOrderID>".$v["AmazonOrderID"]."</AmazonOrderID>";
			func_backprocess_log("Amazon_Reports_Cron", $log_text);


			$acc_paymentid = "";
			if ($v["Fulfillment"]["MerchantFulfillmentID"] == "MFN"){
				$acc_paymentid = "1";
			}
			elseif ($v["Fulfillment"]["MerchantFulfillmentID"] == "AFN"){
				$acc_paymentid = "101";
			}

			db_query("UPDATE $sql_tbl[order_groups] SET acc_paymentid='$acc_paymentid' WHERE orderid='".$order_info["orderid"]."'");


                        if (strpos($k, "Order") !== false){
				$k_name = "Item";
                        } 
                        elseif (strpos($k, "Refund") !== false) {
				$k_name = "AdjustedItem";
                        }

//func_print_r($v);
//die();




			$mid_info = array();

# https://basecamp.com/2070980/projects/1577907/messages/52362361
## If products become in separate reports
###
			$AmazonOrderItemCode_arr = array();
			foreach ($v["Fulfillment"] as $kk => $vv){
				if (strpos($kk, $k_name)!==false){	
					$AmazonOrderItemCode_arr[] = $vv["AmazonOrderItemCode"];
				}
			}

//func_print_r($AmazonOrderItemCode_arr);

			$cost_to_us_IN_DB = 0;
			$RefundSum_IN_DB = 0;
			$FBAPerOrderFulfillmentFee_IN_DB = 0;
			$FBAPerUnitFulfillmentFee_IN_DB = 0;
			$FBAWeightBasedFee_IN_DB = 0;
			$AmazonCommission_IN_DB = 0;

			if (!empty($AmazonOrderItemCode_arr)){

//				$RECORDS_order_details = func_query("SELECT amount, productid, amazon_item_refunded, FBAPerOrderFulfillmentFee, FBAPerUnitFulfillmentFee, FBAWeightBasedFee, AmazonCommission FROM $sql_tbl[order_details] WHERE AmazonOrderItemCode NOT IN ('".implode("','", $AmazonOrderItemCode_arr)."')");
				$RECORDS_order_details = func_query("SELECT $sql_tbl[order_details].amount, $sql_tbl[order_details].productid, $sql_tbl[order_details].amazon_item_refunded, $sql_tbl[order_details].FBAPerOrderFulfillmentFee, $sql_tbl[order_details].FBAPerUnitFulfillmentFee, $sql_tbl[order_details].FBAWeightBasedFee, $sql_tbl[order_details].AmazonCommission FROM $sql_tbl[order_details] LEFT JOIN $sql_tbl[orders] ON $sql_tbl[orders].orderid=$sql_tbl[order_details].orderid WHERE $sql_tbl[order_details].AmazonOrderItemCode NOT IN ('".implode("','", $AmazonOrderItemCode_arr)."') AND $sql_tbl[orders].amazonorderid='$v[AmazonOrderID]'");

				if (!empty($RECORDS_order_details)){
					foreach ($RECORDS_order_details as $k_RECORD => $v_RECORD){

						$tmp_prod_info = func_query_first("SELECT cost_to_us, manufacturerid FROM $sql_tbl[products] WHERE productid='$v_RECORD[productid]'");
						$manufacturerid = $tmp_prod_info["manufacturerid"];
						$cost_to_us = $tmp_prod_info["cost_to_us"];
						$cost_to_us_IN_DB += $cost_to_us*$v_RECORD["amount"];

						if ($v_RECORD["amazon_item_refunded"] == "Y"){
							$RefundSum_IN_DB += func_query_first_cell("SELECT accounting_net_3_ref_to_cust FROM $sql_tbl[order_groups] WHERE orderid='".$order_info["orderid"]."' AND manufacturerid='$manufacturerid'");
						}

						$FBAPerOrderFulfillmentFee_IN_DB += $v_RECORD["FBAPerOrderFulfillmentFee"];
						$FBAPerUnitFulfillmentFee_IN_DB += $v_RECORD["FBAPerUnitFulfillmentFee"];
						$FBAWeightBasedFee_IN_DB += $v_RECORD["FBAWeightBasedFee"];
						$AmazonCommission_IN_DB += $v_RECORD["AmazonCommission"];
					}
				}


				unset($AmazonOrderItemCode_arr);
			}

###
##
#


			foreach ($v["Fulfillment"] as $kk => $vv){
				if (strpos($kk, $k_name)!==false){
	                                $SKU = $vv["SKU"];
					$manufacturerid = func_query_first_cell("SELECT manufacturerid FROM $sql_tbl[products] WHERE productcode='$SKU'");
				
					$cost_to_us = func_query_first_cell("SELECT cost_to_us FROM $sql_tbl[products] WHERE productcode='$SKU'");

#
##
					$RefundSum = 0;

					$RefundSum += $RefundSum_IN_DB;
					$RefundSum_IN_DB = 0;

					if ($k_name == "AdjustedItem"){

#
##
###
						db_query("UPDATE $sql_tbl[order_details] SET amazon_item_refunded='Y' WHERE orderid='".$order_info["orderid"]."' AND productcode='$SKU'");
					
//						$current_cb_status = func_query_first_cell("SELECT cb_status FROM $sql_tbl[order_groups] WHERE orderid='".$order_info["orderid"]."' AND manufacturerid='$manufacturerid'");

//						if ( !($current_cb_status == "H" || $current_cb_status == "R")){
							db_query("UPDATE $sql_tbl[order_groups] SET cb_status='H' WHERE orderid='".$order_info["orderid"]."' AND manufacturerid='$manufacturerid'");
//						}

						$igor_query_for_status = func_query_first("select SUM(if(amazon_item_refunded='Y',1,0)) As refunded_items, COUNT(AmazonOrderItemCode) As all_items from xcart_order_details where orderid = '".$order_info["orderid"]."'Group By orderid");

						if ($igor_query_for_status["refunded_items"] == $igor_query_for_status["all_items"]){
							db_query("UPDATE $sql_tbl[order_groups] SET cb_status='R' WHERE orderid='".$order_info["orderid"]."'");
						}
###
##
#

						if (!isset($vv["Quantity"])){
							$vv["Quantity"] = 1;
						}

						if (!empty($vv["ItemPriceAdjustments"]) && is_array($vv["ItemPriceAdjustments"])){
							foreach ($vv["ItemPriceAdjustments"] as $kkk => $vvv){
								$field_name = $vvv["Type"];
								if ($field_name == "Principal" && !empty($vvv["Amount"])){
									$RefundSum += $vvv["Amount"];
								}
							}
						}

                                                if (!empty($vv["ItemFeeAdjustments"]) && is_array($vv["ItemFeeAdjustments"])){
                                                        foreach ($vv["ItemFeeAdjustments"] as $kkk => $vvv){
                                                                $field_name = $vvv["Type"];
                                                                if (($field_name == "Commission" || $field_name == "RefundCommission") && !empty($vvv["Amount"])){
                                                                        $RefundSum += $vvv["Amount"];
                                                                }
                                                        }
                                                }


					}

                                        $RefundSum = abs($RefundSum);
                                        if (!isset($mid_info[$manufacturerid]["RefundSum"])){
                                                $mid_info[$manufacturerid]["RefundSum"] = 0; 
                                        }
                                        $mid_info[$manufacturerid]["RefundSum"] += $RefundSum;
##
#


					$cost_to_us *= $vv["Quantity"];

#
##
					$cost_to_us += $cost_to_us_IN_DB; // If products become in separate reports
					$cost_to_us_IN_DB = 0;
##
#

                                        if (!isset($mid_info[$manufacturerid]["cost_to_us"])){
                                                $mid_info[$manufacturerid]["cost_to_us"] = 0;
                                        }
                                        $mid_info[$manufacturerid]["cost_to_us"] += $cost_to_us;


					$ProcessorFee = 0;

					if (!empty($vv["ItemFees"]) && is_array($vv["ItemFees"])){


						$update_fields = array();

						foreach ($vv["ItemFees"] as $kkk => $vvv){
							if (in_array($vvv["Type"], array("FBAPerOrderFulfillmentFee", "FBAPerUnitFulfillmentFee", "FBAWeightBasedFee", "Commission"))){
								$field_name = $vvv["Type"];
								if ($field_name == "Commission"){
									$field_name = "AmazonCommission";
								}

##
								if ($v["Fulfillment"]["MerchantFulfillmentID"] == "MFN" && in_array($field_name, array("FBAPerOrderFulfillmentFee", "FBAPerUnitFulfillmentFee", "FBAWeightBasedFee"))){
									$vvv["Amount"] = 0;
								}
##

#
##
								if ($field_name == "FBAPerOrderFulfillmentFee"){
									if ($FBAPerOrderFulfillmentFee_IN_DB > 0){
										$vvv["Amount"] = 0;
									}

//									$vvv["Amount"] += $FBAPerOrderFulfillmentFee_IN_DB;
//									$FBAPerOrderFulfillmentFee_IN_DB = 0;
								}
								elseif ($field_name == "FBAPerUnitFulfillmentFee"){
									$vvv["Amount"] += $FBAPerUnitFulfillmentFee_IN_DB;
									$FBAPerUnitFulfillmentFee_IN_DB = 0;
								}
								elseif ($field_name == "FBAWeightBasedFee"){
                                                                        $vvv["Amount"] += $FBAWeightBasedFee_IN_DB;
                                                                        $FBAWeightBasedFee_IN_DB = 0;
								}
								elseif ($field_name == "AmazonCommission"){
                                                                        $vvv["Amount"] += $AmazonCommission_IN_DB;
                                                                        $AmazonCommission_IN_DB = 0;
                                                                }
##
#

								$update_fields[] = $field_name."='".$vvv["Amount"]."'";

								$ProcessorFee += $vvv["Amount"];
							}
						}

						if (!empty($update_fields)){
							db_query("UPDATE $sql_tbl[order_details] SET ".implode(', ', $update_fields)." WHERE orderid='".$order_info["orderid"]."' AND productcode='$SKU'");
							unset($update_fields);
						}
					}

					$ProcessorFee = abs($ProcessorFee);
					if (!isset($mid_info[$manufacturerid]["ProcessorFee"])){
						$mid_info[$manufacturerid]["ProcessorFee"] = 0;
					}
					$mid_info[$manufacturerid]["ProcessorFee"] += $ProcessorFee;

				} // if (strpos($kk, $k_name)!==false
			} // foreach ($v["Fulfillment"] as $kk => $vv

			if (!empty($mid_info)){
				foreach ($mid_info as $manufacturerid => $m_val){

					$order_group_info = func_query_first("SELECT * FROM $sql_tbl[order_groups] WHERE orderid='".$order_info["orderid"]."' AND manufacturerid='$manufacturerid'");

					$accounting_net_0=$order_group_info['accounting_net_0'];
					$accounting_gross_0=$order_group_info['accounting_gross_0'];
					$accounting_gross_1_cost_to_us=$order_group_info['accounting_gross_1_cost_to_us'];
					$accounting_net_1_cost_to_us=$order_group_info['accounting_net_1_cost_to_us'];
					$accounting_net_2_shipping=$order_group_info['accounting_net_2_shipping'];
					$accounting_gross_2_shipping=$order_group_info['accounting_gross_2_shipping'];
					$accounting_net_3_ref_to_cust=$order_group_info['accounting_net_3_ref_to_cust'];
					$accounting_gross_3_ref_to_cust=$order_group_info['accounting_gross_3_ref_to_cust'];
					$accounting_net_4_ref_to_us=$order_group_info['accounting_net_4_ref_to_us'];
					$accounting_gross_4_ref_to_us=$order_group_info['accounting_gross_4_ref_to_us'];
					$accounting_net_5_profit=$order_group_info['accounting_net_5_profit'];
					$accounting_gross_5_profit=$order_group_info['accounting_gross_5_profit'];
					$profit_margin=$order_group_info['profit_margin'];
			
				
					if ($m_val["RefundSum"] > 0){
						$accounting_net_3_ref_to_cust = $accounting_gross_3_ref_to_cust = $m_val["RefundSum"];
					} 
					else {

						$ProcessorFee = $m_val["ProcessorFee"];
        	                                $accounting_net_0 = $order_group_info["total_net"] - $ProcessorFee;
                	                        $accounting_gross_0 = $order_group_info["total_gross"] - $ProcessorFee;

                        	                if ($v["Fulfillment"]["MerchantFulfillmentID"] == "AFN"){
                                	                $accounting_net_1_cost_to_us = $accounting_gross_1_cost_to_us = $m_val["cost_to_us"];
                                        	} else {
							$accounting_net_1_cost_to_us = $accounting_gross_1_cost_to_us = 0;
						}

//        $acc[5]["net"] = $acc[0]["net"] - $acc[1]["net"] - $acc[2]["net"] - $acc[3]["net"] + $acc[4]["net"];
//        $acc[5]["gross"] = $acc[0]["gross"] - $acc[1]["gross"] - $acc[2]["gross"] - $acc[3]["gross"] + $acc[4]["gross"];

						$accounting_net_5_profit = $accounting_net_0 - $accounting_net_1_cost_to_us - $accounting_net_2_shipping - $accounting_net_3_ref_to_cust + $accounting_net_4_ref_to_us;
						$accounting_gross_5_profit = $accounting_gross_0 - $accounting_gross_1_cost_to_us - $accounting_gross_2_shipping - $accounting_gross_3_ref_to_cust + $accounting_gross_4_ref_to_us;
					}

					$profit_margin = price_format(($accounting_net_5_profit/($accounting_net_0 - $accounting_net_3_ref_to_cust + $accounting_net_4_ref_to_us))*100);

                                        db_query("UPDATE $sql_tbl[order_groups] SET accounting_net_0='$accounting_net_0', accounting_gross_0='$accounting_gross_0', accounting_gross_1_cost_to_us='$accounting_gross_1_cost_to_us', accounting_net_1_cost_to_us='$accounting_net_1_cost_to_us', accounting_net_3_ref_to_cust='$accounting_net_3_ref_to_cust', accounting_gross_3_ref_to_cust='$accounting_gross_3_ref_to_cust', accounting_net_4_ref_to_us='$accounting_net_4_ref_to_us', accounting_gross_4_ref_to_us='$accounting_gross_4_ref_to_us', accounting_net_5_profit='$accounting_net_5_profit', accounting_gross_5_profit='$accounting_gross_5_profit', profit_margin='$profit_margin' WHERE orderid='".$order_info["orderid"]."' AND manufacturerid='$manufacturerid'");
				}
			}
			unset($mid_info);


		} else {
			$dom_xml_arr["AmazonEnvelope"]["Message"]["SettlementReport"][$k]["order_not_found"] = "Y";

			$order_not_found = true;

			$log_text = "!!! ORDER NOT FOUND YET: <AmazonOrderID>".$v["AmazonOrderID"]."</AmazonOrderID>";
			func_backprocess_log("Amazon_Reports_Cron", $log_text);
		}
	}
    }

func_flush("\r\n");
func_flush("\r\n");
func_print_r("Step 3: b) END");
# ## ### End Step 3 ### ## #
//die("=====");


# ## ### Start Step 4 ### ## #
	if (!$order_not_found){

		func_flush("\r\n");
		func_flush("\r\n");
		func_print_r("Step 4: START");

		$request = new MarketplaceWebService_Model_UpdateReportAcknowledgementsRequest();
		$request->setMerchant(MERCHANT_ID);

		$idList = new MarketplaceWebService_Model_IdList();
		$request->setReportIdList($idList->withId($reportId));
		$request->setAcknowledged(true); //true
    
		invokeUpdateReportAcknowledgements($service, $request);
	}


# ## ### End Step 4 ### ## #
}




 


db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='cidev_amazon_settlement_report'");

$log_text = "Cron completed.";
func_backprocess_log("Amazon_Reports_Cron", $log_text);

die("DONE!");
?>
