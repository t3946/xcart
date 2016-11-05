<?php
global $xcart_dir, $login, $batch;


require "./auth.php";
require $xcart_dir . "/include/security.php";


$location[] = array("Verificator management", "operators.php");
$location[] = array("Verification batches", "operators_batches.php?operator=".\Xcart\External_Product_Verification\ExternalVerificationBatch::model(['batch_id' => (int)$batch])->getBatchLogin());

include $xcart_dir . "/modules/External_Product_Verification/operators_batch_view.php";
$smarty->assign('batch_id', (int)$batch);
# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir . "/modules/gold_display.php";
func_display("admin/home.tpl", $smarty);