<?php
global $xcart_dir;
require_once $xcart_dir . "/modules/External_Marketplaces/include/classStoreFrontMarketPlace.php";
require_once $xcart_dir . "/include/libs/autoload.php";
include_once $xcart_dir. "/include/libs/google/apiclient/examples/templates/base.php";

class classGMC extends classStoreFrontMarketPlace
{
    /**
     * @param classProduct $oProduct
     * @param int $update_type
     * @param string $sExtraLog
     * @return classGMC
     */
    public function addProductToBatch(classProduct $oProduct, $update_type, $sExtraLog = "N")
    {
        if ($this->checkProductExcludedMarketPlace($oProduct->getProductId()) && $this->checkMarketplaceRestrictions($oProduct)) {
            if ($update_type == "1" || $update_type == "1,2" || (($update_type == "2" && $oProduct->getField('forsale') == "N"))) {
                $batchid = $this->iProductsBatchCount;
                $count_bproducts = count($this->aProducts);
                $this->aProducts[$count_bproducts]["productid"] = $oProduct->getProductId();
                $this->aProducts[$count_bproducts]["Batchid"] = $batchid;
                $this->aProducts[$count_bproducts]["product_info"] = GetGoogleBaseOneRow($oProduct->getProductId(), "main_google", $sExtraLog);
                $this->iProductsBatchCount++;

            } elseif ($update_type == "2" && $oProduct->getField('forsale') == "Y") {
                $batchid = $this->iInventoryBatchCount;
                $count_binventory = count($this->aInventory);
                $this->aInventory[$count_binventory]["productid"] = $oProduct->getProductId();
                $this->aInventory[$count_binventory]["Batchid"] = $batchid;
                $this->iInventoryBatchCount++;
            }

        }
        return $this;
    }

    public function checkMarketplaceRestrictions(classProduct $oProduct)
    {
        $bResult = true;
        $aDetailedImages = $oProduct->getDetailedImages();
        if (empty($aDetailedImages))
            $bResult = false;
        return $bResult;
    }

    private function getService($debug_mode = 'N')
    {
        if (empty($this->oService)) {
            global $xcart_dir;

            $client = new Google_Client();
            $client->setApplicationName("Client_Library_Examples");
            $client->setAuthConfig($xcart_dir.'/include/system/gapi-3c467d1a8e76.json');
            $client->addScope(Google_Service_ShoppingContent::CONTENT);
            $this->oService = new Google_Service_ShoppingContent($client);

        }
        return $this->oService;
    }

    public function submitInventoryBatch($debug_mode = 'N', $extra_log = 'N')
    {
        $error = SubmitGoogleInventoryBatch($this->getInventory(), $this->getService($debug_mode), $this->getP1(), $debug_mode, $extra_log);
        if ($error == 500)
            $this->RestoreQueue($this->getInventory(), 2);

        $this->setInventoryBatchCount(0)->setInventory([]);
    }

    public function submitProductsBatch($debug_mode = 'N', $extra_log = 'N')
    {
        $error = SubmitGoogleProductsBatch($this->getProducts(), $this->getService($debug_mode), $this->getP1(), $debug_mode);
        if ($error == 500)
            $this->RestoreQueue($this->getProducts(), 1);

        $this->setProductsBatchCount(0)->setProducts([]);
    }

    public function getProductStatuses()
    {
        $oResponse = $this->getService()->productstatuses->listProductstatuses($this->getP1(),['includeInvalidInsertedItems'=>true]);

        var_dump($oResponse->getResources());

        return $this;
    }
}