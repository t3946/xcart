<?php
namespace Modules\Goods\Helpers;

use Elastic\EnterpriseSearch\AppSearch\Request\QuerySuggestion;
use Elastic\EnterpriseSearch\AppSearch\Request\Search;
use Elastic\EnterpriseSearch\AppSearch\Schema\QuerySuggestionRequest;
use Elastic\EnterpriseSearch\AppSearch\Schema\SearchRequestParams;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\ProductImageModel;
use Modules\Goods\Models\ProductModel;
use Modules\Search\SearchModule;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;
use Xcart\ElasticSearch;

class SearchSuggestionHelper
{
    private $elastic;
    private $search;

    public function __construct($search, $indexes=null, $type = 'product') {

        $config = Xcart::app()->getModule('Sites')->getSite()->getGlobalConfig();
        $this->search = trim($search);

        $this->elastic = new ElasticSearch($config['es_url'], $indexes ?: $this->getSearchIndex());
        $this->elastic->setSource("*._id");
        $this->elastic->setMinScore($config['search_results_minimum_score_value']);
        $this->elastic->setType($type);
        $this->elastic->setQueryParams($search);
    }

    public function getSearchIndex(): string
    {
        if ($siteModel = Xcart::app()->getModule('Sites')->getSite(false)) {
            return $siteModel->domain;
        }

        $sites = SiteModel::getAllEnabled();
        $indexes = array_map(static fn($model) => $model->domain, $sites);

        return implode(',', $indexes);
    }

    public function elastic_suggestion($count = 5, array $html = []): array
    {
        $client = Xcart::app()->elastic->getClient()->appSearch();

        $site = Xcart::app()->getModule('Sites')->getSite();

        $searchParam = new SearchRequestParams(trim($this->search));
        $searchParam->filters = (object)['all' => [(object)['sites' => $site->code], (object)['in_stock' => 1]]];
        $searchParam->search_fields = (object)['product' => (object)[], 'upc' => (object)[], 'productcode' => (object)[]];
        $searchParam->page = (object)['current' => 1, 'size' => $count];

        $request = new Search(SearchModule::PRODUCTS_ENGINE, $searchParam);

        $searchResult = $client->search($request)->asArray();

        $p_suggestions = [];

        foreach($searchResult['results'] as $result) {
            $p_suggestions[] = [
                'id' => $result['id']['raw'],
                'link' => $result['url']['raw'],
                'name' => $result['product']['raw'],
                'image' => json_decode($result['main_image']['raw'], true)['thumb']
            ];
        }
        return $p_suggestions;
    }
    public function elastic_category_suggestion($count = 5, array $html = []): array
    {
        //TODO rewrite on new elastic engine
        return [];

        $this->elastic->setType('category');
        $result = $this->elastic->query(['size' => $count, 'from' => 0, 'q' => $this->search]);

        $suggests = [];

        if (isset($result['hits']['hits']) && $result['hits']['hits'] && is_array($result['hits']['hits'])) {
            foreach($result['hits']['hits'] as $hit) {
                $ids[] = $hit['_id'];
            }
            /** @var ProductModel[] $products */
            if ($ids && $categories = CategoryModel::objects()->filter(['categoryid__in' => $ids])->all()) {
                foreach($categories as $category) {
                    $suggests[] = [
                        'id' => $category->categoryid,
                        'link' => $category->getAbsoluteUrl(),
                        'name' => $category->getFrontendName(),
                    ];
                }
            }
        }

        return $suggests ?? [];
    }

    public function suggestion_phrase($count = 5, $self_include = false): array
    {
        $client = Xcart::app()->elastic->getClient()->appSearch();

        $request = new QuerySuggestionRequest();
        $request->query = trim($this->search);
        $request->types = (object)['documents' => ['fields' => ['product', 'brand', 'productcode', 'upc']]];
        $request->size = $count;

        $suggestion = new QuerySuggestion(SearchModule::PRODUCTS_ENGINE, $request);

        $suggestions = $client->querySuggestion($suggestion)->asArray();

        foreach ($suggestions['results']['documents'] as $suggestion) {
            $result[] = $suggestion['suggestion'];
        }

        return $result ?? [];
    }

    public function mixed_suggestion($count = 5, $self_include = false): array
    {
        return array_merge(
            ['product_suggestions' => $this->elastic_suggestion($count)],
            ['category_suggestions' => $this->elastic_category_suggestion(4)],
            ['phrase_suggestions' => $this->suggestion_phrase($count, $self_include)]
        );
    }
}