<?php

global $xcart_dir;
require_once $xcart_dir . "/modules/External_Marketplaces/include/classStoreFrontMarketPlace.php";

class classAmazon extends classStoreFrontMarketPlace
{
    public function addProductToBatch($oProduct, $update_type, $sExtraLog = "N")
    {
        if ($this->checkProductExcludedMarketPlace($oProduct->getField('productid'))) {
            if ($update_type == "2" || $update_type == "1,2" || $update_type == "1") {
                $count_ainventory = count($this->aInventory);
                $this->aInventory[$count_ainventory]["productid"] = $oProduct->getField('productid');
                $this->iInventoryBatchCount++;
            }
        }
        return $this;
    }

    public function submitInventoryBatch($debug_mode = 'N', $extra_log='N') {
        $a_config = [
            'ServiceURL' => $this->getP0(), //"https://mws.amazonservices.com",
            'ProxyHost' => null,
            'ProxyPort' => -1,
            'MaxErrorRetry' => 3,
        ];
        $marketplaceIdArray = ["Id" => [$this->getP2()], "MerchantIdentifier"=>$this->getP1()]; //'ATVPDKIKX0DER'
        SubmitAmazonInventoryBatch($this->getInventory(), $a_config, $marketplaceIdArray);

        $this->setInventoryBatchCount(0)->setInventory([]);
    }

    public function submitProductsBatch($debug_mode = 'N', $extra_log='N') {
        SubmitAmazonProductsBatch();
        $this->setProductsBatchCount(0)->setProducts([]);
    }
}