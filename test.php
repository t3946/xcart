<?php
/** @var classOrder $oOrderManufacturer */
session_start();

require "./top.inc.php";
require "./init.php";

global $xcart_dir;

require_once $xcart_dir . "/include/class/classProducts.php";

$oProduct = new classProduct(275567);
$aManufacturerProductVerifySettings = $oProduct->getManfacturerClass()->getFields(['products_always_verify', 'days_before_verify']);
var_dump($oProduct->getManfacturerClass()->getField('products_always_verify'));
var_dump($aManufacturerProductVerifySettings);

exit;

require_once $xcart_dir . "/include/class/classOrders.php";

$oOrders = new classOrders();
$aOrders = $oOrders->getOrdersWithProductsForVerification();

if (!empty($aOrders)) {

    $aManufacturers = [];
    foreach ($aOrders as $oOrder) {
        foreach ($oOrder->getOrderProducts() as $oProduct) {
            $aManufacturers[$oProduct->getField('manufacturerid')][$oOrder->getField('orderid')] = $oOrder;
        }
    }


    foreach ($aManufacturers as $iManufacturerId => $aManufacturer) {
        $aProducts = [];
        foreach ($aManufacturer as $oOrderManufacturer) {
            $aOrderProducts = $oOrderManufacturer->getOrderProducts();
            foreach ($aOrderProducts as $oProduct) {
                if ($oProduct->getField('manufacturerid') == $iManufacturerId) {
                    if (!in_array($oProduct->getField('productid'), $aProducts)) {
                        $aProducts[] = $oProduct->getField('productid');
                    } else {
                        $oOrderManufacturer->unsetOrderProduct($oProduct->getField('productid'));
                    }
                }
            }
        }
    }

    foreach ($aManufacturers as $iManufacturerId => $aManufacturer) {

        foreach ($aManufacturer as $oOrderManufacturer) {

            foreach ($oOrderManufacturer->getOrderProducts() as $oProduct) {
                if ($oProduct->getField('manufacturerid') == $iManufacturerId) {
                    echo $oProduct->getManfacturerClass()->getField('manufacturer');
                    echo '<a href="' . $oOrderManufacturer->getOrderModifyURL() . '">' . $oOrderManufacturer->getDisplayOrderNumber() . '</a>';
                    echo '<a href="' . $oProduct->getProductModifyURL() . '" > ' . $oProduct->getField('productcode') . '</a> <a href="' . $oProduct->getProductFrontURL() . '">' . $oProduct->getField('product') . '</a>';
                    echo '<a href="' . $oProduct->getProductURLOnDistributorWebSite() . '">' . $oProduct->getMPN() . '</a>';
                    $oVerifyDate = $oProduct->getProductLastVerifyDate();
                    if ($oVerifyDate instanceof DateTime)
                        echo $oVerifyDate->format('d.M.Y');

                    echo '<br>';
                }
            }
        }
    }
}






