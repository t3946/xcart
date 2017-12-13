<?php
namespace Xcart\External_Marketplaces\Marketplaces;
use Modules\Goods\Models\ProductModel;
use Modules\Goods\Models\UpdatedProductModel;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Xcart\External_Marketplaces\StoreFrontMarketPlace;
use Xcart\External_Marketplaces\IssuesProcessingRules;
use Xcart\External_Marketplaces\GMCQualityIssues;
use Xcart\External_Marketplaces\DisabledMarketPlace;
use Google_Client;
use Google_Service_ShoppingContent_ProductStatus;
use Google_Service_ShoppingContent;
use Google_Service_ShoppingContent_ProductStatusDataQualityIssue;

global $xcart_dir;

class GMC extends StoreFrontMarketPlace
{
    /**
     * @param int $update_type
     * @param string $googleOneRow
     * @param string $sExtraLog
     * @return bool
     */
    public function addProductToBatch($queue, $googleOneRow = "", $sExtraLog = "N")
    {
        $result = false;
        $oProduct = $queue->product;
        if ($this->checkProductExcludedMarketPlace($oProduct->productid) && $this->checkMarketplaceRestrictions($queue)) {
            if ($queue->type == "1" || $queue->type == "1,2" || (($queue->type == "2" && $oProduct->forsale == "N"))) {
                $batchid = $this->iProductsBatchCount;
                $count_bproducts = count($this->aProducts);
                $this->aProducts[$count_bproducts]["productid"] = $oProduct->productid;
                $this->aProducts[$count_bproducts]["Batchid"] = $batchid;
                $this->aProducts[$count_bproducts]["product_info"] = $googleOneRow;
                $this->aProducts[$count_bproducts]["queue"] = $queue;
                $this->iProductsBatchCount++;
                $result = true;

            } elseif ($queue->type == "2" && $oProduct->forsale == "Y") {
                $batchid = $this->iInventoryBatchCount;
                $count_binventory = count($this->aInventory);
                $this->aInventory[$count_binventory]["productid"] = $oProduct->productid;
                $this->aInventory[$count_binventory]["Batchid"] = $batchid;
                $this->aInventory[$count_binventory]["queue"] = $queue;
                $this->iInventoryBatchCount++;
                $result = true;
            }

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
            $bResult = false;
        }

        return $bResult;
    }

