<?php

namespace Modules\Admin\Controllers;

use DateTime;
use Modules\Order\Helpers\OrderAnalyticsHelper;
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
        'AUTHORIZATION VOIDED RATE' => null,
        'PAID' => [OrderStatusModel::ORDER_STATUS_COMPLETED],
        'REFUNDED' => [
            OrderStatusModel::ORDER_STATUS_FULLY_REFUND,
            OrderStatusModel::ORDER_STATUS_PARTIAL_REFUND,
        ],
        'REFUNDED RATE' => null,
    ];
    private const TOTAL_COLUMN = 'Total / Up to date';

    private const ORDER_DATES_PERIODS = [
        'Last 24 hours' => 1,
        'Last 7 days' => 7,
        'Last 30 days' => 30,
    ];

    public function index(): void
    {
        $table_orders = [];
        $start_date = (new DateTime('-120 days'))->setTime(0, 0);

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
                if ($days === null) {
                    $table_orders[$type][$period]['total'] = OrderAnalyticsHelper::ordersTotalSum($filtered);
                    $table_orders[$type][$period]['count'] = count($filtered);
                } else {
                    $min_date = (new DateTime("-$days days"))->getTimestamp();
                    $orders_by_date = array_filter($filtered, static fn($order) => $order['date'] >= $min_date);
                    $table_orders[$type][$period]['total'] = OrderAnalyticsHelper::ordersTotalSum($orders_by_date);
                    $table_orders[$type][$period]['count'] = count($orders_by_date);
                }
            }
        }

        $orders_rates['AUTHORIZATION VOIDED RATE'] = self::geTypeRates(
            $table_orders,
            'AUTHORIZATION VOIDED',
            ['PAID', 'AUTHORIZATION VOIDED', 'REFUNDED'],
        );
        $orders_rates['REFUNDED RATE'] = self::geTypeRates(
            $table_orders,
            'REFUNDED',
            ['PAID'],
        );

        $average_daily_sales = round($table_orders['AUTHORIZED AND PAID']['Last 30 days']['total'] / 30, 2);

        $auth_orders = array_filter( $orders,
            static fn($order) => $order['cb_status'] === OrderStatusModel::ORDER_STATUS_AUTHORIZED
        );
        $authorized_outstanding = OrderAnalyticsHelper::ordersTotalSum($auth_orders);
        $authorized_outstanding_count = count($auth_orders);

        $last_orders = OrderModel::objects()->order(['-orderid'])->cache(10)->limit(10)->all();

        echo $this->renderInSmarty('admin/index.tpl', [
            'orders' => $table_orders,
            'last_orders' => $last_orders,
            'average_daily_sales' => $average_daily_sales,
            'authorized_outstanding' => $authorized_outstanding,
            'authorized_outstanding_count' => $authorized_outstanding_count,
            'orders_rates' => $orders_rates,
        ]);
    }

    private static function geTypeRates($table_orders, string $source_type, array $dest_types): array
    {
        $result = [];
        foreach (self::ORDER_DATES_PERIODS as $period => $days) {
            $rate = $table_orders[$source_type][$period]
                ? self::getRates(
                    $table_orders[$source_type][$period]['total'],
                    array_reduce($dest_types, static fn($c, $t) => $c + $table_orders[$t][$period]['total'])
                )
                : null;
            $result[$period] = $rate;
        }
        return $result;
    }

    private static function getRates(float $source, float $dest): float
    {
        return $dest ? round(($source / $dest) * 100, 2) : 0;
    }
}