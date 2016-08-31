<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classCustomer.php";
require_once $xcart_dir . "/modules/External_Product_Verification/include/classExternalVerificationBatches.php";

$smarty->assign("main","operators_batches");

$oCustomer = new classCustomer(['login'=>$operator]);
$smarty->assign("oCustomer", $oCustomer);
$location[] = array(sprintf(func_get_langvar_by_name('txt_verificator_batches',null,false,true),$oCustomer->getCustomerFullName()), "");

if ($batch_status == 'all') $batch_status = null;
$batch_status = str_replace('_',' ',$batch_status);
$smarty->assign("batch_status", $batch_status);

$aStatuses = ['In progress', 'Completed', 'Paid'];
$smarty->assign("batch_statuses", $aStatuses);