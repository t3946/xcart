<?php

namespace Xcart\External_Marketplaces\Marketplaces;


use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\ProductCatalog;
use Modules\Goods\Models\ProductModel;
use Modules\Goods\Models\UpdatedProductModel;
use Xcart\External_Marketplaces\StoreFrontMarketPlace;

class Facebook extends StoreFrontMarketPlace
{

    /**
     * @param UpdatedProductModel $queue
     * @param string $googleOneRow
     * @param string $sExtraLog
     * @return mixed
     */
    public function addProductToBatch($queue, $googleOneRow = '', $sExtraLog = 'N')
    {
        $result = false;
        if ($this->checkMarketplaceRestrictions($queue)) {
            $oProduct = $queue->product;
            if ($queue->type === '1' || $queue->type === '1,2' || ($queue->type === '2' && $oProduct->forsale === 'N')) {
                $batchid = $this->iProductsBatchCount;
                $count_bproducts = \count($this->aProducts);
                $this->aProducts[$count_bproducts]['productid'] = $oProduct->productid;
                $this->aProducts[$count_bproducts]['Batchid'] = $batchid;
                $this->aProducts[$count_bproducts]['product_info'] = $googleOneRow;
                $this->aProducts[$count_bproducts]['queue'] = $queue;
                $this->iProductsBatchCount++;
                $result = true;

            } elseif ($queue->type === '2' && $oProduct->forsale === 'Y') {
                $batchid = $this->iInventoryBatchCount;
                $count_binventory = \count($this->aInventory);
                $this->aInventory[$count_binventory]['productid'] = $oProduct->productid;
                $this->aInventory[$count_binventory]['Batchid'] = $batchid;
                $this->aInventory[$count_binventory]['queue'] = $queue;
                $this->iInventoryBatchCount++;
                $result = true;
            }
        } else {
            $this->skipProduct($queue);
            $result = false;
        }
        return $result;
    }

    public function submitInventoryBatch($debug_mode = 'N', $extra_log = 'N')
    {
        // TODO: Implement submitInventoryBatch() method.
    }

    public function submitProductsBatch($debug_mode = 'N', $extra_log = 'N')
    {
        if ($products = $this->getProducts()) {
            $access_token = 'EAAWPV2YIh6YBAIOpS4H3B7dBwyyseh7mi7xYSkf0aYvmufu2jpE6GWAvEZCwetkoOweFsWOE6J6qdGsUF4OCVt6je8uiiZBZBsyapGOGXkMif0rZAbZCGvQhIBOvMT6Mr24brA6uBSjxzmIBhpC1lVcD29RPs5xGnTb1sjnbfNgZDZD';
            $app_secret = 'f6882524fec74a9bf8ca0000300ff0ac';
            $app_id = '1564980420315046';
            $id = '1883924868356414';
            $api = Api::init($app_id, $app_secret, $access_token);
            $api->setLogger(new CurlLogger());

            foreach ($products as $product_a)  {

                $product = $product_a['queue']->product;
                /** @var ProductModel $product */
                $images_model = $product->getImages();

                if ($image_model = $images_model ? reset($images_model) : $product->getThumbnail()) {

                    $requests[] =
                        [
                            'method' => 'CREATE',
                            'retailer_id' => $product->productcode,
                            'data' => [
                                'availability' => $product->isOutOfStock() ? 'out of stock' : 'in stock',
                                'brand' => $product->brand->brand,
                                'category' => $product->getMainCategory()->getFrontendName(),
                                'currency' => 'USD',
                                'description' => strip_tags($product->fulldescr),
                                'image_url' => 'https:' . $image_model->getCdnURL(),
                                'name' => $product->getFrontendName(),
                                'price' => (int)$product->getPrice() * 100,
                                'url' => 'https:' . $product->getAbsoluteUrl(true) . '?origin=facebook_product_ads',
                                'gtin' => $product->upc,
                                'condition' => 'new',
                                'manufacturer_part_number' => $product->getMPN(),
                            ],
                        ];
                }
            }



            echo json_encode((new ProductCatalog($id))->createBatch([],['requests' => $requests])->exportAllData(), JSON_PRETTY_PRINT);
            return true;
        }
    }
}