<?php


namespace Modules\Goods\Commands;


use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Modules\Goods\Models\ProductImageModel;
use Modules\Goods\Models\ProductModel;
use PhpAmqpLib\Message\AMQPMessage;
use Throwable;
use Xcart\App\Commands\Command;
use Xcart\App\Main\Xcart;
use Xcart\App\Storage\Files\RemoteFile;

class QueueImagesCommand extends Command
{

    public function handle($arguments = [])
    {
        Xcart::app()->queue->consume('images', [$this, 'consume']);
    }

    public function consume(AMQPMessage $message): void
    {
        /** @var ProductModel $product */

        if ($data = json_decode($message->body, true, 512, JSON_THROW_ON_ERROR)) {
            if ($product = ProductModel::objects()->get(['productcode' => $data['product_code']])) {
                $images = [];
                $uploaded = 0;
                try {
                    foreach ($data['images'] as $image_url) {
                        $file = new RemoteFile($image_url);
                        $hash =  $file->getHash();
                        [$model, $is_new] = ProductImageModel::objects()->getOrNew(['hash' => $hash]);
                        if ($is_new) {
                            try {
                                $model->path = $file;
                                $model->save();
                                [$model->width, $model->height] = $model->path->getImageSizes();
                                $model->save();
                                $uploaded++;
                            } catch (UniqueConstraintViolationException $exception) {
                                //Duplicate image
                                if ($exception->getCode() === 1062) {
                                    $model = ProductImageModel::objects()->get(['hash' => $hash]);
                                }
                            }
                        }
                        $images[] = $model;
                    }
                } catch (Throwable $exception) {
                    echo "$product->productcode: {$exception->getMessage()} $image_url\n";
                    $message->ack();
                    return;
                }

                $product->detail_images = $images;
                $product->save();

                $count = count($images);
                echo "$product->productcode:  Uploaded: $uploaded, Total: $count \n";
            }
            $message->ack();
        }
    }
}
