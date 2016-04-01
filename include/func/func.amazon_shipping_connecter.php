<?php

### Amazon ###
include_once $xcart_dir . "/src/FBAOutboundServiceMWS/Samples/.config.inc.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Client.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Exception.php";

require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/Address.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/CancelFulfillmentOrderRequest.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/CancelFulfillmentOrderResponse.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/CODSettings.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/CreateFulfillmentOrderItemList.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/CreateFulfillmentOrderItem.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/CreateFulfillmentOrderRequest.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/CreateFulfillmentOrderResponse.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/Currency.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/DeliveryWindowList.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/DeliveryWindow.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/FeeList.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/Fee.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/FulfillmentMethodList.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/FulfillmentOrderItemList.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/FulfillmentOrderItem.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/FulfillmentOrderList.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/FulfillmentOrder.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/FulfillmentPreviewItemList.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/FulfillmentPreviewItem.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/FulfillmentPreviewList.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/FulfillmentPreview.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/FulfillmentPreviewShipmentList.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/FulfillmentPreviewShipment.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/FulfillmentShipmentItemList.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/FulfillmentShipmentItem.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/FulfillmentShipmentList.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/FulfillmentShipmentPackageList.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/FulfillmentShipmentPackage.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/FulfillmentShipment.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/GetFulfillmentOrderRequest.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/GetFulfillmentOrderResponse.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/GetFulfillmentOrderResult.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/GetFulfillmentPreviewItemList.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/GetFulfillmentPreviewItem.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/GetFulfillmentPreviewRequest.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/GetFulfillmentPreviewResponse.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/GetFulfillmentPreviewResult.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/GetPackageTrackingDetailsRequest.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/GetPackageTrackingDetailsResponse.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/GetPackageTrackingDetailsResult.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/GetServiceStatusRequest.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/GetServiceStatusResponse.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/GetServiceStatusResult.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/ListAllFulfillmentOrdersByNextTokenRequest.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/ListAllFulfillmentOrdersByNextTokenResponse.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/ListAllFulfillmentOrdersByNextTokenResult.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/ListAllFulfillmentOrdersRequest.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/ListAllFulfillmentOrdersResponse.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/ListAllFulfillmentOrdersResult.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/NotificationEmailList.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/ResponseHeaderMetadata.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/ResponseMetadata.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/ScheduledDeliveryInfo.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/ShippingSpeedCategoryList.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/StringList.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/TrackingAddress.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/TrackingEventList.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/TrackingEvent.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/UnfulfillablePreviewItemList.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/UnfulfillablePreviewItem.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/UpdateFulfillmentOrderItemList.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/UpdateFulfillmentOrderItem.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/UpdateFulfillmentOrderRequest.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/UpdateFulfillmentOrderResponse.php";
require_once $xcart_dir . "/src/FBAOutboundServiceMWS/Model/Weight.php";



  function invokeGetFulfillmentPreview(FBAOutboundServiceMWS_Interface $service, $request)
  {
      try {
        $response = $service->GetFulfillmentPreview($request);

//        echo ("Service Response\n");
//        echo ("=============================================================================\n");

        $dom = new DOMDocument();
        $dom->loadXML($response->toXML());
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $return_echo["saveXML"] = $dom->saveXML();
        $return_echo["ResponseHeaderMetadata"] = $response->getResponseHeaderMetadata();

	return $return_echo;

     } catch (FBAOutboundServiceMWS_Exception $ex) {
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






 $a_config = array (
   'ServiceURL' => "https://mws.amazonservices.com/FulfillmentOutboundShipment/2010-10-01",
   'ProxyHost' => null,
   'ProxyPort' => -1,
   'ProxyUsername' => null,
   'ProxyPassword' => null,
   'MaxErrorRetry' => 3,
 );

 $service = new FBAOutboundServiceMWS_Client(
        AWS_ACCESS_KEY_ID,
        AWS_SECRET_ACCESS_KEY,
        $a_config,
        APPLICATION_NAME,
        APPLICATION_VERSION);

 $marketplaceIdArray = array("Id" => array('ATVPDKIKX0DER'));

?>
