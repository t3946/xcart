<?php

namespace Modules\Mail\Commands;


use Modules\Goods\Models\ProductModel;
use Xcart\App\Commands\Command;
use Xcart\App\Main\Xcart;

class TestCommand extends Command
{
    public function handle($arguments = [])
    {
        foreach (ProductModel::without_group()->filter(['sites__storefrontid' => 0, 'forsale' => 'Y', 'manufacturerid' => 523]) as $product) {
            foreach ($product->detail_images as $image) {
                $url = $image->links->limit(1)->get()->url;
                if ($url) {
                    $action = [
                        'action' => 'update',
                        'image_id' => $image->pk,
                        'image_path' => $image->path->getValue(),
                        'image_link' => $url
                ];
                    Xcart::app()->queue->send('images_action', json_encode($action));
                }
            }
        }
    }

    public function exception($arguments = [])
    {
        throw new \Exception();
    }
}