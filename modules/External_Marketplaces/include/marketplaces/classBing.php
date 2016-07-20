<?php
global $xcart_dir;
require_once $xcart_dir . "/modules/External_Marketplaces/include/classStoreFrontMarketPlace.php";

class classBing extends classStoreFrontMarketPlace
{
    public function addProductToBatch($oProduct, $update_type, $sExtraLog = "N")
    {
        if ($this->checkProductExcludedMarketPlace($oProduct->getField('productid'))) {
            if ($update_type == "1" || $update_type == "1,2" || (($update_type == "2" && $oProduct->getField('forsale') == "N"))) {
                $batchid = $this->iProductsBatchCount;
                $count_bproducts = count($this->aProducts);
                $this->aProducts[$count_bproducts]["productid"] = $oProduct->getField('productid');
                $this->aProducts[$count_bproducts]["Batchid"] = $batchid;
                $this->aProducts[$count_bproducts]["product_info"] = GetGoogleBaseOneRow($oProduct->getField('productid'), "main_google", $sExtraLog);
                $this->iProductsBatchCount++;

            } elseif ($update_type == "2" && $oProduct->getField('forsale') == "Y") {
                $batchid =  $this->iInventoryBatchCount;
                $count_binventory = count($this->aInventory);
                $this->aInventory[$count_binventory]["productid"] = $oProduct->getField('productid');
                $this->aInventory[$count_binventory]["Batchid"] = $batchid;
                $this->iInventoryBatchCount++;
            }
        }

        return $this;
    }

    public function submitInventoryBatch($debug_mode = 'N', $extra_log='N') {
        $error = SubmitBingInventoryBatch($this->getInventory(), $this->getP1(), $this->getP2(), $this->getFTPLogin(), $this->getFTPPassword(), $this->getFTPPath(), $debug_mode);
        if ($error == 500)
            $this->RestoreQueue($this->getInventory(), 2);

        $this->setInventoryBatchCount(0)->setInventory([]);
    }

    public function submitProductsBatch($debug_mode = 'N', $extra_log='N') {
        $error = SubmitBingProductsBatch($this->getProducts(), $this->getP1(), $this->getP2(), $this->getFTPLogin(), $this->getFTPPassword(), $this->getFTPPath(), $debug_mode);
        if ($error == 500)
            $this->RestoreQueue($this->getProducts(), 1);

        $this->setProductsBatchCount(0)->setProducts([]);
    }
}