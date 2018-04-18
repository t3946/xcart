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

class PromoController extends AbstractCatalogController
{
    public function actionBestsellers()
    {
        echo '123';
    }


    public function actionFeatured()
    {
        /** @var SiteModel $site */
        $site = Xcart::app()->getModule('Sites')->getSite();

        $productsQS = $this->getDefaultProductFilter()->filter([
            'pk__in' => FeaturedProductsModel::objects()->filter(['storefrontid' => $site])->select(['product__productid']),
        ]);

        if ($this->getRequest()->getIsAjax()) {
            $sProducts = '';
            foreach ($productsQS as $product) {
                $sProducts .= $this->render('catalog/parts/_catalog_list_item.tpl', ['item' => $product]);
            }

            $this->jsonResponse([
                'html' => "<div class='product-items tile-view' itemtype='http://schema.org/OfferCatalog'>{$sProducts}</div>",
            ]);
        }




    }

    public function actionNew()
    {
        /** @var SiteModel $site */
        $site = Xcart::app()->getModule('Sites')->getSite();
        /** @var CategoryModel $category_new */
        $category_new = CategoryModel::objects()->filter([
            'category' => 'New Products',
            'storefrontid' => $site->pk,
            'level' => 1
        ])
            ->limit(1)
            ->get();

        $productsQS = $this->getDefaultProductFilter()->filter([
            'category_main__categoryid__in' => $category_new->getObjects()->descendants(true)->select(['pk']),
            ])->all();

        if ($this->getRequest()->getIsAjax()) {
            $sProducts = '';
            foreach ($productsQS as $product) {
                $sProducts .= $this->render('catalog/parts/_catalog_list_item.tpl', ['item' => $product]);
            }

            $this->jsonResponse([
                'html' => "<div class='product-items tile-view' itemtype='http://schema.org/OfferCatalog'>{$sProducts}</div>",
            ]);
        }



    }

    public function actionViewed()
    {

    }

    private function getDefaultProductFilter(): Manager
    {
        return ProductModel::objects()->filter([
            'forsale' => 'Y',
            'avail__gt' => 10,
            new QOr([
                'group_root__isnull' => true,
                'pk' => new Expression('group_root'),
            ])
        ])

            ->limit(20)
            ->order(['?'])
            ->cache(10);
    }
}