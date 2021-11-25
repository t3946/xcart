<?php


namespace Modules\Goods\Commands;


use Modules\Distributor\Models\SupplierFeedModel;
use Xcart\App\Commands\Command;
use Xcart\App\Main\Xcart;

class QueueDxFeedCommand extends Command
{

    public function handle($arguments = [])
    {
        /** @var SupplierFeedModel[] $feeds */
        $feeds = SupplierFeedModel::objects()->filter(['enabled' => 'Y'])
            ->order(['-process_time'])
            ->all();

        array_map(
            static fn($feed) => Xcart::app()->queue->send(
                'feeds',
                json_encode([
                    'code' => $feed->getCode(),
                    'run_force' => false
                ], JSON_THROW_ON_ERROR),
                true
            ),
            $feeds
        );
    }
}