<?php


namespace Modules\Goods\Commands;


use Modules\Distributor\Models\DistributorModel;
use Modules\Distributor\Models\SupplierFeedModel;
use Modules\Goods\Models\ProductModel;
use PhpAmqpLib\Message\AMQPMessage;
use Xcart\App\Commands\Command;
use Xcart\App\Main\Xcart;

class QueueDiscontinueCommand extends Command
{

    public function handle($arguments = [])
    {
        Xcart::app()->queue->consume('products_active', [$this, 'consume']);
    }

    public function consume(AMQPMessage $message): void
    {
        /** @var SupplierFeedModel $feed */
        /** @var DistributorModel $dx */

        if ($data = json_decode($message->body, true, 512, JSON_THROW_ON_ERROR)) {
            $dx = DistributorModel::objects()->get(['code' => $data['dx_code']]);
            $feed = SupplierFeedModel::objects()->limit(1)->get(['manufacturerid' => $dx->pk, 'storefront_id' => $data['storefront']]);
            $dis_count = ProductModel::without_group()
                ->filter([
                    'forsale' => 'Y',
                    'manufacturerid' => $dx->manufacturerid,
                    'sites__storefrontid' => $data['storefront']
                ])
                ->exclude(['productcode__in' => $data['active_sku'] ?? []])
                ->update(['forsale' => 'N']);

            $feed->update([
                'process_time' => $data['process_time'],
                'last_update_time' => time(),
                'last_update_period' => time() - $feed->last_update_time,
                'feed_source' => $data['feed_source'],
                'feed_source_date' => $data['feed_source_date'],
                'products_in_feed' => $data['products_in_feed']
            ]);

            echo "Discontinued {$data['dx_code']}: $dis_count products\n";

            $message->ack();
        }
    }
}
