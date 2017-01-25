<?php
use \Xcart\External_Product_Verification\ExternalVerificationProductsQueue;

$aParams['limit'] = 50;
$aParams['page'] = 1;
$a = ExternalVerificationProductsQueue::getVerificationProductsReadyForListings($aParams);
$smarty->assign('aVerifiactionResults', $a);
