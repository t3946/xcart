<?php
namespace Modules\Product\Helpers;

use Xcart\App\Main\Xcart;
use Xcart\ElasticSearch;

class SearchSuggestionHelper
{
    private $elastic;
    private $search;

    public function __construct($search) {
        /** @var \Modules\Sites\SitesModule $siteModule */
        /** @var \Modules\Core\CoreModule $coreModule */
        $siteModule = Xcart::app()->getModule('Sites');
        $coreModule = Xcart::app()->getModule('Core');
        $config = $coreModule::getGlobalConfig();
        $config_min_scope = $config["ElasticSearch_options"]["search_results_minimum_score_value"];

        $this->search = $search;
        $this->elastic = new ElasticSearch($config["ElasticSearch_options"], $siteModule->getSite()->domain);
//        $this->elastic->setSource("*._id");
        $this->elastic->setMinScore($config_min_scope);
//        $this->elastic->setType('product');
        $this->elastic->setQueryParams($search);
    }

    public function elastic_suggestion($count, array $html = [])
    {
        $query = /** @lang JSON */ <<<JSON
{
    "suggest" : {
        "text" : "{$this->search}",
        "simple_phrase" : {
            "phrase" : {
                "highlight": {
                  "pre_tag": "<span class='higlight'>",
                  "post_tag": "</span>"
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
        $result = $this->elastic->query(['size' => 5, 'from' => 0]);

        func_dump($result);
        die();


        $suggests = array();

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


        return $suggests;
    }

}