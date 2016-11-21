<?php
namespace Xcart;

use Xcart\OrderGroup;
use Xcart\Order;


define('DATE_FORMAT', 'Y-m-d\TH:i:s\Z');
define('AWS_ACCESS_KEY_ID', 'AKIAJFLBZ4Y7BVG5Q22A');
define('AWS_SECRET_ACCESS_KEY', '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');
define('APPLICATION_NAME', 's3stores');
define('APPLICATION_VERSION', '1');
define('MERCHANT_ID', 'A2SWKX6V1OVQ89');
define('MARKETPLACE_ID', 'ATVPDKIKX0DER');

class AmazonMWS
{
    const BACK_PROCESS_LOG_NAME = 'AmazonFeeReport';
    const BACK_PROCESS_LOG_NAME_SETTLEMENT = 'Amazon_Reports_Cron';
    const BACK_PROCESS_LOG_NAME_ORDERS = 'amazon_orders';
    const BACK_PROCESS_LOG_NAME_ORDER_INFO = 'amazon_info';
    const DEFAULT_ORDER_MESSAGE = 'Thank you for your order!';
    const AMAZON_ORDER_LINK = "https://sellercentral.amazon.com/gp/orders-v2/list/ref=ag_myo_apsearch_myosearch?searchType=OrderID&searchKeyword=%s&showPending=1&isDebug=&isAdvancedSearch=1&ignoreSearchType=0&searchLanguage=en_US";

    private $oMWSService;
    private $marketplaceIdArray;
    private $dom_xml_arr;
    private $aWaitLoopExitCondition = [];
    private $aReportValue = [];
    private $aReportIds = [];
    private $sleepTimeOut = 60;
    public $error = [];
    private $amazonReportType;
    private $sql_tbl;
    private $sBackProcessLogName = null;
    private $tStartDate = null;
    private $nextToken = 'start';
    private $oOrder = null;
    private $sServiceUrl = null;
    private $getOnlyAcknowledged = true;


    public function __construct($oServiceClass = 'MarketplaceWebService_Client', $uri = '')
    {
        global $sql_tbl;
        $this->sServiceUrl = "https://mws.amazonservices.com" . $uri;
        $a_config = array(
            'ServiceURL' => $this->sServiceUrl,
            'ProxyHost' => null,
            'ProxyPort' => -1,
            'ProxyUsername' => null,
            'ProxyPassword' => null,
            'MaxErrorRetry' => 3,
        );

        if ($oServiceClass == 'MarketplaceWebServiceOrders_Client') {
            $this->oMWSService = new $oServiceClass(
                AWS_ACCESS_KEY_ID,
                AWS_SECRET_ACCESS_KEY,
                APPLICATION_NAME,
                APPLICATION_VERSION,
                $a_config);
        } else
            $this->oMWSService = new $oServiceClass(
                AWS_ACCESS_KEY_ID,
                AWS_SECRET_ACCESS_KEY,
                $a_config,
                APPLICATION_NAME,
                APPLICATION_VERSION);

        $this->marketplaceIdArray = array("Id" => array('ATVPDKIKX0DER'));
        $this->amazonReportType = '_GET_FBA_ESTIMATED_FBA_FEES_TXT_DATA_';
        $this->sBackProcessLogName = self::BACK_PROCESS_LOG_NAME;

        $this->sql_tbl = $sql_tbl;
    }

    public function setBackProcessName($sName)
    {
        $this->sBackProcessLogName = $sName;
        return $this;
    }

    public function setStartDate($tDate)
    {
        $this->tStartDate = $tDate;
        return $this;
    }

