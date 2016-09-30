<?php
global $xcart_dir;
require_once $xcart_dir . "/modules/External_Marketplaces/include/classStoreFrontMarketPlace.php";
require_once $xcart_dir . "/modules/External_Marketplaces/include/classIssuesProcessingRules.php";
require_once $xcart_dir . "/modules/External_Marketplaces/include/classGMCQualityIssues.php";
require_once $xcart_dir . "/include/libs/autoload.php";
include_once $xcart_dir . "/include/libs/google/apiclient/examples/templates/base.php";

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

    private function getServiceNew($debug_mode = 'N')
    {
        if (empty($this->oService)) {
            global $xcart_dir;

            $client = new Google_Client();
            $client->setApplicationName("Client_Library_Examples");
            $client->setAuthConfig($xcart_dir . '/include/system/gapi-3c467d1a8e76.json');
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
        $iUpdateProductCount = $iNewIssues = 0;
        do {
            if (!empty($pageToken)) {
                $parameters['pageToken'] = $pageToken;
            }
            $parameters['includeInvalidInsertedItems'] = true;
            $aQueue = [];
            try {
                $oResponse = $this->getServiceNew()->productstatuses->listProductstatuses($this->getP1(), $parameters);
                /** @var Google_Service_ShoppingContent_ProductStatus[] $aProducts */
                $aProducts = $oResponse->getResources();
                if (!empty($aProducts)) {
                    foreach ($aProducts as $oProduct) {
                        list($sStatus, $lang, $Country, $iProductId) = explode(':', $oProduct->getProductId());

                        /** @var Google_Service_ShoppingContent_ProductStatusDataQualityIssue $oDataQualityIssues */
                        $aDataQualityIssues = $oProduct->getDataQualityIssues();

                        if (!empty($aDataQualityIssues)) {
                            foreach ($aDataQualityIssues as $oDataQualityIssues) {
                                $oIssue = classIssuesProcessingRules::getIssueByGoogleIssueId($oDataQualityIssues->getId());
                                if (empty($oIssue)) {
                                    $oIssue = new classIssuesProcessingRules();
                                    $oIssue->setIssueGMCId($oDataQualityIssues->getId());
                                    $iIssueId = $oIssue->_insert();
                                    if ($iIssueId) {
                                        $oIssue->setIssueId($iIssueId);
                                    }
                                }

                                $oIssueDate = DateTime::createFromFormat(DateTime::ISO8601, $oDataQualityIssues->getTimestamp());
                                if (!$oIssueDate) $oIssueDate = new DateTime('NOW');
                                $oGMCQualityIssues = new classGMCQualityIssues(['productid' => $iProductId, 'issue_id' => $oIssue->getIssueId()]);
                                if ($oGMCQualityIssues->getProductId()) {
                                    if ($oIssueDate > $oGMCQualityIssues->getIssueDate()) {
                                        $oGMCQualityIssues->_delete();
                                        $oGMCQualityIssues = new classGMCQualityIssues();
                                    }
                                }
                                if (!$oGMCQualityIssues->getProductId()) {
                                    $oGMCQualityIssues->fillPrimaryTableValues(['productid' => $iProductId,
                                        'issue_id' => $oIssue->getIssueId(),
                                        'issue_date' => $oIssueDate->format('Y-m-d H:i:s'),
                                        'issue_data' => addslashes(json_encode($oDataQualityIssues)),
                                        'issue_destination' => addslashes(json_encode($oProduct->getDestinationStatuses()))
                                    ]);
                                    if ($oIssue->getIssueProcessing() == 'exclude') {
                                        //Google
                                        $oDisableMarketplace = new classDisabledMarketPlace();
                                        $oDisableMarketplace->fillPrimaryTableValues(['marketplace_id' => 1, 'resource_id' => $iProductId, 'resource_type' => 'P']);
                                        $oDisableMarketplace->addDisabledMarketPlace();
                                        //Bing
                                        $oDisableMarketplace->fillPrimaryTableValues(['marketplace_id' => 2, 'resource_id' => $iProductId, 'resource_type' => 'P']);
                                        $oDisableMarketplace->addDisabledMarketPlace();
                                        $oGMCQualityIssues->setField('fixed', 'Y');
                                    }
                                    $oGMCQualityIssues->_insert();
                                    $iNewIssues++;
                                }
                            }

                        }

                        $oExpiredDate = DateTime::createFromFormat(DateTime::ISO8601, $oProduct->getGoogleExpirationDate());
                        $iDaysInterval = $oExpiredDate->diff(new DateTime('now'))->days;
                        if ($iDaysInterval <= $this->getUpdateExpiredBeforeDays() && $iUpdateProductCount <= $this->getUpdateMaxExpiredProductsPerDay()) {
                            $aQueue[] = ['productid' => $iProductId];
                            $iUpdateProductCount++;
                        }
                    }
                }
            }
            catch (Google_Service_Exception $e) {
                func_backprocess_log('google_product_statuses', sprintf('Google_Service_Exception. %s', $e->getMessage()));
            }
            $this->restoreQueue($aQueue, 1);
            $pageToken = $oResponse->getNextPageToken();
        } while ($pageToken);

        func_backprocess_log('google_product_statuses', sprintf('%d new issues found.', $iNewIssues));
        func_backprocess_log('google_product_statuses', sprintf('%d products added for update queue.', $iUpdateProductCount));

        return $this;
    }
}