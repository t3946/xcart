<?php
use Xcart\StoreFront;

define("CIDEV_CRON_START", "CRON");

require __DIR__ . DIRECTORY_SEPARATOR . "../www/top.inc.php";
require __DIR__ . DIRECTORY_SEPARATOR . "../www/init.php";

ini_set('memory_limit', '512M');
set_time_limit(0);

// Db tables added by the module
$sql_tbl['xmlmap_extra']   = 'xcart_xmlmap_extra';
$sql_tbl['xmlmap_lastmod'] = 'xcart_xmlmap_lastmod';
// Config adjustment
$config['XML_Sitemap']['filename'] = 'sitemap.xml';

$cidev_storefronts = $storefronts;

if (!empty($cidev_storefronts) && is_array($cidev_storefronts)){

    foreach ($cidev_storefronts as $storefrontid => $sf_info){
	$cidev_storefronts[$storefrontid] = func_get_storefront_info($storefrontid);
    }

    $cidev_storefronts[0] = func_get_storefront_info(0);

    $cidev_storefronts = my_array_sort($cidev_storefronts, 'storefrontid');

    foreach ($cidev_storefronts as $storefrontid => $sf_info) {

        print($sf_info["domain"] . ": XML generation.");

        $config['XML_Sitemap']['filename'] = 'sitemap.xml';

        /** @var StoreFront $oStoreFront */
        $oStoreFront = new StoreFront(['storefrontid' => $storefrontid]);
        if ($oStoreFront) {
            $xml_protocol = ($oStoreFront->getConfigValue('https_enabled') == 'Y') ? 'https://' : 'http://';
            $xmlmap_location = $xml_protocol . func_get_http_location_sf($storefrontid) . $xcart_web_dir;

            $sf_condition = "storefrontid=$storefrontid";

            $config['XML_Sitemap']['items'] = func_XML_Sitemap_items_arr(null, $storefrontid);

            $config['XML_Sitemap']['items'][1]['items_query'] = <<<SQL
SELECT CONCAT('%s', {$sql_tbl['products']}.productid) as url, {$sql_tbl['products']}.productid as id, IFNULL({$sql_tbl['xmlmap_lastmod']}.date, '%s') as date
                 FROM {$sql_tbl['products']} LEFT JOIN {$sql_tbl['xmlmap_lastmod']} ON {$sql_tbl['xmlmap_lastmod']}.id = {$sql_tbl['products']}.productid AND {$sql_tbl['xmlmap_lastmod']}.type = 'P'
                 LEFT JOIN {$sql_tbl['products_sf']} ON {$sql_tbl['products_sf']}.productid = {$sql_tbl['products']}.productid
                 LEFT JOIN {$sql_tbl['products_categories']} ON {$sql_tbl['products_categories']}.productid = {$sql_tbl['products']}.productid
                 LEFT JOIN {$sql_tbl['categories']} ON {$sql_tbl['categories']}.categoryid = {$sql_tbl['products_categories']}.categoryid
                 WHERE {$sql_tbl['products']}.forsale='Y' AND {$sql_tbl['products']}.prevent_search_indexing_this_product_page !='Y' AND {$sql_tbl['products_sf']}.sfid = {$storefrontid}
                 AND {$sql_tbl['categories']}.prevent_index_products != 'Y'
                 AND ( {$sql_tbl['products']}.group_root IS NULL 
                      OR {$sql_tbl['products']}.group_root = {$sql_tbl['products']}.productid )
                 GROUP BY {$sql_tbl['products']}.productid
SQL;

            $config['XML_Sitemap']['items'][2]['items_query'] = <<<SQL
SELECT CONCAT('%s', {$sql_tbl['brands']}.brandid) as url, {$sql_tbl['brands']}.brandid as id, IFNULL({$sql_tbl['xmlmap_lastmod']}.date, '%s') as date
                 FROM {$sql_tbl['brands']} LEFT JOIN {$sql_tbl['xmlmap_lastmod']} ON {$sql_tbl['xmlmap_lastmod']}.id = {$sql_tbl['brands']}.brandid AND {$sql_tbl['xmlmap_lastmod']}.type = 'B'
                 LEFT JOIN {$sql_tbl['brands_sf']} ON {$sql_tbl['brands_sf']}.brandid = {$sql_tbl['brands']}.brandid
                 WHERE {$sql_tbl['brands']}.avail='Y' AND {$sql_tbl['brands']}.prevent_search_indexing_brand_page != 'Y' AND {$sql_tbl['brands']}.parent_brand_id IS NULL  AND {$sql_tbl['brands_sf']}.sfid = {$storefrontid}
SQL;
            xmlmap_generate("Y", $storefrontid);
        }
    }
}

die("DONE!");
