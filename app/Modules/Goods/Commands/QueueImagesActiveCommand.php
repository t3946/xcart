<?php


namespace Modules\Goods\Commands;

use Modules\Goods\Models\ProductImageLinkModel;
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

        if ($message->body && $data = json_decode($message->body, true, 512, JSON_THROW_ON_ERROR)) {
            [$image_by_link] = ProductImageLinkModel::objects()->getOrNew(
                ['hash' => $data['image_link_hash']]
            );

            /** @var ProductImageModel $image */
            [$image] = ProductImageModel::objects()->getOrNew(
                ['hash' => $data['image_hash']]
            );

            $image->setAttributes([
                'path' => $data['image_path'],
                'width' => $data['image_width'],
                'height' => $data['image_height'],
                'hash' => $data['image_hash']
            ]);

            if ($image->getChangedAttributes()) {
                echo "Image has changed\n";
                echo "==============Queue data=============\n";
                print_r($data);
                echo "==============Changed image data=============\n";
                print_r($image->getChangedAttributes());
            }

            $image->save();

            $image_by_link->setAttributes([
                'image_id' => $image->pk,
                'url' => $data['image_link']
            ]);

            if ($image_by_link->getChangedAttributes()) {
                echo "Image link has changed\n";
                echo "==============Queue data=============\n";
                print_r($data);
                echo "==============Changed image data=============\n";
                print_r($image_by_link->getChangedAttributes());
            }

            $image_by_link->save();


            if (isset($data['product_id']) && $product = ProductModel::objects()->get(
                    ['productid' => $data['product_id']]
                )) {
                self::addProductImage($product, $image, (int)$data['image_position']);
            }
        }
        $message->ack();
    }

    private static function addProductImage(ProductModel $product, ProductImageModel $image, int $order_by): void
    {
        try {
            [$model] = ProductImagesModel::objects()->getOrNew([
                'product_id' => $product->pk,
                'image_id' => $image->pk,
            ]);

            $model->order_by = $order_by;

            $model->save();
        } catch (Throwable $exception) {
            echo "{$exception->getCode()} {$exception->getMessage()}\n";
        }
    }
}
