<?php

namespace Modules\Amazon\Helpers;


use Modules\Order\Models\OrderDetailModel;
use Modules\Order\Models\OrderGroupModel;
use Xcart\App\Helpers\Xml;

class AmazonFbaFeedHelper
{
    public static function sendTrackingCodeToAmazon(OrderGroupModel $orderGroup, array $trackNumberData)
    {
        self::encodeOrderfulfillmentFeed($orderGroup, $trackNumberData);
    }

    public static function encodeOrderfulfillmentFeed(OrderGroupModel $orderGroup, array $trackNumberData)
    {
        $items = [];
        if ($details = $orderGroup->getOrderDetailModels()){
            $items['CarrierCode'] = $trackNumberData['carrier'];
            $items['ShippingMethod'] = $trackNumberData['shipping_method'];
            $items['ShipperTrackingNumber'] = $trackNumberData['track_number'];
            foreach ($details as $detail) {
                $product = $detail->product_model;
                $items[] = ['Item' => [
                    'MerchantOrderItemID' => $product->productcode,
                    'MerchantFulfillmentItemID' => $product->productcode,
                    'Quantity' => $detail->amount
                ]];
            }
        }
        $data = [
            'Header' => [
                'DocumentVersion' => '1.01',
                'MerchantIdentifier' => 'S3 Stores'
            ],
            'MessageType' => 'OrderFulfillment',
            'Message' => [
                'MessageID' => '1',
                'OrderFulfillment' => [
                    'MerchantOrderID' => 'Orderid',
                    'MerchantFulfillmentID' => 'MerchantFulfillmentID',
                    'FulfillmentDate' => '2002-05-01T15:36:33-08:00',
                    'FulfillmentData' => $items
                ]
            ]
        ];
        return Xml::encode('AmazonEnvelope', $data, true);
    }
}