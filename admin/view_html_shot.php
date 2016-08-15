<?php

require "./auth.php";
require $xcart_dir . "/include/security.php";
include_once $xcart_dir . "/include/class/classProducts.php";
include_once $xcart_dir . "/include/class/classOrderDetail.php";

x_load("category");
x_load('product');

if (!empty($id) && is_numeric($id)) {

    $oHTMLShot = new classHTMLShot(['id'=>$id]);

    $oProduct = $oHTMLShot->getHTMLShot();

    //$oProduct = new classProduct(331743);
    $aPricing = $oProduct->getPricing();

    $productid = $oProduct->getProductId();

    $smarty->assign("oProduct", $oProduct);

    $product_info = $oProduct->getProductTableValues();
    $product_info['price'] = $product_info['taxed_price'] = $oProduct->getPrice();
    $product_info['cart_manufact_text_displayed'] = $oProduct->getManfacturerClass()->getField('cart_manufact_text_displayed');
    $smarty->assign("product", $product_info);

    $aImages = $oProduct->getImages('D');
    foreach ($aImages as $oImage)
        $aImageToView[] = $oImage->getFields(null);


    $product_tabs[0]["title"] = "Product description";
    $product_tabs[0]["tpl"] = "_product_description_";
    $product_tabs[0]["anchor"] = 0;


    $cidev_cart_manufact_text_displayed_arr = explode("<s3-tab>", $product_info["cart_manufact_text_displayed"]);

    $cidev_make_array_values = false;
    if (!empty($cidev_cart_manufact_text_displayed_arr) && is_array($cidev_cart_manufact_text_displayed_arr)) {
        foreach ($cidev_cart_manufact_text_displayed_arr as $k => $v) {
            if (empty($v) || trim($v) == "") {
                unset($cidev_cart_manufact_text_displayed_arr[$k]);
                $cidev_make_array_values = true;
            }
        }
    }
    $cart_manufact_text_displayed_tabs = array();
    $cart_manufact_text_displayed_tabs_index = 0;
    if (!empty($cidev_cart_manufact_text_displayed_arr) && is_array($cidev_cart_manufact_text_displayed_arr)) {
        foreach ($cidev_cart_manufact_text_displayed_arr as $k => $v) {
            $cidev_pos2 = strpos($v, "</s3-tab>");
            if ($cidev_pos2 !== false) {
                $cart_manufact_text_displayed_tabs[$cart_manufact_text_displayed_tabs_index] = explode("</s3-tab>", $v);
                $cart_manufact_text_displayed_tabs_index++;
            }
        }
    }
    if (empty($cart_manufact_text_displayed_tabs) && !empty($product_info["cart_manufact_text_displayed"])) {
        $cart_manufact_text_displayed_tabs[0][0] = "Shipping information";
        $cart_manufact_text_displayed_tabs[0][1] = $product_info["cart_manufact_text_displayed"];
    }

    if (!empty($cart_manufact_text_displayed_tabs) && is_array($cart_manufact_text_displayed_tabs)) {
        $count_product_tabs = count($product_tabs);
        foreach ($cart_manufact_text_displayed_tabs as $k => $v) {
            $product_tabs[$k + $count_product_tabs]["title"] = $v[0];
            $product_tabs[$k + $count_product_tabs]["tpl"] = $v[1];
            $product_tabs[$k + $count_product_tabs]["anchor"] = $k + $count_product_tabs;
        }
    }


    $smarty->assign('product_tabs', $product_tabs);

    $smarty->assign("images", $aImageToView);


    include $xcart_dir . "/modules/Wholesale_Trading/product.php";

    $location = [];
    if (!empty($product_info)) {
        $location[] = array($product_info['product'],'');
        if (is_array($location) && !empty($location)) {
            if (is_array($location)) {
                foreach (array_reverse($location) as $l) {
                    $product_info['meta_keywords'] .= $l[0] . ', ';
                }
                $product_info['meta_keywords'] = trim(strip_tags(substr($product_info['meta_keywords'], 0, strlen($product_info['meta_keywords']) - 2)));
            }
        }
    }

}

# Assign the current location line
$smarty->assign("location", $location);

func_display("customer/main/product_detail.tpl", $smarty);
