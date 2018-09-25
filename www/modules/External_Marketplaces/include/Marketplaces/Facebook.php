<?php

namespace Xcart\External_Marketplaces\Marketplaces;


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
            foreach ($products as $product)  {

            }
        }
    }
}