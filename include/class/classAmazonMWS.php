<?php
global $xcart_dir;
include_once $xcart_dir . "/MarketplaceWebService/Samples/.config.inc.php";
require_once $xcart_dir . "/MarketplaceWebService/Client.php";
require_once $xcart_dir . "/MarketplaceWebService/Exception.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/CancelFeedSubmissionsRequest.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportCountResult.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportScheduleListByNextTokenResult.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/CancelFeedSubmissionsResponse.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportListByNextTokenRequest.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportScheduleListRequest.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/CancelFeedSubmissionsResult.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportListByNextTokenResponse.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportScheduleListResponse.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/CancelReportRequestsRequest.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportListByNextTokenResult.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportScheduleListResult.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/CancelReportRequestsResponse.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportListRequest.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/IdList.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/CancelReportRequestsResult.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportListResponse.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/ManageReportScheduleRequest.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/ContentType.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportListResult.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/ManageReportScheduleResponse.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/Error.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportRequestCountRequest.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/ManageReportScheduleResult.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/ErrorResponse.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportRequestCountResponse.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/ReportInfo.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/FeedSubmissionInfo.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportRequestCountResult.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/ReportRequestInfo.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetFeedSubmissionCountRequest.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportRequestListByNextTokenRequest.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/ReportSchedule.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetFeedSubmissionCountResponse.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportRequestListByNextTokenResponse.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/RequestReportRequest.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetFeedSubmissionCountResult.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportRequestListByNextTokenResult.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/RequestReportResponse.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetFeedSubmissionListByNextTokenRequest.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportRequestListRequest.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/RequestReportResult.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetFeedSubmissionListByNextTokenResponse.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportRequestListResponse.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/ResponseHeaderMetadata.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetFeedSubmissionListByNextTokenResult.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportRequestListResult.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/ResponseMetadata.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetFeedSubmissionListRequest.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportRequest.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/StatusList.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetFeedSubmissionListResponse.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportResponse.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/SubmitFeedRequest.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportResult.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/SubmitFeedResponse.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetFeedSubmissionResultRequest.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportScheduleCountRequest.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/SubmitFeedResult.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetFeedSubmissionResultResponse.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportScheduleCountResponse.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/TypeList.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetFeedSubmissionResultResult.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportScheduleCountResult.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/UpdateReportAcknowledgementsRequest.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportCountRequest.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportScheduleListByNextTokenRequest.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/UpdateReportAcknowledgementsResponse.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportCountResponse.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/GetReportScheduleListByNextTokenResponse.php";
require_once $xcart_dir . "/MarketplaceWebService/Model/UpdateReportAcknowledgementsResult.php";

require_once $xcart_dir . "/include/class/classProducts.php";

class classAmazonMWS
{
    private $oMWSService;
    private $marketplaceIdArray;
    private $dom_xml_arr;
    private $aWaitLoopExitCondition = [];
    private $aReportValue = [];
    private $aReportIds;
    private $sleepTimeOut = 60;
    public $error = [];


    public function __construct()
    {
        $a_config = array(
            'ServiceURL' => "https://mws.amazonservices.com",
            'ProxyHost' => null,
            'ProxyPort' => -1,
            'MaxErrorRetry' => 3,
        );

        $this->oMWSService = new MarketplaceWebService_Client(
            AWS_ACCESS_KEY_ID,
            AWS_SECRET_ACCESS_KEY,
            $a_config,
            APPLICATION_NAME,
            APPLICATION_VERSION);

        $this->marketplaceIdArray = array("Id" => array('ATVPDKIKX0DER'));
    }

