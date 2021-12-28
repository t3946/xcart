<?php
namespace Xcart\Helpers;

use Modules\Core\Components\GlobalConfig;
use Xcart\App\Main\Xcart;
use Xcart\ElasticSearch;

/**
 * @deprecated deprecated class
 */
class ViewedRelatedProducts
{
    private $categories = null;
    private $search_string = null;
    private $ssid = null;

    /***
     * @var ElasticSearch
     */
    private ElasticSearch $elastic;
    private bool $_search_with_categories = false;

    public function __construct( $categories = null, $search_string = null )
    {
        $site = Xcart::app()->getModule('Sites')->getSite();

        $config = $site->getGlobalConfig();

        $site_domain = $site->domain;

        $this->ssid = Xcart::app()->request->session->getId();

        $this->elastic = new ElasticSearch($config['es_url'],  $site_domain);

        if (!empty($categories)) {
            $this->categories = $categories;
        }
        if (!empty($search_string)) {
            $this->search_string = $search_string;
        }
    }

    public function setCategories($categories): void
    {
        if (!empty($categories)) {
            $this->categories = $categories;
        }
    }
    public function setSearchString($search_string): void
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
            if ($p_ids = $this->getFromElastic($elastic_query))
            {
                usort($p_ids, function($a, $b)
                {
                    return ($a['score'] < $b['score']) ? -1 : 1;
                });

                return $p_ids;
            }

        }

        return [];
    }


    public function getLastViewedResources($categories = null): array
    {
        if (!$this->ssid) {
            return [];
        }

        $to_sql = '';

        if ($this->_search_with_categories) {
            if (!empty($categories) && is_array($categories)) {
                $cats = implode(',', $categories);
                $to_sql = "join xcart_products_categories c ON c.productid = P.productid and c.categoryid in ({$cats}) ";
            }
        }

        $sql =  /** @lang MySQL */
            <<<SQL
SELECT SP.*
FROM xcart_cidev_surf_path SP
INNER JOIN xcart_cidev_surf_meta M ON M.id = SP.meta_id
INNER JOIN xcart_products P ON P.productid = SP.resource_id AND P.forsale = 'Y'
{$to_sql}
WHERE sessid='{$this->ssid}' AND SP.resource_type in ('P') 
GROUP BY resource_id
UNION 
(
SELECT SP.*
FROM xcart_cidev_surf_path SP
INNER JOIN xcart_cidev_surf_meta M ON M.id = SP.meta_id
WHERE sessid='{$this->ssid}' AND SP.resource_type in ('S')
ORDER BY POSITION DESC
LIMIT 1)
ORDER BY FIELD(resource_type, 'S', 'P'), POSITION DESC;
SQL;

        $resources = Xcart::app()->db->getConnection()->fetchAllAssociative($sql);

        if (empty($resources)) {
            return [];
        }

        return $resources;
    }

    public function getElasticQuery($last_viewed)
    {
        $resources = \array_slice($last_viewed, 0, 11);
        $summary_pids = [];
        $jsons = [];
        $mlt_s_phrase = '';

        $boost = 1 + count($resources) / 10 - 0.1;
        foreach ($resources as $n => $resource)
        {
            if ($resource['resource_type'] === 'P')
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
            elseif($resource['resource_type'] === 'S') {

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

    public function getFromElastic(array $query = [], $minScope = 0.3, $size = 500, $from = 0, $pull_categories = true)
    {
        if (true || !$result = Xcart::app()->cache->get($query)) {
            $classElastic = clone $this->elastic;
            $classElastic->setMinScore($minScope);
            $classElastic->setType('product');
            $classElastic->setSearchQuery($query);
            $result =  $classElastic->query(['size' => $size, 'from' => $from]);

            //Xcart::app()->cache->set($query, $result, 60);
        }

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
            $t_arr[] = [
                'productid' => $product['_id'],
                'score' => $product['_score'],
                'categoryid' => [],
            ];
        }

        return $t_arr;
    }

    private static function pullCategoriesToElasticProducts(iterable $products)
    {
        $p_ids = [];

        foreach ($products as $product) {
            $p_ids[] = $product['productid'];
        }

        $p_ids = implode(',', $p_ids);

        $sql = /** @lang MySQL */ <<<SQL
SELECT productid, categoryid
FROM xcart_products_categories
WHERE productid in ({$p_ids}) 
ORDER BY FIELD(main, 'Y', 'N')
SQL;
        $categories = Xcart::app()->db->getConnection()->fetchAllAssociative($sql);

        if (!empty($categories))
        {
            foreach ($products as $k=>$product) {
                foreach ($categories as $cat_product)
                {
                    if ($product['productid'] == $cat_product['productid'])
                    {
                        $products[$k]['categoryid'][] = $cat_product['categoryid'];
                    }
                }
            }
        }

        return $products;
    }
}