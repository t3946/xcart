<?php

namespace Modules\Admin\Controllers;

use Modules\Admin\Contrib\Admin;
use Xcart\App\Main\Xcart;

class AdminController extends BackendController
{
    public function all($module, $admin, $id = null)
    {
        $admin = $this->getAdmin($module, $admin);
        $admin->all($id);
    }

    public function create($module, $admin, $id)
    {
        $admin = $this->getAdmin($module, $admin);
        $admin->create($id);
    }

    public function update($module, $admin, $pk)
    {
        $admin = $this->getAdmin($module, $admin);
        $admin->update($pk);
    }

    public function remove($module, $admin, $pk)
    {
        if (!$this->getRequest()->getIsPost()) {
            $this->error(404);
        }
        $admin = $this->getAdmin($module, $admin);
        $admin->remove($pk);
    }

    public function sort($module, $admin)
    {
        $admin = $this->getAdmin($module, $admin);

        $pkList = isset($_POST['pk_list']) && is_array($_POST['pk_list']) ? $_POST['pk_list'] : [];
        $to = isset($_POST['to']) ? $_POST['to'] : null;
        $prev = isset($_POST['prev']) ? $_POST['prev'] : null;
        $next = isset($_POST['next']) ? $_POST['next'] : null;

        $admin->sort($pkList, $to , $prev, $next);
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

    /**
     * @param $module
     * @param $admin
     * @return Admin
     */
    public function getAdmin($module, $admin)
    {
        $class = "Modules\\{$module}\\Admin\\{$admin}";
        if (class_exists($class)) {
            return new $class($this);
        }
        $this->error(404);
    }
}