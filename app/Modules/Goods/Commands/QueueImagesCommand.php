<?php


namespace Modules\Goods\Commands;

use Modules\Goods\Models\ProductImageModel;
use Modules\Goods\Models\ProductImagesModel;
use Modules\Goods\Models\ProductModel;
use PhpAmqpLib\Message\AMQPMessage;
use Throwable;
use Xcart\App\Commands\Command;
use Xcart\App\Main\Xcart;

class QueueImagesCommand extends Command
{

    public function handle($arguments = [])
    {
        Xcart::app()->queue->consume('images', [$this, 'consume']);
    }

    public function consume(AMQPMessage $message): void
    {
        /** @var ProductModel $product */
        /** @var ProductImageModel $model */

        if ($data = json_decode($message->body, true, 512, JSON_THROW_ON_ERROR)) {
            if ($product = ProductModel::objects()->get(['productcode' => $data['product_code']])) {
                $found_images = [];
                try {
                    foreach ($data['images'] as $key => $image_link) {
                        $link_hash = md5($image_link);

                        if ($model = ProductImageModel::objects()->get(['link' => $link_hash])) {
                            $found_images[] = $model->image_id;
                        } else {
                            //create image
                            $action = [
                                'product_id' => $product->pk,
                                'image_position' => ($key + 1) * 10,
                                'image_link' => $image_link,
                                'action' => 'create'
                            ];
                            Xcart::app()->queue->send('images_action', json_encode($action, JSON_THROW_ON_ERROR));
                            print_r($action);
                        }
                    }
                    if ($found_images) {
                        //delete not existed images from product
                        /** @var ProductImagesModel $product_image */
                        foreach (ProductImagesModel::objects()
                            ->filter(['product_id' => $product->pk])
                            ->exclude(['image_id__in' => $found_images]) as $product_image) {
                            $image = $product_image->image;
                            $product_image->delete();
                            if (!$image->products->count()) {
                                //delete image from s3 cloud
                                $action = [
                                    'image_path' => $image->path,
                                    'action' => 'delete'
                                ];
                                Xcart::app()->queue->send('images_action', json_encode($action, JSON_THROW_ON_ERROR));
                                print_r($action);
                            }
                        }
                    }

                } catch (Throwable $exception) {
                    echo "$product->productcode: {$exception->getMessage()}\n";
                }
            }
        }
        $message->ack();
    }
}
