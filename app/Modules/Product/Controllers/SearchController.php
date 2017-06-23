<?php

namespace Modules\Product\Controllers;

use Xcart\App\Components\Breadcrumbs;

class SearchController extends AbstractCatalogController
{
    public $view = 'catalog/search.tpl';
    public $filters = ['price', 'brand', 'filter'];

    public function actionKeywords($q)
    {
        $q = str_replace(['_', '-'], ' ', $q);
        $this->redirect('catalog:search', [], 301, ['q' => $q]);

//        $this->view_internal(CategoryModel::objects()->filter(['productcode' => $sku])->get());
    }
    public function actionSearch()
    {
        echo $this->render($this->view, [
            'q' => $this->getRequest()->get->get('q', ''),
            'breadcrumbs' => $this->getBreadcrumbs([]),
        ]);

//        $this->view_internal(CategoryModel::objects()->filter(['productcode' => $sku])->get());
    }

    public function getQS($data)
    {
//        return parent::getQS($data)->filter(['categories__categoryid__in' => CategoryModel::objects($data)->descendants(true)->select('pk')->order([])]);
    }

    public function getBreadcrumbs($data)
    {
        $bread = new Breadcrumbs();
        $bread->add('Search');

        return $bread;
    }
}