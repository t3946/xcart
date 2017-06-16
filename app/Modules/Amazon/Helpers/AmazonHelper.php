<?php

namespace  Modules\Amazon\Helpers;
use FBAInboundServiceMWS_Exception;
use FBAInboundServiceMWS_Interface;
use MarketplaceWebService_Exception;
use MarketplaceWebService_Model_SubmitFeedResponse;
use MarketplaceWebServiceOrders_Exception;
use MarketplaceWebServiceProducts_Exception;
use Modules\Amazon\Models\AmazonFbaProductModel;
use Modules\Amazon\Models\AmazonFbaProductsQuickModel;
use Xcart\Product;

class AmazonHelper
{
    public static function invokeGetCompetitivePricingForSKU($request, $oMWSService)
    {
        try {
            $response = $oMWSService->GetCompetitivePricingForSKU($request);
            $dom = new \DOMDocument();
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
            return $return_echo;
        }
    }

    public static function invokeGetLowestOfferListingsForSKU($request, $oMWSService)
    {
        try {
            $response = $oMWSService->GetLowestOfferListingsForSKU($request);

            $dom = new \DOMDocument();
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
            return $return_echo;
        }
    }

    public static function invokeListInventorySupplyByNextToken($request, $oMWSService)
    {
        try {
            $response = $oMWSService->ListInventorySupplyByNextToken($request);
            $dom = new \DOMDocument();
            $dom->loadXML($response->toXML());
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            return $dom->saveXML();

        } catch (\FBAInventoryServiceMWS_Exception $ex) {
            $return_echo["function"] = "invokeListInventorySupplyByNextToken";
            $return_echo["Caught_Exception"] = $ex->getMessage();
            $return_echo["Response_Status_Code"] = $ex->getStatusCode();
            $return_echo["Error_Code"] = $ex->getErrorCode();
            $return_echo["Error_Type"] = $ex->getErrorType();
            $return_echo["Request_ID"] = $ex->getRequestId();
            $return_echo["XML"] = $ex->getXML();
            $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
            $return_echo["message"] = "Delay 2 minutes and trying the same Request";
            return $return_echo;
        }
    }

    public static function invokeListInventorySupply($request, $oMWSService)
    {
        try {
            $response = $oMWSService->ListInventorySupply($request);
            $dom = new \DOMDocument();
            $dom->loadXML($response->toXML());
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            return $dom->saveXML();

        } catch (\FBAInventoryServiceMWS_Exception $ex) {
            $return_echo["function"] = "invokeListInventorySupply";
            $return_echo["Caught_Exception"] = $ex->getMessage();
            $return_echo["Response_Status_Code"] = $ex->getStatusCode();
            $return_echo["Error_Code"] = $ex->getErrorCode();
            $return_echo["Error_Type"] = $ex->getErrorType();
            $return_echo["Request_ID"] = $ex->getRequestId();
            $return_echo["XML"] = $ex->getXML();
            $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
            $return_echo["message"] = "Delay 2 minutes and trying the same Request";
            return $return_echo;
        }
    }

