<?php
namespace Modules\Main\Controllers;

use Modules\Goods\Helpers\PromotionalProductsHelper;
use Modules\Goods\Models\CategoryModel;
use Modules\Meta\Types\MetaType;
use Modules\Sites\Models\SiteModel;
use Modules\Translate\Classes\I18nextManager;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class DefaultController extends FrontendController
{
    public $defaultAction= 'index';

    public function index()
    {
        /** @var SiteModel $site */
        $site = Xcart::app()->getModule('Sites')->getSite();

        $this->setMetaBase(MetaType::DEFAULT, [
            'site' => $site
        ]);

        $this->setCanonical('');

        $this->display('home.tpl', [
            'category_new' => $site->base_category,
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