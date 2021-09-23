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
	$_module_dir  = $xcart_dir . XC_DS . 'modules' . XC_DS . 'CIDEV_Best_Search_Filter';
	require_once $_module_dir . XC_DS . 'include'. XC_DS . 'func'. XC_DS . 'func.php';
	include_once $_module_dir . XC_DS . 'init.php';
//}
?>
