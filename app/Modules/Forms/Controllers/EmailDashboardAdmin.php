<?php


namespace Modules\Forms\Controllers;


use Modules\Admin\Controllers\BackendController;
use Xcart\App\Main\Xcart;

class EmailDashboardAdmin extends BackendController
{
    public function index($page = 1) {
        Xcart::app()->breadcrumbs->add('Inbox/Sorting dashboard', "/admin/forms/email-dashboard/page/1");
        echo $this->renderInSmarty('admin/email_base.tpl');
    }
}