<?php

namespace Modules\Dashboard\Controllers;

use Modules\Dashboard\Helpers\SearchHelper;
use Modules\Dashboard\Models\DashboardFilter;
use Modules\Dashboard\Stores\OrderSearchStore;
use Xcart\App\Controller\AdminController;

class DashboardController extends AdminController
{
    public $defaultAction = 'index';

    public function index()
    {
        $models = DashboardFilter::objects()->filter(['enabled' => true])->all();
//        $models = DashboardFilter::objects()->all();

        foreach ($models as $model)
        {
            echo "<a href='{$model->getAdminUrl()}'>{$model}</a>";
        }
    }

    public function settings()
    {
        $models = DashboardFilter::objects()->all();

        foreach ($models as $model)
        {
            echo "<a href='{$model->getAdminUrl()}'>{$model}</a>";
        }
    }

    public function create()
    {
        $this->edit();
    }

    public function edit($id = null)
    {
        $class = DashboardFilter::classNameShort();

        if (!is_null($id)) {
            $model = DashboardFilter::objects()->get(['id' => $id]);
        }
        else {
            $model = new DashboardFilter();
        }

        if ($_POST[$class] && $_POST['search']) {
            $model->setAttributes($_POST[$class]);
            $model->form_data = OrderSearchStore::getClearedData($_POST['search']);

            if ($model->isValid() && $model->save()) {
                $this->refresh();
            }
        }

        echo $this->renderInternal('dashboard/edit_form.tpl', array_merge(
            SearchHelper::getFormAndListData(),
            [
                'model'     => $model,
                'form_data' => SearchHelper::prepareFormDataForTemplate($model->form_data),
            ])
        );
    }
}