<?php

namespace Modules\Goods\Controllers\Api;

use Modules\Goods\Controllers\AbstractCatalogController;
use Modules\Goods\GoodsModule;
use Modules\Goods\Helpers\ProductFilterHelper;
use Modules\Goods\Helpers\ProductSortHelper;
use Modules\Goods\Helpers\ApiProductHelper;
use Modules\Goods\Helpers\PromotionalProductsHelper;
use Modules\Goods\Helpers\SliderDataHelper;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\ProductImageModel;
use Modules\Goods\Models\ProductModel;
use Modules\User\Models\SurfMetaModel;
use Xcart\App\Main\Xcart;

class ApiCategoriesController extends AbstractCatalogController
{
    public function actionSliderBestsellers(): void
    {
        $qs = PromotionalProductsHelper::getBestsellersSQ();

        if ($this->getRequest()->getIsAjax()) {
            $this->jsonResponse($this->getPaginatedProducts($qs));
        }
    }

    public function actionSliderNew(): void
    {
        $qs = parent::getQS()->filter(
            [
                'detail_images__image_id__isnull' => false,
            ]
        )->order(['-add_date'])->group(['productid'])->limit(1000)->cache(3600);

        if ($this->getRequest()->getIsAjax()) {
            $this->jsonResponse($this->getPaginatedProducts($qs));
        }
    }

    public function actionSliderFeatured()
    {
        $qs = parent::getQS()
            ->filter(
                [
                    'featured__product_order__isnull' => false,
                ]
            )
            ->order(['?']);

        if ($this->getRequest()->getIsAjax()) {
            $this->jsonResponse($this->getPaginatedProducts($qs));
        }
    }

    public function actionSliderAlsoBought($id): void
    {
        $qs = parent::getQS();
        $qs = $qs->getQuerySet();
        $qs->select(['p2.*'])
            ->distinct()
            ->filter([
                'order_details__order_group__cb_status' => 'P',
                'productid' => $id
            ]
        );
        $qs->join('inner join', 'xcart_order_details', ['xcart_products_1.productid' => 'xcart_order_details_1.productid'], 'xcart_order_details_1');
        $qs->join('inner join', 'xcart_order_group', ['xcart_order_details_1.order_group_id' => 'xcart_order_groups_1.order_group_id'], 'xcart_order_groups_1');
        $qs->join('inner join', 'xcart_order_details', ['xcart_order_details_1.order_group_id' => 'xcart_order_details_2.order_group_id'], 'xcart_order_details_2');
        $qs->join('inner join', 'xcart_products', ['xcart_order_details_2.productid' => 'p2.productid'], 'p2');
        $qs->exclude(['p2.productid' => $id]);

        $this->jsonResponse($this->getPaginatedProducts($qs));
    }

    public function actionSliderRelatedProducts($id): void
    {
        $products = SliderDataHelper::getSliderData('similar_products', $id);

        if ($products) {
            $data = $this->getProductData($products);
            $this->jsonResponse(['items' => $data]);
        }
    }

    public function actionSliderViewed(): void
    {
        if ($meta_id = SurfMetaModel::getInstance()->id) {
            $qs = parent::getQS()
                ->distinct()
                ->filter(['surf_path__meta_id' => $meta_id])
                ->order(['-surf_path__position']);

            $this->jsonResponse($this->getPaginatedProducts($qs));
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

    private function getPaginatedProducts($qs) {
        //sorting products
        $isCatalogPage = (int)$this->getRequest()->get->get('isCatalogPage', 0);

        if ($isCatalogPage === 1) {
            $this->sort = Xcart::app()->request->session->get('category_sort', ProductSortHelper::$default);
            $fh = new ProductFilterHelper($qs, $this->getRequest()->get->get('filter', []), $this->filters);
            if ($this->getRequest()->getIsAjax()) {
                $qs = $fh->getFiltrateQS();
                $this->sort = $this->getRequest()->get->get('sort', $this->sort);
                $qs = $this->getSortedQS($qs);
            }
        }

        $pager = $this->getPager($qs);
        $this->setCanonical($this->model);
        $products = $pager->paginate();

        return [
            'items' => $this->getProductData($products),
            'pager' => [
                'pageSize' => $pager->getPageSize(),
                'currentPage' => $pager->getPage(),
                'paginateCount' => count($products),
                'total' => $pager->getTotal(),
                'pagesCount' => $pager->getPagesCount(),
            ],
        ];
    }

    /**
     * get paginated category by id
     * @param int $id category id
     * @param string $slug
     * @throws \Exception
     */
    public function actionCatalogCategory(int $id): void
    {
        if ($_GET['sort']) {
            Xcart::app()->request->session->add('category_sort', $_GET['sort']);
        }

        $this->model = CategoryModel::objects()->filter(['categoryid' => $id])->get();

        /** @var \Xcart\App\Orm\QuerySet $qs */
        $qs = $this->getQS($this->model);

        if ($this->getRequest()->getIsAjax()) {
            $this->jsonResponse($this->getPaginatedProducts($qs));
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
            $products->limit(20)->cache(3600);
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
                /** @var ProductModel[] $children */
                $children = $product->getFrontendChilds()->limit(4)->all();
                $unique_hash_list = [];

                foreach ($children as $child) {
                    $image = $child->getImages()[0];

                    if (in_array($image->hash, $unique_hash_list, true) === true) {
                        continue;
                    }

                    $unique_hash_list[] = $image->hash;

                    if ($image && $url = $image->getCdnURL(ProductImageModel::IMAGE_SIZE_THUMB)) {
                        $images[] = [
                            'url' => $url,
                            'alt' => $child->getFrontendName(),
                        ];
                    }
                }
            } else {
                $imageModel = $product->getImages()[0];

                if ($imageModel && $url = $imageModel->getCdnURL(ProductImageModel::IMAGE_SIZE_THUMB)) {
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

            $dx = $product->distributor;
            $brand = $product->brand;

            $data[] = [
                'name' => htmlspecialchars_decode($product->getFrontendName() ?: $product->product, ENT_QUOTES),
                'url' => $product->getAbsoluteUrl(),
                'mpn' => $product->getMpn(),
                'upc' => $product->upc,
                'images' => $images,
                'description' => utf8_encode( $product->getCatalogDescription(140) ),
                'short_description' => utf8_encode( $product->getCatalogDescription(70) ),
                'inStock' => !$product->isOutOfStock(),
                'productcode' => $product->productcode,
                'brand' => $product->brand->brand ?? null,
                'brandUrl' => $product->brand ? $product->brand->getAbsoluteUrl() : null,
                'min_amount' => $product->min_amount,
                'lead_time' => [
                    'lead_time_message' => trim($product->lead_time_message),
                    'dx' => [
                        'leadtime' => $dx->dx_leadtime,
                        'leadtime_to' => $dx->dx_leadtime_to,
                    ],
                    'brand' => [
                        'leadtime_from' => $brand->leadtime_from,
                        'leadtime_to' => $brand->leadtime_to,
                    ],
                ],
                'mult_order_quantity' => $product->mult_order_quantity,
                'eta_date' => $eta_date,
                'avail' => $product->r_avail,
                'productid' => $product->productid,
                'isNew' => $product->isNewProduct(),
                'isSale' => $product->isSaleSticker(),
                'isGroupRoot' => $product->isGroupRoot(),
                'childrenNumber' => $product->isGroupRoot() ? $product->getFrontendChilds()->count() : 0,

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