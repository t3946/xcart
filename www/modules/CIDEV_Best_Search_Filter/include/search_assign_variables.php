<?php
/*
+----------------------------------------------------------------------+
| Best Search Filter Mod                                               |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*/

if ( !defined('XCART_SESSION_START') ) { header("Location: ../../../"); die("Access denied"); }

if (!empty($active_modules['CIDEV_Best_Search_Filter'])) {
	if ($current_area == 'C') {
                if (!empty($search_data["products"]["categoryid"]) && ($search_data["products"]["cidev_main_value"] == "catalog")) {
                    $smarty->assign('navigation_script', 'home.php?cat='. $search_data["products"]["categoryid"] . (!empty($input_args) ? '&' . $input_args : '') . (!empty($sort) ? '&sort='.$sort : '') . (!empty($sort_direction) ? '&sort_direction='.$sort_direction : ''));
                }

//                if (array_key_exists('cidev_selected_filter_values', $search_data['products']) && ($search_data["products"]["cidev_main_value"] == "manufacturer_products")){
                if (!empty($search_data) && is_array($search_data) && array_key_exists('cidev_selected_filter_values', $search_data['products']) && ($search_data["products"]["cidev_main_value"] == "manufacturer_products")){
                    $smarty->assign('navigation_script', 'manufacturers.php?manufacturerid='. $search_data["products"]["manufacturers"]["0"] . (!empty($input_args) ? '&' . $input_args : '') . (!empty($sort) ? '&sort='.$sort : '') . (!empty($sort_direction) ? '&sort_direction='.$sort_direction : ''));
                }
	}
}
?>
