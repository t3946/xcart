<?php
  function invokeGetFulfillmentPreview(FBAOutboundServiceMWS_Interface $b_service, $request)
  {
      try {
        $response = $b_service->GetFulfillmentPreview($request);

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






 $b_config = array (
   'ServiceURL' => "https://mws.amazonservices.com/FulfillmentOutboundShipment/2010-10-01",
   'ProxyHost' => null,
   'ProxyPort' => -1,
   'ProxyUsername' => null,
   'ProxyPassword' => null,
   'MaxErrorRetry' => 3,
 );

 $b_service = new FBAOutboundServiceMWS_Client(
        AWS_ACCESS_KEY_ID,
        AWS_SECRET_ACCESS_KEY,
        $b_config,
        APPLICATION_NAME,
        APPLICATION_VERSION);

 $marketplaceIdArray = array("Id" => array('ATVPDKIKX0DER'));

?>
