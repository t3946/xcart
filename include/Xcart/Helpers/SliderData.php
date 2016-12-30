<?php
namespace Xcart\Helpers;

use Mindy\QueryBuilder\Q\QAndNot;
use Mindy\QueryBuilder\QueryBuilder;
use Xcart\Brands;
use Xcart\Connection;
use Xcart\ElasticSearch;
use Xcart\Product;

class SliderData
{
    /***
     * @param $params array
     * @param $smarty \Smarty
     */
    public static function getSliderDataSmarty ($params , $smarty)
    {
        if (!isset($params['productid'])) {
            $params['productid'] = null;
        }

        list($products, $gaparam) = self::getSliderData($params['mode'], $params['productid']);

        $smarty->assign($params['assign'], $products);
    }

    public static function getRandFbaProducts($limit = 3, array $no_ids = null)
    {
        $where = ['amazon_fba' => 'Y', 'amazon_fba_avail__gt' => 1];

        if (!empty($no_ids)) {
            $where[] = new QAndNot(['productid__in' => $no_ids]);
        }

        $sql = QueryBuilder::getInstance(Connection::getInstance())
                           ->setTypeSelect()
                           ->select(['needed_resource_id' => 'productid'])
                           ->from('xcart_products')
                           ->order(['in_list_showed', '?'])
                           ->where($where)
                           ->limit($limit)
                           ->toSQL();

        return Connection::getInstance()->fetchAll($sql);
    }

