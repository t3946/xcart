<?php
use Xcart\External_Product_Verification\ExternalVerificationBatch;
use Xcart\External_Product_Verification\ExternalVerificationProductsQueue;
use Xcart\Customer;

$smarty->assign("main", "verificators");

if (empty($active)) {
    $active = 'Y';
}
$oClassCustomer = new Customer();
$aCustomers = $oClassCustomer->getCustomersByType('V', $active);
$oBatches = new ExternalVerificationBatch();

//usort($aCustomers, array($oClassCustomer, "sortByAmazonCompletedBatchesDesc"));

$smarty->assign("aCustomers", $aCustomers);
$smarty->assign("active", $active);


$smarty->assign('oBatches', $oBatches);

$smarty->assign('aVerifiactionResults', ExternalVerificationProductsQueue::getVerificationResultsProducts());
