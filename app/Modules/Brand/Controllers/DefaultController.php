<?php
namespace Modules\Brand\Controllers;

use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QOr;
use Modules\Brand\Models\BrandModel;
use Modules\Core\Helpers\Cache;
use Modules\Goods\Controllers\AbstractCatalogController;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\ProductModel;
use Modules\Meta\Helpers\MetaExtHelper;
use Modules\Meta\Types\MetaType;
use Xcart\App\Components\Breadcrumbs;
use Xcart\App\Main\Xcart;
use Xcart\Brand;

class DefaultController extends AbstractCatalogController
{
    public $view = 'brand/view.tpl';
    public $filters = ['price', 'filter'];

    public function actionViewOld($id, $slug)
    {
        /** @var \Modules\Sites\Models\SiteModel $site */
        $site = Xcart::app()->getModule('Sites')->getSite();

        $model = BrandModel::objects()->get(['brandid' => $id]);

        $this->setMetaBase(MetaType::BRAND, [
            'model' => $model,
            'site' => $site
        ]);

        $this->view_internal($model);
}

    public function actionView($sku)
    {
//        $this->view_internal(BrandModel::objects()->filter(['productcode' => $sku])->get());
    }

    public function actionToList()
    {
        $this->redirect('brand:list', [], 301);
    }

    /**
     * Show all brands alphabetically
     * @throws \Xcart\App\Exceptions\UnknownMethodException
     * @throws \Xcart\App\Exceptions\UnknownPropertyException
     */
    public function actionList()
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('Brands', 'brand:list');

        $this->display('brand/list.tpl', [
            'breadcrumbs' => $breadcrumbs,
            'brands' => BrandModel::getAllAlphabetically()
        ]);
    }

    public function getAdvancedData($data = null): array
    {
        /** @var \Modules\Sites\SitesModule $siteModule */
        $siteModule = Xcart::app()->getModule('Sites');
        $qs = CategoryModel::objects()->filter([
            'categoryid__in' => $this->getQS($data)->select(['categories__categoryid']),
            'storefrontid' => $siteModule->getSite()->storefrontid,
            'active_product_count__gt' => 0,
        ]);

        $categories = $qs->order(['category'])->all();

        return [
            'categories' => $categories ? : [],
            'analytics_source' => 'brand'
        ];
    }

    public function getQS($data)
    {
        /** @var \Modules\Sites\SitesModule $siteModule */
        $siteModule = Xcart::app()->getModule('Sites');
        return ProductModel::objects()->filter([
            'forsale' => 'Y',
            'brand__brandid' => $data->brandid,
            'sites__storefrontid' => $siteModule->getSite()->storefrontid
        ]);
    }
}