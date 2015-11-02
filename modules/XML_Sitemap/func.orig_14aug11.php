<?php
/**
 * Module functions
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

/**
 * Add current date to db for provided item
 *
 * @param  string $type item type (C|P|M|S)
 * @param  int    $id   item id
 * @return void
 */
function xmlmap_update_lastmod($type, $id)
{
	global $sql_tbl;
	$id = intval($id);
	$result = db_query("REPLACE INTO $sql_tbl[xmlmap_lastmod] (id, type, date) VALUES ($id, '$type', CONCAT(CURDATE(), 'T', CURTIME(), '+00:00'))");
	db_free_result($result);
}

/**
 * Remove lastmod entry from db for deleting items
 *
 * @return void
 */
function xmlmap_delete_lastmod()
{
	global $sql_tbl;
	
	// Category is deleting
	if (isset($_POST['confirmed']) && $_POST['confirmed'] == 'Y' && isset($_POST['cat'])) {
		$cat = intval($_POST['cat']);
		$categoryid_path = func_query_first_cell("SELECT categoryid_path FROM $sql_tbl[categories] WHERE categoryid='$cat'");
		$ids = func_query_column("SELECT categoryid FROM $sql_tbl[categories] WHERE categoryid='$cat' OR categoryid_path LIKE '$categoryid_path/%'");
		// If deleting category has products, delete entries for them too
		$products_ids = func_query_column("SELECT $sql_tbl[products_categories].productid FROM $sql_tbl[categories], $sql_tbl[products_categories] WHERE ($sql_tbl[categories].categoryid='$cat' OR $sql_tbl[categories].categoryid_path LIKE '$categoryid_path/%') AND $sql_tbl[products_categories].categoryid=$sql_tbl[categories].categoryid AND $sql_tbl[products_categories].main='Y'");
		if (is_array($products_ids)) {
			$result = db_query("DELETE FROM $sql_tbl[xmlmap_lastmod] WHERE type = 'P' AND id IN ('" . implode("','", $products_ids) . "')");
			db_free_result($result);
		}
		$result = db_query("DELETE FROM $sql_tbl[xmlmap_lastmod] WHERE type = 'C' AND id IN ('" . implode("','", $ids) . "')");
		db_free_result($result);
		
	// Products are deleting
	} else if ($_POST['confirmed'] == 'Y') {
		x_session_register('products_to_delete');
		global $products_to_delete;
		if (is_array($products_to_delete['products'])) {
			$ids = array_keys($products_to_delete['products']);
			$result = db_query("DELETE FROM $sql_tbl[xmlmap_lastmod] WHERE type = 'P' AND id IN ('" . implode("','", $ids) . "')");
			db_free_result($result);
		}
		
	// Manufacturers are deleting
	} else if (isset($_POST['to_delete']) && is_array($_POST['to_delete'])) {
		$ids = array_keys($_POST['to_delete']);
		$result = db_query("DELETE FROM $sql_tbl[xmlmap_lastmod] WHERE type = 'M' AND id IN ('" . implode("','", $ids) . "')");
		db_free_result($result);
		
	// Static pages are deleting
	} else if (is_array($_POST['posted_data']) && $_POST['sec'] == 'E') {
		$ids = array_keys($_POST['posted_data']);
		$result = db_query("DELETE FROM $sql_tbl[xmlmap_lastmod] WHERE type = 'S' AND id IN ('" . implode("','", $ids) . "')");
		db_free_result($result);
	}
}

/**
 * Generate xml sitemap
 *
 * @return string|void Error text if any error present
 */
