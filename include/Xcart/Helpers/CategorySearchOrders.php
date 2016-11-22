<?php
namespace Xcart\Helpers;

use Xcart\ElasticSearch;

class CategorySearchOrders
{
    public static function getOrderByLikeLastViewed($search_string = null)
    {
        global $XCART_SESSION_NAME;
        global $$XCART_SESSION_NAME;

        $ssid = $$XCART_SESSION_NAME;
        $t_arr = array();

        $sql =  /** @lang MySQL */<<<SQL
select SP.resource_id as needed_resource_id
from xcart_cidev_surf_path SP
inner join xcart_products P ON P.productid = SP.resource_id and P.forsale = 'Y'

where SP.meta_id = (SELECT id FROM xcart_cidev_surf_meta WHERE sessid='{$ssid}' limit 1)
  and SP.resource_type = 'P'
  and SP.meta_id > 0
  
group by SP.resource_id
order by max(SP.`position`) desc
SQL;

        $pids = func_query($sql);
        $ids = array();
        foreach ($pids as $pid)
        {
            $ids[] = $pid['needed_resource_id'];
        }
        $ids = array_slice($ids, 0, 5);


        $ids = implode(', ', $ids);

        $json = /** @lang JSON */ <<<JSON
{
    "more_like_this": {
        "fields": [ 
            "productname", "description", "brand"
        ],
        "ids": [{$ids}],
        "min_term_freq": 2,
        "percent_terms_to_match": 0.7,
        "min_doc_freq": 5
    }
}
JSON;
        $query = json_decode($json);

        if ($search_string) {
            $query->more_like_this->like_text = $search_string;
        }

        print_r(json_encode($query));

        $t_arr = self::getFromElastic($query);

        return $t_arr;
    }

    public static function getFromElastic($query = array(), $minScope = 0.8, $size = 50, $from = 0)
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
            return self::pullCategoriesToElasticProducts(self::clearProductsFromElastic($result['hits']['hits']));
        }

        return array();
    }

    private static function clearProductsFromElastic($products)
    {
        $t_arr = array();
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
        global $sql_tbl;

        $p_ids = array();

        foreach ($products as $product) {
            $p_ids[] = $product['productid'];
        }

        $p_ids = implode(',', $p_ids);

        $sql = <<<SQL
SELECT productid, group_concat(categoryid) as categories 
FROM {$sql_tbl['products_categories']}
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