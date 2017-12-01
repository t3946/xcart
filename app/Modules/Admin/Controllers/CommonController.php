<?php

namespace Modules\Admin\Controllers;

class CommonController extends BackendController
{
    public function index()
    {
        $this->redirect('/admin/', 302);
        echo $this->render('admin/index.tpl', []);
    }
}