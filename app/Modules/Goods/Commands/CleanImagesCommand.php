<?php


namespace Modules\Goods\Commands;


use Dariuszp\CliProgressBar;
use Modules\Goods\Models\ImageDModel;
use Throwable;
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
                try {
                    if ($images = ImageDModel::objects()->all(['image_path' => './' . $file['path']])) {
                        $md5 = md5($storage->read($file['path']));
                        array_walk($images, static function ($image) use ($md5, $file) {
                            if ($image->md5 !== $md5) {
                                echo "{$file['path']} new md5: " . $md5 . PHP_EOL;
                                $image->md5 = $md5 ?? null;
                                $image->save();
                            }
                        });
                    } else {
                        echo "Delete: {$file['path']}". PHP_EOL;
                        //$storage->delete($file['path']);
                    }
                } catch (Throwable $e) {
                    echo $e->getMessage() . PHP_EOL;
                }
                $bar->progress();
            }
            $bar->end();
        }
    }
}