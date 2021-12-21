<?php
namespace Xcart\External_Marketplaces\Marketplaces;
use DateTime;
use Exception;
use Google\Client;
use Google\Service\ShoppingContent;
use Modules\Goods\Models\GoogleProductQualityIssueModel;
use Modules\Goods\Models\GoogleProductsModel;
use Modules\Goods\Models\GoogleIssuesProcessingRuleModel;
use Modules\Goods\Models\ProductModel;
use Modules\Goods\Models\UpdatedProductModel;
use Modules\Marketplace\Models\ExternalMarketplaceDisabledModel;
use Xcart\External_Marketplaces\StoreFrontMarketPlace;


class GMC extends StoreFrontMarketPlace
{
    public function addProductToBatch(UpdatedProductModel $queue, string $googleOneRow = '', string $sExtraLog = 'N'): bool
    {
        $result = false;
        $oProduct = $queue->product;
        if ($this->checkProductExcludedMarketPlace($oProduct->productid) && $this->checkMarketplaceRestrictions($queue)) {
            if ($queue->type === "1" || $queue->type === "1,2" || (($queue->type === "2" && $oProduct->forsale === "N"))) {
                $batchid = $this->iProductsBatchCount;
                $count_bproducts = count($this->aProducts);
                $this->aProducts[$count_bproducts]['productid'] = $oProduct->productid;
                $this->aProducts[$count_bproducts]['Batchid'] = $batchid;
                $this->aProducts[$count_bproducts]['product_info'] = $googleOneRow;
                $this->aProducts[$count_bproducts]['queue'] = $queue;
                $this->iProductsBatchCount++;
                $result = true;

            } elseif ($queue->type === "2" && $oProduct->forsale === "Y") {
                $batchid = $this->iInventoryBatchCount;
                $count_binventory = count($this->aInventory);
                $this->aInventory[$count_binventory]['productid'] = $oProduct->productid;
                $this->aInventory[$count_binventory]['Batchid'] = $batchid;
                $this->aInventory[$count_binventory]['queue'] = $queue;
                $this->iInventoryBatchCount++;
                $result = true;
            }

        } else {
            $this->skipProduct($queue);
        }
        return $result;
    }

    public function checkMarketplaceRestrictions($queue): bool
    {
        $bResult = parent::checkMarketplaceRestrictions($queue);

        $aDetailedImages = $queue->product->getDetailedImages();

        if (empty($aDetailedImages)) {
            $bResult = false;
        }

        return $bResult;
    }

    private function getService($debug_mode = 'N'): ?ShoppingContent
    {
        if (empty($this->oService)) {
            global $xcart_dir;

            $client = new Client(['verify' => false]);
            $client->setApplicationName("Client_Library_Examples");
            $client->setAuthConfig($xcart_dir . '/include/system/gapi-3c467d1a8e76.json');
            $client->addScope(ShoppingContent::CONTENT);
            $this->oService = new ShoppingContent($client);

        }
        return $this->oService;
    }

    public function submitInventoryBatch($debug_mode = 'N', $extra_log = 'N'): bool
    {
        $error = SubmitGoogleInventoryBatch($this->getInventory(), $this->getService($debug_mode), $this->getP1(), $debug_mode, $extra_log);
        if ($error == 500) {
            return false;
        }

        return true;
    }

    public function submitProductsBatch($debug_mode = 'N', $extra_log = 'N'): bool
    {
        $error = SubmitGoogleProductsBatch($this->getProducts(), $this->getService($debug_mode), $this->getP1(), $debug_mode);

        if ($error == 500) {
            return false;
        }

        return true;
    }

    public function getProductStatuses(): GMC
    {
        $iUpdateProductCount = $iNewIssues = $totalCounter = 0;
        $pageToken = null;
        $products = [];
        do {
            $oResponse = null;
            if (!empty($pageToken)) {
                $parameters['pageToken'] = $pageToken;
            }
            $parameters['maxResults'] = 250;
            $aQueue = [];

            try {
                $oResponse = $this->getService()->productstatuses->listProductstatuses($this->getP1(), $parameters);

                if ($aProducts = $oResponse->getResources()) {
                    foreach ($aProducts as $oProduct) {
                        [,,,$iProductId] = explode(':', $oProduct->getProductId());

                        $products[] = (int)$iProductId;

                        foreach ($oProduct->getDestinationStatuses() as $destinationStatus) {
                            if ($destinationStatus->getDestination() === 'Shopping') {
                                if (ProductModel::objects()->get(['productid' => $iProductId])) {
                                    GoogleProductsModel::objects()->updateOrCreate(['product_id' => $iProductId],['shopping_status' => $destinationStatus->getStatus()]);
                                }
                            }
                        }

                        if ($oProduct->getItemLevelIssues()) {
                            foreach ($oProduct->getItemLevelIssues() as $quality_issue) {
                                [$issue_model] = GoogleIssuesProcessingRuleModel::objects()->updateOrCreate(
                                    [
                                        'issue_gmc_id' => $quality_issue->getCode(),
                                    ],
                                    [
                                        'servability' => $quality_issue->getServability(),
                                        'issue_description' => $quality_issue->getDetail(),
                                    ]);

                                [$product_issue, $is_new] = GoogleProductQualityIssueModel::objects()->getOrNew([
                                    'productid' => $iProductId,
                                    'issue_id' => $issue_model->pk,
                                    'name' => $quality_issue->getDescription()
                                ]);

                                if ($issue_model->issue_processing === 'exclude') {
                                    ExternalMarketplaceDisabledModel::objects()->getOrCreate([
                                        'marketplace_id' => 1,
                                        'resource_id' => $iProductId,
                                        'resource_type' => 'P'
                                    ]);
                                }

                                $product_issue->save();
                                if ($is_new) {
                                    $iNewIssues++;
                                }
                            }
                        }

                        $oExpiredDate = DateTime::createFromFormat(DateTime::ISO8601, $oProduct->getGoogleExpirationDate());
                        $iDaysInterval = $oExpiredDate->diff(new DateTime('now'))->days;
                        if ($iDaysInterval <= $this->update_expired_before && $iUpdateProductCount < $this->update_max_expired_products_per_day) {
                            $aQueue[] = ['productid' => $iProductId];
                            $iUpdateProductCount++;
                        }
                    }
                }
            } catch (Exception $e) {
                echo sprintf("%s \n", $e->getMessage());
            }
            $this->restoreQueue($aQueue, 1);
            $totalCounter++;
            try {
                if ($oResponse) {
                    $pageToken = $oResponse->getNextPageToken();
                }
            } catch (Exception $e) {
                echo "Error Get Next Page Token. %s \n", $e->getMessage();
                $pageToken = false;
            }

        } while ($pageToken);

        func_backprocess_log('google_product_statuses', sprintf('%d new issues found.', $iNewIssues));
        func_backprocess_log('google_product_statuses', sprintf('%d products added for update queue.', $iUpdateProductCount));
        func_backprocess_log('google_product_statuses', sprintf('%d total products.', $totalCounter));

        foreach (GoogleProductsModel::objects()->valuesList(['product_id'], true) as $product_id) {
            if (!in_array((int)$product_id, $products, true)){
                GoogleProductsModel::objects()->delete(['product_id' => $product_id]);
            }
        }

        return $this;
    }
}