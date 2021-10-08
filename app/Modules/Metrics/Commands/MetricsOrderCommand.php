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
//        $data_result = '';
//        /** @var SiteModel $site */
//        foreach (SiteModel::objects()->all() as $site) {
//            $data_result .= MetricsDataHelper::convertToMetricsWithParams('sites', '1', [
//               'code' => $site->code,
//            ]);
//        }
////        /** @var PaymentMethodModel $process */
////        foreach (PaymentMethodModel::objects()->all() as $process) {
////            $name = str_replace('/', '', (string)$process);
////            $data_result .= MetricsDataHelper::convertToMetricsWithParams('payments', '1', [
////                'name' => $name
////            ]);
////        }
//        /** @var DistributorModel $distributor */
//        foreach (DistributorModel::objects()->all() as $distributor) {
//            $name_distributor = trim(preg_replace('~\(.+\)~s', '', (string)$distributor), '.');
//            $data_result .= MetricsDataHelper::convertToMetricsWithParams('distributors', '1', [
//                'name' => $name_distributor,
//            ]);
//        }
//        /** @var CountryModel $country */
//        foreach (CountryModel::objects()->filter(['active' => 'Y']) as $country) {
//            $data_result .= MetricsDataHelper::convertToMetricsWithParams('countries', '1', [
//                'name' => (string)$country,
//            ]);
//        }
//        MetricsDataHelper::pushMetrics('base_data', "$data_result\n");

        $time = [
            '1' => new \DateTime('-1 days'),
            '7' => new \DateTime('-7 days'),
            '30' => new \DateTime('-30 days'),
            '90' => new \DateTime('-90 days')
        ];
        /** @var OrderModel $order */
        foreach (OrderModel::objects()->filter(['date__gte' => $start_date->getTimestamp()]) as $order) {
            $site = $order->site;
            foreach ($order->groups as $group_model) {
                foreach ($time as $period => $date_time) {
                    if ($order->date > $date_time->getTimestamp()) {
                        $ar_metrics[$period][] = [
                            'order_id' => $order->pk,
                            'dx_code' => $group_model->manufacturer->code,
                            'site' => $site->code,
                            'status' => $group_model->cb_status,
                            'zip_code' => $order->b_zipcode,
                            'country' => (string)$order->billing_country,
                            'sum' => $group_model->total_gross,
                            'payment_process' => (string)$order->payment_method,
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