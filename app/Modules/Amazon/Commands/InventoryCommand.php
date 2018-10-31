<?php

namespace Modules\Amazon\Commands;


use Modules\Amazon\Helpers\AmazonFbaFeedHelper;
use Modules\Amazon\Models\AmazonInventoryQueueModel;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Commands\Command;
use Xcart\External_Marketplaces\Marketplaces\Amazon;

class InventoryCommand extends Command
{

    public function handle($arguments = [])
    {
        $log = " * * *  Cron started  * * * \n";
        func_backprocess_log('amazon_inventory', $log);
        echo $log;

        $start_time = new \DateTime('now');

        $client = new Amazon();

        $items = $pids = [];

        foreach (AmazonInventoryQueueModel::objects()->order(['type'])->limit(60000) as $queue) {
            /** @var ProductModel $product */
            $product = $queue->product;

            $pids[] = $product->productid;

            $prevent_selling = $product->amazon_fields->limit(1)->get()->prevent_selling_on_amazon;



            if ($product->isAmazonFBAEnabled() && ((int)$product->amazon_fba_avail > 0 || $product->getAmazonFBAStockReservedTransfers() > 0) &&
                !\in_array($prevent_selling, ['FBA', 'MFN'])) {

                $price = $product->getAmazonPrice();
                $min_price = $product->getZeroPrice();

                $item = [
                    'sku' => $product->productcode,
                    'channel' => 'AFN',
                    'price' => $price,
                    'min_price' => $min_price,
                    'max_price' => $price
                ];

            } else {
                $min_price = $product->getMinimumAmazonPrice();
                $item = [
                    'sku' => $product->productcode,
                    'channel' => 'MFN',
                    'quantity' => ($prevent_selling === 'MFN') ? 0 : $product->getAmazonQuantity(),
                    'latency' => $product->distributor->amazon_leadtime_to_ship,
                    'price' => $min_price,
                    'min_price' => $min_price,
                    'max_price' => $min_price
                ];
            }

            $items[] = $item;

            foreach ($product->missing_products as $missing) {
                $items[] = array_merge(
                    $item,
                    [
                        'sku' => $missing->missing_productcode
                    ]
                );
            }
        }

        if ($items) {

            $log_text = 'AMZ: tried to submit ' . \count($items) . ' items as inventory feed';
            func_backprocess_log('amazon_inventory', $log_text);

            $feed = AmazonFbaFeedHelper::encodeInventoryFeed($items);

            echo "INVENTORY pull\n\n";

            if ($client->submitInventoryFeed($feed)) {
                $feed = AmazonFbaFeedHelper::encodePriceFeed($items);

            } else {
                echo "Error INVENTORY pull\n";
            }
        }

        $str_time = (new \DateTime('now'))->diff($start_time)->format('%H:%I:%S');

        func_backprocess_log('amazon_inventory', $log = "Cron completed. Processing time: {$str_time}\n");
        echo $log;
    }
}