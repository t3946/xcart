<?php
global $xcart_dir;
require "./auth.php";
require_once $xcart_dir . "/modules/External_Product_Verification/include/classExternalVerificationBatches.php";

if (empty($batch)) {
    func_header_location ("error_message.php?access_denied&id=1");
} else {
    $oVerificationBatch = new classExternalVerificationBatch(['batch_id'=>$batch]);
    $bAccess = $oVerificationBatch->checkAccess();
    if (!$bAccess)
        func_header_location ("error_message.php?access_denied&id=2");
    $bStatus = $oVerificationBatch->getBatchStatus();
	$oVerificationBatch->checkBatchTestProductsComplete();
    if ($bStatus != 'In progress' || $oVerificationBatch->isAccountSuspended()) {
        func_header_location ("index.php");
    }

    $smarty->assign('oVerificationBatch', $oVerificationBatch);
}

$smarty->assign("main", "verification");

@include $xcart_dir . "/modules/gold_display.php";
func_display("verificator/main/verification.tpl", $smarty);