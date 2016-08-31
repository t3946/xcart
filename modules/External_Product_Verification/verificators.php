<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classCustomer.php";
require_once $xcart_dir . "/modules/External_Product_Verification/include/classExternalVerificationBatches.php";

$smarty->assign("main","verificators");

$aCustomers = classCustomer::getCustomersByType('V', 'All');
$smarty->assign("aCustomers", $aCustomers);

$oBatches = new classExternalVerificationBatch();
$smarty->assign('oBatches', $oBatches);