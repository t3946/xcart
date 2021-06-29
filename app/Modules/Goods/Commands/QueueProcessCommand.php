<?php


namespace Modules\Goods\Commands;


use Modules\Goods\Helpers\ProductHelper;
use Modules\Goods\Models\ProductModel;
use PhpAmqpLib\Message\AMQPMessage;
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
            if ($model = ProductModel::objects()->get(['productcode' => $child_product['productcode']])) {
                if ($parent = $model->parent) {
                    return $parent->productcode;
                }
            }
        }
        return ProductHelper::getNewGroupSKU($data['manufacturerid']);
    }

    public function consume(AMQPMessage $message): void
    {
        /** @var ProductModel $product  */
        /** @var ProductModel $group_product  */

        if ($data = json_decode($message->body, true, 512, JSON_THROW_ON_ERROR)) {

            $data = array_filter($data, static fn($v) => $v !== null);
            $product_code = $data['productcode'];

            if ($product_code && !$data['is_group']) {

                //Simple product process
                [$product] = ProductModel::objects()->getOrNew(['productcode' => $product_code]);
                if ($product->hash_product !== $data['hash_product']) {
                    print("{$product_code}\n");
                    $product->setAttributes($data);
                    $product->save();
                }
            } elseif ($data['is_group']) {

                //Group product process
                $group_code = self::getGroupProductCode($data);
                [$group_product] = ProductModel::objects()->getOrNew(['productcode' => $group_code]);
                if ($group_product->hash_product !== $data['hash_product']) {
                    $group_product->setAttributes($data);
                    $group_product->group_root = $group_product->productid;
                    $group_product->productcode = $group_code;
                    $group_product->save();
                }

                foreach ($data['child_products'] as $child) {
                    [$product] = ProductModel::objects()->getOrNew(['productcode' => $child['productcode']]);
                    if ($product->hash_product !== $child['hash_product']) {
                        print("{$child['productcode']}\n");
                        $product->setAttributes($child);
                        $product->parent = $group_product;
                        $product->group_mask = $group_product->product;
                        $product->save();
                    }
                }
            }

            $message->ack();
        }
    }
}
