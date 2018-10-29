<?php

namespace Modules\Amazon\Commands;


use FBAInventoryServiceMWS_Model_InventorySupply;
use FBAInventoryServiceMWS_Model_ListInventorySupplyResponse;
use Modules\Amazon\Helpers\AmazonOfferHelper;
use Modules\Amazon\Models\AmazonOfferModel;
use Modules\Amazon\Stores\AmazonPoolStore;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Commands\Command;

class FBATotalInventoryCommand extends Command
{

    public function handle($arguments = [])
    {
        $log = " * * *  Cron started  * * * \n";
        func_backprocess_log('amazon_fba_total_inventory', $log);
        echo $log;

        $start_time = new \DateTime('now');

        $amzPool = new AmazonPoolStore();
        $client = $amzPool->getFbaInventoryClientPack();

        $max_products = 50;
        $i = 0;

        while ($aProductsBatch = ProductModel::objects()->filter([
            'amazon_enabled' => 'Y',
        ])
            ->paginate(++$i, $max_products)
            ->all()) {

            $aSKUs = array_map(function ($a) {
                return $a->productcode;
            }, $aProductsBatch);

            try {
                /** @var FBAInventoryServiceMWS_Model_ListInventorySupplyResponse $inventory */
                $inventory = $client->callGetListInventory($aSKUs);

                if ($res = $inventory->getListInventorySupplyResult()->getInventorySupplyList()->getmember()) {
                    /** @var FBAInventoryServiceMWS_Model_InventorySupply $item */
                    foreach ($res as $item) {
                        $totalSupplyQuantity = $item->getTotalSupplyQuantity();
                        $inStockSupplyQuantity = $item->getInStockSupplyQuantity();
                        $sASIN = $item->getASIN();
                        $sFNSKU = $item->getFNSKU();
                        if (!$item->isSetASIN()) {
                            continue;
                        }
                        /** @var AmazonOfferModel $offer */
                        [$offer] = AmazonOfferModel::objects()->getOrNew(['ASIN' => $sASIN]);
                        $offer->setAttributes([
                            'fba_total_supply' => $totalSupplyQuantity,
                            'fba_instock_supply' => $inStockSupplyQuantity,
                            'FNSKU' => $sFNSKU,
                        ]);
                        $offer->save();
                        func_backprocess_log('amazon_fba_total_inventory', "ASIN fba stock update {$sASIN}");
                    }
                }

            } catch (\Exception $e) {
                $log_text = 'ERROR in amazon_fba_inventory cron for SKU\'s: ' . implode(', ', $aSKUs) . "\n";
                func_backprocess_log('amazon_fba_total_inventory', $log_text . $e->getMessage());
            }

        }

        $str_time = (new \DateTime('now'))->diff($start_time)->format('%H:%I:%S');

        func_backprocess_log('amazon_fba_inventory', $log = "Cron completed. Processing time: {$str_time}\n");
        echo $log;
    }
}