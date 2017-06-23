<?php

namespace Modules\Product\Controllers;

use Mindy\QueryBuilder\Expression;
use Modules\Product\Models\ProductModel;
use Xcart\App\Components\Breadcrumbs;
use Xcart\App\Main\Xcart;
use Xcart\ElasticSearch;

class SearchController extends AbstractCatalogController
{
    public $view = 'catalog/search.tpl';
    public $filters = ['price', 'brand', 'filter'];

    public $ids;

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

        $this->getProductFromElastic($q);

        if (empty($this->ids)) {
            echo $this->render('catalog/search_empty.tpl', [
                'model' => $q,
                'breadcrumbs' => $this->getBreadcrumbs($q),
            ]);
            die();
        }

        $this->view_internal($q);
    }

    public function getProductFromElastic($search, $min_score = null)
    {
        /** @var \Modules\Sites\SitesModule $siteModule */
        /** @var \Modules\Core\CoreModule $coreModule */
        $siteModule = Xcart::app()->getModule('Sites');
        $coreModule = Xcart::app()->getModule('Core');
        $config = $coreModule::getGlobalConfig();

        $classElastic = new ElasticSearch($config["ElasticSearch_options"], $siteModule->getSite()->domain);
        $classElastic->setSource("*._id");
        $classElastic->setMinScore($min_score ?: $config["ElasticSearch_options"]["search_results_minimum_score_value"]);
        $classElastic->setType('product');
        $classElastic->setQueryParams($search);

        $result = $classElastic->query(['from' => 0, 'size' => 1000]);
        $items = $result["hits"]["hits"];

        if ($items) {
            $items = $this->getProductFromElastic($search, .01);
        }

        usort($items, function($a, $b){
            if ($a['_score'] == $b['_score']) {
                return 0;
            }
            return $a['_score'] < $b['_score'] ?  -1 : 1;
        });

        return $this->ids = array_map(function($item) {return $item['_id']; }, $items);
    }

    public function getQS($data)
    {
        /** @var \Modules\Sites\SitesModule $siteModule */
        $siteModule = Xcart::app()->getModule('Sites');

        return parent::getQS($data)
                     ->filter(['sites__storefrontid' => $siteModule->getSite()->storefrontid, 'productid__in' => $this->ids]);
    }

    public function getSortedQS($qs, $model = null)
    {
        if ($this->sort == 'relevance') {
            $ta = $qs->getTableAlias();
            return $qs->order([new Expression("FIELD({$ta}.productid, " . implode(',', $this->ids) . ") ASC")]);
        }

        return parent::getSortedQS($qs, $model);
    }

    public function getBreadcrumbs($data)
    {
        $bread = new Breadcrumbs();
        $bread->add('Search: '. strip_tags($data));

        return $bread;
    }
}