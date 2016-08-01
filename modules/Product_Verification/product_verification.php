<?php

/** @var classOrder $oOrderManufacturer */

if ( !defined('XCART_START') ) { header("Location: ../"); die("Access denied"); }

$oOrders = new classOrders();
$aOrders = $oOrders->getOrdersWithProductsForVerification();

if (!empty($aOrders)) {

    $aManufacturers = [];
    foreach ($aOrders as $oOrder) {
        $aOrderProducts = $oOrder->getOrderProducts();
        if (!empty($aOrderProducts))
        foreach ($aOrderProducts as $oProduct) {
            $aManufacturers[(int) $oProduct->getField('manufacturerid')][(int) $oOrder->getField('orderid')] = $oOrder;
        }
    }

    foreach ($aManufacturers as $iManufacturerId => $aManufacturer) {
        $aProducts = [];
        foreach ($aManufacturer as $oOrderManufacturer) {
            $aOrderManufacturerProducts = $oOrderManufacturer->getOrderProducts();
            if (!empty($aOrderManufacturerProducts))
            foreach ($aOrderManufacturerProducts as $oProduct) {
                if ($oProduct->getField('manufacturerid') == $iManufacturerId) {
                    if (!in_array($oProduct->getField('productid'), $aProducts)) {
                        $aProducts[] = $oProduct->getField('productid');
                    } else {
                        //$oOrderManufacturer->unsetOrderProduct($oProduct->getField('productid'));
                    }
                }
            }
        }
    }

    $aVerifyStatuses = classProduct::getProductVerificationStatuses();

    $smarty->assign('aVerifyStatuses',$aVerifyStatuses);
    $smarty->assign('aManufacturers',$aManufacturers);


    /*foreach ($aManufacturers as $iManufacturerId => $aManufacturer) {

        foreach ($aManufacturer as $oOrderManufacturer) {

            foreach ($oOrderManufacturer->getOrderProducts() as $oProduct) {
                if ($oProduct->getField('manufacturerid') == $iManufacturerId) {
                    echo $oProduct->getManfacturerClass()->getField('manufacturer');
                    echo '<a href="' . $oOrderManufacturer->getOrderModifyURL() . '">' . $oOrderManufacturer->getDisplayOrderNumber() . '</a>';
                    echo '<a href="'.$oProduct->getProductModifyURL(). '" > '.$oProduct->getField('productcode'). '</a> <a href="'.$oProduct->getProductFrontURL().'">'.$oProduct->getField('product'). '</a>';
                    echo '<a href="'.$oProduct->getProductURLOnDistributorWebSite().'">'.$oProduct->getMPN().'</a>';
                    $oVerifyDate = $oProduct->getProductLastVerifyDate();
                    if ($oVerifyDate instanceof DateTime)
                        echo $oVerifyDate->format('d.M.Y');

                    echo '<br>';
                }
            }
        }
    }*/
}

$smarty->assign("main","product_verification");
