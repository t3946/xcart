<?php

namespace Modules\Admin\Controllers;

use DateTime;
use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;

class CommonController extends BackendController
{
    private const ORDER_STATS_TYPES = [
        'ALL ORDERS INCLUDING ABANDONED' => [],
        'AUTHORIZED AND PAID' => [
            OrderStatusModel::ORDER_STATUS_AUTHORIZED,
            OrderStatusModel::ORDER_STATUS_COMPLETED,
        ],
        'AUTHORIZED' => [OrderStatusModel::ORDER_STATUS_AUTHORIZED],
        'AUTHORIZATION VOIDED' => [OrderStatusModel::ORDER_STATUS_CANCELED],
        'PAID' => [OrderStatusModel::ORDER_STATUS_COMPLETED],
        'REFUNDED' => [
            OrderStatusModel::ORDER_STATUS_FULLY_REFUND,
            OrderStatusModel::ORDER_STATUS_PARTIAL_REFUND,
        ],
    ];
    private const TOTAL_COLUMN = 'Total / Up to date';

    private const ORDER_DATES_PERIODS = [
        'Last 24 hours' => 1,
        'Last 7 days' => 7,
        'Last 30 days' => 30,
        'Total / Up to date' => null
    ];

    public function index(): void
    {
        $table_orders = [];
        $start_date = (new DateTime('-360 days'))->setTime(0, 0);

        $orders = OrderGroupModel::objects()
            ->filter(['order__date__gte' => $start_date->getTimestamp(),])
            ->cache(300)
            ->group(['orderid'])
            ->valuesList(['cb_status', 'total_gross', 'order__date']);

        foreach (self::ORDER_STATS_TYPES as $type => $statuses) {
            $filtered = empty($statuses)
                ? $orders
                : array_filter($orders, static fn($order) => in_array($order['cb_status'], $statuses, true));

            foreach (self::ORDER_DATES_PERIODS as $period => $days) {

                if ($days !== null) {
                    $min_date = (new DateTime("-$days days"))->setTime(0, 0)->getTimestamp();
                    $table_orders[$type][$period] = array_filter($filtered, static fn($order) => $order['date'] >= $min_date);
                } else {
                    $table_orders[$type][$period] = $filtered;
                }

                if ($type === 'AUTHORIZATION VOIDED') {
                    $table_orders['AUTHORIZATION VOIDED RATE'][$period] = self::getRates(
                        $table_orders['AUTHORIZATION VOIDED'][$period],
                        array_merge($table_orders['PAID'][$period], $table_orders['AUTHORIZATION VOIDED'][$period], $table_orders['REFUNDED'][$period])
                    );
                    if ($period === self::TOTAL_COLUMN){
                        $table_orders['AUTHORIZATION VOIDED RATE'][$period] = null;
                    }
                }
                if ($type === 'REFUNDED') {
                    $table_orders['REFUND RATE'][$period] = self::getRates($table_orders['REFUNDED'][$period], $table_orders['PAID'][$period]);
                    if ($period === self::TOTAL_COLUMN){
                        $table_orders['REFUND RATE'][$period] = null;
                    }
                }

                if ($period === self::TOTAL_COLUMN && $type !== 'AUTHORIZED') {
                    $table_orders[$type][$period] = null;
                }
            }
        }

        $last_orders = OrderModel::objects()->order(['-orderid'])->cache(10)->limit(10)->all();
        $last_orders = array_reverse($last_orders);

        echo $this->renderInSmarty('admin/index.tpl', ['orders' => $table_orders, 'last_orders' => $last_orders]);
    }

    private static function getRates($source, $dest): string
    {
        return number_format($dest ? round((count($source) / count($dest)) * 100, 2) : 0, 2);
    }
}