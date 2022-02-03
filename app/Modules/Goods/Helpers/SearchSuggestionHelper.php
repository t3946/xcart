<?php
namespace Modules\Goods\Helpers;

use Elastic\EnterpriseSearch\AppSearch\Request\QuerySuggestion;
use Elastic\EnterpriseSearch\AppSearch\Request\Search;
use Elastic\EnterpriseSearch\AppSearch\Schema\QuerySuggestionRequest;
use Elastic\EnterpriseSearch\AppSearch\Schema\SearchRequestParams;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\ProductImageModel;
use Modules\Goods\Models\ProductModel;
use Modules\Search\Helpers\Searchers\CategoryDocumentSearcher;
use Modules\Search\Helpers\Searchers\ProductDocumentSearcher;
use Modules\Search\SearchModule;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;
use Xcart\ElasticSearch;

class SearchSuggestionHelper
{
    private $elastic;
    private $search;

    public function __construct($search, $indexes = null, $type = 'product')
    {
        $this->search = trim($search);
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
        $site = Xcart::app()->getModule('Sites')->getSite();

        $searchResult = Xcart::app()->elastic->search(
            SearchModule::getEngine($site->code),
            $this->search,
            new ProductDocumentSearcher(),
            1,
            $count
        );

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

        $site = Xcart::app()->getModule('Sites')->getSite();

        $searchResult = Xcart::app()->elastic->search(
            SearchModule::getEngine($site->code, SearchModule::CATEGORIES_ENGINE),
            $this->search,
            new CategoryDocumentSearcher(),
            1,
            $count
        );

        $p_suggestions = [];

        foreach($searchResult['results'] as $result) {
            $p_suggestions[] = [
                'id' => $result['id']['raw'],
                'link' => $result['url']['raw'],
                'name' => $result['category']['raw'],
            ];
        }
        return $p_suggestions;

    }

    public function suggestion_phrase($count = 5, $self_include = false): array
    {
        $site = Xcart::app()->getModule('Sites')->getSite();

        $search = trim($this->search);

        $suggestions = Xcart::app()->elastic->suggestion(SearchModule::getEngine($site->code), $search, $count);

        foreach ($suggestions['results']['documents'] as $suggestion) {
            if (!$self_include &&  $suggestion['suggestion'] === $search) {
                continue;
            }
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