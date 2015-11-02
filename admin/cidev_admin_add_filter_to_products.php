<?php
/*
+----------------------------------------------------------------------+
| Best Search Filter Mod                                               |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*/

define("NUMBER_VARS", "posted_data[price_min],posted_data[price_max],posted_data[avail_min],posted_data[avail_max],posted_data[weight_min],posted_data[weight_max],price_min,price_max,avail_min,avail_max,weight_min,weight_max");

require './auth.php';
require $xcart_dir.'/include/security.php';

x_session_register("search_data");

if ($REQUEST_METHOD == 'POST' && $mode == "search_reset" && $current_area != "C") {
        $search_data = "";
        x_session_save("search_data");
	func_header_location("cidev_admin_add_filter_to_products.php");
}

//if(empty($active_modules['CIDEV_Best_Search_Filter']))
//    func_403(25);

include $xcart_dir.'/modules/CIDEV_Best_Search_Filter/admin/cidev_admin_add_filter_to_products.php';

$smarty->assign('single_mode', $single_mode);

$smarty->assign('main','cidev_admin_add_filter_to_products');

// Assign the current location line
$smarty->assign('location', $location);

if (
    file_exists($xcart_dir.'/modules/gold_display.php')
    && is_readable($xcart_dir.'/modules/gold_display.php')
) {
    include $xcart_dir.'/modules/gold_display.php';
}
func_display('admin/home.tpl',$smarty);

?>
