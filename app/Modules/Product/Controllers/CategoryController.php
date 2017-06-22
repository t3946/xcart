<?php

namespace Modules\Product\Controllers;

use Modules\Product\Models\CategoryModel;

class CategoryController extends AbstractCatalogController
{
    public $view = 'catalog/category.tpl';
    public $filters = ['price', 'brand', 'filter'];

    public function actionViewOld($id, $slug)
    {
        $this->view_internal(CategoryModel::objects()->filter(['categoryid' => $id])->get());
    }

    public function actionView($sku)
    {
        $this->view_internal(CategoryModel::objects()->filter(['productcode' => $sku])->get());
    }

    public function getQS($data)
    {
        return parent::getQS($data)->filter(['categories__categoryid__in' => CategoryModel::objects($data)->descendants(true)->select('pk')->order([])]);
    }
}