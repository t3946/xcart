<?php

namespace Modules\Product\Controllers;

use Modules\Product\Models\ProductModel;
use Xcart\App\Components\Breadcrumbs;
use Xcart\App\Main\Xcart;

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
        $q = $this->getRequest()->get->get('q', '');
        if (!$q) {
            $this->redirect('/');
        }

        if ($product = ProductModel::objects()->filter(['productcode' => $q])->get()) {
            $this->redirect($product->getAbsoluteUrl());
        }
//
//        echo $this->render($this->view, [
//            'q' => $q,
//            'breadcrumbs' => $this->getBreadcrumbs($q),
//        ]);

        $this->view_internal($q);
    }

    public function getQS($data)
    {
        /** @var \Modules\Sites\SitesModule $siteModule */
        $siteModule = Xcart::app()->getModule('Sites');
//        return parent::getQS($data)->filter(['name__like' => "%{$data}%"]);
        return parent::getQS($data)->filter(['sites__storefrontid' => $siteModule->getSite()->storefrontid]);
    }

    public function getBreadcrumbs($data)
    {
        $bread = new Breadcrumbs();
        $bread->add('Search: '. strip_tags($data));

        return $bread;
    }
}