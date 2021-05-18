<?php


namespace Modules\Goods\Commands;


use DateInterval;
use DateTime;
use Modules\Distributor\Helpers\SchedulerHelper;
use Modules\Distributor\Models\DistributorModel;
use Modules\Distributor\Models\SupplierFeedModel;
use Xcart\App\Commands\Command;
use Xcart\App\Main\Xcart;

class QueueDxFeedCommand extends Command
{
    private const FEEDS_START_TIME = '00:00';
    private const TIME_FRAME_SEC = 32400;

    private static function getCode($feed)
    {
        $dx = $feed->distributor;
        $code = str_replace('-', '_', $dx->code);
        return $dx->feeds->count() === 1 ? $code : "{$code}__{$feed->storefront_id}";
    }

    public function getDxInfo($code, $sfId = null): array
    {
        /** @var DistributorModel $dx */
        /** @var SupplierFeedModel $feed */

        if ($dx = DistributorModel::objects()->get(['code' => $code])) {
            $feedData = [];
            if ($sfId !== null) {
                $filter = ['storefront_id' => $sfId];
            }
            foreach ($dx->feeds->filter($filter ?? []) as $feed) {
                $feedData[$feed->storefront_id] = [
                    'type' => ($type = $feed->getField('feed_type')) ? $type->toText() : null,
                    'feed_source' => $feed->feed_source,
                    'feed_file_name' => $feed->feed_file_name,
                    'dont_update_fields' => $feed->dont_update_fields,
                    'md5' => $feed->last_md5,
                    'enabled' => $feed->enabled,
                    'source_date' => $feed->feed_source_date,
                ];
            }
            return [
                'id' => $dx->manufacturerid,
                'name' => $dx->manufacturer,
                'prefix' => $dx->code . '-',
                'source' => $dx->url,
                'feeds' => $feedData
            ];
        }
        return [];
    }

    public function handle($arguments = [])
    {
        [$h, $m] = explode(':', self::FEEDS_START_TIME);
        $now = new DateTime();
        $start = (int)$now->format('H') < (int)$h ? new DateTime('yesterday') : new DateTime();
        $start->setTime($h, $m);
        if ($now->getTimestamp() >= $start->getTimestamp()) {
            $offset = (int)(($now->getTimestamp() - $start->getTimestamp()) / 60);
            if ($offset >= 0) {
                $feeds = SupplierFeedModel::objects()->filter(['enabled' => 'Y'])
                    ->order(['-process_time'])
                    ->cache($start->add(new DateInterval('P1D'))->getTimestamp() - $now->getTimestamp())
                    ->all();

                $times = array_map(static fn($f) => (int)$f->process_time, $feeds);

                $runForces = SupplierFeedModel::objects()->filter(['enabled' => 'Y', 'run_force' => true])->all();

                $schedule = SchedulerHelper::algorithm(self::TIME_FRAME_SEC, $times);
                $schedule = array_map(static fn($sh) => (int)($sh / 60), $schedule);

                $idsToLaunch = array_keys(array_filter($schedule, static fn($o) => $o === $offset));
                $nextRunning = array_map(static fn($id) => self::getCode($feeds[$id]), $idsToLaunch);
                $nextRunning = array_merge($nextRunning, array_map(static function ($feed) {
                    $feed->run_force = false;
                    $feed->save();
                    return self::getCode($feed);
                }, $runForces));

                $array = array_map(static fn($code) => self::getDxInfo($code), $nextRunning);

                array_map(static fn($info) => Xcart::app()->queue->send('feeds', json_encode($info, JSON_THROW_ON_ERROR)), $array);

            }
        }
    }
}