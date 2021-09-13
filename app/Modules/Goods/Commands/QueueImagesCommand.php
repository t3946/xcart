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

        if ($message->body && $data = json_decode($message->body, true, 512, JSON_THROW_ON_ERROR)) {
            if ($product = ProductModel::objects()->get(['productcode' => $data['product_code']])) {
                $found_images = [];
                try {
                    foreach ($data['images'] as $key => $image_link) {
                        $link_hash = md5($image_link);

                        /** @var ProductImageLinkModel $link */
                        if ($link = ProductImageLinkModel::objects()->get(['hash' => $link_hash])) {
                            $found_images[] = $link->image_id;
                            QueueImagesActiveCommand::addProductImage($product, $link->image, ($key + 1) * 10);
                        } else {
                            //create image
                            $action = [
                                'product_id' => $product->pk,
                                'dx_code' => $product->distributor->code,
                                'image_position' => ($key + 1) * 10,
                                'image_link' => $image_link,
                                'action' => 'create'
                            ];
                            print_r($action);
                            Xcart::app()->queue->send('images_action', json_encode($action, JSON_THROW_ON_ERROR));
                        }
                    }

                    if ($data['images']) {
                        //delete not existed images from product
                        /** @var ProductImagesModel $product_image */
                        $filter = ProductImagesModel::objects()->filter(
                            ['product_id' => $product->pk, 'image__is_manual' => false]
                        );

                        if ($found_images) {
                            $filter->exclude(['image_id__in' => $found_images]);
                        }

                        foreach ($filter as $product_image) {
                            $product_image->delete();
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
