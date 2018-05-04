<?php
namespace Modules\Goods\Helpers;

use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QAndNot;
use Modules\Goods\Models\ProductModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;
use Xcart\Brands;
use Xcart\ElasticSearch;
use Xcart\Product;

class SliderDataHelper
{
    /**
     * @param $mode
     * @param null $productid
     * @param int $fba_limit
     * @param int $max_products
     * @return ProductModel[]
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Xcart\App\Exceptions\UnknownMethodException
     * @throws \Xcart\App\Exceptions\UnknownPropertyException
     */
    public static function getSliderData ($mode, $productid = null, $fba_limit = 1, $max_products = 30): array
    {
        global $sql_tbl;

        /** @var SiteModel $site */
        $site = Xcart::app()->getModule('Sites')->getSite();

        $section_name = $mode;
        $saveOrder = false;
        $extendFilter = [];


        $productids = $productid ? [$productid] : [];

        if ($section_name !== 'recently_viewed_products') {
            foreach (Xcart::app()->cart->getItems() as $cartItem) {
                $productids[] = $cartItem->getObject()->pk;
            }
        }


        if (\in_array($section_name, ['products_also_bought_with_this_product', 'related_products', 'recently_viewed_products'])){

            $productids = implode("','", $productids);
            $p_query = '';

            if ($section_name === 'products_also_bought_with_this_product'){
                $p_query = <<<SQL
select RO.related_resource_id as needed_resource_id
from xcart_cidev_related_objects RO
inner join xcart_products P ON P.productid = RO.related_resource_id and P.forsale = 'Y' AND P.productid != P.group_root
inner join xcart_products_sf SF ON P.productid = SF.productid
where RO.resource_id = '{$productid}' 
  and RO.resource_type = 'OP' 
  and RO.related_resource_type = 'P'  
  and RO.related_resource_id NOT IN ('{$productids}')
  and SF.sfid = '{$site->pk}'
  
order By RO.related_resource_orderby 
limit 30
SQL;
            }
            elseif ($section_name === 'recently_viewed_products' && !\defined('IS_ROBOT')){

                $saveOrder = true;

                $meta_id = \Modules\User\Models\SurfMetaModel::getInstance()->id;

                $p_query = <<<SQL
select SP.resource_id as needed_resource_id
from xcart_cidev_surf_path SP
inner join xcart_products P ON P.productid = SP.resource_id and P.forsale = 'Y'
inner join xcart_products_sf SF USING (productid)
where SP.meta_id = '{$meta_id}' 
  and SP.resource_type = 'P' 
  and SP.resource_id NOT IN ('{$productids}')
  and SP.meta_id > 0
  and SF.sfid = '{$site->pk}'
  
group By SP.resource_id
order By max(SP.`position`) desc
LIMIT 50
SQL;
            }
            elseif ($section_name === 'related_products'){

                $avail_condition = '';

                $p_query = "SELECT $sql_tbl[products].productid as needed_resource_id FROM $sql_tbl[product_links], $sql_tbl[products] WHERE $sql_tbl[products].productid=$sql_tbl[product_links].productid2 AND $sql_tbl[product_links].productid1='$productid' AND $sql_tbl[products].forsale = 'Y' AND $sql_tbl[products].productid NOT IN ('$productids') $avail_condition GROUP BY $sql_tbl[products].productid ORDER BY $sql_tbl[product_links].orderby, product";
            }

            $pids = [];

            if (!empty($p_query)) {
                $pids = func_query($p_query);

                d($pids, $p_query);
            }


        }
        elseif ( \in_array($section_name, ['similar_products', 'similar_products_ob']) ) {

            $saveOrder = true;

            $classElastic = new ElasticSearch($site->getGlobalConfig()['ElasticSearch_options'], $site->domain);
            $classElastic->setSource('*._id');
            $classElastic->setType('product');
            $classElastic->setMinScore(0.5);
            $classElastic->setSize(100);
            $classElastic->setProductId($productid);

            if ($section_name === 'similar_products')
            {
                $sGoogleAnaliticsParam = 'similar_products_all_carousel';
                $classElastic->setSearchQuery($classElastic->getQuerySimilarProductsBrands());
            }
            elseif ($section_name === 'similar_products_ob')
            {
                $sGoogleAnaliticsParam = 'similar_products_other_brands_carousel';
                $classBrands = new Brands();
                $aBrand = $classBrands->getBrandByProductId($productid);
                $extendFilter[] = new QAndNot(['brandid' => $aBrand['brandid']]);

                $classElastic->setSearchQuery($classElastic->getQuerySimilarProductsBrands($aBrand['brand']));
                $classElastic->setSize(200);
            }

            $res = $classElastic->query();

            if (!empty($res['hits']['hits'])) {
                $hits = $res['hits']['hits'];
                usort($hits, function($a, $b){
                    if ($a['_score'] == $b['_score']) {
                        return 0;
                    }
                    return $a['_score'] < $b['_score'] ?  1 : -1;
                });
                $pids = array_map(function($item){ return ['needed_resource_id' => $item["_id"]]; }, $hits);
            }
        }

        $isInStock = \in_array($section_name, ['similar_products', 'similar_products_ob']);

        $p_ids = [];
        $products = [];
        if (!empty($pids))
        {
            $fba_pids = [];
            $i_ids = array_map(function($item){ return $item['needed_resource_id']; }, $pids);

            if (!\in_array($section_name, ['related_products', 'recently_viewed_products']))
            {
                if ($fba_pids = Product::getRandFbaProducts($fba_limit, array_merge($i_ids, [$productid]), $site->pk))
                {
                    $fba_pids = array_map(function($item){ return $item['needed_resource_id']; }, $fba_pids);

                    $i_ids = array_merge($fba_pids, $i_ids);
                }
            }

            if (($key = array_search($productid, $i_ids, true)) !== false) {
                unset($i_ids[$key]);
            }

            $qs = ProductModel::objects()->filter(array_merge(['productid__in' => $i_ids], $extendFilter));
            $ta = $qs->getTableAlias();

            $qs->getQueryBuilder()
                ->join('inner join', 'xcart_products_sf', ['ps.productid' => $ta.'.productid' , 'ps.sfid' => new Expression($site->pk)], 'ps');


            if ($saveOrder) {
                $qs = $qs->order([new Expression("FIELD({$qs->getTableAlias()}.productid, " . implode(',', $i_ids) . ") ASC")]);
            }

            $oProducts = $qs->all();

            if (\count($oProducts) <= $fba_limit && \in_array($oProducts[0]->productid, $fba_pids, true)) {
                return [$products, $sGoogleAnaliticsParam];
            }

            if (\in_array($section_name, ['similar_products', 'similar_products_ob', 'related_products'])) {
                $oProducts = ProductHelper::groupRootProducts($oProducts);
                if (isset($oProducts[$productid])) {
                    unset($oProducts[$productid]);
                }
            }

            foreach ($oProducts as $oProduct)
            {
                if ($isInStock && $oProduct->isProductOutOfStock() && !$oProduct->isGroupRoot()) {
                    continue;
                }

                if ($oProduct->isGroupRoot() && $oProduct->getFrontendChilds()->count() === 0) {
                    continue;
                }

                $p_ids[] = $oProduct->productid;
                $oProduct->product = str_replace("'", '&#39;', $oProduct->product);
                $products[] = $oProduct;

                if (\count($p_ids) >= $max_products) {
                    break;
                }
            }
        }


        Product::updateShowInLists($p_ids);

        return $products;
    }
}