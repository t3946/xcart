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
            [$image_by_link, $is_image_by_link_new] = ProductImageModel::objects()->getOrCreate(
                ['link' => $data['image_link_hash']]
            );

            [$image_by_hash, $is_image_by_hash_new] = ProductImageModel::objects()->getOrNew(
                ['hash' => $data['image_hash'], 'link__isnt' => $data['image_link_hash']]
            );

            if (!$is_image_by_hash_new) {
                // изменилась ссылка у картинки
                foreach (ProductImagesModel::objects()->filter(['image_id' => $image_by_hash->pk]) as $product_image) {
                    $product_image->image_id = $image_by_link->pk;
                    $product_image->save();
                }

                echo "image link changed\n";
                print_r($action);

                $image_by_hash->delete();
            }

            if (!$is_image_by_link_new && $data['image_hash'] !== $image_by_link->hash) {
                // изменился hash картинки у ссылки
                $action = [
                    'image_path' => $image_by_link->path->getValue(),
                    'action' => 'delete'
                ];

                echo "image hash changed\n";
                print_r($action);

                Xcart::app()->queue->send('images_action', json_encode($action, JSON_THROW_ON_ERROR));
            }

            $image_by_link->setAttributes([
                                              'path' => $data['image_path'],
                                              'width' => $data['image_width'],
                                              'height' => $data['image_height'],
                                              'link' => $data['image_link_hash'],
                                              'link_uri' => $data['image_link'],
                                              'hash' => $data['image_hash']
                                          ]);

            $image_by_link->save();
            print_r($data);

            if (isset($data['product_id']) && $product = ProductModel::objects()->get(
                    ['productid' => $data['product_id']]
                )) {
                self::addProductImage($product, $image_by_link);
            }
        }
        $message->ack();
    }

    private
    static function addProductImage(
        ProductModel $product,
        ProductImageModel $image
    ): void {
        try {
            $images = array_merge($product->detail_images->all(), [$image]);
            $product->detail_images = $images;
            $product->save();
        } catch (Throwable $exception) {
            echo "{$exception->getCode()} {$exception->getMessage()}\n";
        }
    }
}
