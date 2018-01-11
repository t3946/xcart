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
        if ($orderGroup && $feedContent = self::encodeOrderfulfillmentFeed($orderGroup, $trackNumberData)) {
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
            $shipDate = ($trackNumberData['shipping_date']) ? $trackNumberData['shipping_date']->format(DATE_ISO8601) : '';
            if ($details = $orderGroup->detail_models->all()) {
                foreach ($details as $detail) {
                    $items[] = ['Item' => [
                        'AmazonOrderItemCode' => $detail->AmazonOrderItemCode,
                        'MerchantFulfillmentItemID' => $detail->productcode,
                        'Quantity' => $detail->amount
                    ]];
                }
            }
            $data_0 = [
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
                        'FulfillmentData' => [
                            'CarrierName' => $trackNumberData['carrier'],
                            'ShippingMethod' => $trackNumberData['shipping_method'],
                            'ShipperTrackingNumber' => $trackNumberData['tracknum']
                        ]
                    ]
                ]
            ];
            $data_0['Message']['OrderFulfillment'] = array_merge($data_0['Message']['OrderFulfillment'], $items);
            $data = [
                '@attributes' => [
                    'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
                    'xsi:noNamespaceSchemaLocation' => 'amzn-envelope.xsd',
                ],
                $data_0
            ];
            return Xml::encode('AmazonEnvelope', $data, false);
        }
        return null;
    }

    public static function encodeInventoryFeed($messages)
    {
        $center = [
            'MFN' => 'DEFAULT',
            'AFN' => 'AMAZON_NA'
        ];

        $num = 0;
        $feeds = [];

        foreach ($messages as $message) {
            $inventory = [
                'SKU' => $message['sku'],
                'FulfillmentCenterID' => $center[$message['channel']]
            ];

            switch ($message['channel']) {
                case 'AFN':
                    $inventory['Lookup'] = 'FulfillmentNetwork';
                    break;
                case 'MFN':
                    $inventory['Quantity'] = $message['quantity'];
                    $inventory['FulfillmentLatency'] = $message['latency'];
                    break;
            }

            $inventory = array_merge($inventory, [
                'SwitchFulfillmentTo' => $message['channel']
            ]);

            $feeds[] = ['Message' => [
                'MessageID' => ++$num,
                'OperationType' => 'Update',
                'Inventory' => $inventory
            ]];
        }

        $data_0 = [
            'Header' => [
                'DocumentVersion' => '1.01',
                'MerchantIdentifier' => 'S3 Stores'
            ],
            'MessageType' => 'Inventory',
        ];

        $data = array_merge([
            '@attributes' => [
                'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
                'xsi:noNamespaceSchemaLocation' => 'amzn-envelope.xsd',
            ],
            $data_0,
        ], $feeds);

        return Xml::encode('AmazonEnvelope', $data, true);
    }

    public static function encodePriceFeed($messages)
    {
        $num = 0;
        $feeds = [];

        foreach ($messages as $message) {

            $feeds[] = ['Message' => [
                'MessageID' => ++$num,
                'Price' => [
                    'SKU' => $message['sku'],
                    'StandardPrice' =>
                        [
                            '@attributes' => ['currency' => 'USD'],
                            '@value' => floatval($message['price'])
                        ],
                    'MinimumSellerAllowedPrice' =>
                        [
                            '@attributes' => ['currency' => 'USD'],
                            '@value' => floatval($message['min_price'])
                        ]
                ]
            ]];
        }

        $data_0 = [
            'Header' => [
                'DocumentVersion' => '1.01',
                'MerchantIdentifier' => 'S3 Stores'
            ],
            'MessageType' => 'Price',
        ];

        $data = array_merge([
            '@attributes' => [
                'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
                'xsi:noNamespaceSchemaLocation' => 'amzn-envelope.xsd',
            ],
            $data_0,
        ], $feeds);

        return Xml::encode('AmazonEnvelope', $data, true);
    }

}