<?php
namespace Xcart\Helpers;

use Xcart\ElasticSearch;

class ViewedRelatedProducts
{
    private $categories = null;
    private $search_string = null;
    private $ssid = null;

    /***
     * @var ElasticSearch
     */
    private $elastic;
    private $_search_with_categories = false;

    public function __construct( $categories = null, $search_string = null )
    {
        global $config, $site_domain;

        global $XCART_SESSION_NAME;
        global $$XCART_SESSION_NAME;

        $this->ssid = $$XCART_SESSION_NAME;

        $this->elastic = new ElasticSearch($config["ElasticSearch_options"], \Xcart\App\Main\Xcart::app()->getModule('Sites')->getSite()->domain);

        if (!empty($categories)) {
            $this->categories = $categories;
        }
        if (!empty($search_string)) {
            $this->search_string = $search_string;
        }
    }

    public function setCategories($categories)
    {
        if (!empty($categories)) {
            $this->categories = $categories;
        }
    }
    public function setSearchString($search_string)
    {
        if (!empty($search_string)) {
            $this->search_string = $search_string;
        }
    }

    public function getRelated()
    {
        $last_viewed = $this->getLastViewedResources($this->categories);

        if ($elastic_query = $this->getElasticQuery($last_viewed))
        {
            return self::getFromElastic($elastic_query);
        }

        return [];
    }


    public function getLastViewedResources($categories = null)
    {
        $to_sql = '';

        if ($this->_search_with_categories) {
            if (!empty($categories) && is_array($categories)) {
                $cats = implode(',', $categories);
                $to_sql = "and c.categoryid in ({$cats})";
            }
        }

        $sql =  /** @lang MySQL */ <<<SQL
select SP.*
from (
    select SP.*
    from xcart_cidev_surf_path SP
    where SP.meta_id = (SELECT id FROM xcart_cidev_surf_meta WHERE sessid='{$this->ssid}' limit 1)
      and SP.resource_type in ('S') 
      and SP.meta_id > 0
      and SP.position = (select max(sp1.position) from xcart_cidev_surf_path sp1 where sp1.meta_id = SP.meta_id and sp1.resource_type = 'S')
    group by SP.resource_id, SP.additional_data
    
    union
    
    select SP.*
    from xcart_cidev_surf_path SP
    join xcart_products P ON P.productid = SP.resource_id and P.forsale = 'Y'
    join xcart_products_categories c ON c.productid = P.productid {$to_sql}
    
    where SP.meta_id = (SELECT id FROM xcart_cidev_surf_meta WHERE sessid='{$this->ssid}' limit 1)
      and SP.resource_type in ('P') 
      and SP.meta_id > 0
    group by SP.resource_id
) as SP
order by FIELD(SP.resource_type, 'S', 'P') asc, SP.position desc
SQL;

        $resources = func_query($sql);

        if (empty($resources)) {
            return [];
        }

        return $resources;
    }

    public function getElasticQuery($last_viewed)
    {
        global $variant_id_for_point10, $variant_id_for_point11;

        $resources = array_slice($last_viewed, 0, 11);
        $summary_pids = [];
        $jsons = [];
        $mlt_s_phrase = '';

        $boost = 1 + count($resources) / 10 - 0.1;
        foreach ($resources as $n => $resource)
        {
            if ($resource['resource_type'] == 'P' &&  $variant_id_for_point10 == 1)
            {
                $summary_pids[] = $resource['resource_id'];
                $o_boost = $boost;

                $jsons[] = /** @lang JSON */ <<<JSQN
{
    "constantScore" : {
        "filter" : { 
            "and": [ 
                {
                    "terms": { 
                        "_id": [ {$resource['resource_id']} ]
                    }
                }
            ] 
        },
        "boost" : {$o_boost}
    }
},
{
    "more_like_this": {
        "analyzer": "snowball",
        "boost": {$boost},
        "ids" : [{$resource['resource_id']}],
        "fields": [ "productname", "description" ],
        "min_doc_freq": 7,
        "min_term_freq" : 1,
        "min_word_length": 2
    }
}
JSQN;
            }
            elseif($resource['resource_type'] == 'S' &&  $variant_id_for_point11 == 1) {

                $s_phrase = preg_replace("/[^0-9a-zA-Z=.'-]/", " ", $resource['additional_data']);
                $s_phrase = trim($s_phrase);

                $this->elastic->reinit();
                $this->elastic->setDisMaxBoost($boost * 10 * 0.5);
                $this->elastic->setQueryParams($s_phrase);

                $jsons[] = json_encode($this->elastic->getQuery());

                if (empty($mlt_s_phrase)) { $mlt_s_phrase = $s_phrase; }
            }

            $boost = $boost - 0.1;
        }

        if (empty($jsons)) {
            return null;
        }

        $jsons = implode(',', $jsons);
        $json = /** @lang JSON */ <<<JSON
{
    "dis_max": {
        "queries": [ {$jsons} ]
    }
}
JSON;

        return json_decode($json, true);
    }

    public static function getFromElastic($query = array(), $minScope = 0.3, $size = 500, $from = 0, $pull_categories = true)
    {
        global $config, $site_domain;

        $classElastic = new ElasticSearch($config["ElasticSearch_options"], $site_domain);
        $classElastic->setMinScore($minScope);
        $classElastic->setType('product');
        $classElastic->setSearchQuery($query);
        $result =  $classElastic->query(array("size"=>$size, "from"=>$from));

        if ($result['hits']['total'])
        {
            $products = self::clearProductsFromElastic($result['hits']['hits']);

            if ($pull_categories) {
                return self::pullCategoriesToElasticProducts($products);
            }

            return $products;
        }

        return [];
    }

    private static function clearProductsFromElastic($products)
    {
        $t_arr = [];
        foreach ($products as $product) {
            $t_arr[] = array(
                'productid' => $product['_id'],
                'score' => $product['_score'],
                'categoryid' => array(),
            );
        }

        return $t_arr;
    }

    private static function pullCategoriesToElasticProducts($products)
    {
        $p_ids = [];

        foreach ($products as $product) {
            $p_ids[] = $product['productid'];
        }

        $p_ids = implode(',', $p_ids);

        $sql = /** @lang MySQL */ <<<SQL
SELECT productid, group_concat(categoryid) as categories 
FROM xcart_products_categories
WHERE productid in ({$p_ids}) 
group by productid
ORDER BY FIELD(main, 'Y', 'N')
SQL;
        $categories = func_query($sql);

        if (!empty($categories))
        {
            foreach ($products as $k=>$product) {
                foreach ($categories as $cat_product)
                {
                    if ($product['productid'] == $cat_product['productid'])
                    {
                        $products[$k]['categoryid'] = explode(',', $cat_product['categories']);
                        break;
                    }
                }
            }
        }

        return $products;
    }
}