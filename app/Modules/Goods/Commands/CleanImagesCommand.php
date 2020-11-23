<?php


namespace Modules\Goods\Commands;


use Dariuszp\CliProgressBar;
use Modules\Goods\Models\ImageDModel;
use Xcart\App\Commands\Command;
use Xcart\App\Main\Xcart;

class CleanImagesCommand extends Command
{

    public function handle($arguments = [])
    {
        ini_set('memory_limit', '4G');
        if ($storage = Xcart::app()->storage->getFilesystem('www')) {
            $total = $storage->listContents('images/D');
            $bar = new CliProgressBar(count($total));
            foreach ($total as $file) {
                if ($images = ImageDModel::objects()->all(['image_path' => './' . $file['path']])) {
                    $md5 = md5($storage->read($file['path']));
                    array_walk($images, static function ($image) use ($md5) {
                        if ($image->md5 !== $md5) {
                            $image->update(['md5' => $md5]);
                        }
                    });

                } else {
                    $storage->delete($file['path']);
                }
                $bar->progress();
            }
            $bar->end();
        }
    }
}