<?php
/*
+----------------------------------------------------------------------+
| Best Search Filter Mod                                               |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*/

if ( !defined('XCART_START') ) { header('Location: ../../'); die('Access denied'); }

//if (!empty($active_modules['CIDEV_Best_Search_Filter'])) {
	$sql_tbl['cidev_filters'] = 'xcart_cidev_filters';
	$sql_tbl['cidev_filter_categories'] = 'xcart_cidev_filter_categories';
	$sql_tbl['cidev_filter_values'] = 'xcart_cidev_filter_values';
	$sql_tbl['cidev_filter_products'] = 'xcart_cidev_filter_products';

	$css_files['CIDEV_Best_Search_Filter'][] = array('subpath' => 'css/');
//}
?>
