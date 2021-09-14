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

class QueueImagesUpdateCommand extends Command
{

    public function handle($arguments = [])
    {
        $product = ProductModel::objects()->get(['productid' => 3680225]);
        /** @var ProductImageModel $image */
        foreach ($product->detail_images as $image) {
            if ($link = $image->links->limit(1)->order(['-created_at'])->get()) {
                $action = [
                    'action' => 'update',
                    'image_id' => $image->pk,
                    'image_path' => $image->path->getValue(),
                    'image_link' => $link->url
            ];
                Xcart::app()->queue->send('images_action', json_encode($action, JSON_THROW_ON_ERROR));
            }

        }
    }
}
