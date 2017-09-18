<?php

namespace Xcart\External_Marketplaces\Marketplaces;

use CaponicaAmazonMwsComplete\ClientPack\MwsFeedAndReportClientPack;
use Modules\Amazon\Helpers\AmazonFbaFeedHelper;
use Modules\Amazon\Stores\AmazonPoolStore;
use Modules\Product\Models\ProductModel;
use Modules\Product\Models\UpdatedProductModel;
use Xcart\External_Marketplaces\StoreFrontMarketPlace;

class Amazon extends StoreFrontMarketPlace
{
    public function addProductToBatch($queue, $googleOneRow = "", $sExtraLog = "N")
    {
        $result = false;
        if ($this->checkMarketplaceRestrictions($queue)) {
            if ($queue->type == "2" || $queue->type == "1,2" || $queue->type == "1") {
                $this->aInventory[] = ['queue' => $queue];
                $this->iInventoryBatchCount++;
                $result = true;
            }
        } else {
            $queue->mask &= ~intval($this->getExternalMarketPlaceEntity()->mask);
            $queue->save();
        }
        return $result;
    }

    public function submitInventoryBatch($debug_mode = 'N', $extra_log = 'N')
    {

        $items = [];

        if ($products = $this->getInventory()) {

            /** @var ProductModel[] $products */
            foreach ($products as $queue) {

                $product = $queue['queue']->product;

                $price = $product->getAmazonPrice();
                $zero_price = $product->getZeroPrice();
                $min_price = ($price < $zero_price) ? max($price, 2.5) : $zero_price;

                if ($product->isAmazonFBAEnabled() &&
                    (intval($product->amazon_fba_avail) > 0 || $product->getAmazonFBAStockReservedTransfers() > 0) &&
                    !in_array($product->amazon_fields->prevent_selling_on_amazon, ['FBA', 'MFN'])) {
                    $item = [
                        'sku' => $product->productcode,
                        'channel' => 'AFN',
                        'price' => $price,
                        'min_price' => $min_price
                    ];

                } else {
                    $item = [
                        'sku' => $product->productcode,
                        'channel' => 'MFN',
                        'quantity' => $product->getAmazonQuantity(),
                        'latency' => $product->distributor->amazon_leadtime_to_ship,
                        'price' => $price,
                        'min_price' => $min_price
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
        }

        if ($items) {

            $log_text = "AMZ: tried to submit " . count($items) . " items as inventory feed";
            func_backprocess_log("incremental feeds", $log_text);

            $feed = AmazonFbaFeedHelper::encodeInventoryFeed($items);

            print("INVENTORY pull\n\n");

            if (!$this->submitInventoryFeed($feed)) {
                return false;
            }

            $feed = AmazonFbaFeedHelper::encodePriceFeed($items);

            print("PRICE pull\n\n");

            if (!$this->submitPriceFeed($feed)) {
                return false;
            }
        }

        return true;
    }

    private function submitFeed($feed, $type)
    {
        $result = true;
        func_dump($feed);
        $feedHandle = @fopen('php://temp', 'rw+');
        fwrite($feedHandle, $feed);
        rewind($feedHandle);
        $amzPool = new AmazonPoolStore();

        try {
            $amzPool->getFeedAndReportClientPack()
                ->callSubmitFeed($type, $feedHandle)
                ->getSubmitFeedResult();

        } catch (\Exception $e) {
            if (method_exists($e, 'getErrorCode') && ('RequestThrottled' == $e->getErrorCode() || 'QuotaExceeded' == $e->getErrorCode())) {

            } else {
                func_backprocess_log('incremental feeds', $e->getMessage() . " - " . $e->getCode());
            }
            $result = false;
        }

        @fclose($feedHandle);

        return $result;
    }

    private function submitPriceFeed($feed)
    {
        return $this->submitFeed($feed, MwsFeedAndReportClientPack::FEED_TYPE_PAI_PRICING);
    }

    private function submitInventoryFeed($feed)
    {
        return $this->submitFeed($feed, MwsFeedAndReportClientPack::FEED_TYPE_PAI_INVENTORY);
    }

    public function submitProductsBatch($debug_mode = 'N', $extra_log = 'N')
    {
        return true;
    }

    public function checkMarketplaceRestrictions($queue)
    {
        $bResult = parent::checkMarketplaceRestrictions($queue);

        if ($queue->product->amazon_enabled != "Y") {
            $bResult = false;
        }

        return $bResult;
    }

    public function getGoogleOneRow(ProductModel $oProduct, $queue, $sExtraLog)
    {
        return null;
    }
}