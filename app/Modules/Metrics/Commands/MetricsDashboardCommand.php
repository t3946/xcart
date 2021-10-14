<?php

namespace Modules\Metrics\Commands;

use Modules\Dashboard\Models\DashboardFilter;
use Modules\Dashboard\Models\DashboardFilterStatisticModel;
use Modules\Metrics\Helpers\MetricsDataHelper;
use Xcart\App\Commands\Command;

class MetricsDashboardCommand extends Command
{

    public function handle($arguments = [])
    {
        $str_metrics = '';
        $models = DashboardFilter::objects()->filter(['enabled' => true])->cache(60)->all();
        /** @var DashboardFilter $model */
        foreach ($models as $model) {
            $storage = $model->getSearchStorage();
            $str_metrics .= MetricsDataHelper::convertToMetricsWithParams('dashboards', $storage->getCashedCount(), [
                'name' => (string)$model,
                'group' => (string)$model->group
            ]);
        }
        MetricsDataHelper::pushMetrics('dashboards', $str_metrics);
    }
}