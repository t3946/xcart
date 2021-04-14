<?php

namespace Modules\Goods\Controllers\Api;

use Modules\Goods\Controllers\AbstractCatalogController;
use Modules\Goods\GoodsModule;
use Modules\Goods\Helpers\ProductFilterHelper;
use Modules\Goods\Helpers\ProductSortHelper;
use Modules\Goods\Helpers\PromotionalProductsHelper;
use Modules\Goods\Helpers\SliderDataHelper;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Main\Xcart;

class ApiCategoriesController extends AbstractCatalogController
{
    public function actionSliderBestsellers(): void
    {
        $qs = PromotionalProductsHelper::getBestsellersSQ();
        $data = $this->getProductData($qs);
        $this->jsonResponse($data);
    }

    public function actionSliderNew(): void
    {
        $site = Xcart::app()->getModule('Sites')->getSite();

        $category_new = CategoryModel::objects()->filter(
            [
                'category' => GoodsModule::t('New Products'),
                'storefrontid' => $site->pk,
                'level' => 1
            ]
        )->limit(1)->get();

        $data = $this->getProductData(
            parent::getQS()->filter(
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

    public function actionSliderFeatured(): void
    {
        $qs = parent::getQS()
            ->filter(
                [
                    'featured__product_order__isnull' => false,
                ]
            )
            ->order(['?']);

        $data = $this->getProductData($qs);
        $this->jsonResponse($data);
    }

    public function actionSliderAlsoBought($id): void
    {
        /** @var ProductModel[] $products */
        $products = SliderDataHelper::getSliderData('products_also_bought_with_this_product', $id);
        if ($products) {
            $data = $this->getProductData($products);
            $this->jsonResponse($data);
        }
    }

    public function actionSliderRelatedProducts($id): void
    {
        /** @var ProductModel[] $products */
        $products = SliderDataHelper::getSliderData('similar_products', $id);

        if ($products) {
            $data = $this->getProductData($products);
            $this->jsonResponse($data);
        }
    }

    public function actionSliderViewed(): void
    {
        /** @var ProductModel[] $products */
        $products = SliderDataHelper::getSliderData('recently_viewed_products');

        if ($products) {
            $data = $this->getProductData($products);
            $this->jsonResponse($data);
        }
    }

    public function getQS($data)
    {
        return parent::getQS($data)
            ->filter(
                [
                    'categories__lft__gte' => $data->lft,
                    'categories__rgt__lte' => $data->rgt,
                    'categories__root' => $data->root,
                ]
            );
    }

    /**
     * get paginated category by id
     * @param int $id category id
     * @param string $slug
     * @throws \Exception
     */
    public function actionCatalogCategory(int $id, string $slug): void
    {
        //actionViewOld
        $model = CategoryModel::objects()->filter(['categoryid' => $id])->get();

        //view_internal
        $this->model = $model;
        $orderBy = Xcart::app()->request->session->get('category_sort', ProductSortHelper::$default);

        /** @var \Xcart\App\Orm\QuerySet $pqs */
        $pqs = $this->getQS($model);
        $fh = new ProductFilterHelper($pqs, $this->getRequest()->get->get('filter', []), $this->filters);


        if ($this->getRequest()->getIsAjax()) {
            $pqs = $fh->getFiltrateQS();
            $pqs = $this->getSortedQS($pqs);
        }

        $pager = $this->getPager($pqs);

        $this->setCanonical($model);

        if ($this->getRequest()->getIsAjax()) {
            $pagerView = $pager->createView();
            $this->jsonResponse(
                [
                    'href' => $pagerView->hasNextPage() ? $pagerView->getUrl($pager->getPage() + 1) : false,
                    'items' => $this->getProductData($pager->paginate()),
                    'pager' => [
                        'pageSize' => $pager->getPageSize(),
                        'currentPage' => $pager->getPage(),
                        'paginateCount' => count($pager->paginate()),
                        'total' => $pager->getTotal(),
                    ],
                ]
            );
        }
    }

    /**
     * get array of main product fields (product has many excess data because this method takes only needed info) and return this
     * @param $products
     * @return array
     */
    private function getProductData($products): array
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
                $unique_hash_list = [];

                foreach ($children as $child) {
                    $image = $child->images->filter(['avail' => 'Y'])->order(['orderby'])->limit(1)->get();

                    if (in_array($image->md5, $unique_hash_list, true) === true) {
                        continue;
                    }

                    $unique_hash_list[] = $image->md5;

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

            $eta_date = '';

            if ($product->eta_date_mm_dd_yyyy && $product->eta_date_mm_dd_yyyy > time()) {
                $date = (new \DateTime())->setTimestamp($product->eta_date_mm_dd_yyyy);
                $eta_date = date_format($date, "d F Y");
            }

            $data[] = [
                'name' => htmlspecialchars_decode($product->getFrontendName() ?: $product->product, ENT_QUOTES),
                'url' => $product->getAbsoluteUrl(),
                'mpn' => $product->getMpn(),
                'upc' => $product->upc,
                'images' => $images,
                'description' => $product->getCatalogDescription(),
                'inStock' => !$product->isOutOfStock(),
                'productcode' => $product->productcode,
                'brand' => $product->brand->brand ?? null,
                'brandUrl' => $product->brand ? $product->brand->getAbsoluteUrl() : null,
                'min_amount' => $product->min_amount,
                'lead_time_message' => trim($product->lead_time_message),
                'mult_order_quantity' => $product->mult_order_quantity,
                'eta_date' => $eta_date,
                'avail' => $product->r_avail,
                'productid' => $product->productid,
                'isNew' => $product->isNewProduct(),
                'isSale' => $product->isSaleSticker(),
                'isGroupRoot' => $product->isGroupRoot(),
                'childrenNumber' => $product->getFrontendChilds()->count(),

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