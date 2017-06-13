<?php

namespace Modules\Amazon\Helpers;


use DateTime;
use FBAOutboundServiceMWS_Model_FulfillmentShipment;
use FBAOutboundServiceMWS_Model_FulfillmentShipmentList;
use FBAOutboundServiceMWS_Model_FulfillmentShipmentPackage;
use FBAOutboundServiceMWS_Model_FulfillmentShipmentPackageList;
use FBAOutboundServiceMWS_Model_GetFulfillmentOrderResponse;
use FBAOutboundServiceMWS_Model_GetFulfillmentOrderResult;

class AmazonFbaOutboundHelper
{
    public static function getFulfillmentOrderTrackingNumbers(FBAOutboundServiceMWS_Model_GetFulfillmentOrderResponse $cpResult)
    {
        $trackings = [];
        /** @var FBAOutboundServiceMWS_Model_GetFulfillmentOrderResult $ffResult */
        $ffResult = $cpResult->getGetFulfillmentOrderResult();
        if (!empty($ffResult)) {
            /** @var FBAOutboundServiceMWS_Model_FulfillmentShipmentList $ffShipment */
            $ffShipment = $ffResult->getFulfillmentShipment();
            /** @var FBAOutboundServiceMWS_Model_FulfillmentShipment[] $afShipment */
            if ($ffShipment && $afShipment = $ffShipment->getmember()){
                foreach ($afShipment as $shipment){
                    /** @var FBAOutboundServiceMWS_Model_FulfillmentShipmentPackageList $package */
                    $package = $shipment->getFulfillmentShipmentPackage();
                    /** @var FBAOutboundServiceMWS_Model_FulfillmentShipmentPackage[] $aPackage */
                    if ($package && $aPackage = $package->getmember()) {
                        foreach ($aPackage as $pack){
                            $trackings[] =[
                                'track_number' => $pack->getTrackingNumber(),
                                'shipping_date' => DateTime::createFromFormat(DateTime::ISO8601, $shipment->getShippingDateTime()),
                                'carrier_code' => $pack->getCarrierCode()
                            ];
                        }
                    }
                }
            }
        }
        return $trackings;
    }
}