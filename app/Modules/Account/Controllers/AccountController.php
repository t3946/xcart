<?php

namespace Modules\Account\Controllers;

use Modules\Core\Helpers\AdminHelper;
use Modules\Core\Models\CountryModel;
use Modules\Core\Models\GlobalConfigModel;
use Modules\Core\TemplateLibraries\StaticMessagesLibrary;
use Modules\Main\Helpers\WorkingTimeHelper;
use Modules\Order\Helpers\OrderHelper;
use Modules\Sites\Helpers\StorageHelper;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Modules\Goods\TemplateLibraries\MenuLibrary as GoodsMenuLibrary;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

class AccountController extends FrontendController
{
    private function generateQrCode()
    {
        $user = Xcart::app()->auth->getUser(true);

        if ($user->getIsGuest()) {
            return;
        }

        $g = new GoogleAuthenticator();
        $account_name = $user->getAttribute('email');
        $secret = $user->getAttribute('tsv_secret');

        if (!$secret) {
            $secret = $g->generateSecret();
            $user->setAttribute('tsv_secret', $secret);
            $user->save();
        }

        $site = Xcart::app()->getModule('Sites')->getSite();
        $issuer = $site->company_name;
        $url = GoogleQrUrl::generate($account_name, $secret, $issuer);

        StorageHelper::push([
            "url" => $url,
            "secret" => $secret,
        ], null, 'tsv');
    }

    private function getCountryPhoneCodes(): array
    {
        $codes = [];

        $countries = CountryModel::objects()->all();

        foreach ($countries as $country) {
            $codes[] = [
                "name" => $country->name,
                "code" => $country->code,
                "phone_code" => $country->phone_code,
            ];
        }

        return $codes;
    }

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
            "checkoutUrl" => OrderHelper::getCheckoutUrl(),
        ], null, 'Cart');

        $config = GlobalConfigModel::objects();

        StorageHelper::push([
            "cidev_top_header_code" => $config->get(['name' => 'cidev_top_header_code'])->value,
            "cidev_header_code" => $config->get(['name' => 'cidev_header_code'])->value,
            "companyName" => $config->get(['name' => 'company_name'])->value,
        ], null, 'config');

        StorageHelper::push([
            "renderStaticNotifications" => StaticMessagesLibrary::renderStaticMessages(),
        ], null, 'templates');

        StorageHelper::push([
            'desktop' => GoodsMenuLibrary::toArrayDesktop(),
            'mobile' => GoodsMenuLibrary::toArrayMobile(),
        ], null, 'departmentsMenu');

        StorageHelper::push(Xcart::app()->request->get->all(), 'get', 'params');

        StorageHelper::push(APP_LOCAL, 'APP_LOCAL');

        StorageHelper::push($this->getCountryPhoneCodes(), null, 'countries');

        $this->generateQrCode();

        AdminHelper::routesData();

        $this->display('account/base.tpl');
    }

    public function actionTSVAddNew()
    {
        self::actionIndex();
    }

    public function register()
    {
        $user = Xcart::app()->auth->getUser(true);

        if (!$user->getIsGuest()) {
            $this->getRequest()->redirect("account:index");
        } else {
            $this->actionIndex();
        }
    }

    public function login()
    {
        $user = Xcart::app()->auth->getUser(true);

        if (!$user->getIsGuest()) {
            $this->getRequest()->redirect("account:index");
        } else {
            $this->actionIndex();
        }
    }

    public function logout()
    {
        Xcart::app()->auth->logout(false);
        $this->actionIndex();
    }

    public function dashboard()
    {
        $this->actionIndex();
    }

    public function publicProfile()
    {
        $this->actionIndex();
    }
}