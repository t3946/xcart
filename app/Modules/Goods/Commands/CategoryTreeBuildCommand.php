<?php
namespace Modules\Goods\Commands;

use Dariuszp\CliProgressBar;
use Modules\Goods\Models\CategoryModel;
use Xcart\App\Commands\Command;

class CategoryTreeBuildCommand extends Command
{
    public function handle($arguments = [])
    {
        $i = 0;
        $prev_fixed = 0;
        $qs = CategoryModel::objects()->getQuerySet();

        while (($count = $qs->filter(['lft__isnull' => true])->count()) != 0) {
            ++$i;
            $fixed = 0;
            echo 'Iteration: '.$i.PHP_EOL;

            $bar = new CliProgressBar($count);

            $clone = clone $qs;
            $models = $clone
                ->filter(['lft__isnull' => true])
                ->order(['categoryid_path'])
                ->all();

            /** @var \Xcart\App\Orm\TreeModel $model */
            foreach ($models as $model) {
                $model->lft = $model->rgt = $model->level = $model->root = null;
                $bar->setColorToWhite();

                if ($model->saveRebuild()) {
                    ++$fixed;
                    $bar->setColorToGreen();
                }
                $bar->progress(1);
            }
            echo PHP_EOL;
            echo 'Fixed: '.$fixed.PHP_EOL;

            if ($prev_fixed == $fixed && $fixed == 0) {
                echo 'Break Not fixed: '.count($models).PHP_EOL;
                echo 'idx: '.implode(', ',array_map(function($model){ return $model->pk;}, $models)).PHP_EOL;
                break;
            }

            $bar->end();
        }
    }
}