    private function getService($debug_mode = 'N')
    {
        if (empty($this->oService)) {
            global $xcart_dir;

            $client = new Google_Client();
            $client->getHttpClient()->setDefaultOption('verify',false);
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
        if ($error == 500) {
            return false;
        }

        return true;
    }

    public function submitProductsBatch($debug_mode = 'N', $extra_log = 'N')
    {
        $error = SubmitGoogleProductsBatch($this->getProducts(), $this->getService($debug_mode), $this->getP1(), $debug_mode);

        if ($error == 500) {
            return false;
        }

        return true;
    }

    public function getProductStatuses($iStoreFrontId)
    {
        $iUpdateProductCount = $iNewIssues = $totalCounter = 0;
        $pageToken = null;
        $log = new Logger('gmc_info');
        $logFile = sprintf("../var/log/gmc_products-{$iStoreFrontId}-%s.php", date('ymd'));
        $log->pushHandler(new StreamHandler($logFile, Logger::INFO));
        do {
            $oResponse = null;
            if (!empty($pageToken)) {
                $parameters['pageToken'] = $pageToken;
            }
            $parameters['includeInvalidInsertedItems'] = true;
            $parameters['maxResults'] = 250;
            $aQueue = $aLinks = [];
            try {
                $oResponse = $this->getService()->productstatuses->listProductstatuses($this->getP1(), $parameters);
                /** @var Google_Service_ShoppingContent_ProductStatus[] $aProducts */
                $aProducts = $oResponse->getResources();
                if (!empty($aProducts)) {
                    foreach ($aProducts as $oProduct) {
                        list($sStatus, $lang, $Country, $iProductId) = explode(':', $oProduct->getProductId());

                        /** @var Google_Service_ShoppingContent_ProductStatusDataQualityIssue $oDataQualityIssues */
                        $aDataQualityIssues = $oProduct->getDataQualityIssues();

                        if (!empty($aDataQualityIssues)) {
                            foreach ($aDataQualityIssues as $oDataQualityIssues) {
                                $oIssue = IssuesProcessingRules::getIssueByGoogleIssueId($oDataQualityIssues->getId());
                                if (empty($oIssue)) {
                                    $oIssue = new IssuesProcessingRules();
                                    $oIssue->setIssueGMCId($oDataQualityIssues->getId());
                                    $iIssueId = $oIssue->_insert();
                                    if ($iIssueId) {
                                        $oIssue->setIssueId($iIssueId);
                                    }
                                }

                                $oIssueDate = \DateTime::createFromFormat(\DateTime::ISO8601, $oDataQualityIssues->getTimestamp());
                                if (!$oIssueDate) $oIssueDate = new \DateTime('NOW');
                                $oGMCQualityIssues = new GMCQualityIssues(['productid' => $iProductId, 'issue_id' => $oIssue->getIssueId()]);
                                if ($oGMCQualityIssues->getProductId()) {
                                    if ($oIssueDate > $oGMCQualityIssues->getIssueDate()) {
                                        $oGMCQualityIssues->_delete();
                                        $oGMCQualityIssues = new GMCQualityIssues();
                                    }
                                }
                                if (!$oGMCQualityIssues->getProductId() && $oIssue->getIssueProcessing() != 'skip') {
                                    $oGMCQualityIssues->fill(['productid' => $iProductId,
                                        'issue_id' => $oIssue->getIssueId(),
                                        'issue_date' => $oIssueDate->format('Y-m-d H:i:s'),
                                        'issue_data' => addslashes(json_encode($oDataQualityIssues)),
                                        'issue_destination' => addslashes(json_encode($oProduct->getDestinationStatuses()))
                                    ]);
                                    if ($oIssue->getIssueProcessing() == 'exclude') {
                                        //Google
                                        $oDisableMarketplace = new DisabledMarketPlace();
                                        $oDisableMarketplace->fill(['marketplace_id' => 1, 'resource_id' => $iProductId, 'resource_type' => 'P']);
                                        $oDisableMarketplace->addDisabledMarketPlace();
                                        //Bing
                                        $oDisableMarketplace->fill(['marketplace_id' => 2, 'resource_id' => $iProductId, 'resource_type' => 'P']);
                                        $oDisableMarketplace->addDisabledMarketPlace();
                                        $oGMCQualityIssues->setField('fixed', 'Y');
                                    }
                                    $oGMCQualityIssues->_insert();
                                    $iNewIssues++;
                                }
                            }

                        }

                        $oExpiredDate = \DateTime::createFromFormat(\DateTime::ISO8601, $oProduct->getGoogleExpirationDate());
                        $iDaysInterval = $oExpiredDate->diff(new \DateTime('now'))->days;
                        if ($iDaysInterval <= $this->update_expired_before && $iUpdateProductCount < $this->update_max_expired_products_per_day) {
                            $aQueue[] = ['productid' => $iProductId];
                            $iUpdateProductCount++;
                        }
                        $log->addInfo($oProduct->link);
                    }
                }
            } catch (\Exception $e) {
                func_backprocess_log('google_product_statuses', sprintf('Google_Service_Exception. %s', $e->getMessage()));
            }
            $this->restoreQueue($aQueue, 1);
            $totalCounter++;
            try {
                if ($oResponse) {
                    $pageToken = $oResponse->getNextPageToken();
                }
            } catch (\Exception $e) {
                func_backprocess_log('google_product_statuses', sprintf('Error Get Next Page Token. %s', $e->getMessage()));
                $pageToken = false;
            }

        } while ($pageToken);

        func_backprocess_log('google_product_statuses', sprintf('%d new issues found.', $iNewIssues));
        func_backprocess_log('google_product_statuses', sprintf('%d products added for update queue.', $iUpdateProductCount));
        func_backprocess_log('google_product_statuses', sprintf('%d total products.', $totalCounter));



        return $this;
    }
}