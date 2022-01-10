<?php


namespace Modules\Order\Helpers;


use AfterShip\Couriers;
use AfterShip\Trackings;
use Exception;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderTrackingModel;
use Modules\Order\Models\OrderTransactionModel;
use Modules\Payment\Gateways\Gateway;

class OrderTrackingHelper
{
    private static string $key = '88b94002-c64e-44a2-aa62-4052f33dadf0';

    public static function trackAfterShip(OrderTrackingModel $track): ?array
    {
        $order = $track->order_group->order;
        $tracking_numbers = new Trackings(self::$key);
        $tracking_info = [
            'slug' => $track->carrier->aftership_code ?: strtolower($track->carrier->carrier),
            'title' => $order->getOrderNumber(),
            'emails' => [$order->email, 'tn@s3stores.com'],
            'customer_name' => $order->firstname,
            'order_id' => $order->orderid
        ];
        if ($order->track_sms) {
            $tracking_info['smses'] = [$order->getPhoneNormalized()];
        }
        try {
            $response = $tracking_numbers->create($track->tracknum, $tracking_info);
        } catch (Exception $e) {

        }
        return $response ?? null;
    }

    public static function deleteAfterShip(OrderTrackingModel $track): void
    {
        $trackings = new Trackings(self::$key);

        try {
            $trackings->deleteById($track->aftership_id);
        } catch (Exception $e) {

        }
    }

    public static function getCouriers(): array
    {
        $couriers = new Couriers(self::$key);

        try {
            if ($arr = $couriers->all()) {
                $arr = $arr['data']['couriers'];
                usort($arr, static function ($item1, $item2) {
                    return $item1['name'] <=> $item2['name'];
                });
            }
        } catch (Exception $e) {
            $arr = [];
        }

        return $arr;
    }

    public static function trackStripe(OrderTrackingModel $tracking, OrderModel $order, OrderTransactionModel $transaction)
    {
        if ($gw = Gateway::getGateway($transaction->payment_method_model->processor)) {
            $address = $order->getAddressInfo();
            $gw->gateway->update([
                'paymentIntentReference' => $transaction->transaction_id,
                'shipping' => [
                    'address' => [
                        'city' => $order->s_city,
                        'country' => $order->s_country,
                        'line1' => $address['address'][0],
                        'line2' => $address['address'][1] ?? '',
                        'postal_code' => $order->s_zipcode,
                        'state' => $order->s_state,
                    ],
                    'name' => $order->s_firstname,
                    'carrier' => $tracking->carrier->carrier,
                    'phone' => $order->phone . ($order->phone_ext ? ' (' . $order->phone_ext .')'  : ''),
                    'tracking_number' => $tracking->tracknum
                ]
            ]);
        }
    }
}