<?php

namespace Modules\Dashboard\Controllers;

use Xcart\App\Controller\AdminController;
use Xcart\App\Main\Xcart;
use Xcart\Connection;

class DashboardController extends AdminController
{
    public $defaultAction = 'index';

    public function index()
    {
        func_dump($this->getRequest()->get->get('module'));
    }

    public function search()
    {
        $render = '';

        if (isset($_GET['search']))
        {


        }
        else {
            $render = $this->render('dashboard/search_form.tpl');
        }

        echo $this->renderSmarty("admin/home.tpl",[
            'single_mode' => true,
            'main' => 'raw_html',
            'content' => $render,
        ]);
    }
}