<?php
use Xcart\External_Product_Verification\ExternalVerificationProductsQueue;

$smarty->assign("main","operators_batch_view");

$smarty->assign('aVerifiactionResults', ExternalVerificationProductsQueue::getVerificationResultsProducts(['batch_id'=>(int)$batch]));