function xmlmap_generate()
{
ini_set('memory_limit', -1);
	func_display_service_header();
	
	global $config, $xcart_dir;
	// Define absolute path to the xml sitemap file
	$filename = $xcart_dir . '/' . $config['XML_Sitemap']['filename'];
	
	// If file is not exists, create it
	if (!file_exists($filename)) {
		$handle = fopen($filename, 'a');
		fclose($handle);
		func_flush(func_get_langvar_by_name('xmlmap_log_filecreated', null, false, true));
	} else {
		func_flush(func_get_langvar_by_name('xmlmap_log_fileexists', null, false, true));
	}
	
	// If file is not writable, fail with error
	if (!is_writable($filename)) {
		func_flush(func_get_langvar_by_name('xmlmap_log_filenotwritable', null, false, true));
		return func_get_langvar_by_name('xmlmap_error_filenotwritable');
	} else {
		func_flush(func_get_langvar_by_name('xmlmap_log_generationstart', null, false, true));
	}
	
	$prepared_items = array();
	
	foreach ($config['XML_Sitemap']['items'] as $spec) {
		func_flush(func_get_langvar_by_name('xmlmap_log_type' . $spec['type'], null, false, true));
		func_flush(func_get_langvar_by_name('xmlmap_log_itemsquery', null, false, true));
		// execute a query from specification to collect all items
		$items = func_query(sprintf($spec['items_query'], $spec['url_pattern'], $spec['lastmod']));
		
		if (!empty($items)) {
			func_flush(func_get_langvar_by_name('xmlmap_log_itemsfound', array('count' => count((array) $items)), false, true));
			func_flush(func_get_langvar_by_name('xmlmap_log_itemsprepare', null, false, true));
			$items = xmlmap_prepare_items($spec, $items);
		} else {
			func_flush(func_get_langvar_by_name('xmlmap_log_itemsfound', array('count' => 0), false, true));
			func_flush(func_get_langvar_by_name('xmlmap_log_gotonext', null, false, true));
			continue;
		}
		
		if (!empty($items)) {
			func_flush(func_get_langvar_by_name('xmlmap_log_itemsmerge', null, false, true));
			$prepared_items = array_merge($prepared_items, $items);
		} else {
			func_flush(func_get_langvar_by_name('xmlmap_log_itemsprepareno', null, false, true));
			func_flush(func_get_langvar_by_name('xmlmap_log_gotonext', null, false, true));
			continue;
		}
	}
	
	if (!empty($prepared_items)) {
		func_flush(func_get_langvar_by_name('xmlmap_log_generatexml', null, false, true));
		global $smarty;
		// pass collected items to smarty where they will be formated for xml file
		$smarty->assign('xmlmap_items', $prepared_items);
		$src = func_display('modules/XML_Sitemap/sitemap.tpl', $smarty, false);
		$smarty->clear_assign('xmlmap_items');
		func_flush(func_get_langvar_by_name('xmlmap_log_writexml', null, false, true));
		// Write collected data to file
		$handle = fopen($filename, 'w');
		fwrite($handle, $src);
		fclose($handle);
		func_flush(func_get_langvar_by_name('xmlmap_log_generationend', null, false, true));
	} else {
		func_flush(func_get_langvar_by_name('xmlmap_log_generateno', null, false, true));
		return func_get_langvar_by_name('xmlmap_error_generateno');
	}
}

/**
 * Several manipulations with the passed items
 *
 * @param  array  $spec   @see modules/XML_Siteam/config.php for details
 * @param  array  $items  numeric array of items to be processed.
 * @return array  numeric array of items ready for xml
 */
function xmlmap_prepare_items($spec, $items)
{
	func_flush('  &nbsp;&nbsp;');
	global $http_location, $xmlmap_location;
	$prepared_items = array();
	foreach ($items as $item) {
		if (isset($item['id'])) {
			// get item url from Clean URLs, if enabled (P|C|M|S)
			$url = func_get_resource_url($spec['type'], $item['id'], '', false);
			if (empty($url)) {
				$url = $item['url'];
			}
			$url = $xmlmap_location . constant('DIR_CUSTOMER') . '/' . $url;
		} else {
			// Add domain and web path to url if necessary
			global $https_location;
			if (!strstr($item['url'], $http_location) && !strstr($item['url'], $https_location) && !strstr($item['url'], $xmlmap_location)) {
				$url = $xmlmap_location . constant('DIR_CUSTOMER') . '/' . $item['url'];
			} else {
				$url = $item['url'];
			}
		}
		$url = xmlmap_format_url($url);
		$lastmod = !empty($item['date']) ? $item['date'] : $spec['lastmod'];
		$prepared_items[] = array('loc' => $url, 'lastmod' => $lastmod, 'changefreq' => $spec['changefreq'], 'priority' => $spec['priority']);
		
		$cnt++;
		func_flush('.');
		if ($cnt % 5000 == 0) {
			func_flush("<br />\n");
		}
	}
	func_flush("<br />\n");
	return $prepared_items;
}

/**
 * Replace unalowed symbols from url and encode it
 *
 * @param  string $url
 * @return string
 */
function xmlmap_format_url($url)
{
	$url = str_replace('&', '&amp;', $url);
	$url = str_replace('\'', '&apos;', $url);
	$url = str_replace('"', '&quot;', $url);
	$url = str_replace('>', '&gt;', $url);
	$url = str_replace('<', '&lt;', $url);
	$url = preg_replace_callback('![^A-Za-z0-9\?\=\&\;\:\.\/]!i', create_function('$m', 'return urlencode($m[0]);'), $url);
	return $url;
}

/**
 * Add extra URL to the database
 *
 * @param  string $url
 * @return string|void
 */
