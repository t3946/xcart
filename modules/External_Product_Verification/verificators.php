<?php
use Xcart\External_Product_Verification\ExternalVerificationBatch;
use Xcart\External_Product_Verification\ExternalVerificationProductsQueue;
use Xcart\Customer;

$smarty->assign("main", "verificators");

$active_p = $active;

if (empty($active) || $active == 'B') {
    $active_p = 'Y';
}
if (empty($filter)) {$filter[] = 'asin';}
$oClassCustomer = new Customer();
$aCustomers = $oClassCustomer->getCustomersByType('V', $active_p);
if (!empty($aCustomers)) {
    foreach ($aCustomers as $key => $oCustomer) {
        if ($active != 'B') {
            if ($oCustomer->isAmazonAccountSuspended()) {
                unset($aCustomers[$key]);
            }
        } else {
            if (!$oCustomer->isAmazonAccountSuspended()) {
                unset($aCustomers[$key]);
            }
        }
    }
}
$oBatches = new ExternalVerificationBatch();

$smarty->assign("aCustomers", $aCustomers);
$smarty->assign("active", $active);


$smarty->assign('oBatches', $oBatches);
$aParams['filter'] = $filter;
$aParams['limit'] = 50;
$smarty->assign('aVerifiactionResults', ExternalVerificationProductsQueue::getVerificationResultsProductsWithNotSameVariants($aParams));
$smarty->assign('foundRows', (new Xcart\SQLBuilder())->getFoundRows());
$smarty->assign('filter', $filter);
