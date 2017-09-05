<?php
namespace Xcart\External_Marketplaces\Marketplaces;
use Modules\Product\Models\ProductModel;
use Xcart\External_Marketplaces\StoreFrontMarketPlace;

class Amazon extends StoreFrontMarketPlace
{
    public function addProductToBatch(ProductModel $oProduct, $update_type, $googleOneRow = "", $sExtraLog = "N")
    {
        //$this->checkProductExcludedMarketPlace($oProduct->getProductId())
        if ($this->checkMarketplaceRestrictions($oProduct, $update_type)) {
            if ($update_type == "2" || $update_type == "1,2" || $update_type == "1") {
                $count_ainventory = count($this->aInventory);
                $this->aInventory[$count_ainventory]["productid"] = $oProduct->productid;
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
        SubmitAmazonInventoryBatch($this->getInventory(), $a_config, $marketplaceIdArray, $this);

        $this->setInventoryBatchCount(0)->setInventory([]);
    }

    public function submitProductsBatch($debug_mode = 'N', $extra_log='N') {
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