    public static function invokeGetReport($request, $oMWSService)
    {
        try {
            $response = $oMWSService->getReport($request);
            if ($response->isSetGetReportResult()) {
                $getReportResult = $response->getGetReportResult();

                if ($getReportResult->isSetContentMd5()) {
                    $return_echo["ContentMd5"] = $getReportResult->getContentMd5();
                }
            }
            if ($response->isSetResponseMetadata()) {
                $responseMetadata = $response->getResponseMetadata();
                if ($responseMetadata->isSetRequestId()) {
                    $return_echo["RequestId"] = $responseMetadata->getRequestId();
                }
            }
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

    public static function invokeRequestReport($request, $oMWSService)
    {
        try {
            $response = $oMWSService->requestReport($request);
            if ($response->isSetRequestReportResult()) {
                $requestReportResult = $response->getRequestReportResult();
                if ($requestReportResult->isSetReportRequestInfo()) {
                    $reportRequestInfo = $requestReportResult->getReportRequestInfo();
                    if ($reportRequestInfo->isSetReportRequestId()) {
                        $return_echo["ReportRequestId"] = $reportRequestInfo->getReportRequestId();
                    }
                    if ($reportRequestInfo->isSetReportType()) {
                        $return_echo["ReportType"] = $reportRequestInfo->getReportType();
                    }
                    if ($reportRequestInfo->isSetStartDate()) {
                        $return_echo["StartDate"] = $reportRequestInfo->getStartDate()->format(DATE_FORMAT);
                    }
                    if ($reportRequestInfo->isSetEndDate()) {
                        $return_echo["EndDate"] = $reportRequestInfo->getEndDate()->format(DATE_FORMAT);
                    }
                    if ($reportRequestInfo->isSetSubmittedDate()) {
                        $return_echo["SubmittedDate"] = $reportRequestInfo->getSubmittedDate()->format(DATE_FORMAT);
                    }
                    if ($reportRequestInfo->isSetReportProcessingStatus()) {
                        $return_echo["ReportProcessingStatus"] = $reportRequestInfo->getReportProcessingStatus();
                    }
                }
            }
            if ($response->isSetResponseMetadata()) {
                $responseMetadata = $response->getResponseMetadata();
                if ($responseMetadata->isSetRequestId()) {
                    $return_echo["RequestId"] = $responseMetadata->getRequestId();
                }
            }
            $return_echo["ResponseHeaderMetadata"] = $response->getResponseHeaderMetadata();
            return $return_echo;

        } catch (MarketplaceWebService_Exception $ex) {
            $return_echo["function"] = "invokeRequestReport";
            $return_echo["Caught_Exception"] = $ex->getMessage();
            $return_echo["Response_Status_Code"] = $ex->getStatusCode();
            $return_echo["Error_Code"] = $ex->getErrorCode();
            $return_echo["Error_Type"] = $ex->getErrorType();
            $return_echo["Request_ID"] = $ex->getRequestId();
            $return_echo["XML"] = $ex->getXML();
            $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
            $return_echo["message"] = "Delay 2 minutes and trying the same Request";
            return $return_echo;
        }
    }

    public static function invokeGetReportRequestList($request, $oMWSService)
    {
        try {
            $response = $oMWSService->getReportRequestList($request);
            if ($response->isSetGetReportRequestListResult()) {
                $getReportRequestListResult = $response->getGetReportRequestListResult();
                if ($getReportRequestListResult->isSetNextToken()) {
                    $return_echo["NextToken"] = $getReportRequestListResult->getNextToken();
                }
                if ($getReportRequestListResult->isSetHasNext()) {
                    $return_echo["HasNext"] = $getReportRequestListResult->getHasNext();
                }
                $reportRequestInfoList = $getReportRequestListResult->getReportRequestInfoList();
                foreach ($reportRequestInfoList as $reportRequestInfo) {
                    if ($reportRequestInfo->isSetReportRequestId()) {
                        $return_echo["ReportRequestId"] = $reportRequestInfo->getReportRequestId();
                    }
                    if ($reportRequestInfo->isSetReportType()) {
                        $return_echo["ReportType"] = $reportRequestInfo->getReportType();
                    }
                    if ($reportRequestInfo->isSetStartDate()) {
                        $return_echo["StartDate"] = $reportRequestInfo->getStartDate()->format(DATE_FORMAT);
                    }
                    if ($reportRequestInfo->isSetEndDate()) {
                        $return_echo["EndDate"] = $reportRequestInfo->getEndDate()->format(DATE_FORMAT);
                    }
                    if ($reportRequestInfo->isSetScheduled()) {
                        $return_echo["Scheduled"] = $reportRequestInfo->getScheduled();
                    }
                    if ($reportRequestInfo->isSetSubmittedDate()) {
                        $return_echo["SubmittedDate"] = $reportRequestInfo->getSubmittedDate()->format(DATE_FORMAT);
                    }
                    if ($reportRequestInfo->isSetReportProcessingStatus()) {
                        $return_echo["ReportProcessingStatus"] = $reportRequestInfo->getReportProcessingStatus();
                    }
                    if ($reportRequestInfo->isSetGeneratedReportId()) {
                        $return_echo["GeneratedReportId"] = $reportRequestInfo->getGeneratedReportId();
                    }
                    if ($reportRequestInfo->isSetStartedProcessingDate()) {
                        $return_echo["StartedProcessingDate"] = $reportRequestInfo->getStartedProcessingDate()->format(DATE_FORMAT);
                    }
                    if ($reportRequestInfo->isSetCompletedDate()) {
                        $return_echo["CompletedDate"] = $reportRequestInfo->getCompletedDate()->format(DATE_FORMAT);
                    }

                }
            }
            if ($response->isSetResponseMetadata()) {
                $responseMetadata = $response->getResponseMetadata();
                if ($responseMetadata->isSetRequestId()) {
                    $return_echo["RequestId"] = $responseMetadata->getRequestId();
                }
            }
            $return_echo["ResponseHeaderMetadata"] = $response->getResponseHeaderMetadata();
            return $return_echo;

        } catch (MarketplaceWebService_Exception $ex) {
            $return_echo["function"] = "invokeGetReportRequestList";
            $return_echo["Caught_Exception"] = $ex->getMessage();
            $return_echo["Response_Status_Code"] = $ex->getStatusCode();
            $return_echo["Error_Code"] = $ex->getErrorCode();
            $return_echo["Error_Type"] = $ex->getErrorType();
            $return_echo["Request_ID"] = $ex->getRequestId();
            $return_echo["XML"] = $ex->getXML();
            $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
            $return_echo["message"] = "Delay 2 minutes and trying the same Request";
            return $return_echo;
        }
    }

    public static function invokeGetReportList($request, $oMWSService)
    {
        try {
            $response = $oMWSService->getReportList($request);

            $response_arr["ReportId"] = array();
            if ($response->isSetGetReportListResult()) {
                $getReportListResult = $response->getGetReportListResult();
                $reportInfoList = $getReportListResult->getReportInfoList();
                foreach ($reportInfoList as $reportInfo) {
                    if ($reportInfo->isSetReportId()) {
                        $response_arr["ReportId"][] = $reportInfo->getReportId();
                    }
                }
            }
        } catch (MarketplaceWebService_Exception $ex) {
            $response_arr["Caught Exception"] =  $ex->getMessage();
            $response_arr["Response Status Code"] = $ex->getStatusCode();
            $response_arr["Error Code"] = $ex->getErrorCode();
            $response_arr["Error Type"] = $ex->getErrorType();
            $response_arr["Request ID"] = $ex->getRequestId();
            $response_arr["XML"] = $ex->getXML();
            $response_arr["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
        }
        return $response_arr;
    }

    public static function invokeUpdateReportAcknowledgements($request, $oMWSService)
    {
        try {
            $response = $oMWSService->updateReportAcknowledgements($request);
            $response_arr = [];
        } catch (MarketplaceWebService_Exception $ex) {
            $response_arr["Caught Exception"] =  $ex->getMessage();
            $response_arr["Response Status Code"] = $ex->getStatusCode();
            $response_arr["Error Code"] = $ex->getErrorCode();
            $response_arr["Error Type"] = $ex->getErrorType();
            $response_arr["Request ID"] = $ex->getRequestId();
            $response_arr["XML"] = $ex->getXML();
            $response_arr["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
        }
        return $response_arr;
    }

    public static function invokeGetOrder($request, $oMWSService)
    {
        try {
            $response = $oMWSService->GetOrder($request);
            $dom = new \DOMDocument();
            $dom->loadXML($response->toXML());
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            return $dom->saveXML();
        } catch (MarketplaceWebServiceOrders_Exception $ex) {
            $return_echo["function"] = "invokeGetOrder";
            $return_echo["Caught_Exception"] = $ex->getMessage();
            $return_echo["Response_Status_Code"] = $ex->getStatusCode();
            $return_echo["Error_Code"] = $ex->getErrorCode();
            $return_echo["Error_Type"] = $ex->getErrorType();
            $return_echo["Request_ID"] = $ex->getRequestId();
            $return_echo["XML"] = $ex->getXML();
            $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
            $return_echo["message"] = "Delay 2 minutes and trying the same Request";
            $log_text = "...GetOrder throttling delay";
            func_backprocess_log("amazon_orders", $log_text);
            return $return_echo;
        }
    }

    public static function invokeListOrders($request, $oMWSService)
    {
        try {
            $response = $oMWSService->ListOrders($request);

            $dom = new \DOMDocument();
            $dom->loadXML($response->toXML());
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            return $dom->saveXML();

        } catch (MarketplaceWebServiceOrders_Exception $ex) {
            $return_echo["function"] = "invokeListOrders";
            $return_echo["Caught_Exception"] = $ex->getMessage();
            $return_echo["Response_Status_Code"] = $ex->getStatusCode();
            $return_echo["Error_Code"] = $ex->getErrorCode();
            $return_echo["Error_Type"] = $ex->getErrorType();
            $return_echo["Request_ID"] = $ex->getRequestId();
            $return_echo["XML"] = $ex->getXML();
            $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
            $return_echo["message"] = "Delay 2 minutes and trying the same Request";
            $log_text = "...ListOrders throttling delay";
            func_backprocess_log("amazon_orders", $log_text);

            return $return_echo;
        }
    }

    public static function invokeListOrdersByNextToken($request, $oMWSService)
    {
        try {
            $response = $oMWSService->ListOrdersByNextToken($request);
            $dom = new \DOMDocument();
            $dom->loadXML($response->toXML());
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            return $dom->saveXML();

        } catch (MarketplaceWebServiceOrders_Exception $ex) {
            $return_echo["function"] = "invokeListOrdersByNextToken";
            $return_echo["Caught_Exception"] = $ex->getMessage();
            $return_echo["Response_Status_Code"] = $ex->getStatusCode();
            $return_echo["Error_Code"] = $ex->getErrorCode();
            $return_echo["Error_Type"] = $ex->getErrorType();
            $return_echo["Request_ID"] = $ex->getRequestId();
            $return_echo["XML"] = $ex->getXML();
            $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
            $return_echo["message"] = "Delay 2 minutes and trying the same Request";
            $log_text = "...ListOrdersByNextToken  throttling delay";
            func_backprocess_log("amazon_orders", $log_text);
            return $return_echo;
        }
    }

    public static function invokeListOrderItems($request, $oMWSService)
    {
        try {
            $response = $oMWSService->ListOrderItems($request);
            $dom = new \DOMDocument();
            $dom->loadXML($response->toXML());
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            return $dom->saveXML();

        } catch (MarketplaceWebServiceOrders_Exception $ex) {
            $return_echo["function"] = "invokeListOrderItems";
            $return_echo["Caught_Exception"] = $ex->getMessage();
            $return_echo["Response_Status_Code"] = $ex->getStatusCode();
            $return_echo["Error_Code"] = $ex->getErrorCode();
            $return_echo["Error_Type"] = $ex->getErrorType();
            $return_echo["Request_ID"] = $ex->getRequestId();
            $return_echo["XML"] = $ex->getXML();
            $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
            $return_echo["message"] = "Delay 2 minutes and trying the same Request";
            $log_text = "...ListOrderItems  throttling delay";
            func_backprocess_log("amazon_orders", $log_text);
            return $return_echo;
        }
    }

    public static function invokeGetFulfillmentPreview($request, $oMWSService)
    {
        $return_echo = [];
        try {
            $response = $oMWSService->GetFulfillmentPreview($request);
            $dom = new \DOMDocument();
            $dom->loadXML($response->toXML());
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $return_echo["saveXML"] = $dom->saveXML();
            $return_echo["ResponseHeaderMetadata"] = $response->getResponseHeaderMetadata();

        } catch (\FBAOutboundServiceMWS_Exception $ex) {
            $return_echo["Caught_Exception"] = $ex->getMessage();
            $return_echo["Response_Status_Code"] = $ex->getStatusCode();
            $return_echo["Error_Code"] = $ex->getErrorCode();
            $return_echo["Error_Type"] = $ex->getErrorType();
            $return_echo["Request_ID"] = $ex->getRequestId();
            $return_echo["XML"] = $ex->getXML();
            $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
            $return_echo["message"] = "Delay 2 minutes and trying the same Request";
        }
        return $return_echo;
    }

    public static function invokeSubmitFeed($request, $oMWSService)
    {
        $return_echo = [];
        try {
            /** @var MarketplaceWebService_Model_SubmitFeedResponse $response */
            $response = $oMWSService->submitFeed($request);

            if ($response->isSetSubmitFeedResult()) {
                $submitFeedResult = $response->getSubmitFeedResult();
                if ($submitFeedResult->isSetFeedSubmissionInfo()) {
                    $feedSubmissionInfo = $submitFeedResult->getFeedSubmissionInfo();
                    if ($feedSubmissionInfo->isSetFeedSubmissionId()) {
                        $return_echo['FeedSubmissionId'] = $feedSubmissionInfo->getFeedSubmissionId();
                    }
                    if ($feedSubmissionInfo->isSetFeedType()) {
                        $return_echo['FeedType'] =  $feedSubmissionInfo->getFeedType();
                    }
                    if ($feedSubmissionInfo->isSetSubmittedDate()) {
                        $return_echo['SubmittedDate'] =  $feedSubmissionInfo->getSubmittedDate()->format(DATE_FORMAT);
                    }
                    if ($feedSubmissionInfo->isSetFeedProcessingStatus()) {
                        $return_echo['FeedProcessingStatus'] = $feedSubmissionInfo->getFeedProcessingStatus();
                    }
                    if ($feedSubmissionInfo->isSetStartedProcessingDate()) {
                        $return_echo['StartedProcessingDate'] = $feedSubmissionInfo->getStartedProcessingDate()->format(DATE_FORMAT);
                    }
                    if ($feedSubmissionInfo->isSetCompletedProcessingDate()) {
                        $return_echo['CompletedProcessingDate'] = $feedSubmissionInfo->getCompletedProcessingDate()->format(DATE_FORMAT);
                    }
                }
            }
            if ($response->isSetResponseMetadata()) {
                $responseMetadata = $response->getResponseMetadata();
                if ($responseMetadata->isSetRequestId()) {
                    $return_echo['RequestId'] = $responseMetadata->getRequestId();
                }
            }

            $return_echo["ResponseHeaderMetadata"] = $response->getResponseHeaderMetadata();
        } catch (MarketplaceWebService_Exception $ex) {
            $return_echo["function"] = "invokeSubmitFeed";
            $return_echo["Caught_Exception"] = $ex->getMessage();
            $return_echo["Response_Status_Code"] = $ex->getStatusCode();
            $return_echo["Error_Code"] = $ex->getErrorCode();
            $return_echo["Error_Type"] = $ex->getErrorType();
            $return_echo["Request_ID"] = $ex->getRequestId();
            $return_echo["XML"] = $ex->getXML();
            $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
        }
        return $return_echo;
    }

    public static function invokeGetFeedSubmissionResult($request, $oMWSService)
    {
        $return_echo = [];
        try {
            $response = $oMWSService->getFeedSubmissionResult($request);

            if ($response->isSetGetFeedSubmissionResultResult()) {
                $getFeedSubmissionResultResult = $response->getGetFeedSubmissionResultResult();
                if ($getFeedSubmissionResultResult->isSetContentMd5()) {
                    $return_echo['ContentMd5'] = $getFeedSubmissionResultResult->getContentMd5();
                }
            }
            if ($response->isSetResponseMetadata()) {
                $responseMetadata = $response->getResponseMetadata();
                if ($responseMetadata->isSetRequestId()) {
                    $return_echo['RequestId'] = $responseMetadata->getRequestId();
                }
            }
            $return_echo['ResponseHeaderMetadata'] = $response->getResponseHeaderMetadata();
        } catch (MarketplaceWebService_Exception $ex) {
            $return_echo["Caught Exception"] = $ex->getMessage();
            $return_echo["Response Status Code"] = $ex->getStatusCode();
            $return_echo["Error Code"] = $ex->getErrorCode();
            $return_echo["Error Type"] = $ex->getErrorType();
            $return_echo["Request ID"] = $ex->getRequestId();
            $return_echo["XML"] = $ex->getXML();
            $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
        }
        return $return_echo;
    }

    /**
     * @param $params
     * @return AmazonFbaProductModel|null
     */
    public static function getAmazonFbaProductModel($params)
    {
        $oAmazonFbaProductModel = null;
        if (!$oAmazonFbaProductModel = AmazonFbaProductModel::objects()->get($params)) {
            if ($oAmazonFbaProductsQuickModel = AmazonFbaProductsQuickModel::objects()->get(['productid' => $params['productid']])){
                $oAmazonFbaProductModelOld = AmazonFbaProductModel::objects()->get(['id' => $oAmazonFbaProductsQuickModel->data_id]);
                if ($oAmazonFbaProductModelOld) {
                    $oAmazonFbaProductModel = new AmazonFbaProductModel();
                    $oAmazonFbaProductModel->setAttributes($oAmazonFbaProductModelOld->getAttributes());
                    $oAmazonFbaProductModel->id = null;
                    $oAmazonFbaProductModel->buybox_out = 0;
                    $oAmazonFbaProductModel->buybox_in = 0;
                }
            }
        }
        if (!$oAmazonFbaProductModel) {
            $oAmazonFbaProductModel = (new AmazonFbaProductModel());
        }
        $oAmazonFbaProductModel->setAttributes($params);
        return $oAmazonFbaProductModel;
    }

    public static function getReportContent($report_data)
    {
        $aResultArray = [];
        if (!empty($report_data)) {
            if (is_array($report_data)) {
                foreach ($report_data as $reportId => $arr) {
                    if (!empty($arr['Report_Contents']))
                        $aResultArray[$reportId] = $arr['Report_Contents'];
                }
            } else {
                if (!empty($report_data['Report_Contents']))
                    $aResultArray[] = $report_data['Report_Contents'];
            }
        }
        return $aResultArray;
    }

    /**
     * @param string $report_data
     * @param Product[] $aProducts
     * @return array
     */
    public static function parseAmazonOffers($report_data, $aProducts)
    {
        $aProductOffers = [];
        $report_data = str_replace('http://mws.amazonservices.com/schema/Products/2011-10-01', '', $report_data);
        $docShipping = new \DOMDocument;
        $docShipping->loadXML($report_data);
        $xpath = new \DOMXPath($docShipping);
        $aGetLowestOfferListingsForSKUResult = $xpath->query('/*/GetLowestOfferListingsForSKUResult');
        /** @var \DOMElement[] $aGetLowestOfferListingsForSKUResult */
        foreach ($aGetLowestOfferListingsForSKUResult as $aCompetitiveResult) {
            $aOffers = [];
            $sSKU = $sAsin = '';
            $oAsin = $aCompetitiveResult->getElementsByTagName('ASIN');
            if (!empty($oAsin)) {
                $sAsin = $oAsin->item(0)->nodeValue;
            }
            $oSKU = $aCompetitiveResult->getElementsByTagName('SellerSKU');
            if (!empty($oSKU)) {
                $sSKU = $oSKU->item(0)->nodeValue;
            }
            $aProductModels = array_filter(
                $aProducts,
                function ($e) use ($sSKU) {
                    return $e->productcode == $sSKU;
                });
            $oProductModel = reset($aProductModels);
            $aLowestOfferListing = $aCompetitiveResult->getElementsByTagName('LowestOfferListing');
            if ($aLowestOfferListing->length) {
                /** @var \DOMElement[] $aLowestOfferListing */
                foreach ($aLowestOfferListing as $LowestOfferListing) {
                    $Offer = [];
                    $sItemCondition = $sItemSubcondition = $sShipsDomestically = $sFulfillmentChannel = '';
                    $ItemCondition = $LowestOfferListing->getElementsByTagName('ItemCondition');
                    $ItemSubcondition = $LowestOfferListing->getElementsByTagName('ItemSubcondition');
                    $FulfillmentChannel = $LowestOfferListing->getElementsByTagName('FulfillmentChannel');
                    $ShipsDomestically = $LowestOfferListing->getElementsByTagName('ShipsDomestically');
                    $MultipleOffersAtLowestPrice = $LowestOfferListing->getElementsByTagName('MultipleOffersAtLowestPrice');
                    $AllOfferListingsConsidered = $LowestOfferListing->getElementsByTagName('AllOfferListingsConsidered');
                    $NumberOfOfferListingsConsidered = $LowestOfferListing->getElementsByTagName('NumberOfOfferListingsConsidered');
                    $SellerFeedbackCount = $LowestOfferListing->getElementsByTagName('SellerFeedbackCount');
                    $ShippingTime = $LowestOfferListing->getElementsByTagName('ShippingTime');
                    $SellerPositiveFeedbackRating = $LowestOfferListing->getElementsByTagName('SellerPositiveFeedbackRating');
                    if (!empty($ItemCondition)) {
                        $sItemCondition = $ItemCondition->item(0)->nodeValue;
                    }
                    if (!empty($ItemSubcondition)) {
                        $sItemSubcondition = $ItemSubcondition->item(0)->nodeValue;
                    }
                    if (!empty($ShipsDomestically)) {
                        $sShipsDomestically = $ShipsDomestically->item(0)->nodeValue;
                    }
                    if ($sItemCondition == 'New' && $sItemSubcondition == 'New' && ($sShipsDomestically == 'Unknown' || $sShipsDomestically == 'True')) {

                        if (!empty($FulfillmentChannel)) {
                            switch ($FulfillmentChannel->item(0)->nodeValue) {
                                case 'Merchant':
                                    $Offer['lp_FulfillmentChannel'] = 'MFN';
                                    break;
                                case 'Amazon':
                                    $Offer['lp_FulfillmentChannel'] = 'AFN';
                                    break;
                            }
                        }

                        if (!empty($MultipleOffersAtLowestPrice)) {
                            switch ($MultipleOffersAtLowestPrice->item(0)->nodeValue) {
                                case 'True':
                                    $Offer['lp_MultipleOfferListingsAtLowestPrice'] = 'Y';
                                    break;
                                case 'False':
                                    $Offer['lp_MultipleOfferListingsAtLowestPrice'] = 'N';
                                    break;
                            }
                        }
                        if (!empty($AllOfferListingsConsidered)) {
                            switch ($MultipleOffersAtLowestPrice->item(0)->nodeValue) {
                                case 'True':
                                    $Offer['lp_AllOfferListingsConsidered'] = 'Y';
                                    break;
                                case 'False':
                                    $Offer['lp_AllOfferListingsConsidered'] = 'N';
                                    break;
                            }
                        }
                        if (!empty($NumberOfOfferListingsConsidered)) {
                            $Offer['lp_NumberOfOfferListingsConsidered'] = intval($NumberOfOfferListingsConsidered->item(0)->nodeValue);
                        }
                        if (!empty($SellerFeedbackCount)) {
                            $Offer['lp_SellerFeedbackCount'] = intval($SellerFeedbackCount->item(0)->nodeValue);
                        }
                        if (!empty($SellerPositiveFeedbackRating)) {
                            $Offer['lp_SellerPositiveFeedbackRating'] = $SellerPositiveFeedbackRating->item(0)->nodeValue;
                        }
                        if (!empty($ShippingTime)) {
                            /** @var \DOMNodeList $ShippingTime */
                            $ShippingTimeMax = $ShippingTime->item(0)->getElementsByTagName('Max');
                            if (!empty($ShippingTimeMax)) {
                                $Offer['lp_ShippingTime'] = $ShippingTimeMax->item(0)->nodeValue;
                            }
                        }
                        /** @var \DOMElement[] $LandedPrice */
                        $LandedPrice = $LowestOfferListing->getElementsByTagName('LandedPrice');
                        if (!empty($LandedPrice)) {
                            $Amount = $LandedPrice->item(0)->getElementsByTagName('Amount');
                            if (!empty($Amount)) {
                                $Offer['lp_LandedPrice'] = floatval($Amount->item(0)->nodeValue);
                            }
                        }
                        $Offer['productcode'] = $sSKU;
                        $Offer['productid'] = $oProductModel->productid;
                        $Offer['ASIN'] = $sAsin;
                        $aOffers[] = $Offer;
                    }
                }
                if (!empty($aOffers)) {
                    array_multisort(
                        array_map(function ($a) {
                            return $a['lp_FulfillmentChannel'];
                        }, $aOffers), SORT_ASC,
                        array_map(function ($a) {
                            return $a['lp_LandedPrice'];
                        }, $aOffers), SORT_ASC,
                        $aOffers);
                    $aProductOffers[] = reset($aOffers);
                }
            }
        }
        return $aProductOffers;
    }

    /**
     * Get List Inbound Shipments Action Sample
     * Gets competitive pricing and related information for a product identified by
     * the MarketplaceId and ASIN.
     *
     * @param FBAInboundServiceMWS_Interface $service instance of FBAInboundServiceMWS_Interface
     * @param mixed $request FBAInboundServiceMWS_Model_ListInboundShipments or array of parameters
     */
    public static function invokeListInboundShipmentsItems($request, FBAInboundServiceMWS_Interface $service)
    {
        $return_echo = [];
        try {
            /** @var FBAInboundServiceMWS_Interface */
            $response = $service->listInboundShipmentItems($request);
            $dom = new \DOMDocument();
            $dom->loadXML($response->toXML());
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            return $dom->saveXML();
        } catch (FBAInboundServiceMWS_Exception $ex) {
            $return_echo["Caught Exception"] =  $ex->getMessage();
            $return_echo["Response Status Code"] = $ex->getStatusCode();
            $return_echo["Error Code"] = $ex->getErrorCode();
            $return_echo["Error Type"] = $ex->getErrorType();
            $return_echo["Request ID"] = $ex->getRequestId();
            $return_echo["XML"] = $ex->getXML();
            $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
        }
        return $return_echo;
    }

    public static function invokeListInboundShipments($request, FBAInboundServiceMWS_Interface $service)
    {
        $return_echo = [];
        try {
            /** @var FBAInboundServiceMWS_Interface */
            $response = $service->listInboundShipments($request);
            $dom = new \DOMDocument();
            $dom->loadXML($response->toXML());
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            return $dom->saveXML();
        } catch (FBAInboundServiceMWS_Exception $ex) {
            $return_echo["Caught Exception"] =  $ex->getMessage();
            $return_echo["Response Status Code"] = $ex->getStatusCode();
            $return_echo["Error Code"] = $ex->getErrorCode();
            $return_echo["Error Type"] = $ex->getErrorType();
            $return_echo["Request ID"] = $ex->getRequestId();
            $return_echo["XML"] = $ex->getXML();
            $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
        }
        return $return_echo;
    }

    public static function invokeListInboundShipmentsItemsByNextToken($request, FBAInboundServiceMWS_Interface $service)
    {
        $return_echo = [];
        try {
            /** @var FBAInboundServiceMWS_Interface */
            $response = $service->listInboundShipmentItemsByNextToken($request);
            $dom = new \DOMDocument();
            $dom->loadXML($response->toXML());
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            return $dom->saveXML();
        } catch (FBAInboundServiceMWS_Exception $ex) {
            $return_echo["Caught Exception"] =  $ex->getMessage();
            $return_echo["Response Status Code"] = $ex->getStatusCode();
            $return_echo["Error Code"] = $ex->getErrorCode();
            $return_echo["Error Type"] = $ex->getErrorType();
            $return_echo["Request ID"] = $ex->getRequestId();
            $return_echo["XML"] = $ex->getXML();
            $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
        }
        return $return_echo;
    }

    public static function invokeListInboundShipmentsByNextToken($request, FBAInboundServiceMWS_Interface $service)
    {
        $return_echo = [];
        try {
            /** @var FBAInboundServiceMWS_Interface */
            $response = $service->listInboundShipmentsByNextToken($request);
            $dom = new \DOMDocument();
            $dom->loadXML($response->toXML());
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            return $dom->saveXML();
        } catch (FBAInboundServiceMWS_Exception $ex) {
            $return_echo["Caught Exception"] =  $ex->getMessage();
            $return_echo["Response Status Code"] = $ex->getStatusCode();
            $return_echo["Error Code"] = $ex->getErrorCode();
            $return_echo["Error Type"] = $ex->getErrorType();
            $return_echo["Request ID"] = $ex->getRequestId();
            $return_echo["XML"] = $ex->getXML();
            $return_echo["ResponseHeaderMetadata"] = $ex->getResponseHeaderMetadata();
        }
        return $return_echo;
    }
}