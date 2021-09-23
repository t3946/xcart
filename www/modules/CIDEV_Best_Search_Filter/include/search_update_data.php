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

    if ($cidev_filter_mode == "search"){
        $search_data['products']['cidev_selected_filter_values'] = $cidev_selected_filter_values;
	$search_data['products']['cidev_main_value'] = $cidev_main_value;

        if ($config['CIDEV_Best_Search_Filter']['cidev_disable_manufacturers'] != 'Y') {
            $cidev_manuf_values_arr = array();
            if (!empty($cidev_selected_manuf_values) && is_array($cidev_selected_manuf_values)){
                foreach ($cidev_selected_manuf_values as $cidev_k => $cidev_v){
                    $cidev_manuf_values_arr[] = $cidev_k;
                }
            }
            $search_data['products']['manufacturers'] = $cidev_manuf_values_arr;
        }

        $url .=  (strpos($url, '?') === false ? '?' : '&') . 'cidev_filter_mode=search' . (!empty($sort) ? '&sort='.$sort : '') . (!empty($sort_direction) ? '&sort_direction='.$sort_direction : '');
    }
}
?>
