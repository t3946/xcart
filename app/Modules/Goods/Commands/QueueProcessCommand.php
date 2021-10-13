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

        $body = <<<TAG
{"productcode":"","ASIN":null,"product":"Wall Mounted Baby Changing Station, Horizontal Fold Down Diaper Changer Table With Safety Straps For Commercial Bathrooms, Ada And Ansi Compliant","descr":"","fulldescr":"Restaurants, businesses, and other locations with public or private restrooms will appreciate the durability, safety, and style infused into this ADA-, ANSI-, and ASTM-compliant baby changing station. The ECR4Kids Wall-Mounted Baby Changing Station is a horizontal fold-down diaper changing table with built-in safety straps, designed for use with infants and toddlers in restrooms at businesses, restaurants, shops, and institutional facilities. It has a smooth and comfortable concave changing surface featuring an adjustable nylon child safety strap with slide release to keep kids safely in place.","brand_name":"Ecr4kids","brand_normalized":false,"forsale":"Y","eta_date_mm_dd_yyyy":null,"upc":"","supplier_categories":["Adult","Infant & toddler","New items","Toddler"],"supplier_internal_id":"https:\/\/www.ecr4kids.com\/products\/ecr4kids-wall-mounted-baby-changing-station-with-500-disposable-liners-horizontal-fold-down-diaper-changer-table-with-safety-straps-for-commercial-bathrooms-ada-and-ansi-compliant-white-granite","hash_product":"826d1e1c701662c70a9779606320979e","images":[],"alt_names":[],"dim_x":null,"dim_y":null,"dim_z":null,"shipping_weight":null,"shipping_dim_x":null,"shipping_dim_y":null,"shipping_dim_z":null,"weight":null,"min_amount":1,"mult_order_quantity":"N","is_group":true,"child_products":[{"productcode":"FIP-ELR-18009","ASIN":null,"product":"White Granite","descr":"","fulldescr":"Restaurants, businesses, and other locations with public or private restrooms will appreciate the durability, safety, and style infused into this ADA-, ANSI-, and ASTM-compliant baby changing station. The ECR4Kids Wall-Mounted Baby Changing Station is a horizontal fold-down diaper changing table with built-in safety straps, designed for use with infants and toddlers in restrooms at businesses, restaurants, shops, and institutional facilities. It has a smooth and comfortable concave changing surface featuring an adjustable nylon child safety strap with slide release to keep kids safely in place.","brand_name":"Ecr4kids","brand_normalized":false,"forsale":"Y","eta_date_mm_dd_yyyy":null,"upc":"7639605474720","supplier_categories":["Adult","Infant & toddler","New items","Toddler"],"supplier_internal_id":"https:\/\/www.ecr4kids.com\/products\/ecr4kids-wall-mounted-baby-changing-station-with-500-disposable-liners-horizontal-fold-down-diaper-changer-table-with-safety-straps-for-commercial-bathrooms-ada-and-ansi-compliant-white-granite","hash_product":"3c8e2b736771ed647dbd570862bd2cad","images":["https:\/\/cdn.shopify.com\/s\/files\/1\/0410\/4205\/1239\/products\/ii4qlkffimuxucsddvgc.jpg?v=1633589772"],"alt_names":[],"dim_x":null,"dim_y":null,"dim_z":null,"shipping_weight":null,"shipping_dim_x":null,"shipping_dim_y":null,"shipping_dim_z":null,"weight":95.25,"min_amount":1,"mult_order_quantity":"N","is_group":false,"child_products":[],"group_mask":"Wall Mounted Baby Changing Station, Horizontal Fold Down Diaper Changer Table With Safety Straps For Commercial Bathrooms, Ada And Ansi Compliant","r_avail":0,"mpn":"ELR-18009","lead_time_message":null,"attributes":null,"product_files":[],"options":[],"videos":[]},{"productcode":"FIP-ELR-17520-GS","ASIN":null,"product":"Grey Speckled","descr":"","fulldescr":"Restaurants, businesses, and other locations with public or private restrooms will appreciate the durability, safety, and style infused into this ADA-, ANSI-, and ASTM-compliant baby changing station. The ECR4Kids Wall-Mounted Baby Changing Station is a horizontal fold-down diaper changing table with built-in safety straps, designed for use with infants and toddlers in restrooms at businesses, restaurants, shops, and institutional facilities. It has a smooth and comfortable concave changing surface featuring an adjustable nylon child safety strap with slide release to keep kids safely in place.","brand_name":"Ecr4kids","brand_normalized":false,"forsale":"Y","eta_date_mm_dd_yyyy":null,"upc":"7639607752642","supplier_categories":["Adult","Infant & toddler","New items","Toddler"],"supplier_internal_id":"https:\/\/www.ecr4kids.com\/products\/ecr4kids-wall-mounted-baby-changing-station-with-500-disposable-liners-horizontal-fold-down-diaper-changer-table-with-safety-straps-for-commercial-bathrooms-ada-and-ansi-compliant-white-granite","hash_product":"9f42c903304b787e1e2c47b68cb69520","images":["https:\/\/cdn.shopify.com\/s\/files\/1\/0410\/4205\/1239\/products\/zu82d2e5d5zi3koux35e.jpg?v=1633589772"],"alt_names":[],"dim_x":null,"dim_y":null,"dim_z":null,"shipping_weight":null,"shipping_dim_x":null,"shipping_dim_y":null,"shipping_dim_z":null,"weight":90.72,"min_amount":1,"mult_order_quantity":"N","is_group":false,"child_products":[],"group_mask":"Wall Mounted Baby Changing Station, Horizontal Fold Down Diaper Changer Table With Safety Straps For Commercial Bathrooms, Ada And Ansi Compliant","r_avail":0,"mpn":"ELR-17520-GS","lead_time_message":null,"attributes":null,"product_files":[],"options":[],"videos":[]}],"group_mask":null,"r_avail":0,"mpn":"","lead_time_message":null,"attributes":null,"product_files":[],"options":[],"videos":[],"manufacturerid":640,"storefront":10}
TAG;


        if ($message->body && $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR)) {
            try {
                $feed =  null;
                $data = array_filter($data, static fn($v) => $v !== null);

                if ($data['storefront'] !== null) {
                    $site = SiteModel::objects()->get(['storefrontid' => $data['storefront']]);
                    /** @var SupplierFeedModel $feed */
                    $feed = SupplierFeedModel::objects()->get(
                        ['manufacturerid' => $data['manufacturerid'], 'storefront_id' => $data['storefront']]
                    );
                }

                $data['source'] ??= 'feed';

                if ($data['source'] !== 'manual' && (!$feed || !$feed->enabled)) {
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

                    if ($data['source'] === 'manual' || $product->hash_product !== $data['hash_product']) {
                        $product->setAttributes($data);
                        if ($data['eta_date_mm_dd_yyyy']) {
                            $product->eta_date_mm_dd_yyyy = strtotime($data['eta_date_mm_dd_yyyy']);
                        }
                        if ($site) {
                            $product->sites = [$site];
                            ProductHelper::setProductBrand($product, $data['brand_name'], $site);
                            if ($is_new) {
                                $product->save();
                                $product->setMainCategory($site->base_category);
                            }
                        }
                        if ($data['source'] !== 'manual') {
                            $product->group_root = null;
                        }

                        $changed = SupplierFeedHelper::getChanged($product);
                        $product->save();
                        if ($data['attributes'] && $site) {
                            ProductHelper::setProductAttributes($product, $data['attributes'], $site);
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
                            $product->group_mask = $group_product->product;
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
