<?php


namespace Modules\Goods\Commands;


use Exception;
use Google_Client;
use Google_Service_ShoppingContent;
use Google_Service_ShoppingContent_CustomAttribute;
use Google_Service_ShoppingContent_Error;
use Google_Service_ShoppingContent_Inventory;
use Google_Service_ShoppingContent_InventoryCustomBatchRequest;
use Google_Service_ShoppingContent_InventoryCustomBatchRequestEntry;
use Google_Service_ShoppingContent_Price;
use Google_Service_ShoppingContent_Product;
use Google_Service_ShoppingContent_ProductsCustomBatchRequest;
use Google_Service_ShoppingContent_ProductsCustomBatchRequestEntry;
use Google_Service_ShoppingContent_ProductsCustomBatchResponseEntry;
use Google_Service_ShoppingContent_ProductShipping;
use Google_Service_ShoppingContent_ProductShippingDimension;
use Google_Service_ShoppingContent_ProductShippingWeight;
use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QOr;
use Mindy\QueryBuilder\Q\QOrNot;
use Modules\Core\Models\StateModel;
use Modules\Distributor\Models\DistributorModel;
use Modules\Goods\Models\UpdatedProductModel;
use Modules\Shipping\Helpers\ShippingHelper;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Commands\Command;
use Xcart\App\Helpers\Paths;

class GoogleShoppingInventoryCommand extends Command
{

    public function handle($arguments = []): void
    {

        /** @var SiteModel $site */
        foreach (SiteModel::objects()->filter(['marketplaces__marketplace_id' => 1])->order(['storefrontid']) as $site) {

            func_backprocess_log('incremental inventory feed', $l = "Storefront: {$site->domain} Storefrontid: {$site->storefrontid}");
            echo "{$l}\n";

            $config = $site->getConfig();
            $marketplace = $site->marketplaces->filter(['marketplace_id' => 1])->limit(1)->get();
            $merchantId = $marketplace->P1;
            $lang = $config['Preferred_language'] ?? 'en';
            $i = 0;

            /** @var UpdatedProductModel[] $up */
            while ($up = UpdatedProductModel::objects()
                ->select(['*', 'product__forsale', 'utype' => new Expression('MIN(type)')])
                ->filter(['product__sites__storefrontid' => $site->storefrontid, 'type' => 2, new QOr(['mask__isnull' => true, new QOrNot(['mask' => 0])])])
                ->group(['resourceid'])
                ->order(['-utype', '-product__forsale'])
                ->paginate(++$i, 140)->all()) {
                $toDelete = [];
                $entries = [];

                foreach ($up as $model) {
                    if ($product = $model->product) {
                        /** @var DistributorModel $dX */
                        $dX = $product->distributor;
                        $entry = new Google_Service_ShoppingContent_InventoryCustomBatchRequestEntry();
                        $entry->setProductId("online:{$lang}:{$marketplace->countries}:{$product->productid}");
                        $entry->setStoreCode('online');
                        $inventory = new Google_Service_ShoppingContent_Inventory();
                        $currency = $dX->currency;
                        $sprice = $product->getFrontendPrice();
                        $price = new Google_Service_ShoppingContent_Price();
                        $price->setCurrency($currency->currency_code ?? 'USD');
                        $price->setValue($sprice);
                        $inventory->setPrice($price);

                        $listPrice = $product->list_price;
                        if ($sprice < $listPrice) {
                            $lPrice = new Google_Service_ShoppingContent_Price();
                            $lPrice->setCurrency($currency->currency_code ?? 'USD');
                            $lPrice->setValue($listPrice);
                            $inventory->setSalePrice($price);
                            $inventory->setPrice($lPrice);
                        }
                        $inventory->setKind('content#inventory');
                        $inventory->setAvailability($product->isOutOfStock() ? 'out of stock' : 'in stock');
                        $entry->setInventory($inventory);
                        $entry->setBatchId($product->productid);
                        $entry->setMerchantId($merchantId);
                        $entries[] = $entry;
                    }
                    $toDelete[] = $model->resourceid;
                }
                if ($entries) {
                    $client = new Google_Client(['verify' => false]);
                    $client->setApplicationName('Google Feed');
                    $client->setAuthConfig(Paths::get('www') . '/include/system/gapi-3c467d1a8e76.json');
                    $client->addScope(Google_Service_ShoppingContent::CONTENT);
                    $oService = new Google_Service_ShoppingContent($client);

                    $batchReq = new Google_Service_ShoppingContent_InventoryCustomBatchRequest();
                    $batchReq->setEntries($entries);
                    $log_text = '';
                    try {
                        func_backprocess_log('incremental inventory feed', $l = "GB: tried to submit {$batchReq->count()} items as inventory feed ($merchantId)");
                        echo "{$l}\n";

                        $result = $oService->inventory->customBatch($batchReq);

                        /** @var Google_Service_ShoppingContent_ProductsCustomBatchResponseEntry $entinty */
                        foreach ($result->getEntries() as $entinty) {
                            if ($errors = $entinty->getErrors()) {
                                $log_text .= "Error process product {$entinty->batchId} :\n";
                                /** @var Google_Service_ShoppingContent_Error $error */
                                foreach ($errors as $error) {
                                    $log_text .= "{$error->getMessage()}\n";
                                }
                            }
                        }
                        UpdatedProductModel::objects()->delete(['resourceid__in' => $toDelete, 'type' => 2]);

                    } catch (Exception $e) {
                        $log_text .= "{$e->getMessage()}\n";
                    }

                    if ($log_text) {
                        func_backprocess_log('incremental inventory feed', $log_text);
                        echo $log_text;
                    }
                }
            }
        }
    }

}