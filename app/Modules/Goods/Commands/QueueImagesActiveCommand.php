<?php


namespace Modules\Goods\Commands;

use Modules\Goods\Models\ProductImageModel;
use Modules\Goods\Models\ProductImagesModel;
use Modules\Goods\Models\ProductModel;
use PhpAmqpLib\Message\AMQPMessage;
use Throwable;
use Xcart\App\Commands\Command;
use Xcart\App\Main\Xcart;

class QueueImagesActiveCommand extends Command
{

    public function handle($arguments = [])
    {
        Xcart::app()->queue->consume('images_active', [$this, 'consume']);
    }

    public function consume(AMQPMessage $message): void
    {
        /** @var ProductModel $product */
        /** @var ProductImageModel $model */

        if ($data = json_decode($message->body, true, 512, JSON_THROW_ON_ERROR)) {
            [$image] = ProductImageModel::objects()->getOrCreate(['hash' => $data['image_hash']]);
            $image->setAttributes([
                                      'path' => $data['image_path'],
                                      'width' => $data['image_width'],
                                      'height' => $data['image_height'],
                                      'link' => $data['image_link_hash'],
                                  ]);
            $image->save();

            if (isset($data['product_id']) && $product = ProductModel::objects()->get(
                    ['productid' => $data['product_id']]
                )) {
                self::addProductImage($product, $image);
            }
        }
        $message->ack();
    }

    private static function addProductImage(ProductModel $product, ProductImageModel $image): void
    {
        try {
            $images = array_merge($product->detail_images->all(), [$image]);
            $product->detail_images = $images;
            $product->save();
        } catch (Throwable $exception) {
            echo "{$exception->getCode()} {$exception->getMessage()}\n";
        }
    }
}
