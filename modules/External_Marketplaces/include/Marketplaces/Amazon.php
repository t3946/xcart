<?php

namespace Xcart\External_Marketplaces\Marketplaces;

use CaponicaAmazonMwsComplete\ClientPack\MwsFeedAndReportClientPack;
use MarketplaceWebService_Exception;
use Modules\Amazon\Helpers\AmazonFbaFeedHelper;
use Modules\Amazon\Stores\AmazonPoolStore;
use Modules\Product\Models\ProductModel;
use Xcart\External_Marketplaces\StoreFrontMarketPlace;

class Amazon extends StoreFrontMarketPlace
{
    public function addProductToBatch(ProductModel $oProduct, $update_type, $googleOneRow = "", $sExtraLog = "N")
    {
        //$this->checkProductExcludedMarketPlace($oProduct->getProductId())
        if ($this->checkMarketplaceRestrictions($oProduct, $update_type)) {
            if ($update_type == "2" || $update_type == "1,2" || $update_type == "1") {
                $this->aInventory[] = $oProduct;
                $this->iInventoryBatchCount++;
            }
        }
        return $this;
    }

    public function submitInventoryBatch($debug_mode = 'N', $extra_log = 'N')
    {

        $items = [];

        if ($products = $this->getInventory()) {
            /** @var ProductModel $product */

            foreach ($products as $product) {

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

            $feedResult = $this->submitInventoryFeed($feed);

            $feed = AmazonFbaFeedHelper::encodePriceFeed($items);

            print("PRICE pull\n\n");

            $feedResult = $this->submitPriceFeed($feed);
        }

        $this->setInventoryBatchCount(0)->setInventory([]);
    }

    private function submitFeed($feed, $type)
    {
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
                $error_code = $e->getErrorCode();
            } else {
                $error_code = $e->getCode();
            }

            func_backprocess_log('incremental feeds', $e->getMessage() . " - " . $error_code);
        }

        @fclose($feedHandle);
    }

    private function submitPriceFeed($feed)
    {
        $this->submitFeed($feed, MwsFeedAndReportClientPack::FEED_TYPE_PAI_PRICING);
    }

    private function submitInventoryFeed($feed)
    {
        $this->submitFeed($feed, MwsFeedAndReportClientPack::FEED_TYPE_PAI_INVENTORY);
    }

    public function submitProductsBatch($debug_mode = 'N', $extra_log = 'N')
    {
        $this->setProductsBatchCount(0)->setProducts([]);
    }

    public function checkMarketplaceRestrictions(ProductModel $oProduct, $update_type)
    {
        $bResult = true;
        if ($oProduct->amazon_enabled != "Y")
            $bResult = false;
        return $bResult;
    }

    public function getGoogleOneRow(ProductModel $oProduct, $type, $sExtraLog)
    {
        return null;
    }
}