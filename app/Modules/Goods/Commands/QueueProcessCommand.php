<?php


namespace Modules\Goods\Commands;


use Modules\Distributor\Models\SupplierFeedModel;
use Modules\Goods\Helpers\ProductHelper;
use Modules\Goods\Helpers\SupplierFeedHelper;
use Modules\Goods\Models\ProductModel;
use Modules\Sites\Models\SiteModel;
use PhpAmqpLib\Message\AMQPMessage;
use Throwable;
use Xcart\App\Commands\Command;
use Xcart\App\Main\Xcart;

class QueueProcessCommand extends Command
{

    public function handle($arguments = [])
    {
        Xcart::app()->queue->consume('products', [$this, 'consume']);
    }

    private static function getGroupProductCode(array $data): ?string
    {
        /** @var ProductModel $model */
        /** @var ProductModel $parent */
        foreach ($data['child_products'] as $child_product) {
            $model = ProductModel::objects()->get(['productcode' => $child_product['productcode']]);
            if ($model && ($parent = $model->parent) && strpos($parent->productcode, 'GROUP') !== false) {
                return $parent->productcode;
            }
        }
        return ProductHelper::getNewGroupSKU($data['manufacturerid']);
    }

    public function consume(AMQPMessage $message): void
    {
        /** @var ProductModel $product */
        /** @var ProductModel $group_product */
        /** @var SiteModel $site */

        if ($message->body) {
            try {
                $feed =  null;

                $data = json_decode($message->body, true, 512, JSON_THROW_ON_ERROR);

                if (!$data) {
                    return;
                }

                $data = array_filter($data, static fn($v) => $v !== null);

                $is_manual_feed = $data['source'] === 'manual';

                if ($data['storefront'] !== null) {
                    $site = SiteModel::objects()->get(['storefrontid' => $data['storefront']]);
                    /** @var SupplierFeedModel $feed */
                    $feed = SupplierFeedModel::objects()->get(
                        ['manufacturerid' => $data['manufacturerid'], 'storefront_id' => $data['storefront']]
                    );
                    if ($feed) {
                        foreach($feed->dont_update_fields as $field) {
                            if ($field) {
                                unset($data[$field]);
                            }
                        }
                    }
                }

                $data['source'] ??= 'feed';

                if (!$is_manual_feed && (!$feed || !$feed->enabled)) {
                    echo "Feed is not active\n";
                    return;
                }

                if (!$data['is_group']) {
                    //Simple product process
                    if (!$product_code = $data['productcode']) {
                        echo "Empty productcode, skip product\n";
                        return;
                    }
                    [$product, $is_new] = ProductModel::objects()->getOrNew(['productcode' => $product_code]);

                    if ($is_new && !$site) {
                        echo "Empty site for product {$data['productcode']}\n";
                        return;
                    }

                    if ($is_new && $feed && $feed->feed_type === 'I') {
                        echo "Dont add product for inventory feed: {$data['productcode']}\n";
                        return;
                    }

                    if ($is_manual_feed || $product->hash_product !== $data['hash_product']) {
                        $product->setAttributes($data);
                        if ($data['eta_date_mm_dd_yyyy']) {
                            $product->eta_date_mm_dd_yyyy = strtotime($data['eta_date_mm_dd_yyyy']);
                        }
                        if ($site) {
                            $product->sites = [$site];
                            ProductHelper::setProductBrand($product, $data['brand_name'], $site);
                            if ($is_new) {
                                $product->save();
                                if ($site->base_category) {
                                    $product->setMainCategory($site->base_category);
                                }
                            }
                        }

                        if (!$is_manual_feed) {
                            $product->group_root = null;
                        }

                        if ($is_manual_feed && (float)$data['cost_to_us'] > 0) {
                            $product->forsale = 'Y';
                        }

                        $changed = SupplierFeedHelper::getChanged($product);
                        $product->save();

                        if ($site && !$is_manual_feed) {
                            ProductHelper::setProductAttributes($product, $data['attributes'] ?? [], $site);
                        }
                        SupplierFeedHelper::feedFiles($product, $data);
                        SupplierFeedHelper::getVideos($product);
                        print(($is_new ? 'Adding' : '') . "$product->productcode\n");
                        print_r($changed);
                    }
                } else {
                    //Group product process
                    if (!$site) {
                        echo "Empty site for group product\n";
                        return;
                    }
                    if (!$data['child_products']) {
                        echo "Empty children field for group product\n";
                        return;
                    }

                    $group_code = self::getGroupProductCode($data);
                    /** @var ProductModel $group_product */
                    [$group_product, $is_new] = ProductModel::objects()->getOrNew(['productcode' => $group_code]);

                    if ($is_new && $feed && $feed->feed_type === 'I') {
                        echo "Dont add group product for inventory feed: {$group_code}\n";
                        return;
                    }

                    if ($group_product->hash_product !== $data['hash_product']) {
                        $group_product->setAttributes($data);
                        $group_product->group_root = $group_product->productid;
                        $group_product->productcode = $group_code;
                        $group_product->sites = [$site];
                        $group_product->save();
                        $group_product->parent = $group_product;
                        ProductHelper::setProductBrand($group_product, $data['brand_name'], $site);
                        $group_product->save();
                        ProductHelper::setProductAttributes($group_product, $data['attributes'] ?? [], $site);
                        if ($is_new) {
                            $group_product->setMainCategory($site->base_category);
                        }
                    }

                    foreach ($data['child_products'] as $child) {
                        //Child product process
                        $child_code = $child['productcode'];
                        if (!$child_code) {
                            echo "Empty child productcode of $group_code group, skip product\n";
                            continue;
                        }
                        [$product, $is_new] = ProductModel::objects()->getOrNew(['productcode' => $child_code]);

                        if ($is_new && $feed && $feed->feed_type === 'I') {
                            echo "Dont add child product for inventory feed: {$child_code}\n";
                            return;
                        }

                        if ($product->hash_product !== $child['hash_product'] || $product->group_root != $group_product->productid) {
                            $child += ['manufacturerid' => $group_product['manufacturerid']];
                            $product->setAttributes($child);
                            if ($data['eta_date_mm_dd_yyyy']) {
                                $product->eta_date_mm_dd_yyyy = strtotime($child['eta_date_mm_dd_yyyy']);
                            }
                            $product->parent = $group_product;
                            $product->sites = [$site];
                            ProductHelper::setProductBrand($product, $child['brand_name'], $site);
                            $changed = SupplierFeedHelper::getChanged($product);
                            $product->save();
                            ProductHelper::setProductAttributes($product, $child['attributes'] ?? [], $site);
                            if ($is_new) {
                                $product->setMainCategory($site->base_category);
                            }
                            SupplierFeedHelper::feedFiles($product, $data);
                            SupplierFeedHelper::getVideos($product);
                            print(($is_new ? 'Adding' : '') . "$product->productcode\n");
                            print_r($changed);
                        }
                    }
                }
            } catch (Throwable $exception) {
                Xcart::app()->logger->error($exception->getMessage(), [], 'feed');
            } finally {
                $message->ack();
            }
        }
    }
}
