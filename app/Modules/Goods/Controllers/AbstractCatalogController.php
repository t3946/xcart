<?php

namespace Modules\Goods\Controllers;

use Exception;
use Modules\Goods\Helpers\ApiProductHelper;
use Modules\Goods\Models\ProductImageModel;
use Modules\Sites\Models\CurrencyModel;
use Xcart\App\Components\Breadcrumbs;
use Xcart\App\Exceptions\HttpException;
use Xcart\App\Orm\Manager;
use Xcart\App\Orm\QuerySet;
use Xcart\App\QueryBuilder\Q\Q;
use Xcart\App\QueryBuilder\Q\QOr;
use Modules\Brand\Models\BrandModel;
use Modules\Goods\Helpers\ProductFilterHelper;
use Modules\Goods\Helpers\ProductSortHelper;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;
use Xcart\App\Pagination\Pagination;
use function is_object;

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
            exit();
        }

//        $this->sort = Xcart::app()->request->session->get('category_sort', ProductSortHelper::$default);
    }


    /**
     * @param mixed $data
     *
     * @return QuerySet|Manager
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
     * @param QuerySet $qs
     * @param CategoryModel|[] $qs
     *
     * @return Manager|QuerySet
     */
    public function getSortedQS($qs, $model = null)
    {
        return (new ProductSortHelper($qs))
            ->setCategory(($model instanceof CategoryModel ? $model : null))
            ->getSortedQS($this->sort);
    }

    /**
     * @param QuerySet $qs
     *
     * @return Pagination
     */
    public function getPager($qs): Pagination
    {
        return new Pagination($qs, [
            'pageSize' => $this->pageSize,
            'view' => 'core/pager/front_endless.tpl',
            'pageKey' => 'page',
            'is_ajax' => $this->getRequest()->getIsAjax()
        ], new QuerySetDataSource());
    }

    /**
     * @param $data
     *
     * @return Breadcrumbs|array|null
     */
    public function getBreadcrumbsFromData($data)
    {
        if (is_object($data) && method_exists($data, 'getBreadcrumbs'))
        {
            return $data->getBreadcrumbs();
        }

        return $this->getBreadcrumbs();
    }

    public function getAdvancedData($data = null): array { return []; }

    /**
     * @param CategoryModel|BrandModel|null $model
     *
     * @throws Exception
     * @throws HttpException
     */
    protected function view_internal($model = null): void
    {
        $this->model = $model;

        $orderBy = Xcart::app()->request->session->get('category_sort', ProductSortHelper::$default);

        /** @var QuerySet $pqs */
        $pqs = $this->getQS($model);

        $fh = new ProductFilterHelper($pqs, $this->getRequest()->get->get('filter', []), $this->filters);

        $pqs = $fh->getFiltrateQS();
        $pqs = $this->getSortedQS($pqs);

        $this->setCanonical($model);

        if ($this->getRequest()->getIsAjax())
        {
            $pager = $this->getPager($pqs);
            $pagerView = $pager->createView();

            $products = ApiProductHelper::getProductData($pager->paginate());

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
                    'sort'  => $orderBy,
                    'sort_arr'  => ProductSortHelper::getOrderBy(),
                    'breadcrumbs' => $this->getBreadcrumbsFromData($model),
                    'filters' => $fh->getFilterStructure($this->filters, $model instanceof CategoryModel ? $model->level : 2),
                ], $this->getAdvancedData($model)));
        }
    }
}