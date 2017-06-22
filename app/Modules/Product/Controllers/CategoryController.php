<?php

namespace Modules\Product\Controllers;

use Modules\Product\Models\CategoryModel;
use Xcart\App\Main\Xcart;

class CategoryController extends AbstractCatalogController
{
    public $view = 'catalog/category.tpl';
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