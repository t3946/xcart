<?php

namespace Modules\Amazon\Commands;


use Modules\Amazon\Helpers\AmazonAWSHelper;
use Modules\Amazon\Helpers\AmazonOfferHelper;
use Modules\Amazon\Helpers\AmazonProductHelper;
use Modules\Amazon\Models\AmazonOfferCompetitorsModel;
use Modules\Amazon\Models\AmazonOfferModel;
use Modules\Amazon\Stores\AmazonPoolStore;
use Modules\Goods\Models\ProductModel;
use Modules\Goods\Models\UpdatedProductModel;
use Xcart\App\Commands\Command;

class GetPriceForAsinCommand extends Command
{

    public function handle($arguments = [])
    {
        $i = 0;

        func_backprocess_log('amazon_get_price_for_asin', $log = "Start processing\n");
        echo $log;

        $amzPool = new AmazonPoolStore();
        $client = $amzPool->getProductClientPackExt();

        while ($offers = AmazonOfferModel::objects()->filter(['product__productid__isnull' => true])->paginate(++$i, 20)->all()) {
            $products = [];

            $aASINs = array_values(array_map(function ($a) {
                return $a->ASIN;
            }, $offers));

            if (($response = $client->retrieveMyPriceForASIN($aASINs)) === null){
                $i--;
                continue;
            }

            foreach ($response as $price) {
                if ($price->sellerSku) {
                    $products[$price->asin] = $price->sellerSku;
                }
            }


            $diff = array_diff($aASINs, array_keys($products));

            if ($diff) {
                $log_text = 'ERROR in getPriceForASIN for ASIN\'s: ' . implode(', ', $diff) . "\n";
                func_backprocess_log('amazon_get_price_for_asin', $log_text);
            }

            foreach ($products as $asin => $sku) {
                /** @var ProductModel $p */
                if (!\in_array($asin, $diff, true) && $p = ProductModel::objects()->get(['productcode' => $sku])) {
                    $p->ASIN = $asin;
                    $p->save();
                }
            }
        }

        func_backprocess_log('amazon_get_price_for_asin', $log = "Processing complete.\n");
        echo $log;
    }
}