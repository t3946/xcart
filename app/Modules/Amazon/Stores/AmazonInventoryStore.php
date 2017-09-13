<?php

namespace Modules\Amazon\Stores;


use Modules\Amazon\ClientPack\MwsFbaInventoryClient;
use Modules\Amazon\Helpers\AmazonProductHelper;
use Modules\Amazon\Models\AmazonFbaMissingSkuModel;
use Modules\Amazon\Models\AmazonFbaProductModel;
use Modules\Product\Models\ProductModel;
use Xcart\App\Store\BaseStore;

class AmazonInventoryStore extends BaseStore
{
    /** @var MwsFbaInventoryClient client */
    public $client = null;
    /** @var AmazonFbaProductModel[] */
    public $groupInventory = [];
    /** @var AmazonFbaProductModel[] */
    public $groupProductsById = [];

    public function __construct($client)
    {
        $this->client = $client;
        $this->populate([]);
    }

    public function populate(array $data)
    {
        $this->groupByFNSKU();
        $this->groupByProductId();
    }

    public function groupByFNSKU()
    {
        $i = 1;
        $max_products = 25;
        while ($missings = AmazonFbaMissingSkuModel::objects()->paginate($i++, $max_products)->all())
        {
            $aProductsBatch = [];
            foreach ($missings as $mis) {
                if ($mis->product) {
                    $aProductsBatch[] = $mis->product;
                }
                $aProductsBatch[] = new ProductModel(['productcode' => $mis->missing_productcode, 'productid' => $mis->productid]);
            }

            $aSKUs = array_map(function ($a) { return $a->productcode;}, $aProductsBatch);

            try {
                $inventory = $this->client->callGetListInventory($aSKUs);

                if ($products = AmazonProductHelper::getListInventory($inventory, $aProductsBatch)) {
                    foreach ($products as $aAmazonFbaProduct) {

                        if ($fnsku = $aAmazonFbaProduct->getNotModelAttribute('FNSKU')) {

                            if (array_key_exists($fnsku, $this->groupInventory)) {

                                $this->groupInventory[$fnsku]->lis_TotalSupplyQuantity = max($this->groupInventory[$fnsku]->lis_TotalSupplyQuantity, $aAmazonFbaProduct->lis_TotalSupplyQuantity);
                                $this->groupInventory[$fnsku]->lis_InStockSupplyQuantity = max($this->groupInventory[$fnsku]->lis_InStockSupplyQuantity, $aAmazonFbaProduct->lis_TotalSupplyQuantity);

                            } else {

                                $this->groupInventory[$fnsku] = $aAmazonFbaProduct;
                            }

                        } else {
                            $this->groupInventory[microtime()] = $aAmazonFbaProduct;
                        }
                    }
                }
            } catch (\Exception $e) {
                $log_text = 'ERROR in getFbaInventory list_inventory cron for SKU\'s: ' . implode(', ', $aSKUs) . "\n";
                func_backprocess_log('amazon_info', $log_text . $e->getMessage());
            }
        }
    }

    public function groupByProductId()
    {
        if ($this->groupInventory) {

            foreach ($this->groupInventory as $aAmazonFbaProduct){
                if (array_key_exists($aAmazonFbaProduct->productid, $this->groupProductsById)) {

                    $this->groupProductsById[$aAmazonFbaProduct->productid]->lis_TotalSupplyQuantity += $aAmazonFbaProduct->lis_TotalSupplyQuantity;
                    $this->groupProductsById[$aAmazonFbaProduct->productid]->lis_InStockSupplyQuantity += $aAmazonFbaProduct->lis_InStockSupplyQuantity;

                } else {

                    $this->groupProductsById[$aAmazonFbaProduct->productid] = $aAmazonFbaProduct;

                }
            }

        }
    }
}