<?php
namespace Modules\Goods\Controllers;

use Modules\Goods\Models\CategoryModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;

class PromoController extends AbstractCatalogController
{
    public function actionBestsellers()
    {
        echo '123';
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

        if ($this->getRequest()->getIsAjax()) {

            $productsQS = $this->getQS()->filter([
                'category_main__categoryid__in' => $category_new->getObjects()->descendants(true)->select(['pk']),
            ])
                ->limit(20)
                ->cache(10);

            $sProducts = '';
            foreach ($productsQS as $product) {
                $sProducts .= $this->render('catalog/parts/_catalog_list_item.tpl', ['item' => $product]);
            }

            $this->jsonResponse([
                'html' => "<div class='product-items tile-view' itemtype='http://schema.org/OfferCatalog'>{$sProducts}</div>",
            ]);
            die();
        }

        $this->redirect($category_new->getAbsoluteUrl());
    }

    public function getQS($data = null)
    {
        return parent::getQS($data)->filter([
            'avail__gt' => 10,
            ])
            ->order(['?'])
            ->cache(10);
    }
}