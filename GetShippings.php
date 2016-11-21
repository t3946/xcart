<?php

define("CIDEV_CRON_START", "CRON");
session_start();

require "./top.inc.php";
require "./init.php";

include $xcart_dir ."/include/func/func.amazon_shipping_connecter.php";

ini_set('memory_limit', '512M');
set_time_limit(0);
//x_load('backoffice','files','taxes', 'froogle', 'product', 'crypt', 'xml');

parse_str(file_get_contents('php://input'), $requestData); 

//print_r($requestData);

if (
	$REQUEST_METHOD == "PUT" && 
	$requestData["sid"] == "2376dthjdcbsjct67et23dfxafdgbhsdj08r67fija" &&
	!empty($requestData["cart"]) && is_array($requestData["cart"]) &&
	!empty($requestData["userinfo"]) && is_array($requestData["userinfo"])
){

 $cart = $requestData["cart"];
 $userinfo = $requestData["userinfo"];


 $request = new FBAOutboundServiceMWS_Model_GetFulfillmentPreviewRequest();
 $request->setSellerId(MERCHANT_ID);


 $address = new FBAOutboundServiceMWS_Model_Address();
 $address->setName($userinfo["s_firstname"]);
 $address->setLine1($userinfo["s_address"]);
 if ($userinfo["s_address_2"]){
	$address->setLine2($userinfo["s_address_2"]);
 }
 $address->setCity($userinfo["s_city"]);
 $address->setCountryCode($userinfo["s_country"]);
 $address->setStateOrProvinceCode($userinfo["s_state"]);
 $address->setPostalCode($userinfo["s_zipcode"]);
 $request->setAddress($address);

 $items = array();
 foreach ($cart["products"] as $k => $v){

  $item = new FBAOutboundServiceMWS_Model_GetFulfillmentPreviewItem();
  $item->setSellerSKU($v["productcode"]);
  $item->setQuantity($v["amount"]);
  $item->setSellerFulfillmentOrderItemId($v["productcode"]);
  $items[] = $item;

 }

 $itemList = new FBAOutboundServiceMWS_Model_GetFulfillmentPreviewItemList();
 $itemList->setmember($items);
 $request->setItems($itemList);


 $shippingArray = new FBAOutboundServiceMWS_Model_ShippingSpeedCategoryList();
 $shippingArray->setmember(array("Standard", "Expedited", "Priority"));
 $request->setShippingSpeedCategories($shippingArray);

 $dom_xml = invokeGetFulfillmentPreview((new Xcart\AmazonMWS('FBAOutboundServiceMWS_Client','/FulfillmentOutboundShipment/2010-10-01'))->getService(), $request);

 print($dom_xml["saveXML"]);
// print_r($dom_xml);



/*
#
## For test purpose
###
$test_answer = '<?xml version="1.0"?>
<GetFulfillmentPreviewResponse xmlns="http://mws.amazonaws.com/FulfillmentOutboundShipment/2010-10-01/">
  <GetFulfillmentPreviewResult>
    <FulfillmentPreviews>
      <member>
        <ShippingSpeedCategory>Expedited</ShippingSpeedCategory>
        <IsFulfillable>true</IsFulfillable>
        <IsCODCapable>false</IsCODCapable>
        <EstimatedShippingWeight>
          <Unit>POUNDS</Unit>
          <Value>2</Value>
        </EstimatedShippingWeight>
        <EstimatedFees>
          <member>
            <Name>FBAPerUnitFulfillmentFee</Name>
            <Amount>
              <CurrencyCode>USD</CurrencyCode>
              <Value>1.50</Value>
            </Amount>
          </member>
          <member>
            <Name>FBAPerOrderFulfillmentFee</Name>
            <Amount>
              <CurrencyCode>USD</CurrencyCode>
              <Value>7.75</Value>
            </Amount>
          </member>
          <member>
            <Name>FBATransportationFee</Name>
            <Amount>
              <CurrencyCode>USD</CurrencyCode>
              <Value>1.20</Value>
            </Amount>
          </member>
        </EstimatedFees>
        <FulfillmentPreviewShipments>
          <member>
            <EarliestShipDate>2016-04-01T09:00:00Z</EarliestShipDate>
            <LatestShipDate>2016-04-01T09:00:00Z</LatestShipDate>
            <EarliestArrivalDate>2016-04-02T07:00:00Z</EarliestArrivalDate>
            <LatestArrivalDate>2016-04-03T06:59:59Z</LatestArrivalDate>
            <FulfillmentPreviewItems>
              <member>
                <SellerSKU>ALV-BHS15202</SellerSKU>
                <Quantity>1</Quantity>
                <SellerFulfillmentOrderItemId>ALV-BHS15202</SellerFulfillmentOrderItemId>
                <EstimatedShippingWeight>
                  <Unit>POUNDS</Unit>
                  <Value>0.950</Value>
                </EstimatedShippingWeight>
                <ShippingWeightCalculationMethod>Package</ShippingWeightCalculationMethod>
              </member>
              <member>
                <SellerSKU>ALV-XA05</SellerSKU>
                <Quantity>1</Quantity>
                <SellerFulfillmentOrderItemId>ALV-XA05</SellerFulfillmentOrderItemId>
                <EstimatedShippingWeight>
                  <Unit>POUNDS</Unit>
                  <Value>0.020</Value>
                </EstimatedShippingWeight>
                <ShippingWeightCalculationMethod>Package</ShippingWeightCalculationMethod>
              </member>
            </FulfillmentPreviewItems>
          </member>
        </FulfillmentPreviewShipments>
        <UnfulfillablePreviewItems/>
        <MarketplaceId>ATVPDKIKX0DER</MarketplaceId>
      </member>
      <member>
        <ShippingSpeedCategory>Priority</ShippingSpeedCategory>
        <IsFulfillable>true</IsFulfillable>
        <IsCODCapable>false</IsCODCapable>
        <EstimatedShippingWeight>
          <Unit>POUNDS</Unit>
          <Value>2</Value>
        </EstimatedShippingWeight>
        <EstimatedFees>
          <member>
            <Name>FBAPerUnitFulfillmentFee</Name>
            <Amount>
              <CurrencyCode>USD</CurrencyCode>
              <Value>1.50</Value>
            </Amount>
          </member>
          <member>
            <Name>FBAPerOrderFulfillmentFee</Name>
            <Amount>
              <CurrencyCode>USD</CurrencyCode>
              <Value>14.75</Value>
            </Amount>
          </member>
          <member>
            <Name>FBATransportationFee</Name>
            <Amount>
              <CurrencyCode>USD</CurrencyCode>
              <Value>3.00</Value>
            </Amount>
          </member>
        </EstimatedFees>
        <FulfillmentPreviewShipments>
          <member>
            <EarliestShipDate>2016-04-01T00:00:00Z</EarliestShipDate>
            <LatestShipDate>2016-04-01T00:00:00Z</LatestShipDate>
            <EarliestArrivalDate>2016-04-01T07:00:00Z</EarliestArrivalDate>
            <LatestArrivalDate>2016-04-02T06:59:59Z</LatestArrivalDate>
            <FulfillmentPreviewItems>
              <member>
                <SellerSKU>ALV-BHS15202</SellerSKU>
                <Quantity>1</Quantity>
                <SellerFulfillmentOrderItemId>ALV-BHS15202</SellerFulfillmentOrderItemId>
                <EstimatedShippingWeight>
                  <Unit>POUNDS</Unit>
                  <Value>0.950</Value>
                </EstimatedShippingWeight>
                <ShippingWeightCalculationMethod>Package</ShippingWeightCalculationMethod>
              </member>
              <member>
                <SellerSKU>ALV-XA05</SellerSKU>
                <Quantity>1</Quantity>
                <SellerFulfillmentOrderItemId>ALV-XA05</SellerFulfillmentOrderItemId>
                <EstimatedShippingWeight>
                  <Unit>POUNDS</Unit>
                  <Value>0.020</Value>
                </EstimatedShippingWeight>
                <ShippingWeightCalculationMethod>Package</ShippingWeightCalculationMethod>
              </member>
            </FulfillmentPreviewItems>
          </member>
        </FulfillmentPreviewShipments>
        <UnfulfillablePreviewItems/>
        <MarketplaceId>ATVPDKIKX0DER</MarketplaceId>
      </member>
      <member>
        <ShippingSpeedCategory>Standard</ShippingSpeedCategory>
        <IsFulfillable>true</IsFulfillable>
        <IsCODCapable>false</IsCODCapable>
        <EstimatedShippingWeight>
          <Unit>POUNDS</Unit>
          <Value>2</Value>
        </EstimatedShippingWeight>
        <EstimatedFees>
          <member>
            <Name>FBAPerUnitFulfillmentFee</Name>
            <Amount>
              <CurrencyCode>USD</CurrencyCode>
              <Value>1.50</Value>
            </Amount>
          </member>
          <member>
            <Name>FBAPerOrderFulfillmentFee</Name>
            <Amount>
              <CurrencyCode>USD</CurrencyCode>
              <Value>4.75</Value>
            </Amount>
          </member>
          <member>
            <Name>FBATransportationFee</Name>
            <Amount>
              <CurrencyCode>USD</CurrencyCode>
              <Value>1.00</Value>
            </Amount>
          </member>
        </EstimatedFees>
        <FulfillmentPreviewShipments>
          <member>
            <EarliestShipDate>2016-04-01T07:00:00Z</EarliestShipDate>
            <LatestShipDate>2016-04-02T06:59:59Z</LatestShipDate>
            <EarliestArrivalDate>2016-04-06T07:00:00Z</EarliestArrivalDate>
            <LatestArrivalDate>2016-04-09T06:59:59Z</LatestArrivalDate>
            <FulfillmentPreviewItems>
              <member>
                <SellerSKU>ALV-BHS15202</SellerSKU>
                <Quantity>1</Quantity>
                <SellerFulfillmentOrderItemId>ALV-BHS15202</SellerFulfillmentOrderItemId>
                <EstimatedShippingWeight>
                  <Unit>POUNDS</Unit>
                  <Value>0.950</Value>
                </EstimatedShippingWeight>
                <ShippingWeightCalculationMethod>Package</ShippingWeightCalculationMethod>
              </member>
              <member>
                <SellerSKU>ALV-XA05</SellerSKU>
                <Quantity>1</Quantity>
                <SellerFulfillmentOrderItemId>ALV-XA05</SellerFulfillmentOrderItemId>
                <EstimatedShippingWeight>
                  <Unit>POUNDS</Unit>
                  <Value>0.020</Value>
                </EstimatedShippingWeight>
                <ShippingWeightCalculationMethod>Package</ShippingWeightCalculationMethod>
              </member>
            </FulfillmentPreviewItems>
          </member>
        </FulfillmentPreviewShipments>
        <UnfulfillablePreviewItems/>
        <MarketplaceId>ATVPDKIKX0DER</MarketplaceId>
      </member>
    </FulfillmentPreviews>
  </GetFulfillmentPreviewResult>
  <ResponseMetadata>
    <RequestId>10db643d-4ca5-4655-a58f-ed08adfcd4e7</RequestId>
  </ResponseMetadata>
</GetFulfillmentPreviewResponse>';

print($test_answer);
###
##
#
*/

} else {
	print("Hello World!");
	func_header_location("home.php");
}

?>
