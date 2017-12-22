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
    if ( ($search_data["products"]["cidev_main_value"] != "search" && !empty($search_data["products"]["cidev_main_value"])) && empty($_GET["cidev_filter_mode"]) ){
        unset($search_data["products"]['manufacturers']);
        unset($search_data["products"]['cidev_selected_filter_values']);
        unset($search_data["products"]['cidev_main_value']);
        unset($search_data["products"]['categoryid']);
    }
}
?>
