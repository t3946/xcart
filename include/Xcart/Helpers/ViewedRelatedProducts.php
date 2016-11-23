<?php
namespace Xcart\Helpers;

use Xcart\ElasticSearch;

class ViewedRelatedProducts
{
    private $categories = null;
    private $search_string = null;
    private $ssid = null;

    public function __construct( $categories = null, $search_string = null )
    {
        global $XCART_SESSION_NAME;
        global $$XCART_SESSION_NAME;

        $this->ssid = $$XCART_SESSION_NAME;

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
        $last_viewed_ids = $this->getLastViewedProducts($this->categories);
        $elastic_query = $this->getElasticQuery($last_viewed_ids, $this->search_string);

        return self::getFromElastic($elastic_query);
    }


    public function getLastViewedProducts($categories = null)
    {
        $ids = [];
        $to_sql = '';

        if (!empty($categories) && is_array($categories)) {
            $cats = implode(',', $categories);
            $to_sql = "and c.categoryid in ({$cats})";
        }

        $sql =  /** @lang MySQL */ <<<SQL
select SP.resource_id as needed_resource_id
from xcart_cidev_surf_path SP
inner join xcart_products P ON P.productid = SP.resource_id and P.forsale = 'Y'
join xcart_products_categories c ON c.productid = P.productid {$to_sql}

where SP.meta_id = (SELECT id FROM xcart_cidev_surf_meta WHERE sessid='{$this->ssid}' limit 1)
  and SP.resource_type = 'P'
  and SP.meta_id > 0
  
group by SP.resource_id
order by max(SP.`position`) desc
SQL;

        $pids = func_query_column($sql);

        if (empty($pids)) {
            return [];
        }

        return $pids;
    }

    public function getElasticQuery($product_ids, $search_string = null)
    {
        $ids = array_slice($product_ids, 0, 5);
        $ids = implode(', ', $ids);

        $json = /** @lang JSON */ <<<JSON
{
    "more_like_this": {
        "fields": [ 
            "productname", "description"
        ],
        "ids": [{$ids}],
        "percent_terms_to_match": 0.7,
        "min_doc_freq": 2,
        "min_term_freq": 5
    }
}
JSON;
        $query = json_decode($json);

        if ($search_string) {
            $query->more_like_this->like_text = $search_string;
        }

        return $query;
    }

    public static function getFromElastic($query = array(), $minScope = 0.8, $size = 50, $from = 0, $pull_categories = true)
    {
        global $config;
        global $site_domain;

        $classElastic = new ElasticSearch($config["ElasticSearch_options"], $site_domain);
        $classElastic->setSource("*._id");
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

        $sql = <<<SQL
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