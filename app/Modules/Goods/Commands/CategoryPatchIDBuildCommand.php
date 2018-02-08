<?php
namespace Modules\Goods\Commands;

use Dariuszp\CliProgressBar;
use Modules\Goods\Models\CategoryModel;
use Xcart\App\Commands\Command;

class CategoryPatchIDBuildCommand extends Command
{

    /** @var CliProgressBar */
    private $bar;

    private function rebuild(CategoryModel $model, $path)
    {
        if ($model->categoryid_path != $path) {
            CategoryModel::objects()->filter(['pk' => $model->pk])->update(['categoryid_path' => $path]);
        }

        $this->bar->progress();

        if ($childs = $model->getObjects()->children()->all())
        {
            foreach ($childs as $child) {
                $this->rebuild($child, $path . '/' . $child->pk);
            }
        }
    }

    public function handle($arguments = [])
    {

        $this->bar = new CliProgressBar(CategoryModel::objects()->count());

        foreach (CategoryModel::objects()->filter(['parentid' => 0])->all() as $model) {
            $this->rebuild($model, $model->pk);
        }

        $this->bar->end();
    }
}