<?php
global $xcart_dir;

require "./auth.php";
require $xcart_dir."/include/security.php";

include $xcart_dir."/modules/External_Product_Verification/verificators.php";

$location[] = array("Operators management", "");

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);

?>
