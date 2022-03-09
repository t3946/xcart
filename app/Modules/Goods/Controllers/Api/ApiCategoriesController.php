<?php

namespace Modules\Goods\Controllers\Api;

use DateTime;
use Modules\Goods\Controllers\AbstractCatalogController;
use Modules\Goods\Helpers\ApiProductHelper;
use Modules\Goods\Helpers\ProductFilterHelper;
use Modules\Goods\Helpers\ProductSortHelper;
use Modules\Goods\Helpers\PromotionalProductsHelper;
use Modules\Goods\Helpers\SliderDataHelper;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\ProductImageModel;
use Modules\Goods\Models\ProductModel;
use Modules\Sites\Models\CurrencyModel;
use Modules\User\Models\SurfMetaModel;
use Xcart\App\Exceptions\UnknownPropertyException;
use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\QuerySet;
use Modules\Order\Models\OrderStatusModel;

class ApiCategoriesController extends AbstractCatalogController
{
    public bool $is_slider = false;
    public function actionSliderBestsellers(): void
    {
        $qs = PromotionalProductsHelper::getBestsellersSQ();

        if ($this->getRequest()->getIsAjax()) {
            $this->jsonResponse($this->getPaginatedProducts($qs));
        }
    }

    public function actionSliderNew(): void
    {
        $qs = parent::getQS()
            ->filter(['detail_images__image_id__isnull' => false])
            ->order(['-add_date'])
            ->group(['productid'])
            ->limit(200)
            ->cache(3600);

        if ($this->getRequest()->getIsAjax()) {
            $this->jsonResponse($this->getPaginatedProducts($qs));
        }
    }

    public function actionSliderFeatured(): void
    {
        $qs = parent::getQS()
            ->filter(['featured__product_order__isnull' => false])
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
            $data = ApiProductHelper::getProductData($products, ['is_slider' => $this->is_slider]);
            $this->jsonResponse(['items' => $data]);
        }
    }

    public function actionSliderViewed($id = null): void
    {
        if ($meta_id = SurfMetaModel::getInstance()->id) {
            $qs = parent::getQS()
                ->distinct()
                ->filter(['surf_path__meta_id' => $meta_id])
                ->order(['-surf_path__position']);
            if ($id) {
                $qs->exclude(['productid' => $id]);
            }
            $this->is_slider = true;

            $this->jsonResponse($this->getPaginatedProducts($qs));
        }
    }

    public function getQS($data = null)
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

    private function getPaginatedProducts($qs): array
    {
        //sorting products
        $isCatalogPage = (int)$this->getRequest()->get->get('isCatalogPage', 0);

        if ($isCatalogPage === 1) {
            $this->sort = Xcart::app()->request->session->get('category_sort', ProductSortHelper::$default);
            $fh = new ProductFilterHelper($qs, $this->getRequest()->get->get('filter', []), $this->filters);
            if ($this->getRequest()->getIsAjax()) {
                $qs = $fh->getFiltrateQS();
                $this->sort = $this->getRequest()->get->get('sort', $this->sort);
                $qs = $this->getSortedQS($qs);
                $qs->cache(60);
            }
        }

        $pager = $this->getPager($qs);
        $this->setCanonical($this->model);
        $products = $pager->paginate();
        return [
            'items' => ApiProductHelper::getProductData($products, ['is_slider' => $this->is_slider]),
            'pager' => [
                'pageSize' => $pager->getPageSize(),
                'currentPage' => $pager->getPage(),
                'paginateCount' => count($products),
                'total' => $pager->getTotal(),
                'pagesCount' => $pager->getPagesCount(),
            ],
        ];
    }

    public function getBuyAgainProducts()
    {
        /**
         * check authorisation
         * @var $user UserModel
         */
        $user = Xcart::app()->auth->getUser(true);

        if ($user->getIsGuest()) {
            http_response_code(401);
            return;
        }


        $qs = ProductModel::objects()->filter([
            'order_details__order_group__order__user_id' => $user->user_id,
            'order_details__order_group__order__cb_status' => OrderStatusModel::ORDER_STATUS_COMPLETED,
            'order_details__order_group__order__dc_status' => OrderStatusModel::ORDER_DC_STATUS_DELIVERED
        ])->group(["productid"]);
        $this->sort = $this->getRequest()->get->get('sort', $this->sort);
        $qs = $this->getSortedQS($qs);
        $pager = $this->getPager($qs);
        $this->setCanonical($this->model);
        $products = $pager->paginate();

        $this->jsonResponse([
            'items' => $this->getProductData($products),
            'pager' => [
                'pageSize' => $pager->getPageSize(),
                'currentPage' => $pager->getPage(),
                'paginateCount' => count($products),
                'total' => $pager->getTotal(),
                'pagesCount' => $pager->getPagesCount(),
            ],
        ]);
    }

    /**
     * get paginated category by id
     * @param int $id category id
     */
    public function actionCatalogCategory(int $id): void
    {
        if ($_GET['sort']) {
            Xcart::app()->request->session->add('category_sort', $_GET['sort']);
        }

        if ($this->model = CategoryModel::objects()->filter(['categoryid' => $id])->get()) {
            /** @var QuerySet $qs */
            $qs = $this->getQS($this->model);

            if ($this->getRequest()->getIsAjax()) {
                $this->jsonResponse($this->getPaginatedProducts($qs));
            }
        }
    }
}