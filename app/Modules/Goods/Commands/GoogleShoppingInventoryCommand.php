<?php


namespace Modules\Goods\Commands;


use Exception;
use Google\Client;
use Google\Service\ShoppingContent;
use Google\Service\ShoppingContent\Error;
use Google\Service\ShoppingContent\Price;
use Google\Service\ShoppingContent\Product;
use Google\Service\ShoppingContent\ProductsCustomBatchRequest;
use Google\Service\ShoppingContent\ProductsCustomBatchRequestEntry;
use Xcart\App\QueryBuilder\Expression;
use Xcart\App\QueryBuilder\Q\QOr;
use Xcart\App\QueryBuilder\Q\QOrNot;
use Modules\Distributor\Models\DistributorModel;
use Modules\Goods\Models\UpdatedProductModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Commands\Command;
use Xcart\App\Helpers\Paths;

class GoogleShoppingInventoryCommand extends Command
{

    private array $toDelete = [];

    public function handle($arguments = []): void
    {

        /** @var SiteModel $site */
        foreach (SiteModel::objects()->filter(['marketplaces__marketplace_id' => 1])->order(['storefrontid']) as $site) {

            func_backprocess_log('incremental inventory feed', $l = "Storefront: $site->domain Storefrontid: $site->storefrontid");
            echo "$l\n";

            $i = 0;

            /** @var UpdatedProductModel[] $up */
            while ($up = UpdatedProductModel::objects()
                ->select(['*', 'product__forsale', 'utype' => new Expression('MIN(type)')])
                ->filter(['product__sites__storefrontid' => $site->storefrontid, 'type' => 2, new QOr(['mask__isnull' => true, new QOrNot(['mask' => 0])])])
                ->group(['resourceid'])
                ->order(['-utype', '-product__forsale'])
                ->paginate(++$i, 100)->all()) {

                $entries = $this->getBatchProducts($up, $site);

                if ($entries) {
                    $client = new Client(['verify' => false]);
                    $client->setApplicationName('Google Feed');
                    $client->setAuthConfig(Paths::get('www') . '/include/system/gapi-3c467d1a8e76.json');
                    $client->addScope(ShoppingContent::CONTENT);
                    $oService = new ShoppingContent($client);

                    $batchReq = new ProductsCustomBatchRequest();
                    $batchReq->setEntries($entries);
                    $log_text = '';
                    try {
                        func_backprocess_log('incremental inventory feed', $l = "GB: tried to submit {$batchReq->count()} items as inventory feed ($site->code)");
                        echo "$l\n";

                        $result = $oService->products->customBatch($batchReq);

                        foreach ($result->getEntries() as $entity) {
                            if ($errors = $entity->getErrors()) {
                                $log_text .= "Error process product $entity->batchId :\n";
                                /** @var Error $error */
                                foreach ($errors as $error) {
                                    $log_text .= "{$error->getMessage()}\n";
                                }
                            }
                        }
                        UpdatedProductModel::objects()->delete(['resourceid__in' => $this->toDelete, 'type' => 2]);

                        $this->toDelete = [];

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

    /**
     * @param UpdatedProductModel[] $up
     * @param SiteModel $site
     * @return array
     */
    private function getBatchProducts(array $up, SiteModel $site): array
    {
        $entries = [];
        $lang = $site->lang->lang_code;
        $marketplace = $site->marketplaces->filter(['marketplace_id' => 1])->limit(1)->get();
        $merchantId = $marketplace->P1;

        foreach ($up as $model) {
            if ($product = $model->product) {

                /** @var DistributorModel $dX */
                $dX = $product->distributor;

                $currency = $dX->currency;
                $sprice = $product->getFrontendPrice($product->min_amount ?? 1) * ($product->min_amount ?? 1);

                $price = new Price();
                $price->setCurrency($currency->currency_code ?? 'USD');
                $price->setValue($sprice);

                $inventory = new Product();
                $inventory->setPrice($price);

                $listPrice = $product->list_price;
                if ($sprice < $listPrice) {
                    $lPrice = new Price();
                    $lPrice->setCurrency($currency->currency_code ?? 'USD');
                    $lPrice->setValue($listPrice);
                    $inventory->setSalePrice($price);
                    $inventory->setPrice($lPrice);
                }

                $inventory->setAvailability($product->isOutOfStock() ? 'out of stock' : 'in stock');

                $entry = new ProductsCustomBatchRequestEntry();
                $entry->setProductId("online:$lang:$marketplace->countries:$product->productid");
                $entry->setMethod('update');
                $entry->setProduct($inventory);
                $entry->setBatchId($product->productid);
                $entry->setMerchantId($merchantId);

                $entries[] = $entry;
            }
            $this->toDelete[] = $model->resourceid;
        }

        return $entries;
    }

}