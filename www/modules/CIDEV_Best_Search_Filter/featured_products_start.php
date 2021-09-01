<?php
/*
+----------------------------------------------------------------------+
| Best Search Filter Mod                                               |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*/

if ( !defined('XCART_START') ) { header('Location: ../../'); die('Access denied'); }

if (!empty($active_modules['CIDEV_Best_Search_Filter'])) {
	$search_data['products']['cidev_do_not_use_filter'] = "Y";
}
?>
