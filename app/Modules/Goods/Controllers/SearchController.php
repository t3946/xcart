<?php

namespace Modules\Goods\Controllers;

use Elastic\EnterpriseSearch\AppSearch\Request\Search;
use Elastic\EnterpriseSearch\AppSearch\Schema\SearchRequestParams;
use Modules\Search\SearchModule;
use Xcart\App\QueryBuilder\Expression;
use Modules\Goods\GoodsModule;
use Modules\Goods\Helpers\ProductSortHelper;
use Modules\Goods\Helpers\SearchSuggestionHelper;
use Modules\Goods\Models\ProductModel;
use Modules\Goods\Models\SearchStatsModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Components\Breadcrumbs;
use Xcart\App\Main\Xcart;
use Xcart\ElasticSearch;

class SearchController extends AbstractCatalogController
{
    public $view = 'catalog/search.tpl';
    public $filters = ['price', 'brand', 'filter'];
    public $excluded_indexes = [
        'www.s3stores.com'
    ];
    public $ids;
    public $count;
    private $suggestion;
    private $searched;
    private $q_original;
    private $q;
    private $isSKU = false;

    public function actionKeywords($q): void
    {
        $q = str_replace(['_', '-'], ' ', $q);
        $this->redirect('catalog:search', [], 301, ['q' => $q]);
    }

    public function actionApiSuggestion(): void
    {
        if ($this->getRequest()->getIsAjax()) {
            $q = $this->getRequest()->get->get('q');
            $this->jsonResponse([
                'suggests' => (new SearchSuggestionHelper($q, $this->getSearchIndex()))->mixed_suggestion(5, true),
                'q' => $q,
            ]);
        }
    }

    public function getSearchIndex()
    {
        /** @var \Modules\Sites\SitesModule $siteModule */
        $siteModule = Xcart::app()->getModule('Sites');

        if ($siteModel = $siteModule->getSite()) {
            return $siteModel->domain;
        }

        $sites = SiteModel::getAllEnabled();
        $indexes = [];
        foreach ($sites as $site) {
            $index = strtolower($site->domain);
            if (!\in_array($index, $this->excluded_indexes, true)) {
                $indexes[] = $index;
            }
        }

        return implode(',', $indexes);
    }

    public function actionSearch(): void
    {
        $show_empty = false;

        $q = $this->getRequest()->get->get('q');

        if (!is_array($q)) {
            $this->q = $this->q_original = trim($this->getRequest()->get->get('q', ''));
        } else {
            $this->q = $this->q_original = trim($this->getRequest()->get->get('q', '')[0]);
        }

        if (!$this->q) {
            $this->redirect('/');
        }

        $qs = ProductModel::objects()->filter(['forsale' => 'Y']);

        if (preg_match('/^([a-z0-9]{3,4}-).++/i', $this->q)) {
            $this->isSKU = true;
            $tqs = clone $qs;
            $tqs->filter(['productcode__startswith' => $this->q]);

            if (($count = $tqs->count()) && $count == 1) {
                /** @var ProductModel $product */
                $product = $tqs->get();
                $this->redirect($product->getAbsoluteUrl());
            }
        }

        $this->suggestion = [
            'phrase_suggestions' => (new SearchSuggestionHelper($this->q))->suggestion_phrase(5)
        ];

        $this->searched = $this->getProductFromElastic($this->q);

        $show_empty = $this->searched === 0;

        if ($show_empty) {
            echo $this->render('catalog/search_empty.tpl', [
                'model' => $this->q ?? $q,
                'breadcrumbs' => $this->getBreadcrumbsFromData($this->q ?? $q),
            ]);

            die();
        }

        (new SearchStatsModel(
            [
                'search_phrase' => $this->q ?? $q,
                'storefrontid' => Xcart::app()->getModule('Sites')->getSite()->storefrontid,
                'customer_id' => Xcart::app()->request->session->getId(),
                'hits' => (int) $this->searched,
            ]
        ))->save();

        $q = $this->q ?? $q;

        if ($_GET['sort']) {
            Xcart::app()->request->session->add('category_sort', $_GET['sort']);
        }

        $this->sort = Xcart::app()->request->session->get('category_sort', ProductSortHelper::$default);

        $this->view_internal($q);
    }

    public function getAdvancedData($data = null): array
    {
        return [
            'suggestion' => $this->suggestion,
            'searched' => $this->searched,
            'q_original' => $this->q_original,
            'q' => $this->q,
            'analytics_source' => 'search',
            'model' => $this->q
        ];
    }

    public function getElastic($search, $min_score = null)
    {
        $config = Xcart::app()->getModule('Sites')->getSite()->getGlobalConfig();

        $classElastic = new ElasticSearch($config['es_url'], $this->getSearchIndex());
        $classElastic->setSource("*._id");
        $classElastic->setMinScore($min_score ?: $config['search_results_minimum_score_value']);
        $classElastic->setType('product');
        $classElastic->setQueryParams($search);

        return $classElastic;
    }

    public function getProductFromElastic($search, $min_score = null, $max_size = 20, $page = 1)
    {
        $client = Xcart::app()->elastic->getClient()->appSearch();

        $site = Xcart::app()->getModule('Sites')->getSite();

        $searchParam = new SearchRequestParams(trim($search));
        $searchParam->filters = (object)['all' => [(object)['sites' => $site->code], (object)['in_stock' => 1]]];
        $searchParam->search_fields = (object)[
            'product' => (object)[],
            'upc' => (object)[],
            'productcode' => (object)[],
            'fulldescr' => (object)[]
        ];
        $searchParam->page = (object)['current' => $page, 'size' => $max_size];

        $request = new Search(SearchModule::PRODUCTS_ENGINE, $searchParam);

        $searchResult = $client->search($request)->asArray();

        $items = $searchResult['results'];
        $count = $searchResult['meta']['page']['total_results'];

        $this->ids = array_map(static fn($item) => $item['id']['raw'], $items);

        return $count;
    }

    public function getQS($data = null)
    {
        /** @var \Modules\Sites\SitesModule $siteModule */
        $siteModule = Xcart::app()->getModule('Sites');

        $qs = parent::getQS($data)
                    ->filter(['sites__storefrontid' => $siteModule->getSite()->storefrontid]);

        if ($this->isSKU) {
            return $qs->filter(['productcode__startswith' => $this->q]);
        }

        return $qs->filter(['productid__in' => $this->ids]);
    }

    public function getSortedQS($qs, $model = null)
    {
        if ($this->sort == 'relevance') {
            $ta = $qs->getTableAlias();
            return $qs->order([
                new Expression("FIELD({$ta}.productid, " . implode(',', $this->ids) . ") ASC"),
            ]);
        }

        return parent::getSortedQS($qs, $model);
    }

    public function getBreadcrumbsFromData($data)
    {
        $bread = new Breadcrumbs();
        $bread->add(GoodsModule::t('Search').': '. strip_tags($data));

        return $bread;
    }
}
