<?php


namespace Modules\Dashboard\Commands;


use Modules\Dashboard\Models\DashboardFilter;
use Modules\Dashboard\Models\DashboardFilterStatisticModel;
use Xcart\App\Commands\Command;

class DashboardFilterStatisticCommand extends Command
{

    public function handle($arguments = [])
    {
        $models = DashboardFilter::objects()->filter(['enabled' => true])->cache(60)->all();
        foreach ($models as $model) {
            $storage = $model->getSearchStorage();
            (new DashboardFilterStatisticModel[
                'filter_id' => $model->id,
                'hour' => (int)date('H'),
                'count' => $storage->getCashedCount()
            ])->save();
        }
    }
}