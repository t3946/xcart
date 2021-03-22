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
use Google_Service_ShoppingContent_ProductShipping;
use Google_Service_ShoppingContent_ProductShippingDimension;
use Google_Service_ShoppingContent_ProductShippingWeight;
use Xcart\App\QueryBuilder\Expression;
use Xcart\App\QueryBuilder\Q\QOr;
use Xcart\App\QueryBuilder\Q\QOrNot;
use Modules\Core\Models\StateModel;
use Modules\Distributor\Models\DistributorModel;
use Modules\Goods\Helpers\ProductHelper;
use Modules\Goods\Models\UpdatedProductModel;
use Modules\Shipping\Helpers\ShippingHelper;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Commands\Command;
use Xcart\App\Helpers\Paths;

class GoogleShoppingProductCommand extends Command
{
    public const MAX_WEIGHT = 2000;
    public const MAX_PRICE = 20000;

    public function handle($arguments = []): void
    {

        /** @var SiteModel $site */
        foreach (SiteModel::objects()->filter(['marketplaces__marketplace_id' => 1])->order(['storefrontid']) as $site) {

            func_backprocess_log('incremental product feed', $l = "Storefront: {$site->domain} Storefrontid: {$site->storefrontid}");
            echo "{$l}\n";

            $config = $site->getConfig();

            if (!$site->marketplaces) {
                continue;
            }
            $marketplace = $site->marketplaces->filter(['marketplace_id' => 1])->limit(1)->get();
            $merchantId = $marketplace->P1;
            $lang = $config['Preferred_language'] ?? 'en';
            $i = 0;

            /** @var UpdatedProductModel[] $up */
            while ($up = UpdatedProductModel::objects()
                ->select(['*', 'product__forsale', 'utype' => new Expression('MIN(type)')])
                ->filter(['product__sites__storefrontid' => $site->storefrontid, 'type' => 1, new QOr(['mask__isnull' => true, new QOrNot(['mask' => 0])])])
                ->group(['resourceid'])
                ->order(['-utype', '-product__forsale'])
                ->paginate(++$i, 100)->all()) {
                $toDelete = [];
                $entries = [];

                foreach ($up as $model) {
                    if ($product = $model->product) {
                        /** @var DistributorModel $dX */
                        $dX = $product->distributor;

                        $batch = new Google_Service_ShoppingContent_Product();
                        $batch->setOfferId($product->productid);
                        $batch->setTitle($product->getFrontendName());
                        $batch->setDescription($product->getFrontendDescription());
                        $batch->setLink('https:' . $product->getAbsoluteUrl(true) . '?origin=google_product_ads');

                        $batch->setContentLanguage($lang);
                        $batch->setChannel('online');
                        /*if (!$product->isOutOfStockFrontend() && $product->isOutOfStock()) {
                            $availability = 'out of stock';
                            if ($product->eta_date_mm_dd_yyyy) {
                                $etaDate = new DateTime();
                                $etaDate->setTimestamp($product->eta_date_mm_dd_yyyy);
                                $batch->setAvailabilityDate($etaDate->format(DateTime::ISO8601));
                            }
                        } else*/
                        $availability = $product->isOutOfStock() ? 'out of stock' : 'in stock';
                        $batch->setAvailability($availability);

                        $batch->setCondition('new');
                        $batch->setMpn($product->getMpn());
                        $batch->setOnlineOnly(true);
                        $batch->setTargetCountry($marketplace->countries);

                        $currency = $dX->currency;
                        $sprice = $product->getFrontendPrice($product->min_amount ?? 1) * ($product->min_amount ?? 1);
                        $price = new Google_Service_ShoppingContent_Price();
                        $price->setCurrency($currency->currency_code ?? 'USD');
                        $price->setValue($sprice);
                        $batch->setPrice($price);

                        $listPrice = $product->list_price;
                        if ($sprice < $listPrice) {
                            $lPrice = new Google_Service_ShoppingContent_Price();
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
                        $dimx = $product->shipping_dim_x ?: $product->dim_x;
                        $dimy = $product->shipping_dim_y ?: $product->dim_y;
                        $dimz = $product->shipping_dim_z ?: $product->dim_z;
                        $l->setValue(max(min(max($dimx, $dimy), 150),0.1));
                        $w->setValue(max(min(min($dimx, $dimy), 150), 0.1));
                        $h->setValue(max(min($dimz, 150), 0.1));
                        $batch->setShippingLength($l);
                        $batch->setShippingWidth($w);
                        $batch->setShippingHeight($h);

                        /*if ($l->getValue() > 0) {
                            $batch->setShippingLength($l);
                        }
                        if ($w->getValue() > 0) {
                            $batch->setShippingWidth($w);
                        }
                        if ($h->getValue() > 0) {
                            $batch->setShippingHeight($h);
                        }*/
                        $shippingValues = [];
                        if (ProductHelper::isGoogleShoppingEnabled($product)) {
                            if ($states = StateModel::objects()
                                ->filter(['country_code' => 'US'])
                                ->exclude(['base_state_zipcode' => ''])
                                ->order(['state'])
                                ->all()) {
                                /** @var StateModel $state */
                                foreach ($states as $state) {
                                    $states[$state->stateid] = $state;
                                    $rates[$state->stateid] = ShippingHelper::getStateShipping($product->productid, $product->min_amount ?? 1, $state);
                                    $rate = reset($rates[$state->stateid]);
                                    if ($rate && $sModel = $rate->shipping) {
                                        $shipping = new Google_Service_ShoppingContent_ProductShipping();
                                        $shipping->setCountry($state->country_code);
                                        $shipping->setRegion($state->code);
                                        $shipping->setService($rate->shipping->getFrontendName());
                                        $price = new Google_Service_ShoppingContent_Price();
                                        $price->setCurrency($currency->currency_code ?? 'USD');
                                        $price->setValue($rate->getShippingCharge());
                                        $shipping->setPrice($price);
                                        $shippingValues[] = $shipping;
                                    }
                                }
                            }

                            $batch->setCustomLabel2('UPS rates');

                            $batch->setShipping($shippingValues);
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
                            $batch->setImageLink('https://cdn.' . $product->getBaseDomain(). $image_model->getURL());
                        }
                        if ($product->mult_order_quantity && $product->min_amount > 1) {
                            $batch->setMultipack($product->min_amount);
                        }
                        if (($m_order_amount = $dX->getMinimalAmount()) && (float)$product->getFrontendPrice() < $m_order_amount) {
                            $m_order_amount = number_format($m_order_amount, 2);
                            $batch->setShippingLabel("Minimum order value {$m_order_amount} {$price->getCurrency()}");
                        }

                        $entry = new Google_Service_ShoppingContent_ProductsCustomBatchRequestEntry();

                        if ($shippingValues &&
                            ProductHelper::isGoogleShoppingEnabled($product) &&
                            $batch->getShippingWeight() < self::MAX_WEIGHT &&
                            $batch->getPrice() < self::MAX_PRICE) {
                            $method = 'insert';
                        } else {
                            $method = 'delete';
                        }

                        $entry->setMethod($method);
                        if ($entry->getMethod() === 'delete') {
                            $entry->setProductId("online:{$lang}:{$marketplace->countries}:{$product->productid}");
                        } else {
                            $entry->setProduct($batch);
                        }
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

                    $batchReq = new Google_Service_ShoppingContent_ProductsCustomBatchRequest();
                    $batchReq->setEntries($entries);
                    $log_text = '';
                    try {
                        func_backprocess_log('incremental product feed', $l = "GB: tried to submit {$batchReq->count()} items as product feed ($merchantId)");
                        echo "{$l}\n";

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
                        UpdatedProductModel::objects()->delete(['resourceid__in' => $toDelete, 'type' => 1]);

                    } catch (Exception $e) {
                        $log_text .= "{$e->getMessage()}\n";
                    }

                    if ($log_text) {
                        func_backprocess_log('incremental product feed', $log_text);
                        echo $log_text;
                    }
                }
            }
        }
    }

}