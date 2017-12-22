<?php
global $xcart_dir;
require "./auth.php";
require $xcart_dir."/include/security.php";

include $xcart_dir."/modules/External_Product_Verification/monitor_upload_status.php";

$location[] = array("Monitor Upload Status", "");
# Assign the current location line
$smarty->assign("location", $location);
$smarty->assign("main", "az_monitor_upload_status");

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);