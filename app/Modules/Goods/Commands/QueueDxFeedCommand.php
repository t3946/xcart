<?php


namespace Modules\Goods\Commands;


use DateInterval;
use DateTime;
use Modules\Distributor\Helpers\SchedulerHelper;
use Modules\Distributor\Models\SupplierFeedModel;
use Xcart\App\Commands\Command;
use Xcart\App\Main\Xcart;

class QueueDxFeedCommand extends Command
{
    private const FEEDS_START_TIME = '00:00';
    private const TIME_FRAME_SEC = 42400;

    private static function getCode($feed)
    {
        $dx = $feed->distributor;
        $code = str_replace('-', '_', $dx->code);
        return $dx->feeds->count() === 1 ? $code : "{$code}__{$feed->storefront_id}";
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

                $schedule = array_map(static fn($sh) => (int)($sh / 60), SchedulerHelper::algorithm(self::TIME_FRAME_SEC, $times));

                $idsToLaunch = array_keys(array_filter($schedule, static fn($o) => $o === $offset));

                $nextRunning = array_map(static fn($id) => ['code' => self::getCode($feeds[$id]), 'run_force' => false], $idsToLaunch);

                $runForces = SupplierFeedModel::objects()->filter(['enabled' => 'Y', 'run_force' => true])->all();

                $nextRunning = array_merge($nextRunning, array_map(static function ($feed) {
                    $feed->run_force = false;
                    $feed->save();
                    return ['code' => self::getCode($feed), 'run_force' => true];
                }, $runForces));

                array_map(static fn($info) => Xcart::app()->queue->send('feeds', json_encode($info, JSON_THROW_ON_ERROR)), $nextRunning);

            }
        }
    }
}