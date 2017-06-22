<?php

namespace Modules\Product\Controllers;

class SearchController extends AbstractCatalogController
{
    public $view = 'catalog/search.tpl';
    public $filters = ['price', 'brand', 'filter'];

    public function actionSearch()
    {
        echo $this->getRequest()->get->get('q', '');

//        $this->view_internal(CategoryModel::objects()->filter(['productcode' => $sku])->get());
    }

    public function getQS($data)
    {
//        return parent::getQS($data)->filter(['categories__categoryid__in' => CategoryModel::objects($data)->descendants(true)->select('pk')->order([])]);
    }
}