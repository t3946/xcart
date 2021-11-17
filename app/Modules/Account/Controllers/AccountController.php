<?php

namespace Modules\Account\Controllers;

use Modules\Account\Controllers\Api\AccountListsApi;
use Modules\Account\Models\ProductListsModel;
use Modules\Account\Models\UserListModel;
use Modules\Core\Helpers\AdminHelper;
use Modules\Core\Helpers\CoreHelper;
use Modules\Core\Models\CountryModel;
use Modules\Core\TemplateLibraries\StaticMessagesLibrary;
use Modules\Goods\Models\ProductModel;
use Modules\Goods\Models\TotalProductReviewsModel;
use Modules\Main\Helpers\WorkingTimeHelper;
use Modules\Menu\TemplateLibraries\MenuLibrary;
use Modules\Order\Controllers\Api\DecisionController;
use Modules\Order\Helpers\OrderHelper;
use Modules\Reviews\Models\ProductReviewsModel;
use Modules\Sites\Helpers\StorageHelper;
use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Modules\Goods\TemplateLibraries\MenuLibrary as GoodsMenuLibrary;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

class AccountController extends FrontendController
{
    private static function getCountryPhoneCodes(): array
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

    public static function provideAccountData()
    {
        $user = Xcart::app()->auth->getUser(true);

        if (!$user->getIsGuest()) {
            StorageHelper::push($user->toArray(), null, 'user');

            StorageHelper::push([
                'notSolved' => DecisionController::getDecisions($user['user_id'], 0, 4, 0, ['-created']),
                'solved' => DecisionController::getDecisions($user['user_id'], 1, 4, 0, ['-updated']),
            ], null, 'decisions');

            StorageHelper::push(AccountListsApi::getLists($user), 'lists', 'user');
        }

        $site = Xcart::app()->getModule('Sites')->getSite();

        StorageHelper::push(MenuLibrary::getData("main-menu"), null, 'mainMenu');

        StorageHelper::push([
            "code" => strtolower($site->code),
            "shortName" => $site->short_name,
            "workingDayTimeNow" => WorkingTimeHelper::workingDayTimeNow(),
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

        AdminHelper::routesData();
    }

    public function actionProductIndex($sku)
    {
        $product = ProductModel::objects()->filter(['productcode' => $sku])->get();
        StorageHelper::push([
            "product" => $product->getAttributes(),
        ], null, 'product_info');

        $this->actionIndex();
    }

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

    public function actionIndex()
    {
        $this->generateQrCode();

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

    public function listInvite(string $tag, string $code)
    {
        $user = Xcart::app()->auth->getUser(true);


        if ($user->getIsGuest()) {
            $this->redirect('account:login', [], 301);
            $this->actionIndex();
        }

        [$user_id, $type, $listHash] = explode('/', CoreHelper::decryptText($code, $tag));


        $invite_list = ProductListsModel::objects()->get(['cache_url' => $listHash]);

        $invited_user_name = UserModel::objects()->get(['user_id' => $user_id])->name;

        if (UserListModel::objects()->get(['user_id' => $user->user_id, 'product_list_id' => $invite_list->product_list_id])->user_id) {
            $this->redirect('/account/your-lists/' . $listHash, [], 301);
        }

        StorageHelper::push([
            "userId" => $user_id,
            'userName' => $invited_user_name,
            "type" => $type,
            "inviteList" => $invite_list->getAttributes(),
        ], null, 'invite_data');

        $this->actionIndex();
    }

    /**
     * get product data with total reviews number
     */
    public static function getProduct($product_id): array
    {
        $total_reviews = TotalProductReviewsModel::objects()->get(['product_id' => $product_id])['total'];
        $product = ProductModel::objects()->get(['productid' => $product_id]);
        $attributes = $product->getAttributes();
        $attributes["total_reviews"] = $total_reviews;
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
}