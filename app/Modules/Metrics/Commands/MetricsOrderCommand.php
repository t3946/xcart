<?php

namespace Modules\Metrics\Commands;

use DateTime;
use Modules\Admin\Controllers\CommonController;
use Modules\Admin\Statistic\OrderStatistic;
use Modules\Core\Models\CountryModel;
use Modules\Distributor\Models\DistributorModel;
use Modules\Metrics\Helpers\MetricsDataHelper;
use Modules\Order\Models\OrderDetailModel;
use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderModel;
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
        MetricsDataHelper::pushMetrics('base_data', "$data_result\n");
        /** @var CountryModel $country */
        foreach (CountryModel::objects()->filter(['active' => 'Y']) as $country) {
            $data_result .= MetricsDataHelper::convertToMetricsWithParams('countries', '1', [
                'name' => (string)$country,
            ]);
        }
        MetricsDataHelper::pushMetrics('base_data', "$data_result\n");

        $time = [
            '1' => new \DateTime('-1 days'),
            '7' => new \DateTime('-7 days'),
            '30' => new \DateTime('-30 days'),
            '90' => new \DateTime('-90 days')
        ];
        /** @var OrderModel $order */
        foreach (OrderModel::objects()->filter(['date__gte' => $start_date->getTimestamp()]) as $order) {
            $site = $order->site;
            $name_process = (string)$order->payment_method;
            $name_process = "$name_process [{$order->payment_method->pk}]";
            foreach ($order->groups as $group_model) {
                $name_dx = preg_replace('/[^a-zA-Z0-9\s]/iu', '', (string)$group_model->manufacturer);
                $name_dx = "[{$group_model->manufacturer->code}] $name_dx";
                foreach ($time as $period => $date_time) {
                    if ($order->date > $date_time->getTimestamp()) {
                        $ar_metrics[$period][] = [
                            'order_id' => $order->pk,
                            'dx_name' => $name_dx,
                            'site' => (string)$site,
                            'status' => $group_model->cb_status,
                            'zip_code' => $order->b_zipcode,
                            'country' => (string)$order->billing_country,
                            'sum' => $group_model->total_gross,
                            'payment_process' => $name_process,
                        ];
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
        $result = MetricsDataHelper::pushMetrics('order-info', "$str_result\n");
//        foreach (OrderModel::objects()->limit(1000)->order(['-date']) as $order) {
//            try {
//
//                $count_items = 0;
//                /** @var OrderDetailModel $detail_model */
//                foreach ($order->detail_models as $detail_model) {
//                    $count_items += $detail_model->amount;
//                }
//                $str_result .= MetricsDataHelper::convertToMetricsWithParams('orders_products', $count_items, [
//                    'order_id' => $order->pk,
//                ]);
//
//                $str_result .= MetricsDataHelper::convertToMetricsWithParams('orders_sum', $order->total, [
//                    'order_id' => $order->pk,
//                ]);
//
//                $str_result .= MetricsDataHelper::convertToMetricsWithParams('orders', '1', [
//                    'status' => $order->cb_status,
//                    'zip_code' => $order->b_zipcode,
//                    'country' => $order->b_country,
//                    'site' => $order->site->code,
//                    'order_id' => $order->pk,
//                    'payment_process' => (string)$order->payment_method,
//                ]);
//            } catch (\Throwable $exception) {
//                echo $exception->getMessage();
//            }
//        }
    }
}