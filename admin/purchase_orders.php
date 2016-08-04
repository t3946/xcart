<?php
global $xcart_dir;

require "./auth.php";
require $xcart_dir."/include/security.php";
require_once $xcart_dir . "/include/class/classOrders.php";



$smarty->assign("main","product_verification");

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);

?>