function xmlmap_addurl($url)
{
	$url = trim(stripslashes($url));
	if (!empty($url)) {
		global $http_location, $sql_tbl, $current_storefront, $active_modules, $xmlmap_location;
		if (strpos(strtolower($url), strtolower($location)) === false) {
			$url = $xmlmap_location . '/' . $url;
		}
		if (!empty($active_modules['Multiple_Storefronts']) && $current_storefront) {
			$sf_condition = "storefrontid=$current_storefront";
		} else {
			$sf_condition = '';
		}
		if (!empty($sf_condition)) {
			$add_sf_condition = 'AND ' . $sf_condition;
			$comma_sf_condition = ', ' . $sf_condition;
		} else {
			$add_sf_condition = '';
			$comma_sf_condition = '';
		}
		if (0 < func_query_first_cell("SELECT count(id) FROM $sql_tbl[xmlmap_extra] WHERE url = '$url' $add_sf_condition")) {
			return func_get_langvar_by_name('xmlmap_error_extraurlexists');
		} else {
			$result = db_query("INSERT INTO $sql_tbl[xmlmap_extra] SET url='$url'$comma_sf_condition");
			db_free_result($result);
		}
	} else {
		return func_get_langvar_by_name('xmlmap_error_extraurlempty');
	}
}

/**
 * Remove extra urls from db
 *
 * @param  array $ids
 * @return string|void
 */
function xmlmap_delurls($ids)
{
	if (!is_array($ids) || empty($ids)) {
		return func_get_langvar_by_name('xmlmap_error_extraurlnotselected');
	} else {
		global $sql_tbl, $current_storefront, $active_modules;
		if (!empty($active_modules['Multiple_Storefronts']) && is_numeric($current_storefront)) {
			$sf_condition = "AND storefrontid=$current_storefront";
		} else {
			$sf_condition = '';
		}
		db_query("DELETE FROM $sql_tbl[xmlmap_extra] WHERE id IN ('" . implode("','", $ids) . "') $sf_condition");
	}
}

/**
 * Get extra urls from db
 *
 * @return array
 */
function xmlmap_get_extraurls()
{
	global $sql_tbl, $active_modules, $current_storefront;
	if (!empty($active_modules['Multiple_Storefronts']) && is_numeric($current_storefront)) {
		$sf_condition = "WHERE storefrontid=$current_storefront";
	} else {
		$sf_condition = '';
	}
	return func_query("SELECT * FROM $sql_tbl[xmlmap_extra] $sf_condition");
}

/**
 * Adds aditional section to the options page
 *
 * @param  string    $source
 * @param  Templater $smarty
 * @return string
 */
function xmlmap_prefilter_config($source, &$smarty)
{
	if ($smarty->current_resource_name == 'admin/main/configuration.tpl') {
		$search[] = '{/capture}' . "\n" . '{include file="dialog.tpl" content=$smarty.capture.dialog extra=\'width="100%"\'}';
		$search[] = '{/capture}' . "\n" . '{include file="dialog.tpl" title=$lng.lbl_general_settings content=$smarty.capture.dialog extra=\'width="100%"\'}';
		$include = '{if $additional_config}' . "\n" . '{include file=$additional_config}' . "\n" . '{/if}';
		$replace[] = $include . "\n\n" . $search[0];
		$replace[] = $include . "\n\n" . $search[1];
		$source = str_replace($search, $replace, $source);
	}
	return $source;
}

if (!function_exists('func_get_resource_url')) {
function func_get_resource_url($resource_type, $resource_id, $query_string = '', $absolute_path = true) {
	global $config, $xcart_catalogs;

	switch($resource_type) {
	case 'P':
	case 'product':
		$php_page = "product.php?productid=".$resource_id;
		$clean_url_resource_type = 'P';
		break;

	case 'C':
	case 'category':
		$php_page = "home.php?cat=".$resource_id;
		$clean_url_resource_type = 'C';
		break;

	case 'M':
	case 'manufacturer':
		$php_page = "manufacturers.php?manufacturerid=".$resource_id;
		$clean_url_resource_type = 'M';
		break;

	case 'S':
	case 'static_page':
		$php_page = "pages.php?pageid=".$resource_id;
		$clean_url_resource_type = 'S';
		break;

	default:
		return NULL;
	}

	$postfix = $php_page;

	$url = ($absolute_path == true ? $xcart_catalogs['customer'] . "/" : '') . $postfix; 

	if (!zerolen($query_string)) {
		$url .= ($postfix == $php_page ? '&' : '?') . $query_string;
	}

	return $url;
}
}
?>
