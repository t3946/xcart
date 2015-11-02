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

if (!empty($active_modules['Multiple_Storefronts'])) {
	$sf_condition = "storefrontid=$current_storefront";
} else {
	$sf_condition = '';
}

$config['XML_Sitemap']['items'] = func_XML_Sitemap_items_arr($sf_condition);

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
