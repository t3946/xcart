<?php


namespace Modules\Distributor\Controllers\Api;


use Cron\CronExpression;
use DateInterval;
use DateTime;
use Modules\Distributor\Helpers\SchedulerHelper;
use Modules\Distributor\Models\DistributorModel;
use Modules\Distributor\Models\SupplierFeedModel;
use Xcart\App\Controller\Controller;

class ApiDxController extends Controller
{
    private const FEEDS_START_TIME = '00:00';
    private const TIME_FRAME_SEC = 32400;

    public function getDxInfo($code, $sfId = null): void
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
                    'type' => ($type = $feed->getField('type')) ? $type->toText() : null,
                    'feed_source' => $feed->feed_source,
                    'feed_file_name' => $feed->feed_file_name,
                    'dont_update_fields' => $feed->dont_update_fields,
                    'md5' => $feed->last_md5,
                    'enabled' => $feed->enabled,
                    'source_date' => $feed->feed_source_date,
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

    private static function getCode($feed)
    {
        $dx = $feed->distributor;
        $code = str_replace('-', '_', $dx->code);
        return $dx->feeds->count() === 1 ? $code : "{$code}__{$feed->storefront_id}";
    }

    public function scheduleDynamic(): void
    {
        $feeds = SupplierFeedModel::objects()->filter(['schedule__isnull' => false, 'enabled' => 'Y'])->order(['-process_time'])->all();
        $times = array_map(static fn($f) => (int)$f->process_time, $feeds);
        $runForces = array_filter($feeds, static fn($f) => $f->run_force === true);

        $schedule = SchedulerHelper::algorithm(self::TIME_FRAME_SEC, $times);

        [$h, $m] = explode(':', self::FEEDS_START_TIME);

        $now = new DateTime();
        $start = (int)$now->format('H') < (int)$h ? new DateTime('yesterday') : new DateTime();
        $start->setTime($h, $m);
        $offset = $now->getTimestamp() - $start->getTimestamp();
        if ($offset >= 0) {
            $idsToLaunch = array_keys(array_filter($schedule, static fn($o) => $o === $offset));
            $nextRunning = array_map(static fn($id) => self::getCode($feeds[$id]), $idsToLaunch);
            $nextRunning = array_merge($nextRunning, array_map(static function ($feed) {
                $feed->run_force = false;
                $feed->save();
                return self::getCode($feed);
            }, $runForces));
            $this->jsonResponse($nextRunning);
        }
    }

    public function scheduleDynamic2(): void
    {
        $feeds = SupplierFeedModel::objects()->filter(['schedule__isnull' => false, 'enabled' => 'Y'])->order(['-process_time'])->all();
        $times = array_map(static fn($f) => (int)$f->process_time, $feeds);
        $runForces = array_filter($feeds, static fn($f) => $f->run_force === true);

        $schedule = SchedulerHelper::algorithm(self::TIME_FRAME_SEC, $times);
        $schedule = array_map(static fn($sh) => $sh / 60, $schedule);

        [$h, $m] = explode(':', self::FEEDS_START_TIME);

        $now = new DateTime();
        $start = (int)$now->format('H') < (int)$h ? new DateTime('yesterday') : new DateTime();
        $start->setTime($h, $m);
        $offset = $now->getTimestamp() - $start->getTimestamp();
        $offset /= 60;
        echo $offset;
        var_dump($schedule);
        if ($offset >= 0) {
            $idsToLaunch = array_keys(array_filter($schedule, static fn($o) => $o === $offset));
            $nextRunning = array_map(static fn($id) => self::getCode($feeds[$id]), $idsToLaunch);
            $nextRunning = array_merge($nextRunning, array_map(static function ($feed) {
                $feed->run_force = false;
                $feed->save();
                return self::getCode($feed);
            }, $runForces));
            $this->jsonResponse($nextRunning);
        }
    }
}