    public function setProcessWithoutAcknowledgedFlag()
    {
        $this->getOnlyAcknowledged = false;
        return $this;
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
        } catch (\MarketplaceWebService_Exception $ex) {
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

        } catch (\MarketplaceWebService_Exception $ex) {

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

        } catch (\MarketplaceWebService_Exception $ex) {
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


        } catch (\MarketplaceWebService_Exception $ex) {
            echo("Caught Exception: " . $ex->getMessage() . "\n");
            echo("Response Status Code: " . $ex->getStatusCode() . "\n");
            echo("Error Code: " . $ex->getErrorCode() . "\n");
            echo("Error Type: " . $ex->getErrorType() . "\n");
            echo("Request ID: " . $ex->getRequestId() . "\n");
            echo("XML: " . $ex->getXML() . "\n");
            echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n");
        }
        return $response_arr;
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
        } catch (\MarketplaceWebService_Exception $ex) {
            echo("Caught Exception: " . $ex->getMessage() . "\n");
            echo("Response Status Code: " . $ex->getStatusCode() . "\n");
            echo("Error Code: " . $ex->getErrorCode() . "\n");
            echo("Error Type: " . $ex->getErrorType() . "\n");
            echo("Request ID: " . $ex->getRequestId() . "\n");
            echo("XML: " . $ex->getXML() . "\n");
            echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n");
        }
    }

    private function invokeGetOrder($request)
    {
        try {
            $response = $this->oMWSService->GetOrder($request);
            $dom = new \DOMDocument();
            $dom->loadXML($response->toXML());
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            return $dom->saveXML();
        } catch (\MarketplaceWebServiceOrders_Exception $ex) {
            $return_echo["function"] = "invokeGetOrder";
            $return_echo["Caught_Exception"] = $ex->getMessage();
            $return_echo["Response_Status_Code"] = $ex->getStatusCode();
            $return_echo["Error_Code"] = $ex->getErrorCode();
            $return_echo["Error_Type"] = $ex->getErrorType();
            $return_echo["Request_ID"] = $ex->getRequestId();
            $return_echo["XML"] = $ex->getXML();
            $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
            $return_echo["message"] = "Delay 2 minutes and trying the same Request";
            func_print_r($return_echo);
            $log_text = "...GetOrder throttling delay";
            func_backprocess_log("amazon_orders", $log_text);
            return $return_echo;
        }
    }

    private function invokeListOrders($request)
    {
        try {
            $response = $this->oMWSService->ListOrders($request);

            $dom = new \DOMDocument();
            $dom->loadXML($response->toXML());
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            return $dom->saveXML();

        } catch (\MarketplaceWebServiceOrders_Exception $ex) {
            $return_echo["function"] = "invokeListOrders";
            $return_echo["Caught_Exception"] = $ex->getMessage();
            $return_echo["Response_Status_Code"] = $ex->getStatusCode();
            $return_echo["Error_Code"] = $ex->getErrorCode();
            $return_echo["Error_Type"] = $ex->getErrorType();
            $return_echo["Request_ID"] = $ex->getRequestId();
            $return_echo["XML"] = $ex->getXML();
            $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
            $return_echo["message"] = "Delay 2 minutes and trying the same Request";
            func_print_r($return_echo);
            $log_text = "...ListOrders throttling delay";
            func_backprocess_log("amazon_orders", $log_text);

            return $return_echo;
        }
    }

    private function invokeListOrdersByNextToken($request)
    {
        try {
            $response = $this->oMWSService->ListOrdersByNextToken($request);

//        echo ("Service Response\n");
//        echo ("=============================================================================\n");

            $dom = new \DOMDocument();
            $dom->loadXML($response->toXML());
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            return $dom->saveXML();
//        echo $dom->saveXML();
//        echo ("ResponseHeaderMetadata: " . $response->getResponseHeaderMetadata() . "\n");

        } catch (\MarketplaceWebServiceOrders_Exception $ex) {
            $return_echo["function"] = "invokeListOrdersByNextToken";
            $return_echo["Caught_Exception"] = $ex->getMessage();
            $return_echo["Response_Status_Code"] = $ex->getStatusCode();
            $return_echo["Error_Code"] = $ex->getErrorCode();
            $return_echo["Error_Type"] = $ex->getErrorType();
            $return_echo["Request_ID"] = $ex->getRequestId();
            $return_echo["XML"] = $ex->getXML();
            $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
            $return_echo["message"] = "Delay 2 minutes and trying the same Request";
            func_print_r($return_echo);
            $log_text = "...ListOrdersByNextToken  throttling delay";
            func_backprocess_log("amazon_orders", $log_text);

            return $return_echo;
        }
    }

    private function invokeListOrderItems($request)
    {
        try {
            $response = $this->oMWSService->ListOrderItems($request);
            $dom = new \DOMDocument();
            $dom->loadXML($response->toXML());
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            return $dom->saveXML();

        } catch (\MarketplaceWebServiceOrders_Exception $ex) {
            $return_echo["function"] = "invokeListOrderItems";
            $return_echo["Caught_Exception"] = $ex->getMessage();
            $return_echo["Response_Status_Code"] = $ex->getStatusCode();
            $return_echo["Error_Code"] = $ex->getErrorCode();
            $return_echo["Error_Type"] = $ex->getErrorType();
            $return_echo["Request_ID"] = $ex->getRequestId();
            $return_echo["XML"] = $ex->getXML();
            $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
            $return_echo["message"] = "Delay 2 minutes and trying the same Request";
            func_print_r($return_echo);
            $log_text = "...ListOrderItems  throttling delay";
            func_backprocess_log("amazon_orders", $log_text);

            return $return_echo;
        }
    }

    public function setTimeOut($iTimeOut)
    {
        $this->sleepTimeOut = $iTimeOut;
        return $this;
    }

    public function doRequestReport()
    {
        if (!empty($this->error)) return $this;

        $this->aWaitLoopExitCondition = [];
        $request = new \MarketplaceWebService_Model_RequestReportRequest();
        $request->setMarketplaceIdList($this->marketplaceIdArray);
        $request->setMerchant(MERCHANT_ID);
        $request->setReportType($this->amazonReportType);

        if (!is_null($this->tStartDate))
            $request->setStartDate(new \DateTime($this->tStartDate->format("Y-m-d\T00:00:00P"), new \DateTimeZone('UTC')));

        $this->dom_xml_arr = $this->invokeRequestReport($request);

        if (!empty($this->dom_xml_arr['Caught_Exception'])) {
            $this->error[] = $this->dom_xml_arr["Caught_Exception"];
            $log_text = 'RequestReport -> Error:' . $this->dom_xml_arr["Caught_Exception"];
        } else {
            $log_text = 'RequestReport -> ReportRequestId:' . $this->dom_xml_arr['ReportRequestId'];
        }
        func_backprocess_log($this->sBackProcessLogName, $log_text);
        return $this;
    }

    public function doGetReportRequestList()
    {
        if (!empty($this->error)) return $this;

        $this->setTimeOut(45);

        if ($this->dom_xml_arr['ReportRequestId']) {
            $this->aWaitLoopExitCondition = [['ReportProcessingStatus' => '_DONE_'], ['ReportProcessingStatus' => '_DONE_NO_DATA_'], ['ReportProcessingStatus' => '_CANCELLED_']];

            $reportRequestIdList = new \MarketplaceWebService_Model_IdList();
            $reportRequestIdList->setId($this->dom_xml_arr['ReportRequestId']);

            $request = new \MarketplaceWebService_Model_GetReportRequestListRequest();
            $request->setMerchant(MERCHANT_ID);
            $request->setReportRequestIdList($reportRequestIdList);

            $this->dom_xml_arr = $this->invokeGetReportRequestList($request);

            $log_text = 'GetReportRequestList -> ReportProcessingStatus:' . $this->dom_xml_arr['ReportProcessingStatus'];
            func_backprocess_log($this->sBackProcessLogName, $log_text);

            if (!empty($this->dom_xml_arr['Caught_Exception'])) {
                $this->error[] = $this->dom_xml_arr["Caught_Exception"];
            }

            if ($this->dom_xml_arr['ReportProcessingStatus'] == '_CANCELLED_') {
                $this->error[] = 'RequestReport ' . $this->dom_xml_arr['ReportRequestId'] . ' is CANCELED by Amazon MWS';
            }
            if ($this->dom_xml_arr['ReportProcessingStatus'] == '_DONE_NO_DATA_') {
                $this->error[] = 'RequestReport ' . $this->dom_xml_arr['ReportRequestId'] . ' is DONE_NO_DATA';
            }
        }
        return $this;
    }

    public function doGetReportList()
    {
        $this->aWaitLoopExitCondition = [];

        $this->setTimeOut(60);

        $req = new \MarketplaceWebService_Model_TypeList();

        $req->withType($this->amazonReportType);

        $request = new \MarketplaceWebService_Model_GetReportListRequest();
        $request->setMerchant(MERCHANT_ID);

        $request->setReportTypeList($req);
        $request->setMaxCount("100");
        if ($this->getOnlyAcknowledged)
            $request->setAcknowledged(false);

        $this->dom_xml_arr = $this->invokeGetReportList($request);
        if (!empty($this->dom_xml_arr["ReportId"])) {
            $log_text = 'GetReportList -> ReportId:' . implode(',', $this->dom_xml_arr["ReportId"]);
        } else {
            $log_text = 'GetReportList -> No reports found';
        }
        func_backprocess_log($this->sBackProcessLogName, $log_text);

        $this->setReportId($this->dom_xml_arr["ReportId"]);
        return $this;
    }

    public function doGetReport()
    {
        $this->aWaitLoopExitCondition = [];

        $this->setTimeOut(60);

        if (!empty($this->aReportIds)) {
            if (is_array($this->aReportIds)) {
                $this->dom_xml_arr = [];
                foreach ($this->aReportIds as $reportId) {
                    $request = new \MarketplaceWebService_Model_GetReportRequest();
                    $request->setMerchant(MERCHANT_ID);
                    $request->setReport(@fopen('php://memory', 'rw+'));
                    $request->setReportId($reportId);
                    $this->dom_xml_arr[$reportId] = $this->invokeGetReport($request);
                    $log_text = 'GetReport -> ReportId:' . $reportId;
                    func_backprocess_log($this->sBackProcessLogName, $log_text);
                }
            }
        }
        return $this;
    }


    public function doUpdateReportAcknowledgements()
    {
        $this->setTimeOut(45);

        $request = new \MarketplaceWebService_Model_UpdateReportAcknowledgementsRequest();
        $request->setMerchant(MERCHANT_ID);

        if (!empty($this->aReportIds)) {
            foreach ($this->aReportIds as $iReportId) {
                $idList = new \MarketplaceWebService_Model_IdList();

                $request->setReportIdList($idList->withId($iReportId));
                $request->setAcknowledged(true); //true

                $this->invokeUpdateReportAcknowledgements($request);

                $log_text = 'UpdateReportAcknowledgements -> ReportId:' . $iReportId;
                func_backprocess_log($this->sBackProcessLogName, $log_text);
            }
        }

        return $this;
    }

    public function setOrder($oOrder)
    {
        $this->oOrder = $oOrder;
    }

    public function setReportId($aReportId)
    {
        $this->aReportIds = $aReportId;
        return $this;
    }

    public function setReportType($sReportType)
    {
        $this->amazonReportType = $sReportType;
        return $this;
    }

    private function checkLoopExitConditionStatus()
    {
        $res = false;
        if (!empty($this->aWaitLoopExitCondition)) {
            foreach ($this->aWaitLoopExitCondition as $key => $value) {
                if ($this->dom_xml_arr[key($value)] == $value[key($value)]) {
                    $res = true;
                    break;
                }
            }
        } else $res = true;
        return $res;
    }

    public function getReportContent()
    {
        $aResultArray = [];
        if (!empty($this->dom_xml_arr)) {
            if (is_array($this->dom_xml_arr)) {
                foreach ($this->dom_xml_arr as $reportId => $arr) {
                    if (!empty($arr['Report_Contents']))
                        $aResultArray[$reportId] = $arr['Report_Contents'];
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
            func_backprocess_log($this->sBackProcessLogName, $log_text);

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
                $log_text = "Processing " . ($cntLine - 2) . " products";
                func_backprocess_log($this->sBackProcessLogName, $log_text);
                for ($y = 0; $y < count($aReportValue); $y++) {
                    foreach ($aReportValue[$y] as $iKey => $sItem) {
                        if ($y == 0) {
                            //$this->aReportValue[$y][$sItem] = '';
                        } else {
                            $aReportData[$y][$aReportValue[0][$iKey]] = $sItem;
                            if ($aReportValue[0][$iKey] == 'sku') {
                                $oClassProducts = new Products();
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

        $aFieldsToUpdate = ['productid', 'fnsku', 'asin', 'longest_side', 'median_side', 'shortest_side', 'length_and_girth', 'unit_of_dimension',
            'item_package_weight', 'unit_of_weight', 'product_size_tier', 'estimated_fee_total', 'estimated_referral_fee_per_unit', 'estimated_variable_closing_fee',
            'estimated_order_handling_fee_per_order', 'estimated_pick_pack_fee_per_unit', 'estimated_weight_handling_fee_per_unit', 'amazon_fee_preview_last_update_date'];
        $aFieldsToUpdate = array_flip($aFieldsToUpdate);
        foreach ($this->aReportValue as $aReport)
            foreach ($aReport as $aItem) {
                $aArrInsert = array_intersect_key($aItem, $aFieldsToUpdate);
                $aArrInsert['amazon_fee_preview_last_update_date'] = time();
                if (!empty($aArrInsert['productid']))
                    func_array2insert('products_amz_fields', $aArrInsert, true);
            }
        return $this;
    }

    public function processReportSettlementData()
    {
        x_load('xml');
        $ReportContent = $this->getReportContent();
        if (!empty($ReportContent)) {

            $log_text = "Processing " . count($ReportContent) . " reports";
            func_backprocess_log(self::BACK_PROCESS_LOG_NAME_SETTLEMENT, $log_text);

            foreach ($ReportContent as $report_id => $report_data) {

                $aOrderDetails = [];

                $findme_arr = array("Order", "Refund", "Fee", "Component", "Item", "AdjustedItem");

                foreach ($findme_arr as $findme) {
                    $pos = strpos($report_data, "<$findme>");
                    if ($pos !== "false") {
                        $dom_xml_arr = explode("<$findme>", $report_data);
                        $count_dom_xml_arr = count($dom_xml_arr);
                        $report_data = "";
                        foreach ($dom_xml_arr as $k => $v) {
                            $k_n = $k - 1;
                            $v = str_replace("</$findme>", "</$findme$k_n>", $v);
                            $report_data .= $v . ($k != ($count_dom_xml_arr - 1) ? "<$findme$k>" : "");
                        }
                    }
                }
                $aXmlReportConent = func_xml2hash($report_data, "UTF-8");

                if (!empty($aXmlReportConent["AmazonEnvelope"]["Message"]["SettlementReport"]) && is_array($aXmlReportConent["AmazonEnvelope"]["Message"]["SettlementReport"])) {

                    foreach ($aXmlReportConent["AmazonEnvelope"]["Message"]["SettlementReport"] as $k => $v) {
                        $RefundSum = 0;

                        $k_name = '';

                        if (strpos($k, "Order") !== false) {
                            $k_name = "Item";
                        } elseif (strpos($k, "Refund") !== false) {
                            $k_name = "AdjustedItem";
                        }
                        if ($k_name == 'AdjustedItem' && !empty($v["AdjustmentID"])) $v["ShipmentID"] = $v["AdjustmentID"];
                        if (!empty($v["AmazonOrderID"]) && !empty($v["ShipmentID"])) {

                            if ($v['MarketplaceName'] == 'Non-Amazon') {
                                preg_match("/\w+-(\d+)[-]?(\d+)?/", $v['MerchantOrderID'], $aMatchArray); //AR-65345-12
                                if (!empty($aMatchArray)) {
                                    if (!empty($aMatchArray[1])) {
                                        $iOrderId = intval($aMatchArray[1]);
                                        $order_info = func_query_first("SELECT orderid FROM " . $this->sql_tbl['orders'] . " WHERE orderid=$iOrderId");
                                    }
                                }
                            } else
                                $order_info = func_query_first("SELECT orderid FROM " . $this->sql_tbl['orders'] . " WHERE amazonorderid='$v[AmazonOrderID]'");

                            if (!empty($order_info)) {

                                $log_text = "order processed: " . $v["AmazonOrderID"];

                                func_backprocess_log(self::BACK_PROCESS_LOG_NAME_SETTLEMENT, $log_text);

                                foreach ($v["Fulfillment"] as $kk => $vv) {

                                    if ($k_name == "Item") {
                                        if (!empty($vv["ItemFees"])) {
                                            $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['Quantity'] += $vv['Quantity'];
                                            $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['SKU'] = addslashes($vv['SKU']);
                                            $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['orderid'] = $order_info['orderid'];
                                            $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['type'] = 'Fee';

                                            foreach ($vv["ItemFees"] as $kkk => $vvv) {
                                                if (in_array($vvv["Type"], array("FBAPerOrderFulfillmentFee", "FBAPerUnitFulfillmentFee", "FBAWeightBasedFee", "Commission", "ShippingChargeback"))) {
                                                    $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']][$vvv["Type"]] += floatval($vvv["Amount"]);
                                                }
                                            }
                                        }

                                        if (!empty($vv["ItemPrice"])) {
                                            foreach ($vv["ItemPrice"] as $kkk => $vvv) {
                                                if (in_array($vvv["Type"], array("Principal", "Shipping"))) {
                                                    //$aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']][$vvv["Type"]] = floatVal($vvv["Amount"]);
                                                }
                                            }
                                        }

                                        if (!empty($vv["Promotion"])) {
                                            if (in_array($vv["Promotion"]["Type"], array("Shipping"))) {
                                                $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['Refund'] += abs(floatval($vv["Promotion"]["Amount"]));
                                            }

                                        }

                                    } elseif ($k_name == "AdjustedItem") {

                                        if (!empty($vv["ItemPriceAdjustments"]) && is_array($vv["ItemPriceAdjustments"])) {
                                            foreach ($vv["ItemPriceAdjustments"] as $kkk => $vvv) {
                                                $field_name = $vvv["Type"];
                                                if (($field_name == "Principal" || $field_name == "Shipping") && !empty($vvv["Amount"])) {
                                                    $RefundSum += $vvv["Amount"];
                                                    $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['Refund'] += floatval($vvv["Amount"]);
                                                    $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['SKU'] = addslashes($vv['SKU']);
                                                    $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['orderid'] = $order_info['orderid'];
                                                    $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['type'] = 'Refund';
                                                }
                                                switch ($field_name) {
                                                    case "Principal":
                                                        $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['PrincipalRefund'] = floatval($vvv["Amount"]);
                                                        break;
                                                    case "Shipping":
                                                        $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['ShippingRefund'] = floatval($vvv["Amount"]);
                                                        break;
                                                }
                                            }

                                        }

                                        if (!empty($vv["ItemFeeAdjustments"]) && is_array($vv["ItemFeeAdjustments"])) {
                                            foreach ($vv["ItemFeeAdjustments"] as $kkk => $vvv) {
                                                $field_name = $vvv["Type"];
                                                if (($field_name == "Commission" || $field_name == "RefundCommission" || $field_name == "VariableClosingFee" || $field_name == "ShippingChargeback") && !empty($vvv["Amount"])) {
                                                    $RefundSum += $vvv["Amount"];
                                                    $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['Refund'] += floatval($vvv["Amount"]);
                                                    $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['SKU'] = addslashes($vv['SKU']);
                                                    $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['orderid'] = $order_info['orderid'];
                                                    $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']]['type'] = 'Refund';
                                                }
                                            }
                                        }
                                    }
                                }
                                if (!empty($v["ShipmentFees"])) {
                                    foreach ($v["ShipmentFees"] as $kkk => $vvv) {
                                        if (in_array($vvv["Type"], array("FBATransportationFee"))) {
                                            $aOrderDetails[$v["AmazonOrderID"]][$v["ShipmentID"]][$vv['AmazonOrderItemCode']][$vvv["Type"]] += floatval($vvv["Amount"]);
                                        }
                                    }
                                }

                            }
                        }
                    }
                }


                if (!empty($aOrderDetails)) {
                    foreach ($aOrderDetails as $sAmazonOrderId => $aShippings) {
                        foreach ($aShippings as $sShippingId => $aAmazonCodes)
                            foreach ($aAmazonCodes as $sAmazonCode => $aFees) {
                                $aOrderDetailData = [];
                                $aOrderDetailData['FBAPerOrderFulfillmentFee'] = floatval($aFees['FBAPerOrderFulfillmentFee']);
                                $aOrderDetailData['FBAPerUnitFulfillmentFee'] = floatval($aFees['FBAPerUnitFulfillmentFee']);
                                $aOrderDetailData['FBATransportationFee'] = floatval($aFees['FBATransportationFee']);
                                $aOrderDetailData['FBAWeightBasedFee'] = floatval($aFees['FBAWeightBasedFee']);
                                $aOrderDetailData['ShippingFee'] = floatval($aFees['ShippingChargeback']);
                                $aOrderDetailData['AmazonCommission'] = floatval($aFees['Commission']);
                                $aOrderDetailData['Principal'] = floatval($aFees['Principal']);
                                $aOrderDetailData['PrincipalRefund'] = floatval($aFees['PrincipalRefund']);
                                $aOrderDetailData['Shipping'] = floatval($aFees['Shipping']);
                                $aOrderDetailData['ShippingRefund'] = floatval($aFees['ShippingRefund']);
                                $aOrderDetailData['Quantity'] = intval($aFees['Quantity']);
                                $aOrderDetailData['type'] = $aFees['type'];
                                $aOrderDetailData['SKU'] = $aFees['SKU'];
                                $aOrderDetailData['AmazonShipmentID'] = $sShippingId;
                                $aOrderDetailData['AmazonOrderItemCode'] = $sAmazonCode;
                                $aOrderDetailData['Refund'] = floatval(abs($aFees['Refund']));
                                $aOrderDetailData['reportId'] = $report_id;
                                $iOrderId = $aOrderDetailData['orderid'] = intval($aFees['orderid']);
                                $oProducts = new Products();
                                $aProduct = $oProducts->getProductBySKU($aOrderDetailData['SKU']);
                                if (!empty($aProduct))
                                    $aOrderDetailData['manufacturerid'] = $aProduct['manufacturerid'];


                                //if ($aOrderDetailData['Quantity'] == 0) $aOrderDetailData['Quantity'] = 1;

                                if (!empty($aOrderDetailData)) {
                                    func_array2insert('order_amazon_details', $aOrderDetailData, true);
                                    $aUpdateValues = func_query_first("SELECT SUM(FBAPerOrderFulfillmentFee) AS FBAPerOrderFulfillmentFee,
                                                     SUM(FBAPerUnitFulfillmentFee) AS FBAPerUnitFulfillmentFee,
                                                     SUM(FBATransportationFee) AS FBATransportationFee,
                                                     SUM(FBAWeightBasedFee) AS FBAWeightBasedFee,
                                                     SUM(AmazonCommission) AS AmazonCommission,
                                                     SUM(ShippingFee) AS ShippingFee,
                                                     SUM(Refund) AS Refund,
                                                     SUM(Shipping) AS Shipping,
                                                     SUM(ShippingRefund) AS ShippingRefund,
                                                     SUM(PrincipalRefund) AS PrincipalRefund,
                                                     count(1) as Rows
                                                     FROM " . $this->sql_tbl['order_amazon_details'] . " WHERE orderid = $iOrderId AND SKU = '$aFees[SKU]'");
                                    if ($aUpdateValues['Rows'] > 0) {
                                        if ($aUpdateValues['Refund'] != 0) {
                                            $aUpdateValues['amazon_item_refunded'] = 'Y';
                                        }
                                        unset ($aUpdateValues['Refund']);
                                        unset($aUpdateValues['Rows']);
                                        unset($aUpdateValues['PrincipalRefund']);
                                        unset($aUpdateValues['Shipping']);
                                        unset($aUpdateValues['FBATransportationFee']);
                                        unset($aUpdateValues['ShippingRefund']);
                                        func_array2update('order_details', $aUpdateValues, "orderid = $iOrderId AND productcode='$aFees[SKU]'");


                                        if (!empty($aProduct)) {
                                            $oOrderGroup = new OrderGroup(['orderid' => $iOrderId, 'manufacturerid' => $aProduct['manufacturerid']]);
                                            $oOrderGroup->recalculateAccounting();
                                        }
                                    }
                                }
                            }
                    }
                }
            }
        }
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


    /**
     * @param OrderGroup $oOrderGroup
     * @param string $sAmazonShippingMethodSelect
     * @return bool
     */
    public function shipOrderGroupByAmazon(OrderGroup $oOrderGroup, $sAmazonShippingMethodSelect)
    {
        global $login;
        $oOrder = $oOrderGroup->getOrderInstance();
        $log = "Try to place order shipping by Amazon\n";

        if ($oOrder->getOrderGroupsCount() == 1 && $oOrderGroup->getOrderGroupStatusCB() == 'AP') {
            if (!$oOrder->captureOrderAmount()) {
                func_log_order($oOrderGroup->getOrderId(), 'X', nl2br($log), $login);
                return false;
            }
        }
        $oOrderGroup->_refresh();
        if ($oOrderGroup->getOrderGroupStatusCB() != 'P') {
            $log .= "Shipping order by Amazon - failed. Order group status not Paid.\n";
            func_log_order($oOrderGroup->getOrderId(), 'X', nl2br($log), $login);
            return false;
        }

        $address = new \FBAOutboundServiceMWS_Model_Address();

        $address->setName($oOrder->getClientShippingName());
        $address->setLine1($oOrder->getField('s_address'));
        $address->setCity($oOrder->getField('s_city'));
        $address->setStateOrProvinceCode($oOrder->getField('s_state'));
        $address->setCountryCode($oOrder->getField('s_country'));
        $address->setPostalCode($oOrder->getField('s_zipcode'));
        $sPhone = $oOrder->getField('phone');
        if (!empty($sPhone))
            $address->setPhoneNumber($sPhone);

        $aProducts = $oOrderGroup->getOrderGroupProducts();
        if (!empty($aProducts)) {
            $list = new \FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItemList();

            foreach ($aProducts as $oProduct) {
                $iAmount = 0;
                $item = new \FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItem();

                $aOrderDetails = OrderDetail::getOrderDetailsByOrderIdAndProductId($oOrderGroup->getOrderId(), $oProduct->getProductId());
                foreach ($aOrderDetails as $oOrderDetail) {
                    $iAmount += $oOrderDetail->getAmount();
                }
                $aProductsQty = $oProduct->getProductsAvailOnAmazonParentWithChild($iAmount);
                if (!empty($aProductsQty)) {
                    foreach ($aProductsQty as $aFBAAvail) {
                        $item->setSellerSKU($aFBAAvail['oProduct']->getSKU());
                        $item->setSellerFulfillmentOrderItemId($aFBAAvail['oProduct']->getSKU());
                        $item->setQuantity($aFBAAvail['qty']);
                    }
                } else {
                    $item->setSellerSKU($oProduct->getSKU());
                    $item->setSellerFulfillmentOrderItemId($oProduct->getSKU());
                    $item->setQuantity($iAmount);
                }
                $list->withmember($item);
            }

            $req = new \FBAOutboundServiceMWS_Model_CreateFulfillmentOrderRequest();
            $req->setSellerId(MERCHANT_ID);
            $req->setSellerFulfillmentOrderId($oOrderGroup->getAmazonShippingOrderId());
            $req->setDisplayableOrderId($oOrderGroup->getAmazonShippingOrderId());
            $req->setFulfillmentAction('Ship');
            $req->setFulfillmentPolicy('FillOrKill');

            $req->setDisplayableOrderDateTime($oOrder->getOrderDate(\DateTime::ISO8601));
            $sDisplayAmazonOrderComment = self::DEFAULT_ORDER_MESSAGE;
            $sAmazonShippingNotes = $oOrderGroup->getAmazonShipmentNotes();
            if (!empty($sAmazonShippingNotes))
                $sDisplayAmazonOrderComment = $sAmazonShippingNotes;
            $sDisplayAmazonOrderComment = stripslashes($sDisplayAmazonOrderComment);

            $req->setDisplayableOrderComment($sDisplayAmazonOrderComment);
            $req->setShippingSpeedCategory($sAmazonShippingMethodSelect);
            $req->setDestinationAddress($address);

            $req->setItems($list);

            try {
                $response = $this->oMWSService->CreateFulfillmentOrder($req);

                $log .= "Amazon shipping order placed: <a href='" . sprintf(self::AMAZON_ORDER_LINK, $oOrderGroup->getAmazonShippingOrderId()) . "' target='_blank'>" . $oOrderGroup->getAmazonShippingOrderId() . "</a> \n";
                $log .= "DisplayableOrderComment: " . $sDisplayAmazonOrderComment . "\n";
                $log .= "ShippingSpeedCategory: " . $sAmazonShippingMethodSelect . "\n";
                $oOrderGroup->updateField('amz_fullfilment_order_placed', 'Y');
                $oOrderGroup->changeOrderGroupStatusDC('L');


            } catch (\FBAOutboundServiceMWS_Exception $ex) {
                $log .= "Caught Exception: " . $ex->getMessage() . "\n";
                $log .= "Response Status Code: " . $ex->getStatusCode() . "\n";
                $log .= "Error Code: " . $ex->getErrorCode() . "\n";
                $log .= "Error Type: " . $ex->getErrorType() . "\n";
                $log .= "Request ID: " . $ex->getRequestId() . "\n";
                $log .= "XML: " . $ex->getXML() . "\n";
                $log .= "ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n";
            }

            func_log_order($oOrderGroup->getOrderId(), 'X', nl2br($log), $login);
        }
        return $this;
    }

    public function processReportReservedInventory()
    {
        $this->aReportValue = [];
        $ReportContent = $this->getReportContent();
        if (!empty($ReportContent)) {
            foreach ($ReportContent as $report_id => $report_data) {
                $cntLine = 0;
                $aReportValue = [];
                foreach (preg_split("/((\r?\n)|(\r\n?))/", $report_data) as $sLine) {
                    echo $sLine . "<br/>";
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
                $log_text = "Processing Reserved Inventory Report for " . ($cntLine - 2) . " products";
                func_backprocess_log($this->sBackProcessLogName, $log_text);
                for ($y = 0; $y < count($aReportValue); $y++) {
                    foreach ($aReportValue[$y] as $iKey => $sItem) {

                        if ($y == 0) {

                        } else {
                            if ($aReportValue[0][$iKey] == 'sku') {
                                $iProductId = Product::getProductBySKU($sItem)->getProductId();
                            }
                            $aReportData[$iProductId][$aReportValue[0][$iKey]] = $sItem;
                        }
                    }
                }
                $this->aReportValue[] = $aReportData;
            }

            $report_date = mktime(0, 0, 0, date("n"), date("j"), date("Y"));

            foreach ($this->aReportValue as $aReport)
                foreach ($aReport as $iProductId => $aItem) {
                    /** @var CidevAmazonFbaProducts $oFbaProduct */
                    if (!empty($aItem['sku']))
                        $oFbaProduct = CidevAmazonFbaProducts::model()->find(SQLBuilder::getInstance()->addCondition("productid = '" . $iProductId . "'")->addCondition("report_date = $report_date"));
                    else $oFbaProduct = CidevAmazonFbaProducts::model();

                    $oFbaProduct->setField('reserved_qty', $aItem['reserved_qty']);
                    $oFbaProduct->setField('reserved_customerorders', $aItem['reserved_customerorders']);
                    $oFbaProduct->setField('reserved_fc_transfers', $aItem['reserved_fc_transfers']);
                    $oFbaProduct->setField('reserved_fc_processing', $aItem['reserved_fc_processing']);
                    $oFbaProduct->setField('productid', $iProductId);
                    $oFbaProduct->setField('productcode', $aItem['sku']);
                    $oFbaProduct->setField('ASIN', $aItem['asin']);
                    $oFbaProduct->setField('report_date', $report_date);
                    $oFbaProduct->_save();
                }
        }
        return $this;
    }

    public function groupAmazonFBAProducts()
    {

        global $config;
        /** @var CidevAmazonFbaProducts[][] $aAggregateRows */
        /** @var CidevAmazonFbaProducts[] $aFbaProducts */

        for ($i = 24; $i >= $config['Amazon_FBA_options']['Amazon_FBA_Month']; $i--) {
            $aAggregateRows = $aAggregateStat = $aFbaProducts = null;
            $currentDate = new \DateTime(date('Y-m-d', strtotime("first day of this month")));
            $currentDate->sub(new \DateInterval('P' . $i . 'M'));

            $aFbaProducts = CidevAmazonFbaProducts::model()->findAll(SQLBuilder::getInstance()->addCondition("report_date < " . $currentDate->getTimestamp())->addCondition("precise_data='Y'"));
            if ($aFbaProducts) {
                $log_text = 'Aggregate FBA products data for %s products. Period %s';
                func_backprocess_log($this->sBackProcessLogName, sprintf($log_text, count($aFbaProducts), $currentDate->format('d-M-Y')));
                foreach ($aFbaProducts as $oFbaProduct) {
                    $iReportTimeStamp = $oFbaProduct->getReportPeriod()->getTimeStamp();
                    $iProductId = $oFbaProduct->getField('productid');
                    if (!isset($aAggregateRows[$iProductId][$iReportTimeStamp]))
                        $aAggregateRows[$iProductId][$iReportTimeStamp] = CidevAmazonFbaProducts::model();
                    $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('productid', $iProductId);
                    $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('productcode', $oFbaProduct->getField('productcode'));
                    $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('ASIN', $oFbaProduct->getField('ASIN'));
                    if ($oFbaProduct->getField('cpr_LandedPrice') > 0) {
                        $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('cpr_LandedPrice', floatval($aAggregateRows[$iProductId][$iReportTimeStamp]->getField('cpr_LandedPrice')) + floatval($oFbaProduct->getField('cpr_LandedPrice')));
                        $aAggregateStat[$iProductId][$iReportTimeStamp]['cpr_LandedPrice']++;
                    }
                    if ($oFbaProduct->getField('cpr_belongs_LandedPrice') > 0) {
                        $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('cpr_belongs_LandedPrice', floatval($aAggregateRows[$iProductId][$iReportTimeStamp]->getField('cpr_belongs_LandedPrice')) + floatval($oFbaProduct->getField('cpr_belongs_LandedPrice')));
                        $aAggregateStat[$iProductId][$iReportTimeStamp]['cpr_belongs_LandedPrice']++;
                    }
                    $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('cpr_SalesRank', intval($aAggregateRows[$iProductId][$iReportTimeStamp]->getField('cpr_SalesRank')) + intval($oFbaProduct->getField('cpr_SalesRank')));
                    $aAggregateStat[$iProductId][$iReportTimeStamp]['cpr_SalesRank']++;

                    $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('lis_TotalSupplyQuantity', intval($aAggregateRows[$iProductId][$iReportTimeStamp]->getField('lis_TotalSupplyQuantity')) + intval($oFbaProduct->getField('lis_TotalSupplyQuantity')));
                    $aAggregateStat[$iProductId][$iReportTimeStamp]['lis_TotalSupplyQuantity']++;

                    $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('lis_InStockSupplyQuantity', intval($aAggregateRows[$iProductId][$iReportTimeStamp]->getField('lis_InStockSupplyQuantity')) + intval($oFbaProduct->getField('lis_InStockSupplyQuantity')));
                    $aAggregateStat[$iProductId][$iReportTimeStamp]['lis_InStockSupplyQuantity']++;

                    $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('lp_LandedPrice', floatval($aAggregateRows[$iProductId][$iReportTimeStamp]->getField('lp_LandedPrice')) + floatval($oFbaProduct->getField('lp_LandedPrice')));
                    $aAggregateStat[$iProductId][$iReportTimeStamp]['lp_LandedPrice']++;

                    if ($aAggregateRows[$iProductId][$iReportTimeStamp]->getField('lp_MultipleOfferListingsAtLowestPrice') != 'Y') {
                        if ($oFbaProduct->getField('lp_MultipleOfferListingsAtLowestPrice') == '') {
                            $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('lp_MultipleOfferListingsAtLowestPrice', 'N');
                        } else
                            $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('lp_MultipleOfferListingsAtLowestPrice', $oFbaProduct->getField('lp_MultipleOfferListingsAtLowestPrice'));
                    }
                    $aAggregateStat[$iProductId][$iReportTimeStamp]['lp_MultipleOfferListingsAtLowestPrice']++;

                    if ($aAggregateRows[$iProductId][$iReportTimeStamp]->getField('lp_AllOfferListingsConsidered') != 'N') {
                        if ($oFbaProduct->getField('lp_AllOfferListingsConsidered') == '') $oFbaProduct->setField('lp_AllOfferListingsConsidered', 'N');
                        $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('lp_AllOfferListingsConsidered', $oFbaProduct->getField('lp_AllOfferListingsConsidered'));
                    }
                    $aAggregateStat[$iProductId][$iReportTimeStamp]['lp_AllOfferListingsConsidered']++;

                    $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('lp_NumberOfOfferListingsConsidered', max(intval($aAggregateRows[$iProductId][$iReportTimeStamp]->getField('lp_NumberOfOfferListingsConsidered')), $oFbaProduct->getField('lp_NumberOfOfferListingsConsidered')));
                    $aAggregateStat[$iProductId][$iReportTimeStamp]['lp_NumberOfOfferListingsConsidered']++;

                    $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('lp_SellerFeedbackCount', $aAggregateRows[$iProductId][$iReportTimeStamp]->getField('lp_SellerFeedbackCount') + $oFbaProduct->getField('lp_SellerFeedbackCount'));
                    $aAggregateStat[$iProductId][$iReportTimeStamp]['lp_SellerFeedbackCount']++;


                    if ($aAggregateRows[$iProductId][$iReportTimeStamp]->getField('lp_FulfillmentChannel') != 'AFN') {
                        if ($oFbaProduct->getField('lp_FulfillmentChannel') == '') $oFbaProduct->setField('lp_FulfillmentChannel', 'AFN');
                        $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('lp_FulfillmentChannel', $oFbaProduct->getField('lp_FulfillmentChannel'));
                    }
                    $aAggregateStat[$iProductId][$iReportTimeStamp]['lp_FulfillmentChannel']++;


                    if (intval($oFbaProduct->getField('lp_SellerPositiveFeedbackRating')) > intval($aAggregateRows[$iProductId][$iReportTimeStamp]->getField('lp_SellerPositiveFeedbackRating')))
                        $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('lp_SellerPositiveFeedbackRating', $oFbaProduct->getField('lp_SellerPositiveFeedbackRating'));
                    $aAggregateStat[$iProductId][$iReportTimeStamp]['lp_SellerPositiveFeedbackRating']++;

                    if ($oFbaProduct->getField('lp_ShippingTime') != '') {
                        if ($aAggregateRows[$iProductId][$iReportTimeStamp]->getField('lp_ShippingTime') == '') $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('lp_ShippingTime', $oFbaProduct->getField('lp_ShippingTime'));
                        if (intval($oFbaProduct->getField('lp_ShippingTime')) <= intval($aAggregateRows[$iProductId][$iReportTimeStamp]->getField('lp_ShippingTime')))
                            $aAggregateRows[$iProductId][$iReportTimeStamp]->setField('lp_ShippingTime', $oFbaProduct->getField('lp_ShippingTime'));
                    }
                    $aAggregateStat[$iProductId][$iReportTimeStamp]['lp_ShippingTime']++;
                }
            }

            if (!empty($aAggregateRows)) {
                foreach ($aAggregateRows as $iProductId => $aAggregateRow) {
                    foreach ($aAggregateRow as $iPeriod => $oAggregateRow) {
                        if ($aAggregateStat[$iProductId][$iPeriod]['cpr_LandedPrice'])
                            $oAggregateRow->setField('cpr_LandedPrice', round($oAggregateRow->getField('cpr_LandedPrice') / $aAggregateStat[$iProductId][$iPeriod]['cpr_LandedPrice'], 2));
                        if ($aAggregateStat[$iProductId][$iPeriod]['cpr_belongs_LandedPrice'])
                            $oAggregateRow->setField('cpr_belongs_LandedPrice', round($oAggregateRow->getField('cpr_belongs_LandedPrice') / $aAggregateStat[$iProductId][$iPeriod]['cpr_belongs_LandedPrice'], 2));
                        $oAggregateRow->setField('cpr_SalesRank', round($oAggregateRow->getField('cpr_SalesRank') / $aAggregateStat[$iProductId][$iPeriod]['cpr_SalesRank']));
                        if ($aAggregateStat[$iProductId][$iPeriod]['lis_TotalSupplyQuantity'])
                            $oAggregateRow->setField('lis_TotalSupplyQuantity', round($oAggregateRow->getField('lis_TotalSupplyQuantity') / $aAggregateStat[$iProductId][$iPeriod]['lis_TotalSupplyQuantity']));
                        if ($aAggregateStat[$iProductId][$iPeriod]['lis_InStockSupplyQuantity'])
                            $oAggregateRow->setField('lis_InStockSupplyQuantity', round($oAggregateRow->getField('lis_InStockSupplyQuantity') / $aAggregateStat[$iProductId][$iPeriod]['lis_InStockSupplyQuantity']));
                        if ($aAggregateStat[$iProductId][$iPeriod]['lp_LandedPrice'])
                            $oAggregateRow->setField('lp_LandedPrice', round($oAggregateRow->getField('lp_LandedPrice') / $aAggregateStat[$iProductId][$iPeriod]['lp_LandedPrice'], 2));
                        if ($aAggregateStat[$iProductId][$iPeriod]['lp_SellerFeedbackCount'])
                            $oAggregateRow->setField('lp_SellerFeedbackCount', round($oAggregateRow->getField('lp_SellerFeedbackCount') / $aAggregateStat[$iProductId][$iPeriod]['lp_SellerFeedbackCount']));
                        $oAggregateRow->setField('precise_data', 'N');
                        $oAggregateRow->setField('productid', $iProductId);
                        $oAggregateRow->setField('report_date', $iPeriod);
                        $oAggregateRow->_save(true);
                    }
                }
            }

            if ($aFbaProducts) {
                foreach ($aFbaProducts as $oFbaProduct) {
                    $oFbaProduct->_delete();
                }
            }

        }
    }

    private function doOrderListRequest()
    {
        $timeoffset = 24 * 60 * 30 * 300;
        while (!empty($this->nextToken)) {
            if ($this->nextToken == 'start') {
                $request = new \MarketplaceWebServiceOrders_Model_ListOrdersRequest();
                $request->setSellerId(MERCHANT_ID);
                $request->setMarketplaceId(MARKETPLACE_ID);
                $request->setCreatedAfter(gmdate('Y-m-d\TH:i:s\Z', time() - $timeoffset));
                $request->setOrderStatus(['Shipped', 'Unshipped', 'PartiallyShipped', 'Canceled']);
                $this->dom_xml_arr = $this->invokeListOrders($request);
            } else {
                $request = new \MarketplaceWebServiceOrders_Model_ListOrdersByNextTokenRequest();
                $request->setNextToken($this->nextToken);
                $request->setSellerId(MERCHANT_ID);
                $this->dom_xml_arr = $this->invokeListOrdersByNextToken($request);
            }
            if (!empty($this->dom_xml_arr["Caught_Exception"]) && $this->dom_xml_arr["Caught_Exception"] == "Request is throttled" && $this->dom_xml_arr["Response_Status_Code"] == "503") {
                return $this;
            }
            $this->processOrderList();
        }
        return $this;
    }

    private function doOrderRequest()
    {
        if (!is_null($this->oOrder)) {
            $request = new \MarketplaceWebServiceOrders_Model_GetOrderRequest();
            $request->setSellerId(MERCHANT_ID);
            $request->setAmazonOrderId($this->oOrder->getField('amazonorderid'));
            // object or array of parameters
            $this->dom_xml_arr = $this->invokeGetOrder($request);
        }
        return $this;
    }

    private function doOrderInfoRequest()
    {
        if (!is_null($this->oOrder)) {
            $request = new \MarketplaceWebServiceOrders_Model_ListOrderItemsRequest();
            $request->setSellerId(MERCHANT_ID);
            $request->setAmazonOrderId($this->oOrder->getField('amazonorderid'));
            // object or array of parameters
            $this->dom_xml_arr = $this->invokeListOrderItems($request);
        }
        return $this;
    }

    private function processOrderList()
    {
        if (!empty($this->dom_xml_arr)) {
            $docOrders = new \DOMDocument;
            $this->dom_xml_arr = str_replace($this->sServiceUrl, '', $this->dom_xml_arr);
            $docOrders->loadXML($this->dom_xml_arr);
            $xpath = new \DOMXPath($docOrders);
            $aOrdersEntries = $xpath->query('/*/*/Orders/Order');
            if (!empty($aOrdersEntries) && $aOrdersEntries->length > 0) {
                foreach ($aOrdersEntries as $k => $orderNode) {
                    $sAmazonOrderId = $orderNode->getElementsByTagName('AmazonOrderId')->item(0)->nodeValue;
                    $sOrderStatus = $orderNode->getElementsByTagName('OrderStatus')->item(0)->nodeValue;
                    $oOrder = Order::model()->find(SQLBuilder::getInstance()->addCondition("amazonorderid='" . $sAmazonOrderId . "'"));
                    if (!$oOrder->getOrderId()) {
                        if (in_array($sOrderStatus, ['Unshipped', 'Shipped'])) {
                            $oOrder->setField('amazonorderid', $sAmazonOrderId);
                            $this->oOrder = $oOrder;
                            $this->_Request('OrderRequest');

                            $docOrders = new \DOMDocument;
                            $this->dom_xml_arr = str_replace($this->sServiceUrl, '', $this->dom_xml_arr);
                            $docOrders->loadXML($this->dom_xml_arr);
                            $xpath2 = new \DOMXPath($docOrders);

                            $aOrderInfo = $xpath2->query('/GetOrderResponse/GetOrderResult/Orders/Order')->item(0);


                            print("ORDER INFO: \r\n");
                            func_print_r($aOrderInfo->nodeValue);
                            $log_text = "Processing order: " . $oOrder->getField('amazonorderid') . "  status: " . $sOrderStatus;
                            print($log_text . "\r\n");


                            func_backprocess_log(self::BACK_PROCESS_LOG_NAME_ORDERS, $log_text);

                            $this->_Request('OrderInfoRequest');

                            $docOrders = new \DOMDocument;
                            $this->dom_xml_arr = str_replace($this->sServiceUrl, '', $this->dom_xml_arr);
                            $docOrders->loadXML($this->dom_xml_arr);
                            $xpath3 = new \DOMXPath($docOrders);
                            $aOrderItems = $xpath3->query('/ListOrderItemsResponse/ListOrderItemsResult/OrderItems/OrderItem');

                            if (!empty($aOrderItems) && $aOrderItems->length > 0) {
                                $sAddress = $sShippingAddressCity = $sShippingAddressStateOrRegion = $sShippingAddressCountryCode = $sShippingAddressPostalCode = $sShippingAddressPhone = null;
                                $oOrder->setField('orderid', $oOrder->_insert());
                                $oOrderRaw = CidevAmazonOrderRaw::model()->find(SQLBuilder::getInstance()->addCondition('orderid = ' . $oOrder->getOrderId()));
                                $oOrderRaw->setField('orderid', $oOrder->getOrderId())->
                                setField('order_info', addslashes($xpath2->document->saveXML()))->
                                setField('orderitems_info', addslashes($xpath3->document->saveXML()));
                                $oOrderRaw->_insert(true);

                                $sOrderTotal = $aOrderInfo->getElementsByTagName('OrderTotal')->item(0)->getElementsByTagName('Amount')->item(0)->nodeValue;
                                $oShippingAddress = $aOrderInfo->getElementsByTagName('ShippingAddress')->item(0);
                                if (!empty($oShippingAddress)) {
                                    $sShippingAddressName = addslashes($oShippingAddress->getElementsByTagName('Name')->item(0)->nodeValue);
                                    $sShippingAddressCity = addslashes($oShippingAddress->getElementsByTagName('City')->item(0)->nodeValue);
                                    $sShippingAddressCountryCode = addslashes($oShippingAddress->getElementsByTagName('CountryCode')->item(0)->nodeValue);
                                    $sShippingAddressPhone = addslashes($oShippingAddress->getElementsByTagName('Phone')->item(0)->nodeValue);
                                    $sShippingAddressPostalCode = addslashes($oShippingAddress->getElementsByTagName('PostalCode')->item(0)->nodeValue);
                                    $sShippingAddressStateOrRegion = addslashes($oShippingAddress->getElementsByTagName('StateOrRegion')->item(0)->nodeValue);
                                    $StateOrRegion_code = State::model()->find(SQLBuilder::getInstance()->addCondition("country_code = '$sShippingAddressCountryCode'")->addCondition("state = '$sShippingAddressStateOrRegion'"))->getField('code');
                                    if (!empty($StateOrRegion_code)) {
                                        $sShippingAddressStateOrRegion = addslashes($StateOrRegion_code);
                                    }
                                    $sAddress = addslashes($oShippingAddress->getElementsByTagName('AddressLine1')->item(0)->nodeValue .
                                        (!empty($oShippingAddress->getElementsByTagName('AddressLine2')->item(0)->nodeValue) ? ' ' . $oShippingAddress->getElementsByTagName('AddressLine2')->item(0)->nodeValue : '') .
                                        (!empty($oShippingAddress->getElementsByTagName('AddressLine3')->item(0)->nodeValue) ? ' ' . $oShippingAddress->getElementsByTagName('AddressLine3')->item(0)->nodeValue : ''));
                                }

                                $sFulfilmentChanel = $aOrderInfo->getElementsByTagName('FulfillmentChannel')->item(0)->nodeValue;

                                $sBuyerName = addslashes($aOrderInfo->getElementsByTagName('BuyerName')->item(0)->nodeValue);


                                $oOrder->
                                setField('order_prefix', 'AZ-')->
                                setField('login', 'amazon')->
                                setField('amazon_fulfillment_channel', $sFulfilmentChanel)->
                                setField('total', $sOrderTotal)->
                                setField('subtotal', $sOrderTotal)->
                                setField('date', strtotime($aOrderInfo->getElementsByTagName('PurchaseDate')->item(0)->nodeValue))->
                                setField('cb_status', ($sOrderStatus == 'Canceled' ? 'A' : 'P'))->
                                setField('dc_status', ($sOrderStatus == 'Unshipped' ? 'T' : 'S'))->
                                setField('bd_status', 'W')->
                                setField('payment_method', 'Amazon Seller')->
                                setField('firstname', $sBuyerName)->
                                setField('s_firstname', (empty($sShippingAddressName) ? $sBuyerName : $sShippingAddressName))->
                                setField('s_address', $sAddress)->
                                setField('s_city', $sShippingAddressCity)->
                                setField('s_state', $sShippingAddressStateOrRegion)->
                                setField('s_country', $sShippingAddressCountryCode)->
                                setField('s_zipcode', $sShippingAddressPostalCode)->
                                setField('b_firstname', $sBuyerName)->
                                setField('b_address', $sAddress)->
                                setField('b_city', $sShippingAddressCity)->
                                setField('b_state', $sShippingAddressStateOrRegion)->
                                setField('b_country', $sShippingAddressCountryCode)->
                                setField('b_zipcode', $sShippingAddressPostalCode)->
                                setField('phone', $sShippingAddressPhone)->
                                setField('email', addslashes($aOrderInfo->getElementsByTagName('BuyerEmail')->item(0)->nodeValue))->
                                setField('language', 'US')->
                                setField('storefrontid', 0)->
                                setField('fraud_status', 'C')->
                                setField('overall_fraud_score', 50)->
                                setField('tracking_all_filled', 'N')->
                                setField('vn_status', ($sFulfilmentChanel == 'AFN' ? 'PV' : 'NS'));
                                $oOrder->_update();



                                $aManufacturerid_arr = [];
                                $product_total = 0;

                                foreach ($aOrderItems as $oOrderItem) {
                                    $sAmazonSKU = addslashes($oOrderItem->getElementsByTagName('SellerSKU')->item(0)->nodeValue);
                                    $oProduct = Product::model()->getProductBySKU($sAmazonSKU);

                                    if (!$oProduct->getProductId()) {
                                        /*search product in fba_missing_sku*/
                                        $oFbaMissing = FbaMissingSku::model(['missing_productcode' => $sAmazonSKU]);
                                        if ($oFbaMissing->getProductId()) {
                                            $oProduct = Product::model(['productid' => $oFbaMissing->getProductId()]);
                                        } else
                                            if ($oFbaMissing->getField('missing_productcode') == '') {
                                                $oFbaMissing->setField('missing_productcode', $sAmazonSKU)->setField('productid', 0)->_insert();
                                                global $config;
                                                $to = $config['Company']['product_management'];
                                                $from = 'team@s3stores.com';
                                                func_send_mail($to, 'mail/missing_sku_subj.tpl', 'mail/missing_sku.tpl', $from, true);
                                            }
                                    }

                                    if (!in_array($oProduct->getManufacturerId(), $aManufacturerid_arr)) {
                                        $oOrderGroup = OrderGroup::model()->
                                        setField('orderid', $oOrder->getOrderId())->
                                        setField('manufacturerid', $oProduct->getManufacturerId())->
                                        setField('shipping', addslashes($aOrderInfo->getElementsByTagName('ShipmentServiceLevelCategory')->item(0)->nodeValue))->
                                        setField('cb_status', ($sOrderStatus == 'Canceled' ? 'A' : 'P'))->
                                        setField('dc_status', ($sOrderStatus == 'Unshipped' ? 'T' : 'S'))->
                                        setField('acc_paymentid', PaymentMethod::model()->find(SQLBuilder::getInstance()->addCondition("order_tag_preference='$sFulfilmentChanel'"))->getField('paymentid'))->
                                        setField('bd_status', 'W');
                                        $oOrderGroup->_insert();
                                        $aManufacturerid_arr[] = $oProduct->getManufacturerId();
                                    }

                                    $oOrderDetail = OrderDetail::model()->
                                    setField('orderid', $oOrder->getOrderId())->
                                    setField('productid', $oProduct->getProductId())->
                                    setField('item_cost_to_us', $oProduct->getProductCostToUs())->
                                    setField('price', floatval($oOrderItem->getElementsByTagName('ItemPrice')->item(0)->getElementsByTagName('Amount')->item(0)->nodeValue) /
                                        intval($oOrderItem->getElementsByTagName('QuantityOrdered')->item(0)->nodeValue))->
                                    setField('amount', intval($oOrderItem->getElementsByTagName('QuantityOrdered')->item(0)->nodeValue))->
                                    setField('productcode', $oProduct->getSKU())->
                                    setField('AmazonOrderItemCode', addslashes($oOrderItem->getElementsByTagName('OrderItemId')->item(0)->nodeValue))->
                                    setField('product', addslashes($oProduct->getProductName()));
                                    $oOrderDetail->_insert();

                                    $product_total += $oOrderDetail->getTotalProductPrice();
                                }

                                $oOrder->updateVerificationStatus()->reCalculateTotals();
                                $oOrder->recalculateAccounting();



                                $log = '<a style="color: #1411FF;" href="https://sellercentral.amazon.com/gp/orders-v2/details/ref=ag_orddet_cont_myo?ie=UTF8&orderID=' . $sAmazonOrderId . '" target="_blank">Amazon order # ' . $sAmazonOrderId . '</a><br />Grand total: $' . $product_total;
                                Logs::model()->_log('orders', $oOrder->getOrderId(), 'S', $log, 'Amazon');

                                $statuses = func_query_hash('SELECT code, name, type FROM xcart_order_statuses ORDER BY orderby', array('type', 'code'), false, true);

                                x_load('order');
                                x_load('mail');
                                $order_data = func_order_data($oOrder->getOrderId());
                                $order_status = "I";

                                global $mail_smarty, $config;

                                $mail_smarty->assign("products", $order_data["products"]);
                                $mail_smarty->assign("giftcerts", $order_data["giftcerts"]);
                                $mail_smarty->assign("order", $order_data["order"]);
                                $mail_smarty->assign("userinfo", $order_data["userinfo"]);
                                $mail_smarty->assign('statuses', $statuses);
                                $mail_smarty->assign('oOrder', $oOrder);

                                $aorder_notification = func_get_order_notification($order_status, $order_data);
                                if (!empty($aorder_notification)) {
                                    foreach ($aorder_notification as $oOrderNotification) {
                                        if ($oOrderNotification->isEnabled()) {
                                            $order_notification = $oOrderNotification->getFields();

                                            if ($order_notification['enabled'] == 'Y') {
                                                $mail_smarty->assign('order_notification', $order_notification);

                                                $mail_smarty->assign('type', 'A');
                                                $mail_smarty->assign("show_order_details", "Y");

                                                $mail_smarty->assign("show_amazon_order", "Y");

                                                $to = $config['Company']['orders_department'];
                                                $from = "<" . $config['Company']['orders_department'] . ">";
                                                $reply_to = '';

                                                $attach_pdf_invoice = $order_notification["admin_attach_pdf_invoice"];
                                                $mail_smarty->assign('attach_pdf_invoice', $attach_pdf_invoice);

                                                func_send_mail($to, 'mail/order_notification_subj.tpl', 'mail/order_notification.tpl', $from, true, true, false, false, $reply_to);

                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        $sLog = '';
                        if (in_array($sOrderStatus, ['Shipped', 'Canceled'])) {

                            /** @var OrderGroup $oOrderGroup */
                            $aOrderGroups = $oOrder->getOrderGroups();
                            switch ($sOrderStatus) {
                                case 'Shipped':
                                    foreach ($aOrderGroups as $oOrderGroup) {
                                        if ($oOrderGroup->getOrderGroupStatusCB() != "P") {
                                            if (!empty($sLog)) $sLog .= '<br />';
                                            $sLog .= "<b>" . $oOrderGroup->getManufacturerEntity()->getField('code') . ":</b> cb_status: " . OrderStatus::model(['code' => $oOrderGroup->getOrderGroupStatusCB()])->getName() . " -> " . OrderStatus::model(['code' => 'P'])->getName();
                                            $oOrderGroup->updateField('cb_status', 'P');
                                            $oOrder->updateField('cb_status', 'P');
                                        }
                                        if ($oOrderGroup->getOrderGroupStatusDC() != "S") {
                                            if (!empty($sLog)) $sLog .= '<br />';
                                            $sLog .= "<b>" . $oOrderGroup->getManufacturerEntity()->getField('code') . ":</b> dc_status: " . OrderStatus::model(['code' => $oOrderGroup->getOrderGroupStatusDC()])->getName() . " -> " . OrderStatus::model(['code' => 'S'])->getName();
                                            $oOrderGroup->updateField('dc_status', 'S');
                                            $oOrder->updateField('dc_status', 'S');
                                        }
                                    }

                                    break;
                                case 'Canceled':
                                    foreach ($aOrderGroups as $oOrderGroup) {
                                        if ($oOrderGroup->getOrderGroupStatusCB() != "A") {
                                            if (!empty($sLog)) $sLog .= '<br />';
                                            $sLog .= "<b>" . $oOrderGroup->getManufacturerEntity()->getField('code') . ":</b> cb_status: " . OrderStatus::model(['code' => $oOrderGroup->getOrderGroupStatusCB()])->getName() . " -> " . OrderStatus::model(['code' => 'A'])->getName();
                                            $oOrderGroup->updateField('cb_status', 'A');
                                            $oOrder->updateField('cb_status', 'A');
                                        }
                                    }

                                    break;
                            }
                            if (!empty($sLog))
                                Logs::model()->_log('orders', $oOrder->getOrderId(), 'S', $sLog, 'Amazon');
                        }
                    }

                }
            }

            $this->nextToken = $xpath->query('/*/*/NextToken')->item(0)->nodeValue;
        }
    }
}