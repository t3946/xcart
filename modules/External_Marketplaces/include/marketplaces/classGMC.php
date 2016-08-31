<?php
global $xcart_dir;
require_once $xcart_dir . "/modules/External_Marketplaces/include/classStoreFrontMarketPlace.php";
include_once $xcart_dir . "/google-api-php-client/examples/templates/base.php";
require_once $xcart_dir . "/google-api-php-client/autoload.php";

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
            //$service_account_name = 'account-2@careful-triumph-774.iam.gserviceaccount.com'; //Email Address
            $service_account_name = $this->getFTPLogin();
            $key_file_location = $xcart_dir.'/google-api-php-client/examples/key2.p12'; //key.p12

            $client = new Google_Client();
            $client->setApplicationName("Client_Library_Examples");
            $this->oService = new Google_Service_ShoppingContent($client);

            if (isset($_SESSION['service_token'])) {
                $client->setAccessToken($_SESSION['service_token']);
            }

            $key = file_get_contents($key_file_location);
            $cred = new Google_Auth_AssertionCredentials(
                $service_account_name,
                //array('https://www.googleapis.com/auth/content'),
                [$this->getP0()],
                $key
            );
            $client->setAssertionCredentials($cred);
            if ($debug_mode != "Y") {
                if ($client->getAuth()->isAccessTokenExpired()) {
                    $client->getAuth()->refreshTokenWithAssertion($cred);
                }
            }
            $_SESSION['service_token'] = $client->getAccessToken();
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