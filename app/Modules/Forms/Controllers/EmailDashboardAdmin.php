<?php


namespace Modules\Forms\Controllers;


use Modules\Admin\Controllers\BackendController;

class EmailDashboardAdmin extends BackendController
{
    public function index($page = 1) {
        echo $this->renderInSmarty('admin/email_base.tpl');
    }
}