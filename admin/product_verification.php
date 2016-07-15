<?php
global $xcart_dir;

require "./auth.php";
require $xcart_dir."/include/security.php";
require_once $xcart_dir . "/include/class/classOrders.php";

if(!$active_modules['Product_Verification'])
	func_header_location ("error_message.php?access_denied&id=25");
else
	include $xcart_dir."/modules/Product_Verification/product_verification.php";


//$smarty->assign("single_mode", $single_mode);


# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);

?>
