<?php

namespace Modules\Metrics\Commands;

use Modules\Distributor\Models\DistributorModel;
use Modules\Metrics\Helpers\MetricsDataHelper;
use Xcart\App\Commands\Command;

class MetricsDistributorsCommand extends Command
{

    public function handle($arguments = [])
    {
        $str_result = '';
        $count_dx = DistributorModel::objects()->count();
        /** @var DistributorModel $distributor_model */
        foreach (DistributorModel::objects()->filter(['avail' => 'Y'])->all() as $distributor_model) {
            $name = preg_replace('/[^a-zA-Z0-9\s]/iu', '', (string)$distributor_model);
            $name = "[$distributor_model->code] $name";

            $str_result .= MetricsDataHelper::convertToMetricsWithParams('distributor_shipping_value', $distributor_model->free_shipping_on_orders_over_value, [
                'dx_code' => $name
            ]);
            $str_result .= MetricsDataHelper::convertToMetricsWithParams('distributor_max_lead_time', $distributor_model->dx_leadtime_to, [
                'dx_code' => $name
            ]);
        }
        $str_result .= MetricsDataHelper::convertToMetrics('distributor_count', $count_dx);
        if (!empty($str_result)) {
            MetricsDataHelper::pushMetrics('distributor-data', "$str_result\n");
        }
    }
}