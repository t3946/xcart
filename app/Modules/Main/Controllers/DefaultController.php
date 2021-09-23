<?php

namespace Modules\Main\Controllers;

use Modules\Account\Controllers\AccountController;
use Modules\Goods\Helpers\PromotionalProductsHelper;
use Modules\Goods\Models\CategoryModel;
use Modules\Meta\Types\MetaType;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class DefaultController extends FrontendController
{
    public $defaultAction= 'index';

    public function index()
    {
        AccountController::provideAccountData();

        $site = Xcart::app()->getModule('Sites')->getSite();

        $category_new = CategoryModel::objects()->filter(['category' => 'New Products', 'storefrontid' => $site->pk, 'level' => 1])->limit(1)->get();

        $this->setMetaBase(MetaType::DEFAULT, [
            'site' => $site
        ]);

        $this->setCanonical('');

        $this->display('home.tpl', [
            'category_new' => $category_new,
            'product' => PromotionalProductsHelper::getProductOfTheDay(),
            'best_seller' => PromotionalProductsHelper::getBestSellerProduct(),
            'new_product' => PromotionalProductsHelper::getNewProduct(),
        ]);
    }

    public function actionAboutUs()
    {
        $this->display('about_us.tpl');
    }
}