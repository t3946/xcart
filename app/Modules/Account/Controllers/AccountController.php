<?php

namespace Modules\Account\Controllers;

use Modules\Account\Forms\LoginForm;
use Modules\Account\Forms\RegistrationForm;
use Modules\Core\Models\GlobalConfigModel;
use Modules\Main\Helpers\WorkingTimeHelper;
use Modules\Sites\Helpers\StorageHelper;
use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class AccountController extends FrontendController
{
    public function actionIndex()
    {
        $user = Xcart::app()->auth->getUser(true);

        if (!$user->getIsGuest()) {
            StorageHelper::push($user->toArray(), null, 'user');
        }

        $site = Xcart::app()->getModule('Sites')->getSite();

        StorageHelper::push([
            "shortName" => $site->short_name,
            "workingDayTimeNow" => WorkingTimeHelper::workingDayTimeNow(),
            "cidev_top_header_code" => GlobalConfigModel::objects()->get(['name' => 'cidev_top_header_code'])->value,
        ], null, 'site');

        $this->display('account/base.tpl');
    }

    public function register()
    {
        $this->actionIndex();
    }

    public function login()
    {
        $this->actionIndex();
    }

    public function logout()
    {
        Xcart::app()->auth->logout(false);
        $this->actionIndex();
    }
}