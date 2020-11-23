<?php


namespace Modules\Goods\Commands;


use Modules\Goods\Models\ImageDModel;
use Xcart\App\Commands\Command;
use Xcart\App\Main\Xcart;

class CleanImagesCommand extends Command
{

    public function handle($arguments = [])
    {
        if ($storage = Xcart::app()->storage->getFilesystem('www')) {
            foreach ($storage->listContents('images/D') as $file) {
                if ($images = ImageDModel::objects()->all(['image_path' => './' . $file['path']])) {
                    $md5 = md5($storage->read($file['path']));
                    array_walk($images, static function ($image) use ($file, $md5) {
                        if (($old = $image->md5) !== $md5) {
                            $image->update(['md5' => $md5]);
                            echo "Update | Product_id: {$image['id']} {$file['path']} Old md5 {$old}; New md5 {$md5}" . PHP_EOL;
                        }
                    });

                } else {
                    $storage->delete($file['path']);
                    echo "Delete | ${file['path']}" . PHP_EOL;
                }
            }
        }
    }
}