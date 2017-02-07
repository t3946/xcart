<?php
use \Xcart\External_Product_Verification\ExternalVerificationProductsQueue;
use \Xcart\External_Product_Verification\ExternalVerificationBatch;

global $xcart_dir, $per_page, $page, $REQUEST_METHOD, $productasin;

if ($REQUEST_METHOD == "POST") {
    if (!empty($productids)) {
        $aFeed = null;
        $aProductsIds = array_keys($productids);
        foreach ($aProductsIds as $iProductId) {
            $aFeed[$iProductId]['Product'] = $oProduct = \Xcart\Product::model(['productid' => $iProductId]);
            $aFeed[$iProductId]['ASIN'] = $productasin[$iProductId];
            $aFeed[$iProductId]['cidev_get_amazon_fulfillment_latency'] = $oProduct->getManfacturerClass()->getAmazonLeadtimetoship();
        }
        $res = (new \Xcart\AmazonMWS())->submitToListingLoader($aFeed);
        if ($res) {
            $top_message["content"] = 'Listing has been submitted';
            $top_message["type"] = "I";
        }

        func_header_location('az_create_listings.php');
    }
}

if (empty($per_page)) {
    $per_page = 70;
}
if (empty($page)) {
    $page = 1;
}

$aParams['limit'] = $objects_per_page = $per_page;
$aParams['page'] = $page;

$a = ExternalVerificationProductsQueue::getVerificationProductsReadyForListings($aParams);

$total_items = intval($a['FoundRows']);

$total_nav_pages = ceil($total_items/$objects_per_page)+1;
$oBatch = ExternalVerificationBatch::model();
$smarty->assign('sAmazonLink', $oBatch::LINK_SEARCH_BY_ASIN);
$smarty->assign("navigation_script", "az_create_listings.php?per_page=$per_page");
$smarty->assign("per_page", $per_page);
include $xcart_dir."/include/navigation.php";
$smarty->assign('aVerifiactionResults', $a['resultSet']);
