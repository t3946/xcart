<?php

namespace Modules\Admin\Controllers;

use DateTime;
use Modules\Admin\Statistic\OrderStatistic;
use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;

class CommonController extends BackendController
{
    private const ORDER_STATS_TYPES = [
        'ALL ORDERS INCLUDING ABANDONED' => [],
        'ABANDONED' => [
            OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP1,
            OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP2,
            OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP3,
            OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP4,
            OrderStatusModel::ORDER_STATUS_UNPAID,
            OrderStatusModel::ORDER_STATUS_NOT_FINISHED,
            OrderStatusModel::ORDER_STATUS_FAILED,
        ],
        'ABANDONED RATE' => null,
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
            $stat = new OrderStatistic($orders, $statuses);
            foreach (self::ORDER_DATES_PERIODS as $period => $days) {
                $table_orders[$type][$period] = $stat->setPeriod($days);
            }
        }

        $orders_rates['ABANDONED RATE'] = self::geTypeRates(
            $table_orders,
            'ABANDONED',
            ['ALL ORDERS INCLUDING ABANDONED']
        );

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

        $average_daily_sales = round($table_orders['AUTHORIZED AND PAID']['Last 30 days']->getTotal() / 30, 2);

        $last_orders = OrderModel::objects()->order(['-orderid'])->cache(10)->limit(20)->all();

        echo $this->renderInSmarty('admin/index.tpl', [
            'orders' => $table_orders,
            'last_orders' => $last_orders,
            'average_daily_sales' => $average_daily_sales,
            'authorized_outstanding' => $table_orders['AUTHORIZED']['Last 30 days']->setPeriod()->getTotal(),
            'authorized_outstanding_count' => $table_orders['AUTHORIZED']['Last 30 days']->setPeriod()->getCount(),
            'orders_rates' => $orders_rates,
        ]);
    }

    private static function geTypeRates($table_orders, string $source_type, array $dest_types): array
    {
        $result = [];
        foreach (self::ORDER_DATES_PERIODS as $period => $days) {
            $rate = $table_orders[$source_type][$period]
                ? [
                    'total' => self::getRates(
                        $table_orders[$source_type][$period]->getTotal(),
                        array_reduce($dest_types, static fn($c, $t) => $c + $table_orders[$t][$period]->getTotal())
                    ),
                    'count' => self::getRates(
                        $table_orders[$source_type][$period]->getCount(),
                        array_reduce($dest_types, static fn($c, $t) => $c + $table_orders[$t][$period]->getCount())
                    ),
                ]
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