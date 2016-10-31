<?php
global $xcart_dir, $login, $batch;


require "./auth.php";
require $xcart_dir . "/include/security.php";
require_once $xcart_dir."/modules/External_Product_Verification/include/classExternalVerificationBatches.php";
require_once $xcart_dir."/modules/External_Product_Verification/include/classExternalVerificationProductsQueue.php";


$location[] = array("Verificator management", "operators.php");
$location[] = array("Verification batches", "operators_batches.php?operator=".classExternalVerificationBatch::model(['batch_id' => (int)$batch])->getBatchLogin());

include $xcart_dir . "/modules/External_Product_Verification/operators_batch_view.php";
$smarty->assign('batch_id', (int)$batch);
# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir . "/modules/gold_display.php";
func_display("admin/home.tpl", $smarty);