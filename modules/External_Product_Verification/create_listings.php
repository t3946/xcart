<?php
use \Xcart\External_Product_Verification\ExternalVerificationProductsQueue;

global $xcart_dir, $per_page, $page;

if (empty($per_page)) {
    $per_page = 30;
}
if (empty($page)) {
    $page = 1;
}

$aParams['limit'] = $objects_per_page = $per_page;
$aParams['page'] = $page;

$a = ExternalVerificationProductsQueue::getVerificationProductsReadyForListings($aParams);

$total_items = intval($a['FoundRows']);

$total_nav_pages = ceil($total_items/$objects_per_page)+1;
$smarty->assign("navigation_script", "az_create_listings.php?per_page=$per_page");
$smarty->assign("per_page", $per_page);
include $xcart_dir."/include/navigation.php";
$smarty->assign('aVerifiactionResults', $a['resultSet']);
