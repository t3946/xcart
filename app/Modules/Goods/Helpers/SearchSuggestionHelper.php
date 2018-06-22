<?php
namespace Modules\Goods\Helpers;

use Modules\Core\Components\GlobalConfig;
use Modules\Goods\Models\ProductModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;
use Xcart\ElasticSearch;

class SearchSuggestionHelper
{
    private $elastic;
    private $search;

    public function __construct($search, $indexes=null) {
        /** @var \Modules\Core\CoreModule $coreModule */
        $coreModule = Xcart::app()->getModule('Core');
        $config = $coreModule::getGlobalConfig();

        $config_min_scope = $config["ElasticSearch_options"]["search_results_minimum_score_value"];

        $this->search = trim($search);
        $this->elastic = new ElasticSearch($config["ElasticSearch_options"], $indexes ?: $this->getSearchIndex());
        $this->elastic->setSource("*._id");
        $this->elastic->setMinScore($config_min_scope);
        $this->elastic->setType('product');
        $this->elastic->setQueryParams($search);
        GlobalConfig::getInstance()->setOldMode(false);
    }

    public function getSearchIndex()
    {
        /** @var \Modules\Sites\SitesModule $siteModule */
        $siteModule = Xcart::app()->getModule('Sites');

        if ($siteModel = $siteModule->getSite(false)) {
            return $siteModel->domain;
        }

        $sites = SiteModel::getAllEnabled();
        $indexes = array_map(function($model) { return $model->domain; }, $sites);

        return implode(',', $indexes);
    }

    public function elastic_suggestion($count = 5, array $html = [])
    {
        $query = /** @lang JSON */ <<<JSON
{
    "suggest" : {
        "text" : "{$this->search}",
        "simple_phrase" : {
            "phrase" : {
                "highlight": {
                  "pre_tag": "",
                  "post_tag": ""
                },
                "field" :  "productname",
                "size" :   5,
                "direct_generator" : [{
                    "field" :            "description",
                    "suggest_mode" :     "missing",
                    "min_word_length" :  2
                }],
                "collate": {
                    "query":{
                        "dis_max" : {
                            "queries" : [
                            {
                                  "query_string": {
                                      "query": "{{suggestion}}",
                                      "fields": ["productname.productname_original^1.5","sku","upc","brand.brand_original^0.5","description.description_original"]
                                  }
                            } ,
                            {
                                "query_string": {
                                  "query": "{{suggestion}}",
                                  "analyzer": "snowball",
                                  "fields": ["productname.productname^1.5","sku","upc","brand.brand^0.5","description.description"]
                                }
                            },
                            {
                                "match_phrase_prefix": {
                                  "sku_original": "{{suggestion}}"
                                }
                            }]
                        }
                    }
                }
            }
        }
    }
}
JSON;
        $this->elastic->setQueryParam(json_decode($query));
        $result = $this->elastic->query(['size' => 5, 'from' => 0, 'q' => $this->search]);


        $suggests = [];

        if (!empty($result["suggest"]["simple_phrase"]) && is_array($result["suggest"]["simple_phrase"])){
            foreach ($result["suggest"]["simple_phrase"] as $k => $v){
                if (!empty($v["options"]) && is_array($v["options"])){
                    foreach ($v["options"] as $kk => $vv){
                        if (!empty($vv["highlighted"])){
                            $suggests[] = $vv["highlighted"];
                        }
                    }
                }
            }
        }

        if (isset($result['hits']['hits']) && $result['hits']['hits'] && is_array($result['hits']['hits'])) {
            foreach($result['hits']['hits'] as $hit) {
                $ids[] = $hit['_id'];
            }
            /** @var ProductModel[] $products */
            if ($ids && $products = ProductModel::objects()->filter(['productid__in' => $ids])->all()) {
                foreach($products as $product) {
                    $thumb = $product->thumbnail->limit(1)->get();
                    $p_suggestions[] = [
                        'id' => $product->productid,
                        'link' => $product->getAbsoluteUrl(),
                        'name' => $product->getFrontendName(),
                        'image' => $thumb ? (string) $thumb : null
                    ];
                }
            }
        }

        return array_merge($suggests, ['product_suggestions' => $p_suggestions], ['category_suggestions' => [
            [
                'link' => '/',
                'name' => 'sdfsdfsf'
            ],
            [
                'link' => '/',
                'name' => 'sdfsdfsf'
            ],
            [
                'link' => '/',
                'name' => 'sdfsdfsf'
            ],
            [
                'link' => '/',
                'name' => 'sdfsdfsf'
            ],
            [
                'link' => '/',
                'name' => 'sdfsdfsf'
            ],
        ]]);
    }

    public function suggestion_phrase($count = 5)
    {
        $suggests = [];

        /** @var \Modules\Sites\SitesModule $siteModule */
        /** @var \Modules\Core\CoreModule $coreModule */
        $siteModule = Xcart::app()->getModule('Sites');
        $coreModule = Xcart::app()->getModule('Core');

        $spaces = substr_count($this->search, ' ') + 2;

        $search_phrase_updated = addslashes(ltrim($this->search));

        $query = "select LOWER(SUBSTRING_INDEX(SS.search_phrase,' ', :spaces)) As suggester
       from xcart_search_stats SS 
       where SS.storefrontid = :sfid and SS.hits>0 and SS.search_phrase like :name
       group By Suggester
       Order By COUNT(SS.id) desc
        Limit {$count}";

        $query_result = Xcart::app()->db->getConnection()->fetchAll($query,[
            'spaces' => $spaces,
            'sfid' => $siteModule->getSite()->storefrontid,
            'name' => $search_phrase_updated.'%',
        ]);

        if (!empty($query_result)){
            foreach ($query_result as $k => $v){

                $suggests[] = $v["suggester"];
            }
        }

        return $suggests;
    }

    public function mixed_suggestion($count = 5)
    {
        return array_merge($this->elastic_suggestion($count), ['phrase_suggestions' => $this->suggestion_phrase($count)]);
    }
}