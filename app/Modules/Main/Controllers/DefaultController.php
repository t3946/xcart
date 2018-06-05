<?php
namespace Modules\Main\Controllers;

use Modules\Goods\Helpers\PromotionalProductsHelper;
use Modules\Goods\Models\CategoryModel;
use Modules\Meta\Types\MetaType;
use Modules\Sites\SitesModule;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class DefaultController extends FrontendController
{
    public $defaultAction= 'index';

    public function index()
    {
//        $this->redirect('demo:index');

        //@TODO: To future
//        /** @var SitesModule $module */
//        $module = Xcart::app()->getModule('Sites');
//
//        $module = $module->getSite()->getDefaultModule();
//        $controller = new \Modules\Demo\Controllers\DefaultController($this->getRequest());
//        $controller->run(null, func_get_args());

        $site = Xcart::app()->getModule('Sites')->getSite();

        $category_new = CategoryModel::objects()->filter(['category' => 'New Products', 'storefrontid' => $site->pk, 'level' => 1])->limit(1)->get();

        $this->setMetaBase(MetaType::DEFAULT, [
            'site' => $site
        ]);

        $this->setCanonical('');

        $this->display('home.tpl', [
            'category_new' => $category_new,
            'product' => PromotionalProductsHelper::getProductOfTheDay(),

        ]);
    }

    public function actionAboutUs()
    {
        $this->display('about_us.tpl');
    }
}