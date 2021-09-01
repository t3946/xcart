<?php

namespace Modules\Dashboard\Controllers;

use Modules\Dashboard\Helpers\SearchHelper;
use Modules\Dashboard\Models\DashboardFilter;
use Modules\Dashboard\Models\GroupModel;
use Modules\Dashboard\Stores\OrderSearchStore;
use Xcart\App\Controller\PrototypeAdminController;

class DashboardGroupController extends PrototypeAdminController
{
    public $defaultAction = 'settings';

    public function settings()
    {
        $models = GroupModel::objects()->all();

        echo $this->renderInternal('dashboard/admin/group_list.tpl',
            [
                'models'  => $models,
            ]
        );
    }

    public function create()
    {
        $this->update();
    }

    public function update($id = null)
    {
        $class = GroupModel::classNameShort();

        if (!is_null($id)) {
            $model = GroupModel::objects()->get(['id' => $id]);
        }
        else {
            $model = new GroupModel();
        }

        if (isset($_POST['delete'])) {
            if ($model->delete()) {
                $this->autoRedirect($model);
            }
        }

        if ($_POST[$class]) {
            $model->setAttributes($_POST[$class]);

            if ($model->isValid() && $model->save()) {
                $this->autoRedirect($model);
            }
        }

        echo $this->renderInternal('dashboard/admin/group_edit.tpl', [ 'model'=> $model]);
    }

    private function autoRedirect($model)
    {
        list($url, $params) = $this->autoActions($model);
        $this->redirect($url, $params);
    }

    private function autoActions($model)
    {
        if (array_key_exists('save_continue', $_POST)) {
            return ['dashboard:update_group', ['id' => $model->id]];
        }
        else if (array_key_exists('save_create', $_POST)) {
            return ['dashboard:create_group', []];
        }
        else {
            return ['dashboard:admin_groups', []];
        }
    }
}