<?php


namespace Modules\Goods\Commands;


use Exception;
use Google_Client;
use Google_Service_ShoppingContent;
use Google_Service_ShoppingContent_CustomAttribute;
use Google_Service_ShoppingContent_Error;
use Google_Service_ShoppingContent_Price;
use Google_Service_ShoppingContent_Product;
use Google_Service_ShoppingContent_ProductsCustomBatchRequest;
use Google_Service_ShoppingContent_ProductsCustomBatchRequestEntry;
use Google_Service_ShoppingContent_ProductsCustomBatchResponseEntry;
use Google_Service_ShoppingContent_ProductShippingDimension;
use Google_Service_ShoppingContent_ProductShippingWeight;
use Mindy\QueryBuilder\Expression;
use Modules\Distributor\Models\DistributorModel;
use Modules\Goods\Models\UpdatedProductModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Commands\Command;
use Xcart\App\Helpers\Paths;

class GoogleShoppingCommand extends Command
{

    public function handle($arguments = []): void
    {

        /** @var SiteModel $site */
        foreach (SiteModel::objects()->order(['storefrontid']) as $site) {

            func_backprocess_log('incremental feeds', "Storefront: {$site->domain} Storefrontid: {$site->storefrontid}");

            $entries = [];
            $config = $site->getConfig();

            $marketplace = $site->marketplaces->filter(['marketplace_id' => 1])->limit(1)->get();
            $merchantId = $marketplace->P1;
            $lang = $config['Preferred_language'] ?? 'en';

            /** @var UpdatedProductModel[] $up */
            $up = UpdatedProductModel::objects()
                ->select(['*', 'product__forsale', 'utype' => new Expression('MIN(type)')])
                ->filter(['product__sites__storefrontid' => $site->storefrontid, 'type__lte' => 2, 'mask__isnt' => 0])
                ->group(['resourceid'])
                ->order(['-utype', '-product__forsale'])
                ->limit(100);

            $toDelete = [];

            foreach ($up as $model) {
                if ($product = $model->product) {
                    /** @var DistributorModel $dX */
                    $dX = $product->distributor;

                    $batch = new Google_Service_ShoppingContent_Product();
                    $batch->setOfferId($product->productid);
                    $batch->setTitle($product->getFrontendName());
                    $batch->setDescription($product->getFrontendDescription());
                    $batch->setLink('https:'.$product->getAbsoluteUrl(true) . '?origin=google_product_ads');

                    $batch->setContentLanguage($lang);
                    $batch->setChannel('online');
                    $batch->setAvailability($product->isOutOfStock() ? 'out of stock' : 'in stock');
                    $batch->setCondition('new');
                    $batch->setMpn($product->getMpn());
                    $batch->setOnlineOnly(true);
                    $batch->setTargetCountry($marketplace->countries);

                    $currency = $dX->currency;
                    $sprice = $product->getFrontendPrice();
                    $price = new Google_Service_ShoppingContent_Price();
                    $price->setCurrency($currency->currency_code ?? 'USD');
                    $price->setValue($sprice);
                    $batch->setPrice($price);

                    $listPrice = $product->list_price;
                    if ($sprice < $listPrice) {
                        $lPrice =  new Google_Service_ShoppingContent_Price();
                        $lPrice->setCurrency($currency->currency_code ?? 'USD');
                        $lPrice->setValue($listPrice);
                        $batch->setSalePrice($price);
                        $batch->setPrice($lPrice);
                    }

                    $weight = new Google_Service_ShoppingContent_ProductShippingWeight;
                    $weight->setValue($product->getShippingWeight());
                    $weight->setUnit('lb');
                    if ($weight->getValue() > 0) {
                        $batch->setShippingWeight($weight);
                    }

                    $w = new Google_Service_ShoppingContent_ProductShippingDimension;
                    $l = new Google_Service_ShoppingContent_ProductShippingDimension;
                    $h = new Google_Service_ShoppingContent_ProductShippingDimension;
                    $w->setUnit('in');
                    $l->setUnit('in');
                    $h->setUnit('in');
                    $l->setValue(min(max($product->dim_x, $product->dim_y), 150));
                    $w->setValue(min(min($product->dim_x, $product->dim_y),150));
                    $h->setValue(min($product->dim_z,150));

                    if ($l->getValue() > 0) {
                        $batch->setShippingLength($l);
                    }
                    if ($w->getValue() > 0) {
                        $batch->setShippingWidth($w);
                    }
                    if ($h->getValue() > 0) {
                        $batch->setShippingHeight($h);
                    }

                    $ats = [
                        [
                            'name' => 'payment accepted',
                            'type' => 'text',
                            'value' => 'check'
                        ],
                        [
                            'name' => 'payment accepted',
                            'type' => 'text',
                            'value' => 'visa'
                        ],
                        [
                            'name' => 'payment accepted',
                            'type' => 'text',
                            'value' => 'mastercard'
                        ],
                        [
                            'name' => 'payment accepted',
                            'type' => 'text',
                            'value' => 'discover'
                        ],
                        [
                            'name' => 'payment accepted',
                            'type' => 'text',
                            'value' => 'american express'
                        ],
                        [
                            'name' => 'payment accepted',
                            'type' => 'text',
                            'value' => 'All purchase orders are subject to verification.'
                        ],
                        [
                            'name' => 'quantity',
                            'type' => 'int',
                            'value' => $product->r_avail,
                        ],
                        [
                            'name' => 'model number',
                            'type' => 'text',
                            'value' => $product->getMpn(),
                        ],
                    ];
                    $attrs = [];
                    foreach ($ats as $a) {
                        $ca = new Google_Service_ShoppingContent_CustomAttribute();
                        $ca->setName($a['name']);
                        $ca->setType($a['type']);
                        $ca->setValue($a['value']);
                        $attrs[] = $ca;
                    }
                    $batch->setCustomAttributes($attrs);

                    if ($brand = $product->brand) {
                        $batch->setBrand($brand->brand);
                    }
                    if ($product->upc) {
                        $batch->setGtin($product->upc);
                    }
                    if (($images = $product->getImages()) && $image_model = reset($images)) {
                        $batch->setImageLink('https:' . $image_model);
                    }
                    if ($product->mult_order_quantity && $product->min_amount > 1)
                    {
                        $batch->setMultipack($product->min_amount);
                    }
                    if (($m_order_amount = $dX->getMinimalAmount()) && (float) $product->getFrontendPrice() < $m_order_amount)
                    {
                        $m_order_amount = number_format($m_order_amount, 2);
                        $batch->setShippingLabel("Minimum order value {$m_order_amount} {$price->getCurrency()}");
                    }


                    $entry = new Google_Service_ShoppingContent_ProductsCustomBatchRequestEntry();

                    $entry->setMethod($product->forsale === 'Y' ? 'insert' : 'delete');
                    if ($entry->getMethod() === 'delete') {
                        $entry->setProductId("online:{$lang}:{$marketplace->countries}:{$product->productid}");
                    } else {
                        $entry->setProduct($batch);
                    }
                    $entry->setBatchId($product->productid);
                    $entry->setMerchantId($merchantId);
                    $entries[] = $entry;
                }
            }
            if ($entries) {
                $client = new Google_Client(['verify' => false]);
                $client->setApplicationName('Google Feed');
                $client->setAuthConfig( Paths::get('www') . '/include/system/gapi-3c467d1a8e76.json');
                $client->addScope(Google_Service_ShoppingContent::CONTENT);
                $oService = new Google_Service_ShoppingContent($client);

                $batchReq = new Google_Service_ShoppingContent_ProductsCustomBatchRequest();
                $batchReq->setEntries($entries);
                $log_text = '';
                try {
                    func_backprocess_log('incremental feeds', "GB: tried to submit {$batchReq->count()} items as product feed ($merchantId)");

                    $result = $oService->products->customBatch($batchReq);

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

                } catch (Exception $e) {
                    $log_text .= "{$e->getMessage()}\n";
                }

                if ($log_text) {
                    func_backprocess_log('incremental feeds', $log_text);
                    echo $log_text;
                }

            }
        }
    }

}