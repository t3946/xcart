<?php

namespace Modules\Goods\Controllers\Api;

use Modules\Goods\Controllers\AbstractCatalogController;
use Modules\Goods\GoodsModule;
use Modules\Goods\Helpers\PromotionalProductsHelper;
use Modules\Goods\Helpers\SliderDataHelper;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Main\Xcart;

class ApiCategoriesController extends AbstractCatalogController
{
    public function actionBestsellers(): void
    {
        $qs = PromotionalProductsHelper::getBestsellersSQ();
        $data = $this->renderSliderDataAPI($qs);
        $this->jsonResponse($data);
    }

    public function actionNew(): void
    {
        $site = Xcart::app()->getModule('Sites')->getSite();

        $category_new = CategoryModel::objects()->filter(
            [
                'category' => GoodsModule::t('New Products'),
                'storefrontid' => $site->pk,
                'level' => 1
            ]
        )->limit(1)->get();

        $data = $this->renderSliderDataAPI(
            $this->getQS()->filter(
                [
                    'images__image_path__isnull' => false,
                    'category_main__categoryid__in' => $category_new->getObjects()->descendants(true)->select(
                        ['pk']
                    ),
                ]
            )
        );
        $this->jsonResponse($data);
    }

    public function actionFeatured(): void
    {
        $qs = $this
            ->getQS()
            ->filter(
                [
                    'featured__product_order__isnull' => false,
                ]
            )
            ->order(['?']);

        $data = $this->renderSliderDataAPI($qs);
        $this->jsonResponse($data);
    }

    public function actionAlsoBought($id): void
    {
        /** @var ProductModel[] $products */
        $products = SliderDataHelper::getSliderData('products_also_bought_with_this_product', $id);
        if ($products) {
            $data = $this->renderSliderDataAPI($products);
            $this->jsonResponse($data);
        }
    }

    public function actionRelatedProducts($id): void
    {
        /** @var ProductModel[] $products */
        $products = SliderDataHelper::getSliderData('similar_products', $id);
        if ($products) {
            $data = $this->renderSliderDataAPI($products);
            $this->jsonResponse($data);
        }
    }

    public function actionViewed(): void
    {
        /** @var ProductModel[] $products */
        $products = SliderDataHelper::getSliderData('recently_viewed_products');

        if ($products) {
            $data = $this->renderSliderDataAPI($products);
            $this->jsonResponse($data);
        }
    }

    private function renderSliderDataAPI($products, $view = 'catalog/parts/_catalog_list_item.tpl'): array
    {
        if (!\is_array($products)) {
            $products->limit(20)->cache(10);
        }

        $currency = Xcart::app()->getModule('Sites')->getSite()->getCurrency();
        $data = [];

        /**
         * @var ProductModel $product
         */
        foreach ($products as $product) {
            //get images
            $images = [];

            if ($product->isGroupRoot()) {
                $children = $product->getFrontendChilds()->limit(4)->all();

                foreach ($children as $child) {
                    $image = $child->images->filter(['avail' => 'Y'])->order(['orderby'])->limit(1)->get();

                    if ($image && $url = $image->getCdnURL(174)) {
                        $images[] = [
                            'url' => $url,
                            'alt' => $child->getFrontendName(),
                        ];
                    }
                }
            } else {
                $imageModel = $product->images->filter(['avail' => 'Y'])->order(['orderby'])->limit(1)->get();

                if ($imageModel && $url = $imageModel->getCdnURL(174)) {
                    $images[] = [
                        'url' => $url,
                        'alt' => $product->getFrontendName(),
                    ];
                }
            }

            $data[] = [
                'name' => htmlspecialchars_decode($product->getFrontendName() ?: $product->product, ENT_QUOTES),
                'url' => $product->getAbsoluteUrl(),
                'mpn' => $product->getMpn(),
                'upc' => $product->upc,
                'images' => $images,

                'price' => [
                    'number' => $product->getFrontendPrice(),
                    'formatted' => $currency->getCurrencyFormat($product->getFrontendPrice()),
                ],

                'listPrice' => [
                    'number' => $product->list_price,
                    'formatted' => $currency->getCurrencyFormat($product->list_price),
                ],

                'currency' => [
                    'currency' => (string)$currency,
                    'symbol_prefix' => $currency->symbol_prefix,
                    'after' => $currency->after,
                ]
            ];
        }

        return $data;
    }
}