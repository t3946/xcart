<?php
require "./auth.php";
require $xcart_dir."/include/security.php";

$location[] = array("Reports", "");

$smarty->assign("main", "reports");

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";

func_display("admin/home.tpl",$smarty);

?>
