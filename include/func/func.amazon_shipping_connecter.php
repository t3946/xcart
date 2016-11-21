<?php
if (!function_exists('invokeGetFulfillmentPreview')) {
 function invokeGetFulfillmentPreview(FBAOutboundServiceMWS_Interface $service, $request)
 {
  try {
   $response = $service->GetFulfillmentPreview($request);

   echo ("Service Response\n");
   echo ("=============================================================================\n");

   $dom = new DOMDocument();
   $dom->loadXML($response->toXML());
   $dom->preserveWhiteSpace = false;
   $dom->formatOutput = true;
   echo $dom->saveXML();
   echo("ResponseHeaderMetadata: " . $response->getResponseHeaderMetadata() . "\n");

  } catch (FBAOutboundServiceMWS_Exception $ex) {
   echo("Caught Exception: " . $ex->getMessage() . "\n");
   echo("Response Status Code: " . $ex->getStatusCode() . "\n");
   echo("Error Code: " . $ex->getErrorCode() . "\n");
   echo("Error Type: " . $ex->getErrorType() . "\n");
   echo("Request ID: " . $ex->getRequestId() . "\n");
   echo("XML: " . $ex->getXML() . "\n");
   echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n");
  }
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
