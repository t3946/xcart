<?php

namespace Xcart\External_Marketplaces\Marketplaces;

use Modules\Product\Models\ProductModel;
use Modules\Product\Models\UpdatedProductModel;
use Xcart\External_Marketplaces\StoreFrontMarketPlace;

class Bing extends StoreFrontMarketPlace
{
    public function addProductToBatch($queue, $googleOneRow = "", $sExtraLog = "N")
    {
        $result = false;
        $oProduct = $queue->product;
        if ($this->checkProductExcludedMarketPlace($oProduct->productid) && $this->checkMarketplaceRestrictions($queue)) {
            $batchid = $this->iProductsBatchCount;
            $count_bproducts = count($this->aProducts);
            $this->aProducts[$count_bproducts]["productid"] = $oProduct->productid;
            $this->aProducts[$count_bproducts]["Batchid"] = $batchid;
            $this->aProducts[$count_bproducts]["product_info"] = $googleOneRow;
            $this->aProducts[$count_bproducts]["queue"] = $queue;
            $this->iProductsBatchCount++;
            $result = true;
        } else {
            list($queue_n) = UpdatedProductModel::objects()->getOrNew(
                [
                    'resourceid' => $queue->resourceid,
                    'type' => $queue->type
                ]);
            if ($queue_n) {
                $queue_n->mask &= ~intval($this->getExternalMarketPlaceEntity()->mask);
                $queue_n->save();
            }

        }
        return $result;
    }

    public function checkMarketplaceRestrictions($queue)
    {
        $bResult = parent::checkMarketplaceRestrictions($queue);

        $aDetailedImages = $queue->product->getDetailedImages();

        if (empty($aDetailedImages)) {
            return false;
        }

        return $bResult;
    }

    public function submitInventoryBatch($debug_mode = 'N', $extra_log = 'N')
    {
        $error = SubmitBingInventoryBatch($this->getInventory(), $this->getP0(), $this->getP1(), $this->getP2(), $this->getFTPLogin(), $this->getFTPPassword(), $this->getFTPPath(), $debug_mode);

        if ($error == 500 || $error == 100) {
            return false;
        }

        return true;
    }

    public function submitProductsBatch($debug_mode = 'N', $extra_log = 'N')
    {
        $error = SubmitBingProductsBatch($this->getProducts(), $this->getP0(), $this->getP1(), $this->getP2(), $this->getFTPLogin(), $this->getFTPPassword(), $this->getFTPPath(), $debug_mode);

        if ($error == 500 || $error == 100) {
            return false;
        }

        return true;
    }

    public function getGoogleOneRow(ProductModel $oProduct, $queue, $sExtraLog)
    {
        $result = null;

        if ($this->checkMarketplaceRestrictions($queue)) {
            $result = GetGoogleBaseOneRow($oProduct->productid, "main_google", $sExtraLog, false);
        }

        return $result;

    }
}