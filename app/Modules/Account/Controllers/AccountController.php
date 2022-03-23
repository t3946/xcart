<?php

namespace Modules\Account\Controllers;

use Modules\Account\Controllers\Api\AccountListsApi;
use Modules\Core\Helpers\AdminHelper;
use Modules\Core\Models\CountryModel;
use Modules\Core\TemplateLibraries\StaticMessagesLibrary;
use Modules\Goods\Models\ProductModel;
use Modules\Main\Helpers\WorkingTimeHelper;
use Modules\Menu\TemplateLibraries\MenuLibrary;
use Modules\Order\Helpers\OrderHelper;
use Modules\Payment\Models\ProcessorModel;
use Modules\Sites\Helpers\StorageHelper;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Modules\Goods\TemplateLibraries\MenuLibrary as GoodsMenuLibrary;

class AccountController extends FrontendController
{
    public static function getCountryPhoneCodes(): array
    {
        $codes = [];

        $countries = CountryModel::objects()->all();

        foreach ($countries as $country) {
            $codes[] = [
                "country_id" => $country->country_id,
                "name" => $country->name,
                "code" => $country->code,
                "phone_code" => $country->phone_code,
            ];
        }

        return $codes;
    }

    public static function provideAccountData()
    {
        $site = Xcart::app()->getModule('Sites')->getSite();

        StorageHelper::push(MenuLibrary::getData("main-menu"), null, 'mainMenu');

        StorageHelper::push([
            "code" => strtolower($site->code),
            "shortName" => $site->short_name,
            "workingDayTimeNow" => WorkingTimeHelper::workingDayTimeNow(),
            'account_enabled' => $site->account_enabled,
            'logo' => (string) ($site->logo ?? ''),
            'logo_mobile' => (string) ($site->logo_mobile ?? ''),
        ], null, 'site');

        StorageHelper::push([
            "quantity" => Xcart::app()->cart->getQuantity(),
            "checkoutUrl" => OrderHelper::getCheckoutUrl(),
        ], null, 'cart');

        $config = $site->getConfig();

        StorageHelper::push([
            "cidev_top_header_code" => $config['cidev_top_header_code'],
            "cidev_header_code" => $config['cidev_header_code'],
            "companyName" => $config['company_name'],
            'logo' => (string) ($site->logo ?? ''),
            'logo_mobile' => (string) ($site->logo_mobile ?? ''),
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

        StorageHelper::push(self::getCountryPhoneCodes(), null, 'countries');

        $user = Xcart::app()->getUser(true);

        if ($user->getIsGuest() === false) {
            $attributes = $user->getAttributes();
            unset($attributes['password']);
            unset($attributes['access_token']);
            $attributes['lists'] = AccountListsApi::getLists($user['user_id']);
            $attributes['avatar_image'] = $user->avatar_image->getUrl();

            StorageHelper::push($attributes, null, 'user');
        }

        AdminHelper::routesData();
    }

    public function actionProductIndex($sku)
    {
        $product = ProductModel::objects()->filter(['productcode' => $sku])->get();
        StorageHelper::push([
            "product" => $product->getAttributes(),
            "flags" => [
                "isOutOfStockFrontend" => $product->isOutOfStockFrontend(),
                "isFreeShipping" => $product->isFreeShipping(),
                "isFlatRate" => $product->isFlatRate(),
            ],
        ], null, 'product_info');

        $this->actionIndex();
    }

    public function actionIndex()
    {
        $this->display('account/base.tpl');
    }

    /**
     * get product data with total reviews number
     */
    public static function getProduct($product_id): array
    {
        $product = ProductModel::objects()->get(['productid' => $product_id]);
        if ($product === null) {
            return [];
        }
        $attributes = $product->getAttributes();
        $image = $product->getMainImage();
        $image_url = (string)$image;
        $attributes['image'] = $image_url;

        return $attributes;
    }

    public function createReviewAction($product_id): void
    {
        $product = ProductModel::objects()->get(["productid" => $product_id]);
        $user = Xcart::app()->auth->getUser(true);

        if ($product === null) {
            if ($user->getIsGuest()) {
                $this->redirect("main:index");
            } else {
                $this->redirect("account:dashboard");
            }
        }

        $product = $this->getProduct($product_id);
        StorageHelper::push($product, 'product', 'review');
        $this->actionIndex();
    }

    public function actionDecisionEdit(): void
    {
        $user = Xcart::app()->auth->getUser(true);

        if ($user->getIsGuest()) {
            $this->actionIndex();
        }

        $stripeSettings = ProcessorModel::objects()->asArray()->get(['processor_name' => 'Stripe']);
        StorageHelper::push($stripeSettings['param01'], 'publicKey', 'stripeSettings');

        $this->actionIndex();
    }
}