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
                $cached = 0;
                $fixed = 0;
                try {
                    foreach ($data['images'] as $image_url) {
                        $link_hash = md5($image_url .':'. $data['product_code']);
                        $link_hash_new = md5($image_url);

                        if (!$model = ProductImageModel::objects()->get(['link__in' => [$link_hash, $link_hash_new]])) {
                            $file = new RemoteFile($image_url);
                            $hash = $file->getHash();
                            [$model, $is_new] = ProductImageModel::objects()->getOrNew(['hash' => $hash]);
                            $model->link = $link_hash_new;
                            if ($is_new) {
                                try {
                                    $model->path = $file;
                                    $model->save();
                                    [$model->width, $model->height] = $model->path->getImageSizes();
                                    $uploaded++;
                                } catch (UniqueConstraintViolationException $exception) {
                                    //Duplicate image
                                    if ($exception->getCode() === 1062) {
                                        $model = ProductImageModel::objects()->get(['hash' => $hash]);
                                    }
                                }
                            } else {
                                $fixed++;
                            }
                            $model->save();
                        } else {
                            $model->link = $link_hash_new;
                            $model->save(); //TODO remove after change hash
                            $cached++;
                        }

                        $images[] = $model;
                    }
                } catch (Throwable $exception) {
                    echo "$product->productcode: {$exception->getMessage()} $image_url\n";
                    $message->ack();
                    return;
                }

                self::saveImages($product, $images);

                $count = count($images);
                echo "$product->productcode:  Uploaded: $uploaded, Fixed: $fixed, Cached: $cached, Total: $count \n";
            }
            $message->ack();
        }
    }

    private static function saveImages($product, $images): void
    {
        try {
            $product->detail_images = $images;
            $product->save();
        } catch(Throwable $exception) {
            echo "{$exception->getCode()} {$exception->getMessage()}\n";
            echo "sleep 5 sec\n";
            sleep(5);
            self::saveImages($product, $images);
        }

    }
}
