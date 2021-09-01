<?php
/*
+----------------------------------------------------------------------+
| Best Search Filter Mod                                               |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*/

if ( !defined('XCART_SESSION_START') ) { header("Location: ../../../../"); die("Access denied"); }

if (!empty($active_modules['CIDEV_Best_Search_Filter'])) {
    if ($_GET['cidev_filter_mode'] == "search"){
     $tpl = "modules/CIDEV_Best_Search_Filter/customer/main/products.tpl";
    }
}
?>
