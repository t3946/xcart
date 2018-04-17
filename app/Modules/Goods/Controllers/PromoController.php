<?php
namespace Modules\Goods\Controllers;


use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Main\Xcart;

class PromoController extends AbstractCatalogController
{
    public function actionBestsellers()
    {
        echo '123';
    }


    public function actionFeatured()
    {

        $this->jsonResponse(['val' =>'123', 'html' => '123']);

        ProductModel::objects();
    }

    public function actionNew()
    {
        $site = Xcart::app()->getModule('Sites')->getSite();
        /** @var CategoryModel $category_new */
        $category_new = CategoryModel::objects()->filter(['category' => 'New Products', 'storefrontid' => $site->pk, 'level' => 1])->limit(1)->get();

        /** @var ProductModel[] $products */
        $products = ProductModel::objects()->filter([
            'forsale' => 'Y',
            'avail__gt' => 10,
            'category_main__categoryid__in' => $category_new->getObjects()->descendants(true)->select(['pk'])
        ])
            ->limit(20)
            ->order(['?'])
            ->cache(10)->all();

        $sProducts = '';
        $aProducts = [];
        foreach ($products as $product) {
            $sProducts .= $this->render('catalog/parts/_catalog_list_item.tpl', ['item' => $product]);
        }

        $this->jsonResponse([
            'html' => "<div class='product-items tile-view'>{$sProducts}</div>",
        ]);

    }

    public function actionViewed()
    {

    }
}