    private function invokeGetReport($request)
    {
        try {
            $response = $this->oMWSService->getReport($request);

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
                if ($responseMetadata->isSetRequestId()) {
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

    private function invokeRequestReport($request)
    {
        try {
            $response = $this->oMWSService->requestReport($request);

//                echo ("Service Response\n");
//                echo ("=============================================================================\n");

//                echo("        RequestReportResponse\n");
            if ($response->isSetRequestReportResult()) {
//                    echo("            RequestReportResult\n");
                $requestReportResult = $response->getRequestReportResult();

                if ($requestReportResult->isSetReportRequestInfo()) {

                    $reportRequestInfo = $requestReportResult->getReportRequestInfo();
//                          echo("                ReportRequestInfo\n");
                    if ($reportRequestInfo->isSetReportRequestId()) {
//                              echo("                    ReportRequestId\n");
//                              echo("                        " . $reportRequestInfo->getReportRequestId() . "\n");
                        $return_echo["ReportRequestId"] = $reportRequestInfo->getReportRequestId();
                    }
                    if ($reportRequestInfo->isSetReportType()) {
//                              echo("                    ReportType\n");
//                              echo("                        " . $reportRequestInfo->getReportType() . "\n");
                        $return_echo["ReportType"] = $reportRequestInfo->getReportType();
                    }
                    if ($reportRequestInfo->isSetStartDate()) {
//                              echo("                    StartDate\n");
//                              echo("                        " . $reportRequestInfo->getStartDate()->format(DATE_FORMAT) . "\n");
                        $return_echo["StartDate"] = $reportRequestInfo->getStartDate()->format(DATE_FORMAT);
                    }
                    if ($reportRequestInfo->isSetEndDate()) {
//                              echo("                    EndDate\n");
//                              echo("                        " . $reportRequestInfo->getEndDate()->format(DATE_FORMAT) . "\n");
                        $return_echo["EndDate"] = $reportRequestInfo->getEndDate()->format(DATE_FORMAT);
                    }
                    if ($reportRequestInfo->isSetSubmittedDate()) {
//                              echo("                    SubmittedDate\n");
//                              echo("                        " . $reportRequestInfo->getSubmittedDate()->format(DATE_FORMAT) . "\n");
                        $return_echo["SubmittedDate"] = $reportRequestInfo->getSubmittedDate()->format(DATE_FORMAT);
                    }
                    if ($reportRequestInfo->isSetReportProcessingStatus()) {
//                              echo("                    ReportProcessingStatus\n");
//                              echo("                        " . $reportRequestInfo->getReportProcessingStatus() . "\n");
                        $return_echo["ReportProcessingStatus"] = $reportRequestInfo->getReportProcessingStatus();
                    }
                }
            }
            if ($response->isSetResponseMetadata()) {
//                    echo("            ResponseMetadata\n");
                $responseMetadata = $response->getResponseMetadata();
                if ($responseMetadata->isSetRequestId()) {
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

    private function invokeGetReportRequestList($request)
    {
        try {
            $response = $this->oMWSService->getReportRequestList($request);

//                echo ("Service Response\n");
//                echo ("=============================================================================\n");

//                echo("        GetReportRequestListResponse\n");
            if ($response->isSetGetReportRequestListResult()) {
//                    echo("            GetReportRequestListResult\n");

                $getReportRequestListResult = $response->getGetReportRequestListResult();
                if ($getReportRequestListResult->isSetNextToken()) {
//                        echo("                NextToken\n");
//                        echo("                    " . $getReportRequestListResult->getNextToken() . "\n");
                    $return_echo["NextToken"] = $getReportRequestListResult->getNextToken();
                }
                if ($getReportRequestListResult->isSetHasNext()) {
//                        echo("                HasNext\n");
//                        echo("                    " . $getReportRequestListResult->getHasNext() . "\n");
                    $return_echo["HasNext"] = $getReportRequestListResult->getHasNext();
                }
                $reportRequestInfoList = $getReportRequestListResult->getReportRequestInfoList();
                foreach ($reportRequestInfoList as $reportRequestInfo) {
//                        echo("                ReportRequestInfo\n");
                    if ($reportRequestInfo->isSetReportRequestId()) {
//                              echo("                    ReportRequestId\n");
//                              echo("                        " . $reportRequestInfo->getReportRequestId() . "\n");
                        $return_echo["ReportRequestId"] = $reportRequestInfo->getReportRequestId();
                    }
                    if ($reportRequestInfo->isSetReportType()) {
//                              echo("                    ReportType\n");
//                              echo("                        " . $reportRequestInfo->getReportType() . "\n");
                        $return_echo["ReportType"] = $reportRequestInfo->getReportType();
                    }
                    if ($reportRequestInfo->isSetStartDate()) {
//                              echo("                    StartDate\n");
//                              echo("                        " . $reportRequestInfo->getStartDate()->format(DATE_FORMAT) . "\n");
                        $return_echo["StartDate"] = $reportRequestInfo->getStartDate()->format(DATE_FORMAT);
                    }
                    if ($reportRequestInfo->isSetEndDate()) {
//                              echo("                    EndDate\n");
//                              echo("                        " . $reportRequestInfo->getEndDate()->format(DATE_FORMAT) . "\n");
                        $return_echo["EndDate"] = $reportRequestInfo->getEndDate()->format(DATE_FORMAT);
                    }
                    // add start
                    if ($reportRequestInfo->isSetScheduled()) {
//                              echo("                    Scheduled\n");
//                              echo("                        " . $reportRequestInfo->getScheduled() . "\n");
                        $return_echo["Scheduled"] = $reportRequestInfo->getScheduled();
                    }
                    // add end
                    if ($reportRequestInfo->isSetSubmittedDate()) {
//                              echo("                    SubmittedDate\n");
//                              echo("                        " . $reportRequestInfo->getSubmittedDate()->format(DATE_FORMAT) . "\n");
                        $return_echo["SubmittedDate"] = $reportRequestInfo->getSubmittedDate()->format(DATE_FORMAT);
                    }
                    if ($reportRequestInfo->isSetReportProcessingStatus()) {
//                              echo("                    ReportProcessingStatus\n");
//                              echo("                        " . $reportRequestInfo->getReportProcessingStatus() . "\n");
                        $return_echo["ReportProcessingStatus"] = $reportRequestInfo->getReportProcessingStatus();
                    }
                    // add start
                    if ($reportRequestInfo->isSetGeneratedReportId()) {
//                              echo("                    GeneratedReportId\n");
//                              echo("                        " . $reportRequestInfo->getGeneratedReportId() . "\n");
                        $return_echo["GeneratedReportId"] = $reportRequestInfo->getGeneratedReportId();
                    }
                    if ($reportRequestInfo->isSetStartedProcessingDate()) {
//                              echo("                    StartedProcessingDate\n");
//                              echo("                        " . $reportRequestInfo->getStartedProcessingDate()->format(DATE_FORMAT) . "\n");
                        $return_echo["StartedProcessingDate"] = $reportRequestInfo->getStartedProcessingDate()->format(DATE_FORMAT);
                    }
                    if ($reportRequestInfo->isSetCompletedDate()) {
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
                if ($responseMetadata->isSetRequestId()) {
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

    private function invokeGetReportList($request)
    {
        try {
            $response = $this->oMWSService->getReportList($request);

            $response_arr["ReportId"] = array();

            echo("Service Response\n");
            echo("=============================================================================\n");

            echo("        GetReportListResponse\n");
            if ($response->isSetGetReportListResult()) {
                echo("            GetReportListResult\n");
                $getReportListResult = $response->getGetReportListResult();
                if ($getReportListResult->isSetNextToken()) {
                    echo("                NextToken\n");
                    echo("                    " . $getReportListResult->getNextToken() . "\n");
                }
                if ($getReportListResult->isSetHasNext()) {
                    echo("                HasNext\n");
                    echo("                    " . $getReportListResult->getHasNext() . "\n");
                }
                $reportInfoList = $getReportListResult->getReportInfoList();
                foreach ($reportInfoList as $reportInfo) {
                    echo("                ReportInfo\n");
                    if ($reportInfo->isSetReportId()) {
                        echo("                    ReportId\n");
                        echo("                        " . $reportInfo->getReportId() . "\n");
                        $response_arr["ReportId"][] = $reportInfo->getReportId();
                    }
                    if ($reportInfo->isSetReportType()) {
                        echo("                    ReportType\n");
                        echo("                        " . $reportInfo->getReportType() . "\n");
                    }
                    if ($reportInfo->isSetReportRequestId()) {
                        echo("                    ReportRequestId\n");
                        echo("                        " . $reportInfo->getReportRequestId() . "\n");
                    }
                    if ($reportInfo->isSetAvailableDate()) {
                        echo("                    AvailableDate\n");
                        echo("                        " . $reportInfo->getAvailableDate()->format(DATE_FORMAT) . "\n");
                    }
                    if ($reportInfo->isSetAcknowledged()) {
                        echo("                    Acknowledged\n");
                        echo("                        " . $reportInfo->getAcknowledged() . "\n");
                    }
                    if ($reportInfo->isSetAcknowledgedDate()) {
                        echo("                    AcknowledgedDate\n");
                        echo("                        " . $reportInfo->getAcknowledgedDate()->format(DATE_FORMAT) . "\n");
                    }
                }
            }
            if ($response->isSetResponseMetadata()) {
                echo("            ResponseMetadata\n");
                $responseMetadata = $response->getResponseMetadata();
                if ($responseMetadata->isSetRequestId()) {
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

    private function invokeUpdateReportAcknowledgements($request)
    {
        try {
            $response = $this->oMWSService->updateReportAcknowledgements($request);

            echo("Service Response\n");
            echo("=============================================================================\n");

            echo("        UpdateReportAcknowledgementsResponse\n");
            if ($response->isSetUpdateReportAcknowledgementsResult()) {
                echo("            UpdateReportAcknowledgementsResult\n");
                $updateReportAcknowledgementsResult = $response->getUpdateReportAcknowledgementsResult();
                if ($updateReportAcknowledgementsResult->isSetCount()) {
                    echo("                Count\n");
                    echo("                    " . $updateReportAcknowledgementsResult->getCount() . "\n");
                }
                /*$reportInfoList = $updateReportAcknowledgementsResult->getReportInfo();
                foreach ($reportInfoList as $reportInfo) {
                    echo("                ReportInfo\n");
                    if ($reportInfo->isSetReportId()) {
                        echo("                    ReportId\n");
                        echo("                        " . $reportInfo->getReportId() . "\n");
                    }
                    if ($reportInfo->isSetReportType()) {
                        echo("                    ReportType\n");
                        echo("                        " . $reportInfo->getReportType() . "\n");
                    }
                    if ($reportInfo->isSetReportRequestId()) {
                        echo("                    ReportRequestId\n");
                        echo("                        " . $reportInfo->getReportRequestId() . "\n");
                    }
                    if ($reportInfo->isSetAvailableDate()) {
                        echo("                    AvailableDate\n");
                        echo("                        " . $reportInfo->getAvailableDate()->format(DATE_FORMAT) . "\n");
                    }
                    if ($reportInfo->isSetAcknowledged()) {
                        echo("                    Acknowledged\n");
                        echo("                        " . $reportInfo->getAcknowledged() . "\n");
                    }
                    if ($reportInfo->isSetAcknowledgedDate()) {
                        echo("                    AcknowledgedDate\n");
                        echo("                        " . $reportInfo->getAcknowledgedDate()->format(DATE_FORMAT) . "\n");
                    }
                }*/
            }
            if ($response->isSetResponseMetadata()) {
                echo("            ResponseMetadata\n");
                $responseMetadata = $response->getResponseMetadata();
                if ($responseMetadata->isSetRequestId()) {
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

    public function setTimeOut($iTimeOut){
        $this->sleepTimeOut = $iTimeOut;
        return $this;
    }

    public function doRequestReport()
    {
        if (!empty($this->error)) return $this;

        $this->aWaitLoopExitCondition = [];
        $request = new MarketplaceWebService_Model_RequestReportRequest();
        $request->setMarketplaceIdList($this->marketplaceIdArray);
        $request->setMerchant(MERCHANT_ID);
        $request->setReportType('_GET_FBA_ESTIMATED_FBA_FEES_TXT_DATA_');

        $s_date = new DateTime('-14 days', new DateTimeZone('UTC'));
        $start_date = $s_date->format("Y-m-d\T00:00:00P");

        $request->setStartDate(new DateTime($start_date, new DateTimeZone('UTC')));

        $this->dom_xml_arr = $this->invokeRequestReport($request);
        $log_text = 'RequestReport -> ReportRequestId:' . $this->dom_xml_arr['ReportRequestId'];
        func_backprocess_log("AmazonMWS", $log_text);
        return $this;
    }

    public function doGetReportRequestList()
    {
        if (!empty($this->error)) return $this;

        $this->aWaitLoopExitCondition = [['ReportProcessingStatus' => '_DONE_'], ['ReportProcessingStatus' => '_DONE_NO_DATA_'], ['ReportProcessingStatus' => '_CANCELLED_']];
        $reportRequestIdList = new MarketplaceWebService_Model_IdList();
        $reportRequestIdList->setId($this->dom_xml_arr['ReportRequestId']);

        $request = new MarketplaceWebService_Model_GetReportRequestListRequest();
        $request->setMerchant(MERCHANT_ID);
        $request->setReportRequestIdList($reportRequestIdList);

        $this->dom_xml_arr = $this->invokeGetReportRequestList($request);

        $log_text = 'GetReportRequestList -> ReportProcessingStatus:' . $this->dom_xml_arr['ReportProcessingStatus'];
        func_backprocess_log("AmazonMWS", $log_text);

        if ($this->dom_xml_arr['ReportProcessingStatus'] == '_CANCELLED_') {
            $this->error[] = 'RequestReport ' . $this->dom_xml_arr['ReportRequestId'] . ' is CANCELED by Amazon MWS';
        }
        if ($this->dom_xml_arr['ReportProcessingStatus'] == '_DONE_NO_DATA_') {
            $this->error[] = 'RequestReport ' . $this->dom_xml_arr['ReportRequestId'] . ' is DONE_NO_DATA';
        }
        return $this;
    }

    public function doGetReportList()
    {
        $this->aWaitLoopExitCondition = [];
        $req = new MarketplaceWebService_Model_TypeList();
        $req->withType('_GET_FBA_ESTIMATED_FBA_FEES_TXT_DATA_');

        $request = new MarketplaceWebService_Model_GetReportListRequest();
        $request->setMerchant(MERCHANT_ID);

        $request->setReportTypeList($req);
        $request->setMaxCount("100");
        $request->setAcknowledged(false);

        $this->dom_xml_arr = $this->invokeGetReportList($request);
        if (!empty($this->dom_xml_arr["ReportId"])) {
            $log_text = 'GetReportList -> ReportId:' . implode(',', $this->dom_xml_arr["ReportId"]);
        } else {
            $log_text = 'GetReportList -> No reports found';
        }
        func_backprocess_log("AmazonMWS", $log_text);

        $this->setReportId($this->dom_xml_arr["ReportId"]);
        return $this;
    }

    public function doGetReport()
    {
        $this->aWaitLoopExitCondition = [];

        if (!empty($this->aReportIds)) {
            if (is_array($this->aReportIds)) {
                $this->dom_xml_arr = [];
                foreach ($this->aReportIds as $reportId) {
                    $request = new MarketplaceWebService_Model_GetReportRequest();
                    $request->setMerchant(MERCHANT_ID);
                    $request->setReport(@fopen('php://memory', 'rw+'));
                    $request->setReportId($reportId);
                    $this->dom_xml_arr[] = $this->invokeGetReport($request);
                    $log_text = 'GetReport -> ReportId:' . $reportId;
                    func_backprocess_log("AmazonMWS", $log_text);
                }
            }
        }
        return $this;
    }

    public function doUpdateReportAcknowledgements()
    {
        $request = new MarketplaceWebService_Model_UpdateReportAcknowledgementsRequest();
        $request->setMerchant(MERCHANT_ID);

        foreach ($this->aReportIds as $iReportId) {
            $idList = new MarketplaceWebService_Model_IdList();

            $request->setReportIdList($idList->withId($iReportId));
            $request->setAcknowledged(true); //true

            $this->invokeUpdateReportAcknowledgements($request);

            $log_text = 'UpdateReportAcknowledgements -> ReportId:' . $iReportId;
            func_backprocess_log("AmazonMWS", $log_text);
        }

        return $this;
    }

    public function setReportId($aReportId)
    {
        $this->aReportIds = $aReportId;
        return $this;
    }

    private function checkLoopExitConditionStatus()
    {
        $res = false;
        if (!empty($this->aWaitLoopExitCondition)) {
            foreach ($this->aWaitLoopExitCondition as $key => $value) {
                if ($this->dom_xml_arr[key($value)] == $value[key($value)]) $res = true;
            }
        } else $res = true;
        return $res;
    }

    public function getReportContent()
    {
        $aResultArray = [];
        if (!empty($this->dom_xml_arr)) {
            if (is_array($this->dom_xml_arr)) {
                foreach ($this->dom_xml_arr as $arr) {
                    if (!empty($arr['Report_Contents']))
                        $aResultArray[] = $arr['Report_Contents'];
                }
            } else {
                if (!empty($this->dom_xml_arr['Report_Contents']))
                    $aResultArray[] = $this->dom_xml_arr['Report_Contents'];
            }
        }
        return $aResultArray;
    }

    private function fillReportFeeDataFromFile()
    {
        $this->aReportValue = [];
        $ReportContent = $this->getReportContent();
        if (!empty($ReportContent)) {

            $log_text = "Processing " . count($ReportContent) . " reports";
            func_backprocess_log("AmazonMWS", $log_text);

            foreach ($ReportContent as $report_data) {
                $cntLine = 0;
                $aReportValue = [];
                foreach (preg_split("/((\r?\n)|(\r\n?))/", $report_data) as $sLine) {
                    $arrM = explode("\t", $sLine);
                    if (!empty($arrM)) {
                        if ($cntLine == 0) {
                            foreach ($arrM as &$value)
                                $value = str_replace('-', '_', $value);
                        }
                        $aReportValue[] = $arrM;
                    }
                    $cntLine++;
                }
                $aReportData = [];
                $log_text = "Processing " . count($aReportValue) . " products";
                func_backprocess_log("AmazonMWS", $log_text);
                for ($y = 0; $y < count($aReportValue); $y++) {
                    foreach ($aReportValue[$y] as $iKey => $sItem) {
                        if ($y == 0) {
                            //$this->aReportValue[$y][$sItem] = '';
                        } else {
                            $aReportData[$y][$aReportValue[0][$iKey]] = $sItem;
                            if ($aReportValue[0][$iKey] == 'sku') {
                                $oClassProducts = new classProducts();
                                $iProductId = $oClassProducts->getProductIdBySKU($sItem);
                                if ($iProductId) {
                                    $aReportData[$y]['productid'] = (int)$iProductId;
                                }
                            }
                        }
                    }
                }
                $this->aReportValue[] = $aReportData;
            }
        }
    }

    public function processReportFeeData()
    {
        $this->fillReportFeeDataFromFile();

        $aFieldsToUpdate = ['productid', 'longest_side', 'median_side', 'shortest_side', 'length_and_girth', 'unit_of_dimension',
            'item_package_weight', 'unit_of_weight', 'product_size_tier', 'estimated_fee_total', 'estimated_referral_fee_per_unit', 'estimated_variable_closing_fee',
            'estimated_order_handling_fee_per_order', 'estimated_pick_pack_fee_per_unit', 'estimated_weight_handling_fee_per_unit', 'amazon_fee_preview_last_update_date'];
        $aFieldsToUpdate = array_flip($aFieldsToUpdate);
        foreach ($this->aReportValue as $aReport)
            foreach ($aReport as $aItem) {
                $aArrInsert = array_intersect_key($aItem, $aFieldsToUpdate);
                $aArrInsert['amazon_fee_preview_last_update_date'] = time();
                func_array2insert('products_amz_fields', $aArrInsert, true);
            }
        return $this;
    }

    public function _Request($_request)
    {

        $methodName = 'do' . $_request;
        $this->$methodName();

        while ((!empty($this->dom_xml_arr["Caught_Exception"]) && $this->dom_xml_arr["Caught_Exception"] == "Request is throttled" && $this->dom_xml_arr["Response_Status_Code"] == "503") || !$this->checkLoopExitConditionStatus()) {
            func_flush("sleeping...");
            func_flush();
            sleep($this->sleepTimeOut);
            func_flush("Unsleeped");
            func_flush();
            $this->$methodName();
            if (!empty($this->error)) return $this;
        }
        return $this;
    }
}