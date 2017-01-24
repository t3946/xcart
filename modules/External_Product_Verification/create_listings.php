<?php
use \Xcart\External_Product_Verification\ExternalVerificationProductsQueue;

$aParams['limit'] = 50;
$a = ExternalVerificationProductsQueue::getVerificationResultsProducts($aParams);
var_dump($a);

$smarty->assign('aVerifiactionResults', $a);
$smarty->assign('foundRows', (new Xcart\SQLBuilder())->getFoundRows());