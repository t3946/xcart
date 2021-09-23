<?php
/*
+----------------------------------------------------------------------+
| Best Search Filter Mod                                               |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*/

require './auth.php';
require $xcart_dir.'/include/security.php';

//if(empty($active_modules['CIDEV_Best_Search_Filter']))
//    func_403(25);

include $xcart_dir.'/modules/CIDEV_Best_Search_Filter/admin/cidev_admin_filter_values.php';

$smarty->assign('single_mode', $single_mode);

$smarty->assign('main','cidev_admin_filter_values');

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
