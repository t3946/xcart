<?php


namespace Modules\Goods\Commands;


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
            if ($model && $parent = $model->parent && strpos($parent->productcode, 'GROUP') !== false) {
                return $parent->productcode;
            }
        }
        return ProductHelper::getNewGroupSKU($data['manufacturerid']);
    }

    public function consume(AMQPMessage $message): void
    {
        /** @var ProductModel $product  */
        /** @var ProductModel $group_product  */
        /** @var SiteModel $site */

        if ($data = json_decode($message->body, true, 512, JSON_THROW_ON_ERROR)) {

            try {
                $data = array_filter($data, static fn($v) => $v !== null);

                if ($data['storefront'] !== null) {
                    $site = SiteModel::objects()->get(['storefrontid' => $data['storefront']]);
                } else {
                    echo "Empty site for product {$data['productcode']}\n";
                    return;
                }

                if (!$data['is_group']) {
                    //Simple product process
                    $product_code = $data['productcode'];
                    if (!$product_code) {
                        echo "Empty productcode, skip product\n";
                        return;
                    }
                    [$product, $is_new] = ProductModel::objects()->getOrNew(['productcode' => $product_code]);
                    if ($product->hash_product !== $data['hash_product']) {
                        $product->setAttributes($data);
                        //$product->fulldescr = ProductHelper::cleanProductFullDescription($product->fulldescr);
                        $product->sites = [$site];
                        ProductHelper::setProductBrand($product, $data['brand_name'], $site);
                        $changed = SupplierFeedHelper::getChanged($product);
                        $product->save();
                        ProductHelper::setProductAttributes($product, $data['attributes'] ?? [], $site);
                        if ($is_new) {
                            $product->setMainCategory($site->base_category);
                        }
                        SupplierFeedHelper::feedFiles($product, $data);
                        SupplierFeedHelper::getVideos($product);
                        print($is_new ? "Adding" : '' . "$product_code\n");
                        print_r($changed);
                    }
                } elseif ($data['is_group']) {
                    //Group product process
                    $group_code = self::getGroupProductCode($data);
                    /** @var ProductModel $group_product */
                    [$group_product, $is_new] = ProductModel::objects()->getOrNew(['productcode' => $group_code]);
                    if ($group_product->hash_product !== $data['hash_product']) {
                        $group_product->setAttributes($data);
                        $group_product->group_root = $group_product->productid;
                        $group_product->productcode = $group_code;
                        //$group_product->fulldescr = ProductHelper::cleanProductFullDescription($group_product->fulldescr);
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
                            echo "Empty child productcode of {$group_code} group, skip product\n";
                            continue;
                        }
                        [$product] = ProductModel::objects()->getOrNew(['productcode' => $child_code]);
                        if ($product->hash_product !== $child['hash_product']) {
                            $child += ['manufacturerid' => $group_product['manufacturerid']];
                            $product->setAttributes($child);
                            $product->parent = $group_product;
                            $product->group_mask = $group_product->product;
                            //$product->fulldescr = ProductHelper::cleanProductFullDescription($product->fulldescr);
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
                            print($is_new ? "Adding" : '' . "$child_code\n");
                            print_r($changed);
                        }
                    }
                }
            } catch (Throwable $exception) {
                Xcart::app()->logger->error($exception->getMessage(), [], 'feed');
            }
            finally {
                $message->ack();
            }
        }
    }
}
