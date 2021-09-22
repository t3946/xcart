<?php

namespace Modules\Admin\Controllers;

use Modules\Admin\Contrib\Admin;
use Modules\Sites\Helpers\StorageHelper;
use Xcart\App\Form\ModelForm;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Model;

class AdminController extends BackendController
{
    public function all($module, $admin, $id = null)
    {
        $admin = $this->getAdmin($module, $admin);

        //передача данных на frontend
        $data = [
            "flash" => Xcart::app()->flash->read(),
            "id" => $admin->getId(),
            "cron" => [
                "url" => Xcart::app()->request->getUrl(),
                "groupActionUrl" => $admin->getGroupActionUrl(),
                "sortUrl" => $admin->getSortUrl(),
                "columnsUrl" => $admin->getColumnsUrl(),
            ],
        ];
        StorageHelper::push($data, null, 'app');

        $admin->all($id);
    }

    public function info($module, $admin, $pk)
    {
        $admin = $this->getAdmin($module, $admin);
        $admin->info($pk);
    }

    public function create($module, $admin, $id = null)
    {
        $admin = $this->getAdmin($module, $admin);
        $admin->create($id);
    }

    public function update($module, $admin, $pk)
    {
        $admin = $this->getAdmin($module, $admin);
        $admin->update($pk);
    }
    public function updateOwned($module, $admin, $pk, $owner)
    {
        $admin = $this->getAdmin($module, $admin);
        $admin->update($pk, $owner);
    }

    public function update_section($module, $admin, $pk, $section = null)
    {
        $admin = $this->getAdmin($module, $admin);
        $admin->section = $section;
        $admin->update($pk);
    }

    public function updateall($module, $admin)
    {
        $admin = $this->getAdmin($module, $admin);
        $admin->updateall();
    }

    public function remove($module, $admin, $pk)
    {
        if (!$this->getRequest()->getIsPost()) {
            $this->error(404);
        }
        $admin = $this->getAdmin($module, $admin);
        $admin->remove($pk);
    }

    public function sort($module, $admin, $id = null)
    {
        $admin = $this->getAdmin($module, $admin);

        $pkList = isset($_POST['pk_list']) && is_array($_POST['pk_list']) ? $_POST['pk_list'] : [];
        $to = $_POST['to'] ?? null;
        $prev = $_POST['prev'] ?? null;
        $next = $_POST['next'] ?? null;

        $admin->sort($pkList, $to , $prev, $next, $id);
    }

    public function columns($module, $admin)
    {
        $admin = $this->getAdmin($module, $admin);

        $columns = isset($_POST['columns']) && is_array($_POST['columns']) ? $_POST['columns'] : [];

        $admin->setColumns($columns);
    }

    public function groupAction($module, $admin)
    {
        if (!$this->getRequest()->getIsPost()) {
            $this->error(404);
        }
        $admin = $this->getAdmin($module, $admin);
        $action = isset($_POST['action']) ? $_POST['action'] : null;
        $pkList = isset($_POST['pk_list']) && is_array($_POST['pk_list']) ? $_POST['pk_list'] : [];

        if ($action) {
            $admin->handleGroupAction($action, $pkList);
        } else {
            $this->error(404);
        }
    }

    public function suggestion($module, $admin, $entity)
    {
        $admin = $this->getAdmin($module, $admin);
        $admin->suggestions($entity);
    }

    /**
     * @param $module
     * @param $admin
     * @return Admin
     */
    public function getAdmin($module, $admin)
    {
        $class = "Modules\\{$module}\\Admin\\{$admin}";
        if (class_exists($class)) {
            return new $class();
        }
        $this->error(404);
    }

    public function updateSelectField() : void
    {
        $result = ['result' => false];
        $post = $this->getRequest()->post->all();
        /** @var ModelForm $form */
        $form = new $post['form']();
        $instance = $form->getInstance();
        /** @var Model $model */
        $model = $instance::objects()->get(['pk' => $post['id']]);
        $form->setInstance($model);
        if ($form->populate($post))
        {
            $model->getIsNewRecord(false);
            if ($form->save()) {
                $result = ['result' => true];
            }
        }
        $this->jsonResponse($result);
    }
}