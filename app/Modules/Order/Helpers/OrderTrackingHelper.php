<?php


namespace Modules\Order\Helpers;


use AfterShip\Couriers;
use AfterShip\Trackings;
use Exception;
use Modules\Order\Models\OrderTrackingModel;

class OrderTrackingHelper
{
    private static $key = '88b94002-c64e-44a2-aa62-4052f33dadf0';

    public static function trackAfterShip(OrderTrackingModel $track): ?array
    {
        $order = $track->order_group->order;
        $trackings = new Trackings(self::$key);
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
            $response = $trackings->create($track->tracknum, $tracking_info);
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
}