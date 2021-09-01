<?php
use Xcart\External_Product_Verification\ExternalVerificationBatch;
global $login, $xcart_dir;
require "./auth.php";

if (empty($login))
    func_header_location("error_message.php?antibot_error");

if (!empty($active_modules["Simple_Mode"]))
    func_header_location($current_location . DIR_ADMIN . "/home.php");

$location[] = array("Verificator panel", "");

$oCustomer = new Xcart\Customer(['login' => $login]);
$smarty->assign('oCustomer', $oCustomer);


$oBatches = new ExternalVerificationBatch();
$aCurrentBatches = $oBatches->getCurrentBatches();
$smarty->assign('aCurrentBatches', $aCurrentBatches);

$aPreviousBatches = $oBatches->getPreviousBatches();
$smarty->assign('aPreviousBatches', $aPreviousBatches);

$smarty->assign('isAccountSuspended', $oCustomer->isAmazonAccountSuspended());

if (!empty($login))
    $smarty->assign("location", $location);

$smarty->assign("main", "home");

@include $xcart_dir . "/modules/gold_display.php";
func_display("verificator/home.tpl", $smarty);

