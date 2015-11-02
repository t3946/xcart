<?php
/**
 * Module configuration
 *
 * @copyright   Copyright (c) 2001-2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license     http://www.x-cart.com/software_license_agreement.html X-Cart license agreement
 * @author      Slam <slam@x-cart.com>
 * @category    X-Cart
 * @package     Modules
 * @subpackage  XML Sitemap
 * @version     $Id$
 * @since       4.4.0
 */

if (!defined('XCART_START')) { header('Location: ../../'); die('Access denied'); }

// Db tables added by the module
$sql_tbl['xmlmap_extra']   = 'xcart_xmlmap_extra';
$sql_tbl['xmlmap_lastmod'] = 'xcart_xmlmap_lastmod';

// Config adjustment
$config['XML_Sitemap']['filename'] = 'sitemap.xml';

if (!empty($active_modules['Multiple_Storefronts']) && $current_storefront) {
    $xmlmap_location = (($HTTPS) ? 'https://' : 'http://') . func_get_http_location_sf($current_storefront) . $xcart_web_dir;
} else {
    $xmlmap_location = $http_location;
}
$smarty->assign('xmlmap_location', $xmlmap_location);

/**
 * Items is a numeric array where value is an assoc array of the following options:
 * - type          - can be P(product)|C(category)|M(manufacturer)|S(static page)|H(home page)|E(extra URL)
 * - lastmod       - this value will be used _if_ item contain no entry in the xcart_xmlmap_lastmod table. Value should utilize ISO 8601 format: YYYY-MM-DDThh:mmTZD. If empty, the sitemap generation time will be used.
 * - changefreq    - can be always|hourly|daily|weekly|monthly|yearly|never.
 * - priority      - from 1.0 (extremely important) to 0.1 (not important at all).
 * - url_pattern   -
 * - items_query   - query to the database which will return list of items for the defined type
 * - multilanguage - can be true|false
 * @link http://www.google.com/support/webmasters/bin/answer.py?answer=71936
 */

if (!empty($active_modules['Multiple_Storefronts'])) {
	$sf_condition = "storefrontid=$current_storefront";
} else {
	$sf_condition = '';
}

$config['XML_Sitemap']['items']    = array(
	0 => array(
		'type'          => 'C',
		'lastmod'       => '',
		'changefreq'    => 'weekly',
		'priority'      => '0.6',
		'url_pattern'   => 'home.php?cat=',
		'items_query'   => "SELECT CONCAT('%s', $sql_tbl[categories].categoryid) as url, $sql_tbl[categories].categoryid as id,  IFNULL($sql_tbl[xmlmap_lastmod].date, '%s') as date FROM $sql_tbl[categories] LEFT JOIN $sql_tbl[xmlmap_lastmod] ON $sql_tbl[xmlmap_lastmod].id = $sql_tbl[categories].categoryid AND $sql_tbl[xmlmap_lastmod].type = 'C' WHERE $sql_tbl[categories].avail='Y'" . ((empty($sf_condition)) ? '' : " AND $sql_tbl[categories].$sf_condition"),
		'multilanguage' => false,
	),
	1 => array(
		'type'          => 'P',
		'lastmod'       => '',
		'changefreq'    => 'daily',
		'priority'      => '0.9',
		'url_pattern'   => 'product.php?productid=',
		'multilanguage' => false,
	),
/*
	2 => array(
		'type'          => 'M',
		'lastmod'       => '',
		'changefreq'    => 'weekly',
		'priority'      => '0.8',
		'url_pattern'   => 'manufacturers.php?manufacturerid=',
		'items_query'   => "SELECT CONCAT('%s', $sql_tbl[manufacturers].manufacturerid) as url, $sql_tbl[manufacturers].manufacturerid as id, IFNULL($sql_tbl[xmlmap_lastmod].date, '%s') as date FROM $sql_tbl[manufacturers] LEFT JOIN $sql_tbl[xmlmap_lastmod] ON $sql_tbl[xmlmap_lastmod].id = $sql_tbl[manufacturers].manufacturerid AND $sql_tbl[xmlmap_lastmod].type = 'M' WHERE $sql_tbl[manufacturers].avail='Y'" . ((empty($sf_condition)) ? '' : " AND $sql_tbl[manufacturers].$sf_condition"),
		'multilanguage' => false,
	),
*/
	2 => array(
		'type'          => 'B',
		'lastmod'       => '',
		'changefreq'    => 'monthly',
		'priority'      => '0.7',
		'url_pattern'   => 'brands.php?brandid=',
		'multilanguage' => false,
	),
	3 => array(
		'type'          => 'S',
		'lastmod'       => '',
		'changefreq'    => 'weekly',
		'priority'      => '0.2',
		'url_pattern'   => 'pages.php?pageid=',
		'items_query'   => "SELECT CONCAT('%s', $sql_tbl[pages].pageid) as url, $sql_tbl[pages].pageid as id, IFNULL($sql_tbl[xmlmap_lastmod].date, '%s') as date FROM $sql_tbl[pages] LEFT JOIN $sql_tbl[xmlmap_lastmod] ON $sql_tbl[xmlmap_lastmod].id = $sql_tbl[pages].pageid AND $sql_tbl[xmlmap_lastmod].type = 'S' WHERE $sql_tbl[pages].active='Y' AND $sql_tbl[pages].level='E'",
		'multilanguage' => false,
	),
	4 => array(
		'type'          => 'E',
		'lastmod'       => '',
		'changefreq'    => 'monthly',
		'priority'      => '0.4',
		'url_pattern'   => '',
		'items_query'   => "%s SELECT url, '%s' as date FROM $sql_tbl[xmlmap_extra]" . ((empty($sf_condition)) ? '' : " WHERE $sf_condition"),
		'multilanguage' => false,
	),
	5 => array(
		'type'          => 'H',
		'lastmod'       => '',
		'changefreq'    => 'daily',
		'priority'      => '1.0',
		'url_pattern'   => '',
		'items_query'   => "%s SELECT IF((SELECT value FROM $sql_tbl[config] WHERE name = 'xseo_xmlmap_use_root') = 'Y','$GLOBALS[http_location]','home.php') as url, '%s' as date",
		'multilanguage' => false,
	),
);

