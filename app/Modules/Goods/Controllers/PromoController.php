<?php
namespace Modules\Goods\Controllers;

use Modules\Goods\Helpers\PromotionalProductsHelper;
use Modules\Goods\Helpers\SliderDataHelper;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\FeaturedProductsModel;
use Modules\Goods\Models\ProductModel;
use Modules\Meta\Types\MetaType;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Components\Breadcrumbs;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\QuerySet;

class PromoController extends AbstractCatalogController
{
    public $filters = ['price', 'brand'];

    private $qs;
    private $advancedData = [];

    public function actionBestsellers(): void
    {
        $this->qs = PromotionalProductsHelper::getBestsellersSQ();

        $bread = new Breadcrumbs();

        $bread->add('Bestsellers');

        $this->setMetaBase(MetaType::BESTSELLER,[
            'model' => Xcart::app()->getModule('Sites')->getSite(),
        ]);

        $this->qs->filter(['avail__gt' => 10]);
        $this->view = 'catalog/promo.tpl';
        $this->advancedData = [
            'title' => 'Bestsellers',
            'breadcrumbs' => Xcart::app()->breadcrumbs->set($bread),
        ];
        $this->view_internal();
    }

    public function actionNew(): void
    {
        /** @var SiteModel $site */
        /** @var CategoryModel $category_new */

        $site = Xcart::app()->getModule('Sites')->getSite();
        $category_new = CategoryModel::objects()->filter([
            'category' => 'New Products',
            'storefrontid' => $site->pk,
            'level' => 1
        ])
            ->limit(1)
            ->get();

        if ($this->getRequest()->getIsAjax()) {

            $this->renderSliderData($this->getQS()->filter([
                'images__image_path__isnull' => false,
                'category_main__categoryid__in' => $category_new->getObjects()->descendants(true)->select(['pk']),
            ]));
        }

        $this->redirect($category_new->getAbsoluteUrl());
    }


    public function actionFeatured(): void
    {
        if ($this->getRequest()->getIsAjax() && !$this->getRequest()->get->has('page'))
        {
            $this->renderSliderData($this->getQS()
                ->distinct()
                ->filter([
                    'images__image_path__isnull' => false,
                    'featured__product_order__isnull' => false,
                ])
                ->order(['?']));
        }

        $bread = new Breadcrumbs();

        $bread->add('Featured products');

        $this->setMetaBase(MetaType::FEATURED,[
            'model' => Xcart::app()->getModule('Sites')->getSite(),
        ]);

        $this->view = 'catalog/promo.tpl';
        $this->advancedData = [
            'title' => 'Featured products',
            'breadcrumbs' => Xcart::app()->breadcrumbs->set($bread),
        ];
        $this->view_internal();
    }

    public function actionAlsoBought($id): void
    {
        /** @var ProductModel[] $products */
        $products = SliderDataHelper::getSliderData('products_also_bought_with_this_product', $id);
        if ($products) {
            $this->renderSliderData($products);
        }
    }

    public function actionRelatedProducts($id): void
    {
        /** @var ProductModel[] $products */
        $products = SliderDataHelper::getSliderData('similar_products', $id);
        if ($products) {
            $this->renderSliderData($products);
        }
    }

    public function actionViewed(): void
    {
        /** @var ProductModel[] $products */
        $products = SliderDataHelper::getSliderData('recently_viewed_products');

        if ($products) {
            $this->renderSliderData($products, 'catalog/parts/_minimal_view_item.tpl');
        }
    }


    /**
     * @param QuerySet|ProductModel[] $products
     * @param string $view
     */
    private function renderSliderData($products, $view = 'catalog/parts/_catalog_list_item.tpl'): void
    {
        if (!\is_array($products)) {
            $products->limit(20)->cache(10);
        }

        $sProducts = '';
        foreach ($products as $product) {
            $sProducts .= $this->render($view, ['item' => $product]);
        }

        $this->jsonResponse([
            'html' => "<div class='product-items tile-view' itemtype='http://schema.org/OfferCatalog'>{$sProducts}</div>",
        ]);

        die();
    }

    public function getAdvancedData($data = null): array {
        return $this->advancedData;
    }

    public function getQS($data = null)
    {
        if ($this->qs) {
            return $this->qs;
        }

        return parent::getQS($data)->filter([
            'avail__gt' => 10,
        ])
            ->order(['?'])
            ->cache(10);
    }
}