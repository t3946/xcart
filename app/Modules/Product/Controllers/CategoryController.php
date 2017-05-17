<?php

namespace Modules\Product\Controllers;

use Modules\Product\Helpers\ProductSortHelper;
use Modules\Product\Models\CategoryModel;
use Modules\Product\Models\ProductModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;
use Xcart\App\Pagination\Pagination;

class CategoryController extends Controller
{
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
        if (!$model) {
            $this->error();
        }

        $orderBy = Xcart::app()->request->session->get('category_sort', 'relevance');

        //@TODO: Если категория отключена, редирект на редирект на первую включенную категорию
        /** @var \Xcart\App\Orm\QuerySet $pqs */
        $pqs = ProductModel::objects()
            ->filter([
                 'forsale' => 'Y',
                 'categories__categoryid__in' => CategoryModel::objects($model)->descendants(true)->select('pk')->order([]),
            ]);


        switch ($orderBy) {
            case 'relevance':
            default: {
                $pqs = ProductSortHelper::getOrderByRelevance($pqs, $model);
            }
        }

        $pager = new Pagination($pqs, ['pageSize' => 100], new QuerySetDataSource());


        echo $this->render('catalog/category.tpl', [
            'model' => $model,
            'pager' => $pager,
            'breadcrumbs' => $model->getBreadcrumbs()->get(),
        ]);
    }
}