if (!empty($active_modules['Multiple_Storefronts'])) {
	
    $config['XML_Sitemap']['items'][1]['items_query'] = "SELECT CONCAT('%s', $sql_tbl[products].productid) as url, $sql_tbl[products].productid as id, IFNULL($sql_tbl[xmlmap_lastmod].date, '%s') as date"
        . " FROM $sql_tbl[products] LEFT JOIN $sql_tbl[xmlmap_lastmod] ON $sql_tbl[xmlmap_lastmod].id = $sql_tbl[products].productid AND $sql_tbl[xmlmap_lastmod].type = 'P'"
        . " LEFT JOIN $sql_tbl[products_sf] ON $sql_tbl[products_sf].productid = $sql_tbl[products].productid"
        . " WHERE $sql_tbl[products].forsale='Y' AND $sql_tbl[products_sf].sfid = $current_storefront" 
        . (($config['General']['unlimited_products'] == 'N') ? " AND $sql_tbl[products].avail > 0" : ''); 
	
    $config['XML_Sitemap']['items'][2]['items_query'] = "SELECT CONCAT('%s', $sql_tbl[brands].brandid) as url, $sql_tbl[brands].brandid as id, IFNULL($sql_tbl[xmlmap_lastmod].date, '%s') as date"
        . " FROM $sql_tbl[brands] LEFT JOIN $sql_tbl[xmlmap_lastmod] ON $sql_tbl[xmlmap_lastmod].id = $sql_tbl[brands].brandid AND $sql_tbl[xmlmap_lastmod].type = 'B'"
        . " LEFT JOIN $sql_tbl[brands_sf] ON $sql_tbl[brands_sf].brandid = $sql_tbl[brands].brandid"
        . " WHERE $sql_tbl[brands].avail='Y' AND $sql_tbl[brands_sf].sfid = $current_storefront"; 
} else {
	
    $config['XML_Sitemap']['items'][1]['items_query'] = "SELECT CONCAT('%s', $sql_tbl[products].productid) as url, $sql_tbl[products].productid as id, IFNULL($sql_tbl[xmlmap_lastmod].date, '%s') as date"
        . " FROM $sql_tbl[products] LEFT JOIN $sql_tbl[xmlmap_lastmod] ON $sql_tbl[xmlmap_lastmod].id = $sql_tbl[products].productid AND $sql_tbl[xmlmap_lastmod].type = 'P'"
        . " LEFT JOIN $sql_tbl[products_sf] ON $sql_tbl[products_sf].productid = $sql_tbl[products].productid"
        . " WHERE $sql_tbl[products].forsale='Y'" . (($config['General']['unlimited_products'] == 'N') ? " AND $sql_tbl[products].avail > 0" : ''); 
    
    $config['XML_Sitemap']['items'][2]['items_query'] = "SELECT CONCAT('%s', $sql_tbl[brands].brandid) as url, $sql_tbl[brands].brandid as id, IFNULL($sql_tbl[xmlmap_lastmod].date, '%s') as date" 
        . " FROM $sql_tbl[brands] LEFT JOIN $sql_tbl[xmlmap_lastmod] ON $sql_tbl[xmlmap_lastmod].id = $sql_tbl[brands].brandid AND $sql_tbl[xmlmap_lastmod].type = 'B'"
        . " WHERE $sql_tbl[brands].avail='Y'"; 
}
?>
