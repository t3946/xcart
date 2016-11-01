<?php
if (!defined('XCART_SESSION_START')) {
    header("Location: ../");
    die("Access denied");
}
use Xcart\External_MarketPlace\ExternalMarketPlace;
global $xcart_dir;
global $sql_tbl;

if ($REQUEST_METHOD == 'POST') {

    if ($mode == 'update') {


        if (!empty($external_marketplace)) {
            foreach ($external_marketplace as $key => $aMarketplace) {
                $aMarketplace['id'] = $key;
                if (!(func_array2insert('products_external_marketplaces', $aMarketplace, true, true)))
                    func_array2update('products_external_marketplaces', $aMarketplace, 'id = ' . $aMarketplace['id']);
            }
        }
        if (!empty($external_storefront_marketplace)) {
            foreach ($external_storefront_marketplace as $iMarketPlaceId => $aMarketplace) {
                foreach ($aMarketplace as $iStoreFrontId => $aStoreFront) {
                    if ($iStoreFrontId >= 0) {
                        $aStoreFront['marketplace_id'] = $iMarketPlaceId;

                        if (!(func_array2insert('storefronts_external_marketplaces', $aStoreFront, true, true)))
                            func_array2update('storefronts_external_marketplaces', $aStoreFront, 'marketplace_id = ' . $aStoreFront['marketplace_id'] . ' AND storefront_id = ' . $aStoreFront['storefront_id']);
                    }
                }
            }
        }

        if (!empty($external_storefront_marketplace_to_delete)) {
            foreach ($external_storefront_marketplace_to_delete as $iMarketPlaceId => $aMarketplace) {
                db_exec("DELETE FROM " . $sql_tbl['storefronts_external_marketplaces'] . " WHERE marketplace_id = $iMarketPlaceId AND storefront_id IN (" . implode(',', array_flip($aMarketplace)) . ")");
            }
        }
    }

    $top_message["content"] = 'Done.';
    $top_message["type"] = "I";
    func_header_location("configuration.php?option=External_marketplaces");
}

$aExternalMarketplaces = ExternalMarketPlace::getExternalMarketPlaces();

$smarty->assign("external_marketplaces", $aExternalMarketplaces);
$oStoreFronts = new Xcart\StoreFronts();
$smarty->assign("external_storefronts", $oStoreFronts);