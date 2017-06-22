<?php

namespace Modules\Product\Controllers;

use Modules\Product\Helpers\ProductFilterHelper;
use Modules\Product\Helpers\ProductSortHelper;
use Modules\Product\Models\CategoryModel;
use Modules\Product\Models\ProductModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;
use Xcart\App\Pagination\Pagination;

abstract class AbstractCatalogController extends Controller
{
    public $view = '';
    public $model = null;
    public $filters = ['price', 'brand', 'filter'];

    public function beforeAction($action, $params)
    {
        if ( $this->getRequest()->getIsPost() && !empty($_POST['sort'])) {
            $this->getRequest()->session->add('category_sort', $_POST['sort']);
            echo "OK";
            Xcart::app()->end();
        }

        parent::beforeAction($action, $params);
    }

    /**
     * @param mixed $data
     *
     * @return \Xcart\App\Orm\QuerySet|\Xcart\App\Orm\Manager
     */
    public function getQS($data)
    {
        return ProductModel::objects()->filter([ 'forsale' => 'Y' ]);
    }

    public function getAdvancedData($data = null) { return []; }

    /**
     * @param CategoryModel|null $model
     *
     * @throws \Exception
     * @throws \Xcart\App\Exceptions\HttpException
     */
    protected function view_internal($model = null)
    {
        //@TODO: Если категория отключена, редирект на редирект на первую включенную категорию

        if (!$model) {
            $this->error();
        }

        $this->model = $model;

        $orderBy = Xcart::app()->request->session->get('category_sort', ProductSortHelper::$default);

        /** @var \Xcart\App\Orm\QuerySet $pqs */
        $pqs = $this->getQS($model);

        $filters = (new ProductFilterHelper($pqs, $this->getRequest()->get->get('filter', [])))->getFilterStructure($this->filters);

        if ($this->getRequest()->get->has('filter')) {
            $pqs = (new ProductFilterHelper($pqs, $this->getRequest()->get->get('filter', [])))->getFiltrateQS();
        }

        $pqs = (new ProductSortHelper($pqs))
            ->setCategory(($model instanceof  CategoryModel ? $model : null))
            ->getSortedQS($orderBy);

        $pager = new Pagination($pqs, ['pageSize' => 100, 'view' => 'core/pager/front_endless.tpl'], new QuerySetDataSource());

        if ($this->getRequest()->getIsAjax())
        {
            $this->jsonResponse([
                'content' => $this->render($this->view, [ 'model' => $model, 'pager' => $pager,]),
                'pager' => $pager->render(),
                'page_count' => $this->render('catalog/parts/_page_count.tpl', [ 'model' => $model, 'pager' => $pager,]),
            ]);
        }
        else {
            echo $this->render($this->view, array_merge([
                'model' => $model,
                'pager' => $pager,
                'sort'  => $orderBy,
                'sort_arr'  => ProductSortHelper::$orderBy,
                'breadcrumbs' => $model->getBreadcrumbs(),
                'filters' => $filters,
            ], $this->getAdvancedData($model)));
        }
    }
}