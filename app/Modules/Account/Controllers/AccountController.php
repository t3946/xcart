<?php

namespace Modules\Account\Controllers;

use Modules\Core\Helpers\AdminHelper;
use Modules\Core\Models\GlobalConfigModel;
use Modules\Core\TemplateLibraries\StaticMessagesLibrary;
use Modules\Main\Helpers\WorkingTimeHelper;
use Modules\Menu\TemplateLibraries\MenuLibrary;
use Modules\Sites\Helpers\StorageHelper;
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
            "code" => strtolower($site->code),
            "shortName" => $site->short_name,
            "workingDayTimeNow" => WorkingTimeHelper::workingDayTimeNow(),
        ], null, 'site');

        StorageHelper::push([
            "quantity" => Xcart::app()->cart->getQuantity(),
        ], null, 'Cart');

        StorageHelper::push([
            "cidev_top_header_code" => GlobalConfigModel::objects()->get(['name' => 'cidev_top_header_code'])->value,
            "companyName" => GlobalConfigModel::objects()->get(['name' => 'company_name'])->value,
        ], null, 'config');

        StorageHelper::push([
            "renderStaticNotifications" => StaticMessagesLibrary::renderStaticMessages(),
            "mainMenu" => MenuLibrary::getMenu(['code' => 'main-menu']),
        ], null, 'templates');

        AdminHelper::routesData();

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