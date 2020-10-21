<?php


namespace Modules\Distributor\Controllers\Api;


use Cron\CronExpression;
use Modules\Distributor\Models\DistributorModel;
use Modules\Distributor\Models\SupplierFeedModel;
use Xcart\App\Controller\Controller;

class ApiDxController extends Controller
{
    public function getDxInfo($code): void
    {
        /** @var DistributorModel $dx */
        /** @var SupplierFeedModel $feed */

        if ($dx = DistributorModel::objects()->get(['code' => $code])) {
            $feedData = [];
            foreach ($dx->feeds as $feed) {
                $feedData[$feed->storefront_id] = [
                    'type' => ($type = $feed->getField('type')) ? $type->toText() : null,
                    'feed_source' => $feed->feed_source,
                    'feed_file_name' => $feed->feed_file_name,
                    'dont_update_fields' => json_decode($feed->dont_update_fields, true),
                    'md5' => $feed->last_md5,
                    'enabled' => $feed->enabled,
                    'source_date' => $feed->feed_source_date,
                    'schedule' => $feed->schedule
                ];
            }
            $this->jsonResponse([
                'id' => $dx->manufacturerid,
                'name' => $dx->manufacturer,
                'prefix' => $dx->code . '-',
                'source' => $dx->url,
                'feeds' => $feedData
            ]);
        }
    }

    public function schedule(): void
    {
        $nextRunning = [];

        foreach (SupplierFeedModel::objects()->filter(['schedule__isnull' => false, 'enabled' => 'Y']) as $feed) {
            $schedule = trim($feed->run_force ? "* * * * *" : $feed->schedule);
            if (CronExpression::isValidExpression($schedule) &&
                CronExpression::factory($schedule)->isDue()) {
                $nextRunning[] = $feed;
                $feed->run_force = false;
                $feed->save();
            }
        }
        if ($nextRunning) {
            $nextRunning = array_map(static function ($feed) {
                $dx = $feed->distributor;
                $code = str_replace('-', '_', $dx->code);
                return $dx->feeds->count() === 1 ? $code : "{$code}__{$feed->storefront_id}";
            }, $nextRunning);
            $this->jsonResponse($nextRunning);
        }
    }
}