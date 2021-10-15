<?php

namespace Modules\Metrics\Commands;

use DateTime;
use Modules\Core\Models\CountryModel;
use Modules\Distributor\Models\DistributorModel;
use Modules\Metrics\Helpers\MetricsDataHelper;
use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Payment\Models\PaymentMethodModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Commands\Command;

class MetricsOrderCommand extends Command
{

    public function handle($arguments = [])
    {
        $ar_metrics = [];
        $str_result = '';
        $start_date = (new DateTime('-90 days'))->setTime(0, 0);
        $data_result = '';
        /** @var SiteModel $site */
        foreach (SiteModel::objects()->all() as $site) {
            $data_result .= MetricsDataHelper::convertToMetricsWithParams('sites', '1', [
                'name' => (string)$site,
            ]);
        }
        /** @var PaymentMethodModel $process */
        foreach (PaymentMethodModel::objects()->all() as $process) {
            $name = (string)$process;
            $data_result .= MetricsDataHelper::convertToMetricsWithParams('payments', '1', [
                'name' => "$process [$process->pk]"
            ]);
        }
        /** @var DistributorModel $distributor */
        foreach (DistributorModel::objects()->all() as $distributor) {
            $name = preg_replace('/[^a-zA-Z0-9\s]/iu', '', (string)$distributor);
            $name = "[$distributor->code] $name";
            $data_result .= MetricsDataHelper::convertToMetricsWithParams('distributors', '1', [
                'name' => $name
            ]);
        }

        /** @var CountryModel $country */
        foreach (CountryModel::objects()->filter(['active' => 'Y']) as $country) {
            $data_result .= MetricsDataHelper::convertToMetricsWithParams('countries', '1', [
                'name' => (string)$country,
            ]);
        }
        MetricsDataHelper::pushMetrics('base_data', "$data_result\n");

        $time = [
            'Last 24 hours' => new DateTime('-1 days'),
            'Last 7 days' => new DateTime('-7 days'),
            'Last 30 days' => new DateTime('-30 days'),
            'Last 90 days' => new DateTime('-90 days')
        ];
        /** @var OrderModel $order */
        foreach (OrderModel::objects()->filter(['date__gte' => $start_date->getTimestamp()])->cache(300) as $order) {
            $site = $order->site;
            $name_process = (string)$order->payment_method_model;
            $name_process = "$name_process [{$order->payment_method_model->paymentid}]";

            foreach ($time as $period => $date_time) {
                if ($order->date > $date_time->getTimestamp()) {
                    foreach ($order->groups as $group_model) {
                        $name_dx = preg_replace('/[^a-zA-Z0-9\s]/iu', '', (string)$group_model->manufacturer);
                        $name_dx = "[{$group_model->manufacturer->code}] $name_dx";

                        if (in_array($group_model->cb_status, [
                                    OrderStatusModel::ORDER_STATUS_COMPLETED,
                                    OrderStatusModel::ORDER_STATUS_AUTHORIZED,
                                    OrderStatusModel::ORDER_STATUS_UNPAID_PO
                                ]) && !empty((float)$group_model->accounting_gross_0))
                        {

                            $str_result .= MetricsDataHelper::convertToMetricsWithParams(
                                'order_gross_profit',
                                $group_model->accounting_gross_5_profit, [
                                    'order_id' => $order->pk,
                                    'period' => $period,
                                    'dx_name' => $name_dx
                                ]
                            );
                            $str_result .= MetricsDataHelper::convertToMetricsWithParams('order_sales_volume',
                                $group_model->accounting_gross_0, [
                                    'order_id' => $order->pk,
                                    'period' => $period,
                                    'dx_name' => $name_dx
                                ]
                            );
                        }
                        $data_order = [
                            'order_id' => $order->pk,
                            'dx_name' => $name_dx,
                            'site' => (string)$site,
                            'status' => $group_model->cb_status,
                            'zip_code' => $order->b_zipcode,
                            'country' => (string)$order->billing_country,
                            'sum' => $group_model->total_gross,
                            'payment_process' => $name_process,
                        ];
                        $ar_metrics[$period][] = $data_order;
                    }
                }
            }
        }
        foreach ($ar_metrics as $period => $orders) {
            foreach ($orders as $order_info) {
                $str_result .= MetricsDataHelper::convertToMetricsWithParams(
                    'orders',
                    $order_info['sum'],
                    array_merge($order_info, ['period' => $period])
                );
            }
        }

        foreach ($time as $period => $date_time) {
            /* Получение медианного среднего чека за определённый период */
            $orders_by_period = OrderGroupModel::objects()
                ->filter([
                    'order__date__gte' => $date_time->getTimestamp(),
                    'cb_status__in' => [OrderStatusModel::ORDER_STATUS_COMPLETED, OrderStatusModel::ORDER_STATUS_AUTHORIZED, OrderStatusModel::ORDER_STATUS_UNPAID_PO]
                ])
                ->group(['orderid'])->order(['order__total']);
            $order_list = $orders_by_period->all();

            $medium_order_counter = round($orders_by_period->count() / 2);
            /** @var OrderGroupModel $median_order */
            $median_order = $order_list[$medium_order_counter - 1];
            if (!empty($median_order)) {
                $str_result .= MetricsDataHelper::convertToMetricsWithParams('median_order_amount', $median_order->order->total, [
                    'period' => $period,
                    'order_id' => $median_order->orderid
                ]);
            }

            /* Получение среднего кол-ва товаров по заказам */
            $amount_items = 0;
            /** @var OrderGroupModel $order */
            foreach ($order_list as $order) {
                foreach ($order->detail_models as $detail_model) {
                    $amount_items += $detail_model->amount;
                }
            }
            if (!empty($amount_items)) {
                $str_result .= MetricsDataHelper::convertToMetricsWithParams('average_amount_items', $amount_items, [
                    'period' => $period
                ]);
            }
        }
        $result = MetricsDataHelper::pushMetrics('order-info', "$str_result\n");
    }
}