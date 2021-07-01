<?php


namespace Modules\Account\Controllers;


use Xcart\App\Controller\FrontendController;

class AccountDashboard extends FrontendController
{
    public function actionIndex()
    {
        return $this->display('account/base.tpl');
    }
}