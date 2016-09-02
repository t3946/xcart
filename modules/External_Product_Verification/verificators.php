<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classCustomer.php";
require_once $xcart_dir . "/modules/External_Product_Verification/include/classExternalVerificationBatches.php";

$smarty->assign("main", "verificators");

if (empty($active)) {
    $active = 'all';
}
$oClassCustomer = new classCustomer();
$aCustomers = $oClassCustomer->getCustomersByType('V', $active);
$oBatches = new classExternalVerificationBatch();

usort($aCustomers, array($oClassCustomer, "sortByAmazonCompletedBatchesDesc"));

$smarty->assign("aCustomers", $aCustomers);
$smarty->assign("active", $active);


$smarty->assign('oBatches', $oBatches);