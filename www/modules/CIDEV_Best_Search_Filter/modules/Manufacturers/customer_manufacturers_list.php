<?php
/*
+----------------------------------------------------------------------+
| Best Search Filter Mod                                               |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*/

if ( !defined('XCART_START') ) { header('Location: ../../../../'); die('Access denied'); }

if (!empty($active_modules['CIDEV_Best_Search_Filter'])) {

	$cidev_copy_selected_filter_value = true;

	x_session_register('cidev_previous_manufacturerid');

	if ( !empty($manufacturerid) && ($cidev_previous_manufacturerid != $manufacturerid || ($old_search_data['cidev_main_value'] != "manufacturer_products" && !empty($old_search_data['cidev_main_value']))) ){

        	$cidev_previous_manufacturerid = $manufacturerid;
       		$cidev_copy_selected_filter_value = false;

		if (!empty($old_search_data)){
			unset($old_search_data['manufacturers']);
			unset($old_search_data['cidev_selected_filter_values']);
			unset($old_search_data['cidev_main_value']);
			unset($old_search_data['categoryid']);
		}
	}

//    	if ($cidev_copy_selected_filter_value && array_key_exists('cidev_selected_filter_values', $old_search_data)){
    	if (!empty($old_search_data) && is_array($old_search_data) && $cidev_copy_selected_filter_value && array_key_exists('cidev_selected_filter_values', $old_search_data)){
       		$search_data['products']['cidev_selected_filter_values'] = $old_search_data["cidev_selected_filter_values"];
		$search_data['products']['cidev_main_value'] = $old_search_data["cidev_main_value"];
	}
}
?>
