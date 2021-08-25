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
            if ($data['image_id'] && $image = ProductImageModel::objects()->get(['image_id' => $data['image_id']])) {
                try {
                    if ($old = ProductImageModel::objects()->get(['hash' => $data['image_hash']])) {
                        $old->delete();
                    }

                    $params = [
                        'width' => $data['image_width'],
                        'height' => $data['image_height'],
                        'hash' => $data['image_hash'],
                        'path' => $data['image_path'],
                        'is_downloaded' => true
                    ];
                    $image->setAttributes($params);
                    $image->save();
                    print_r($image->getAttributes());
                } catch (Throwable $exception) {
                    echo "$product->productcode: {$exception->getMessage()}\n";
                }
            }
        }
        $message->ack();
    }
}
