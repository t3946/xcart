<?php

namespace Modules\Amazon\Helpers;


use CaponicaAmazonMwsComplete\ClientPack\MwsFeedAndReportClientPack;
use DateTime;
use Modules\Amazon\Stores\AmazonPoolStore;
use Modules\Order\Models\OrderGroupModel;
use Modules\Shipping\Models\TrackingLinksCarrierModel;
use Modules\Shipping\Models\TrackingLinksModel;
use Xcart\App\Helpers\Xml;

class AmazonFbaFeedHelper
{
    public static function sendTrackingCodeToAmazon(OrderGroupModel $orderGroup, array $trackNumberData)
    {
        $result = null;
        $trackNumberData['shipping_date'] = DateTime::createFromFormat('m/d/Y', $trackNumberData['ship_date']);
        $cm = TrackingLinksCarrierModel::objects()->get(['carrier_id' => $trackNumberData['carrier_id']]);
        $trackNumberData['carrier'] = $cm ? $cm->carrier : '';
        if (isset($trackNumberData['linkid']) && $trackNumberData['linkid']) {
            $lm = TrackingLinksModel::objects()->get(['linkid' => $trackNumberData['linkid']]);
            $trackNumberData['shipping_method'] = $lm ? $lm->shipping : '';
        }
        if ($orderGroup && $feedContent = self::encodeOrderfulfillmentFeed($orderGroup, $trackNumberData)){
            func_dump($feedContent);
            $feedHandle = @fopen('php://temp', 'rw+');
            fwrite($feedHandle, $feedContent);
            rewind($feedHandle);
            $amzPool = new AmazonPoolStore();
            $result = $amzPool->getFeedAndReportClientPack()
                ->callSubmitFeed(MwsFeedAndReportClientPack::FEED_TYPE_ORDER_FULFILLMENT, $feedHandle)
                ->getSubmitFeedResult();
            @fclose($feedHandle);
        }
        return $result;
    }

    public static function encodeOrderfulfillmentFeed(OrderGroupModel $orderGroup, array $trackNumberData)
    {
        $items = [];
        if (!empty($trackNumberData)) {
            $orderModel = $orderGroup->order;
            $shipDate = ($trackNumberData['shipping_date']) ? $trackNumberData['shipping_date']->format(DATE_ISO8601): '';
            if ($details = $orderGroup->getOrderDetailModels()) {
                $items['CarrierName'] = $trackNumberData['carrier'];
                $items['ShippingMethod'] = $trackNumberData['shipping_method'];
                $items['ShipperTrackingNumber'] = $trackNumberData['tracknum'];
                /*foreach ($details as $detail) {
                    $product = $detail->product_model;
                    $items[] = ['Item' => [
                        'AmazonOrderItemCode' => $product->productcode,
                        'MerchantFulfillmentItemID' => $product->productcode,
                        'Quantity' => $detail->amount
                    ]];
                }*/
            }
            $data = [
                '@attributes' => [
                    'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
                    'xsi:noNamespaceSchemaLocation' => 'amzn-envelope.xsd',
                ]
            ];
            $data[] = [
                'Header' => [
                    'DocumentVersion' => '1.01',
                    'MerchantIdentifier' => 'S3 Stores'
                ],
                'MessageType' => 'OrderFulfillment',
                'Message' => [
                    'MessageID' => '1',
                    'OrderFulfillment' => [
                        'AmazonOrderID' => $orderModel->amazonorderid,
                        'MerchantFulfillmentID' => $orderModel->orderid,
                        'FulfillmentDate' => $shipDate,
                        'FulfillmentData' => $items
                    ]
                ]
            ];
            return Xml::encode('AmazonEnvelope', $data, false);
        }
        return null;
    }
}