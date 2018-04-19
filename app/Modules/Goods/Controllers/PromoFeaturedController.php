<?php
namespace Modules\Goods\Controllers;


use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QOr;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\FeaturedProductsModel;
use Modules\Goods\Models\ProductModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Manager;

class PromoFeaturedController extends AbstractCatalogController
{
    public $view = 'catalog/featured.tpl';

    public function getQS($data = null)
    {
        return parent::getQS($data)->filter([
            'pk__in' => FeaturedProductsModel::objects()->filter([
                'storefrontid' => Xcart::app()->getModule('Sites')->getSite()])->select(['product__productid']),
        ]);
    }

    public function actionFeatured()
    {
        if ($this->getRequest()->getIsAjax() && !$this->getRequest()->get->has('page'))
        {
            $productsQS = $this->getQS()
                ->limit(20)
                ->order(['?']);

            $sProducts = '';
            foreach ($productsQS as $product) {
                $sProducts .= $this->render('catalog/parts/_catalog_list_item.tpl', ['item' => $product]);
            }

            $this->jsonResponse([
                'html' => "<div class='product-items tile-view' itemtype='http://schema.org/OfferCatalog'>{$sProducts}</div>",
            ]);

            die();
        }

        $this->view_internal();
    }
}