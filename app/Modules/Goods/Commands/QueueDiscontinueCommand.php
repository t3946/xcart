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

        if ($message->body && $data = json_decode($message->body, true, 512, JSON_THROW_ON_ERROR)) {

            $dx = DistributorModel::objects()->get(['code' => $data['dx_code']]);

            $filter = [
                'forsale' => 'Y',
                'manufacturerid' => $dx->manufacturerid,
            ];

            if (isset($data['storefront'])) {
                $feed = SupplierFeedModel::objects()->limit(1)->get(['manufacturerid' => $dx->pk, 'storefront_id' => $data['storefront']]);
                $filter['sites__storefrontid'] = $data['storefront'];
            }


            if ($data['active_sku']) {
                $dis_count = 0;

                foreach(ProductModel::without_group()
                    ->filter($filter)
                    ->exclude(['productcode__in' => $data['active_sku']]) as $product) {
                    /** @var ProductModel $product */
                    $product->setAttributes(['forsale' => 'N', 'hash_product' => null]);
                    $product->save(['forsale', 'hash_product']);
                    $dis_count++;
                }

                echo "Discontinued {$data['dx_code']}: $dis_count products\n";
            }

            unset($data['active_sku']);
            print_r($data);

            if ($feed) {
                $feed->setAttributes([
                     'process_time' => $data['process_time'],
                     'last_update_time' => time(),
                     'last_update_period' => time() - $feed->last_update_time,
                     'feed_source' => $data['feed_source'],
                     'feed_source_date' => $data['feed_source_date'],
                     'last_update_items_count' => $data['products_in_feed']
                ]);
                $feed->save();
            }

            $message->ack();
        }
    }
}
