<?php
/**
 * Module initializaition
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

$cron_tasks[] = array('function' => 'xmlmap_generate');

if (!empty($active_modules['Multiple_Storefronts']) && defined('AREA_TYPE') && in_array(constant('AREA_TYPE'), array('A', 'P'))) {
	x_session_register('current_storefront');
}

if (defined('AREA_TYPE') && in_array(constant('AREA_TYPE'), array('A', 'P'))) {
	// Process changes on the module options page
	if (isset($_GET['option']) && ($_GET['option'] == 'XML_Sitemap' || $_GET['option'] == 'Multiple_Storefronts')) {
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['xmlmap'])) {
			switch ($_POST['xmlmap']['config']) {
				case 'generate':
					// generate sitemap
					$xmlmap_error = xmlmap_generate();
					break;
				
				case 'addurl':
					// add extra url
					$xmlmap_error = xmlmap_addurl($_POST['xmlmap']['url']);
					break;
				
				case 'delurls':
					// del extra urls
					$xmlmap_error = xmlmap_delurls($_POST['xmlmap']['del_extra']);
					break;
				
				default:
					break;
			}
			
			// Store error or success message in session
			x_session_register("top_message", []);
			if (!empty($xmlmap_error)) {
				$top_message['content'] = $xmlmap_error;
				$top_message['type'] = 'E';
			} else {
				$top_message['content'] = func_get_langvar_by_name('lbl_done');
				$top_message['type'] = 'I';
			}
			func_header_location($_SERVER['REQUEST_URI']);
		} else {
			$smarty->assign('xmlmap_extra', xmlmap_get_extraurls());
			$smarty->assign('additional_config', 'modules/XML_Sitemap/config.tpl');
			$smarty->register_prefilter('xmlmap_prefilter_config');
		}
	}
	
	// CUID lastmod entry for modified items
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mode'])) {
		switch ($_POST['mode']) {
			case 'update':
				// Update lastmod entry for category
				if (isset($_POST['cat'])) {
					xmlmap_update_lastmod('C', $_POST['cat']);
				}
				break;
			
			case 'product_modify':
				// Update lastmod entry for product
				if (isset($_POST['productid'])) {
					xmlmap_update_lastmod('P', $_POST['productid']);
				}
				break;
			
			case 'details':
				// Update lastmod entry for manufacturer
				if (isset($_POST['manufacturerid'])) {
					xmlmap_update_lastmod('M', $_POST['manufacturerid']);
				}
				break;
			
			case 'modified':
				// Update lastmod entry for static page
				if (isset($_POST['pageid'])) {
					xmlmap_update_lastmod('S', $_POST['pageid']);
				}
				break;
			
			case 'delete':
				// Remove lastmod entry
				xmlmap_delete_lastmod();
				break;
			
			default:
				break;
		}
	}
}
?>
