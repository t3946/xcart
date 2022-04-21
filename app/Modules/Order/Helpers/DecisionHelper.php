<?php

namespace Modules\Order\Helpers;

use GuzzleHttp\Client;
use Modules\Forms\Models\TemplateModel;
use Modules\Order\Models\OrderModel;

class DecisionHelper
{
    public static function createDecision(OrderModel $order, TemplateModel $template): void
    {
        $order_id = $order->orderid;

        $type = $template->decision_type->slug;

        if (!$type) {
            return;
        }

        $options = [];

        switch ($type) {
            case 'street-address-required':
                [$address] = $order->getAddressInfo();
                $options = [
                    'problemAddress' => [
                        'street' => $address['address'][0],
                        'detailed' => $address['address'][1] ?? '',
                        'city' => $address['city'],
                        'zip' => $address['zip'],
                        'country' => [
                            'name' => $address['country']
                        ],
                        'state' => [
                            'state' => $address['state']
                        ]
                    ]
                ];
                break;
            case 'additional-shipping-charge':
                $options = [
                    'actualShippingCost' => $order->getRequiredShippingCharge(),
                    'shippingCostPaid' => $order->shipping_cost,
                    'additionalShippingCharge' => $order->getAdditionalShippingCharge(),
                ];
                break;
            case 'additional-information-required':
                $options = [
                    'totalShippingCharge' => $order->shipping_cost
                ];
                break;
        }

        $options = (object)$options;

        $client = new Client();

        $client->post('http://node-server:3001/api-client/user/decisions/create', [
            'json' => compact('order_id', 'type', 'options'),
            'headers' => [
                'Authorization' => 'Bearer mF_9.B5f-4.1JqM2'
            ]
        ]);
    }
}