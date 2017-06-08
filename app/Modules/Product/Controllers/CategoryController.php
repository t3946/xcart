<?php

namespace Modules\Product\Controllers;

use Modules\Product\Helpers\ProductSortHelper;
use Modules\Product\Models\CategoryModel;
use Modules\Product\Models\ProductModel;
use Modules\Product\TemplateLibraries\FilterLibrary;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;
use Xcart\App\Pagination\Pagination;

class CategoryController extends Controller
{
    public function beforeAction($action, $params)
    {
        if ( $this->getRequest()->getIsPost() && !empty($_POST['sort'])) {
            $this->getRequest()->session->add('category_sort', $_POST['sort']);
            echo "OK";
            Xcart::app()->end();
        }

        parent::beforeAction($action, $params);
    }

    public function view_old($id, $slug)
    {
        $this->view_internal(CategoryModel::objects()->filter(['categoryid' => $id])->get());
    }

    public function view($sku)
    {
        $this->view_internal(CategoryModel::objects()->filter(['productcode' => $sku])->get());
    }

    /**
     * @param CategoryModel|null $model
     *
     * @throws \Xcart\App\Exceptions\HttpException
     */
    private function view_internal($model = null)
    {
        //@TODO: Если категория отключена, редирект на редирект на первую включенную категорию

        if (!$model) {
            $this->error();
        }

        $orderBy = Xcart::app()->request->session->get('category_sort', ProductSortHelper::$default);

        /** @var \Xcart\App\Orm\QuerySet $pqs */
        $pqs = ProductModel::objects()
            ->filter([
                 'forsale' => 'Y',
                 'categories__categoryid__in' => CategoryModel::objects($model)->descendants(true)->select('pk')->order([]),
            ]);

        $filters = [];
        $form_data = $this->getRequest()->get->get('fform', []);
        $filters = FilterLibrary::getFilterStructure($pqs, $form_data);

        if ($this->getRequest()->get->has('fform')) {
            $pqs = FilterLibrary::getFiltrateQS($pqs, $form_data);
        }

        $pqs = (new ProductSortHelper($pqs))
            ->setCategory($model)
            ->getSortedQS($orderBy);

        $pager = new Pagination($pqs, ['pageSize' => 100, 'view' => 'core/pager/front_endless.tpl'], new QuerySetDataSource());

        if ($this->getRequest()->getIsAjax())
        {
            $this->jsonResponse([
                'content' => $this->render('catalog/category.tpl', [ 'model' => $model, 'pager' => $pager,]),
                'pager' => $pager->render(),
                'page_count' => $this->render('catalog/parts/_page_count.tpl', [ 'model' => $model, 'pager' => $pager,]),
            ]);
        }
        else {
            echo $this->render('catalog/category.tpl', [
                'model' => $model,
                'pager' => $pager,
                'sort'  => $orderBy,
                'sort_arr'  => ProductSortHelper::$orderBy,
                'breadcrumbs' => $model->getBreadcrumbs(),
                'filters' => $filters,
            ]);
        }
    }
}