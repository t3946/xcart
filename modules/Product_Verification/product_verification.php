<?php

/** @var classOrder $oOrderManufacturer */

if ( !defined('XCART_START') ) { header("Location: ../"); die("Access denied"); }

$oOrders = new classOrders();
$aOrders = $oOrders->getOrdersWithProductsForVerification();

if (!empty($aOrders)) {

    $aManufacturers = [];
    foreach ($aOrders as $oOrder) {
        foreach ($oOrder->getOrderProducts() as $oProduct) {
            $aManufacturers[(int) $oProduct->getField('manufacturerid')][(int) $oOrder->getField('orderid')] = $oOrder;
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