    public static function getSliderData ($mode, $productid = null)
    {
        global $config, $sql_tbl, $site_domain;
        global $XCART_SESSION_NAME, $$XCART_SESSION_NAME;
        global $variant_id_for_point9, $is_robot;

        x_load("product");
        x_session_register("cart");

        $section_name = $mode;


        $productids = array();
        if (!empty($productid)){
            $productids[] = $productid;
        }


        if (!empty($cart["products"]) && is_array($cart["products"]) && ($section_name != "recently_viewed_products")){
            foreach ($cart["products"] as $k => $v){
                $productids[] = $v["productid"];
            }
        }

        $sGoogleAnaliticsParam = "";

        if (
            $section_name == "products_also_bought_with_this_product"  ||
            $section_name == "related_products"  ||
            $section_name == "recently_viewed_products"
        ){

            $productids = implode("','", $productids);

            if ($section_name == "products_also_bought_with_this_product"){
                $p_query = "select RO.related_resource_id as needed_resource_id
                          from xcart_cidev_related_objects RO
                          inner join xcart_products P ON P.productid = RO.related_resource_id and P.forsale = 'Y'
                        where RO.resource_id = '$productid' and RO.resource_type = 'OP' and RO.related_resource_type = 'P'  and RO.related_resource_id NOT IN ('$productids')
                        Order By RO.related_resource_orderby limit 20";
            }
            elseif ($section_name == "recently_viewed_products"){

                $meta_id = func_query_first_cell("SELECT id FROM xcart_cidev_surf_meta WHERE sessid='".$$XCART_SESSION_NAME."'");

                $p_query = "select SP.resource_id as needed_resource_id
                          from xcart_cidev_surf_path SP
                          inner join xcart_products P ON P.productid = SP.resource_id and P.forsale = 'Y'
                        where SP.meta_id = '$meta_id' and SP.resource_type = 'P' and SP.resource_id NOT IN ('$productids')
                        and SP.meta_id > 0
                        Group By SP.resource_id
                        Order By max(SP.`position`) desc";
            }
            elseif ($section_name == "related_products"){

                $avail_condition = "";
                if ($config["General"]["unlimited_products"] == "N" && $config["General"]["disable_outofstock_products"] == "Y") {
                    $avail_condition = "AND $sql_tbl[products].avail > 0";
                }

                $p_query = "SELECT $sql_tbl[products].productid as needed_resource_id FROM $sql_tbl[product_links], $sql_tbl[products] WHERE $sql_tbl[products].productid=$sql_tbl[product_links].productid2 AND $sql_tbl[product_links].productid1='$productid' AND $sql_tbl[products].forsale = 'Y' AND $sql_tbl[products].productid NOT IN ('$productids') $avail_condition GROUP BY $sql_tbl[products].productid ORDER BY $sql_tbl[product_links].orderby, product";
            }

            $pids = func_query($p_query);

            switch ($section_name) {
                case 'products_also_bought_with_this_product': $sGoogleAnaliticsParam = 'customer_also_bought_carousel';
                    break;
                case 'related_products': $sGoogleAnaliticsParam = 'related_products_carousel';
                    break;
                case 'recently_viewed_products': $sGoogleAnaliticsParam = 'recently_viewed_carousel';
                    break;
            }

        }
        elseif ($section_name == "similar_products"){

            $classElastic = new ElasticSearch($config["ElasticSearch_options"],$site_domain);
            $classElastic->setSource("*._id");
            $classElastic->setType("product");
            $classElastic->setSize(30);
            $classElastic->setProductId($productid);
            x_session_register("variant_id_for_point9");
            $variant_id = $variant_id_for_point9;
            if ($is_robot == 'Y' || defined("IS_ROBOT")) {
                $variant_id = Get_AB_Variant(9);
            }
            switch ($variant_id) {
                case 0:
                    $similar_productids = func_query_first_cell("SELECT similar_productids FROM $sql_tbl[products] WHERE productid='$productid'");

                    if (!empty($similar_productids)){

                        $similar_productids_arr = explode(",", $similar_productids);

                        if (!empty($similar_productids_arr) && is_array($similar_productids_arr)){
                            foreach ($similar_productids_arr as $k => $v){

                                $needed_resource_id = trim($v);
                                if (!in_array($needed_resource_id, $productids)){
                                    $pids[$k]["needed_resource_id"] = $needed_resource_id;
                                }
                            }
                        }
                    }
                    $sGoogleAnaliticsParam = 'similar_products_carousel';
                    break;
                case 1:
                    $classElastic->setSearchQuery($classElastic->getQuerySimilarProductsBrands());
                    $res = $classElastic->query();
                    if (!empty($res["hits"]["hits"])) {
                        foreach ($res["hits"]["hits"] as $key => $sValue){
                            if ($sValue["_id"] != $productid) {
                                $pids[]["needed_resource_id"] = $sValue["_id"];
                            }
                        }
                    }
                    $sGoogleAnaliticsParam = 'similar_products_all_carousel';
                    break;
                case 2:
                    $classBrands = new Brands();
                    $aBrand = $classBrands->getBrandByProductId($productid);
                    $classElastic->setSearchQuery($classElastic->getQuerySimilarProductsBrands($aBrand['brand']));
                    $res = $classElastic->query();
                    if (!empty($res["hits"]["hits"])) {
                        foreach ($res["hits"]["hits"] as $key => $sValue){
                            if ($sValue["_id"] != $productid) {
                                $pids[]["needed_resource_id"] = $sValue["_id"];
                            }
                        }
                    }
                    unset($aBrand);
                    $sGoogleAnaliticsParam = 'similar_products_other_brands_carousel';
                    break;

            }
        }


        $p_ids = [];
        $products = [];
        if (!empty($pids))
        {
            if (!in_array($section_name, ['related_products', 'recently_viewed_products']))
            {
                $p_ids[] = $productid;

                foreach ($pids as $pid) {
                    $p_ids[] = $pid['needed_resource_id'];
                }

                if ($fba_pids = self::getRandFbaProducts(rand(2,4), $p_ids))
                {
                    $pids = array_merge($fba_pids, $pids);
                }
            }

            foreach ($pids as $k => $v)
            {
                if (!empty($productid) && $v["needed_resource_id"] == $productid) {
                    continue;
                }
                $product_info = func_select_product($v["needed_resource_id"], 0, false);

                if (!empty($product_info))
                {
                    $p_ids[] = $v["needed_resource_id"];

                    $product_info["product"] = str_replace("'", "&#39;", $product_info["product"]);
                    $products[] = $product_info;
                }
            }
        }

//        Product::updateShowInLists($p_ids);

        return [$products, $sGoogleAnaliticsParam];
    }

}