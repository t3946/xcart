<?php
global $xcart_dir;

$smarty->assign("main","operators_batch_view");

$smarty->assign('aVerifiactionResults', classExternalVerificationProductsQueue::getVerificationResultsProducts(['batch_id'=>(int)$batch]));

