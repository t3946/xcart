<?php

namespace Modules\Amazon\Helpers;


use CaponicaAmazonMwsComplete\ClientPack\MwsFeedAndReportClientPack;
use DateTime;
use DateTimeZone;
use Modules\Amazon\Stores\AmazonPoolStore;
use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderTrackingModel;
use Modules\Shipping\Models\TrackingLinksCarrierModel;
use Modules\Shipping\Models\TrackingLinksModel;
use Xcart\App\Helpers\Xml;

class AmazonFbaFeedHelper
{
    public static function sendTrackingCodeToAmazon(OrderTrackingModel $trackNumberData)
    {
        $result = null;

        if ($feedContent = self::encodeOrderfulfillmentFeed($trackNumberData)) {
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

    public static function encodeOrderfulfillmentFeed(OrderTrackingModel $trackNumberData)
    {
        $items = [];
        if ($trackNumberData) {
            $orderModel = $trackNumberData->order_group->order;
            $date = DateTime::createFromFormat('Y-m-d H:i:s', "{$trackNumberData->shipping_date} 00:00:00", new DateTimeZone('EST'));
            $shipDate = $date->format(DATE_W3C);

            $cm = $trackNumberData->carrier;

            $carrier = $cm ? $cm->carrier : '';

            $lm = $trackNumberData->link;
            $shipping_method = $lm ? $lm->shipping : '';

            $data_0 = [
                'Header' => [
                    'DocumentVersion' => '1.01',
                    'MerchantIdentifier' => AmazonPoolStore::MERCHANT_ID
                ],
                'MessageType' => 'OrderFulfillment',
                'Message' => [
                    'MessageID' => '1',
                    'OrderFulfillment' => [
                        'AmazonOrderID' => $orderModel->amazonorderid,
                        'MerchantFulfillmentID' => $orderModel->orderid,
                        'FulfillmentDate' => $shipDate,
                        'FulfillmentData' => [
                            'CarrierName' => $carrier,
                            'ShippingMethod' => $shipping_method,
                            'ShipperTrackingNumber' => $trackNumberData->tracknum
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

            $inventory = \array_merge($inventory, [
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
                        ],
                    'MaximumSellerAllowedPrice' =>
                        [
                            '@attributes' => ['currency' => 'USD'],
                            '@value' => floatval($message['max_price'])
                        ],
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