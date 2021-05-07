<?php


namespace Modules\Forms\Controllers;


use Modules\Admin\Controllers\BackendController;

class EmailDashboardAdmin extends BackendController
{
    public function index() {
        echo $this->renderInSmarty('admin/email_base.tpl');
    }
}