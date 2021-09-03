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

        if ($message->body && $data = json_decode($message->body, true, 512, JSON_THROW_ON_ERROR)) {
            [$image_by_link, $is_image_by_link_new] = ProductImageModel::objects()->getOrNew(
                ['link' => $data['image_link_hash']]
            );

            [$image_by_hash, $is_image_by_hash_new] = ProductImageModel::objects()->getOrNew(
                ['hash' => $data['image_hash'], 'link__isnt' => $data['image_link_hash']]
            );

            if ($is_image_by_link_new) {
                if ($is_image_by_hash_new) {
                    // новая картинка
                    echo "new image\n";
                } else {
                    // изменилась ссылка у картинки
                    echo "image link changed\n";
                    $image_by_link = $image_by_hash;
                }
            } else {
                if ($is_image_by_hash_new) {
                    if ($data['image_hash'] !== $image_by_link->hash) {
                        // изменился hash картинки у ссылки
                        $action = [
                            'image_path' => $image_by_link->path->getValue(),
                            'action' => 'delete'
                        ];

                        echo "image hash changed\n";
                        print_r($action);

                        Xcart::app()->queue->send('images_action', json_encode($action, JSON_THROW_ON_ERROR));
                    }
                } else {
                    if ($image_by_link->pk === $image_by_hash->pk) {
                        // одинаковые картинки
                        echo "same image\n";
                    } else {
                        // изменился hash картинки у ссылки
                        $action = [
                            'image_path' => $image_by_link->path->getValue(),
                            'action' => 'delete'
                        ];
                        echo "image hash changed 2\n";
                        print_r($action);

                        Xcart::app()->queue->send('images_action', json_encode($action, JSON_THROW_ON_ERROR));

                        $image_by_link->delete();

                        $image_by_link = $image_by_hash;
                    }
                }
            }

            $image_by_link->setAttributes([
                                              'path' => $data['image_path'],
                                              'width' => $data['image_width'],
                                              'height' => $data['image_height'],
                                              'link' => $data['image_link_hash'],
                                              'link_uri' => $data['image_link'],
                                              'hash' => $data['image_hash']
                                          ]);

            if ((int)$data['image_position'] === 0) {
                $image_by_link->is_manual = true;
            }

            $image_by_link->save();

            print_r($data);

            if (isset($data['product_id']) && $product = ProductModel::objects()->get(
                    ['productid' => $data['product_id']]
                )) {
                self::addProductImage($product, $image_by_link, (int)$data['image_position']);
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
