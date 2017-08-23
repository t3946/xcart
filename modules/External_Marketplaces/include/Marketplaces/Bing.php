<?php
namespace Xcart\External_Marketplaces\Marketplaces;
use Modules\Product\Models\ProductModel;
use Xcart\External_Marketplaces\StoreFrontMarketPlace;

class Bing extends StoreFrontMarketPlace
{
    public function addProductToBatch(ProductModel $oProduct, $update_type, $googleOneRow = "", $sExtraLog = "N")
    {
        if ($this->checkProductExcludedMarketPlace($oProduct->productid) && $this->checkMarketplaceRestrictions($oProduct, $update_type)) {
                $batchid = $this->iProductsBatchCount;
                $count_bproducts = count($this->aProducts);
                $this->aProducts[$count_bproducts]["productid"] = $oProduct->productid;
                $this->aProducts[$count_bproducts]["Batchid"] = $batchid;
                $this->aProducts[$count_bproducts]["product_info"] = $googleOneRow;
                $this->iProductsBatchCount++;
        }

        return $this;
    }

    public function checkMarketplaceRestrictions(ProductModel $oProduct, $update_type)
    {
        $bResult = true;

        $aDetailedImages = $oProduct->getDetailedImages();

        if (empty($aDetailedImages)) {
            return false;
        }

        $last_date = (new \DateTime())->setTimestamp($oProduct->last_incremental_update);
        $diff = (new \DateTime())->diff($last_date);

        if ($update_type == 2 && $diff->days*24 + $diff->h < 24) {
            return false;
        }

        return $bResult;
    }

    public function submitInventoryBatch($debug_mode = 'N', $extra_log='N') {
        $error = SubmitBingInventoryBatch($this->getInventory(),$this->getP0(), $this->getP1(), $this->getP2(), $this->getFTPLogin(), $this->getFTPPassword(), $this->getFTPPath(), $debug_mode);
        if ($error == 500)
            $this->RestoreQueue($this->getInventory(), 2);

        $this->setInventoryBatchCount(0)->setInventory([]);
    }

    public function submitProductsBatch($debug_mode = 'N', $extra_log='N') {
        $error = SubmitBingProductsBatch($this->getProducts(),$this->getP0(), $this->getP1(), $this->getP2(), $this->getFTPLogin(), $this->getFTPPassword(), $this->getFTPPath(), $debug_mode);
        if ($error == 500)
            $this->RestoreQueue($this->getProducts(), 1);

        $this->setProductsBatchCount(0)->setProducts([]);
    }

    public function getGoogleOneRow(ProductModel $oProduct, $type, $sExtraLog)
    {
        return GetGoogleBaseOneRow($oProduct->productid, "main_google", $sExtraLog);
    }
}