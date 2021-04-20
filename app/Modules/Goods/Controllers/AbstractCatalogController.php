<?php

namespace Modules\Goods\Controllers;

use Mindy\QueryBuilder\Q\QOr;
use Modules\Brand\Models\BrandModel;
use Modules\Goods\Helpers\ProductFilterHelper;
use Modules\Goods\Helpers\ProductSortHelper;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;
use Xcart\App\Pagination\Pagination;

abstract class AbstractCatalogController extends FrontendController
{
    public $view = '';
    public $model = null;
    public $sort = null;
    public $pageSize = 20;
    public $filters = ['price', 'brand', 'filter'];

    public function getAdvancedCacheData(): array
    {
        return ['category_sort' => Xcart::app()->request->session->get('category_sort', ProductSortHelper::$default)];
    }

    public function beforeAction($action, $params): void
    {
        if ( $this->getRequest()->getIsPost() && !empty($_POST['sort'])) {
            $this->getRequest()->session->add('category_sort', $_POST['sort']);
            echo 'OK';
            Xcart::app()->end();
        }

        $this->sort = Xcart::app()->request->session->get('category_sort', ProductSortHelper::$default);
    }


    /**
     * @param mixed $data
     *
     * @return \Xcart\App\Orm\QuerySet|\Xcart\App\Orm\Manager
     */
    public function getQS($data = null)
    {
        $qs = ProductModel::objects();
        $ta = $qs->getTableAlias();

         $qs->filter([
            'forsale' => 'Y',
            'sites__through__sfid' => Xcart::app()->getModule('Sites')->getSite()->storefrontid,
            new QOr([
                ['group_root__isnull' => true],
                ['group_root__raw' => " = `{$ta}`.`productid`"]
            ])
         ]);

         return $qs;
    }

    /**
     * @param \Xcart\App\Orm\QuerySet $qs
     * @param CategoryModel|[] $qs
     *
     * @return \Xcart\App\Orm\Manager|\Xcart\App\Orm\QuerySet
     */
    public function getSortedQS($qs, $model = null)
    {
        return (new ProductSortHelper($qs))
            ->setCategory(($model instanceof CategoryModel ? $model : null))
            ->getSortedQS($this->sort);
    }

    /**
     * @param \Xcart\App\Orm\QuerySet $qs
     *
     * @return \Xcart\App\Pagination\Pagination
     */
    public function getPager($qs): Pagination
    {
        return new Pagination($qs, [
            'pageSize' => $this->pageSize,
            'view' => 'core/pager/front_endless.tpl',
            'pageKey' => 'page'
        ], new QuerySetDataSource());
    }

    /**
     * @param $data
     *
     * @return \Xcart\App\Components\Breadcrumbs|array|null
     */
    public function getBreadcrumbsFromData($data)
    {
        if (\is_object($data) && method_exists($data, 'getBreadcrumbs'))
        {
            return $data->getBreadcrumbs();
        }

        return $this->getBreadcrumbs();
    }

    public function getAdvancedData($data = null): array { return []; }

    /**
     * @param CategoryModel|BrandModel|null $model
     *
     * @throws \Exception
     * @throws \Xcart\App\Exceptions\HttpException
     */
    protected function view_internal($model = null): void
    {
        $this->model = $model;

        $orderBy = Xcart::app()->request->session->get('category_sort', ProductSortHelper::$default);

        /** @var \Xcart\App\Orm\QuerySet $pqs */
        $pqs = $this->getQS($model);

        $fh = new ProductFilterHelper($pqs, $this->getRequest()->get->get('filter', []), $this->filters);


        if ($this->getRequest()->getIsAjax()) {
            $pqs = $fh->getFiltrateQS();
            $pqs = $this->getSortedQS($pqs);
        }

        $pager =$this->getPager($pqs);

        $this->setCanonical($model);

        if ($this->getRequest()->getIsAjax())
        {
            $pagerView = $pager->createView();

            $products = $this->getProductData(($pager->paginate()));
//            dd($products);

            $this->jsonResponse([
                'href' => $pagerView->hasNextPage() ? $pagerView->getUrl($pager->getPage() + 1) : false,
                'content' => $this->render(
                    $this->view,
                    array_replace(
                        [ 'model' => $model, 'pager' => $pager,],
                        $this->getAdvancedData($model)
                    )
                ),
                'items' => $products,
                'pager' => [
                    'pageSize' => $pager->getPageSize(),
                    'currentPage' => $pager->getPage(),
                    'paginateCount' => count($pager->paginate()),
                    'total' => $pager->getTotal(),
                ],
            ]);
        }
        else {
            $this->display($this->view,
                array_replace([
                'model' => $model,
                'pager' => $pager->setPage(0),
                'sort'  => $orderBy,
                'sort_arr'  => ProductSortHelper::getOrderBy(),
                'breadcrumbs' => $this->getBreadcrumbsFromData($model),
                'filters' => $fh->getFilterStructure($this->filters, $model instanceof CategoryModel ? $model->level : 2),
            ], $this->getAdvancedData($model)));
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

            $dx = $product->distributor;
            $brand = $product->brand;

            $data[] = [
                'name' => utf8_encode(htmlspecialchars_decode($product->getFrontendName() ?: $product->product, ENT_QUOTES)),
                'url' => $product->getAbsoluteUrl(),
                'mpn' => $product->getMpn(),
                'upc' => $product->upc,
                'images' => $images,
                'description' => utf8_encode($product->getCatalogDescription()),
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