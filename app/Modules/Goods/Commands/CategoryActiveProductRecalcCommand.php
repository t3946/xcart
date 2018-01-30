<?php
namespace Modules\Goods\Commands;

use Dariuszp\CliProgressBar;
use Modules\Goods\Models\CategoryModel;
use Xcart\App\Commands\Command;

class CategoryActiveProductRecalcCommand extends Command
{

    /** @var CliProgressBar */
    private $bar;

    private function recalc(CategoryModel $model)
    {
        $model->reCalcProductsCount();

        $this->bar->progress();

        if ($childs = $model->getObjects()->children()->all())
        {
            foreach ($childs as $child) {
                $this->recalc($child);
            }
        }
    }

    public function handle($arguments = [])
    {

        $this->bar = new CliProgressBar(CategoryModel::objects()->count());

        foreach (CategoryModel::objects()->filter(['parentid' => 0])->all() as $model) {
            $this->recalc($model);
        }

        $this->bar->end();
    }
}