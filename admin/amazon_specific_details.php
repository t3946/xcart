<?php
define('USE_TRUSTED_POST_VARIABLES', 1);
define('USE_TRUSTED_SCRIPT_VARS', 1);
$trusted_post_variables = array("add_inq_subject");

require "./auth.php";
require $xcart_dir . "/include/security.php";

if (empty($productid)) {
    die("Empty productid");
}
$oProduct = \Xcart\Product::model(['productid' => $productid]);
$product = $oProduct->getFields();

$location[] = array($oProduct->getProductName(), "product_modify.php?productid=$productid");
$location[] = array("Amazon specific details", "");

if ($REQUEST_METHOD == 'POST') {

    if ($mode == "update") {

        $query_data = array(
            "productid" => $productid,
            "amazon_product" => $amazon_product,
            "amazon_bulletpoint1" => $amazon_bulletpoint1,
            "amazon_bulletpoint2" => $amazon_bulletpoint2,
            "amazon_bulletpoint3" => $amazon_bulletpoint3,
            "amazon_bulletpoint4" => $amazon_bulletpoint4,
            "amazon_bulletpoint5" => $amazon_bulletpoint5,
            "amazon_searchterms1" => $amazon_searchterms1,
            "amazon_searchterms2" => $amazon_searchterms2,
            "amazon_searchterms3" => $amazon_searchterms3,
            "amazon_searchterms4" => $amazon_searchterms4,
            "amazon_searchterms5" => $amazon_searchterms5,
            "amazon_product_type" => $amazon_product_type,
            "amazon_category_item_type" => $amazon_category_item_type,
            "amazon_listing_sku_to_load" => $amazon_listing_sku_to_load,
            "amazon_fba_restricted" => (empty($amazon_fba_restricted) ? 'N' : $amazon_fba_restricted),
            "amazon_fba_restricted_reason" => $amazon_fba_restricted_reason,
            "prevent_selling_on_amazon" => $prevent_selling_on_amazon,
        );

        if (!func_array2insert("products_amz_fields", $query_data, false, true)) {
            unset($query_data['productid']);
            func_array2update("products_amz_fields", $query_data, "productid = '$productid'");
        }

        $aProductsFields = [
            'amazon_enabled' => (empty($amazon_enabled) ? 'N' : $amazon_enabled),
            'amazon_fba' => (empty($amazon_fba) ? 'N' : $amazon_fba),
        ];
        $oProduct->updateFields($aProductsFields);

        if (!empty($amazon_enabled)) {
        }

        $top_message["content"] = 'Done.';
        $top_message["type"] = "I";
        func_header_location("amazon_specific_details.php?productid=$productid");
    }
}

$amazon_specific_details = func_query_first("SELECT * FROM $sql_tbl[products_amz_fields] WHERE productid='$productid'");
if (empty($amazon_specific_details)) {
    $amazon_specific_details = array(
        "id" => '0',
        "productid" => $productid,
        "amazon_product" => '',
        "amazon_bulletpoint1" => '',
        "amazon_bulletpoint2" => '',
        "amazon_bulletpoint3" => '',
        "amazon_bulletpoint4" => '',
        "amazon_bulletpoint5" => '',
        "amazon_searchterms1" => '',
        "amazon_searchterms2" => '',
        "amazon_searchterms3" => '',
        "amazon_searchterms4" => '',
        "amazon_searchterms5" => '',
        "amazon_product_type" => '',
        "amazon_category_item_type" => ''
    );
}

$smarty->assign("amazon_specific_details", $amazon_specific_details);
$smarty->assign("product", $product);
$smarty->assign("oProduct", $oProduct);
$smarty->assign("main", "amazon_specific_details");
$smarty->assign("location", $location);

@include $xcart_dir . "/modules/gold_display.php";
func_display("admin/home.tpl", $smarty);
?>
