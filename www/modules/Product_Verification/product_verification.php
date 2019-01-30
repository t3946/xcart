<?php

use Modules\Amazon\Helpers\AmazonVerificationHelper;
use Modules\Order\Models\OrderModel;
use Modules\Goods\Models\VerificationStatusModel;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;
use Xcart\App\Pagination\Pagination;

if ( !defined('XCART_START') ) { header("Location: ../"); die("Access denied"); }

$orders = AmazonVerificationHelper::getAmazonVerifyOrders();
$pager = new Pagination( $orders->getQuerySet(), ['pageSize' => 50], new QuerySetDataSource());

if ($orders = $pager->paginate()) {

    /** @var OrderModel[] $aManufacturers */
    $aManufacturers = [];
    foreach ($orders as $oOrder) {
        if ($aOrderProducts = $oOrder->getProducts())
        foreach ($aOrderProducts as $oProduct) {
            if ($oProduct->forsale === 'Y') {
                $aManufacturers[$oProduct->manufacturerid][$oOrder->orderid] = $oOrder;
            }
        }
    }

    foreach ($aManufacturers as $iManufacturerId => $aManufacturer) {
        $aProducts = [];
        foreach ($aManufacturer as $oOrderManufacturer) {
            $aOrderManufacturerProducts = $oOrderManufacturer->getProducts();
            if (!empty($aOrderManufacturerProducts))
            foreach ($aOrderManufacturerProducts as $oProduct) {
                if ($oProduct->forsale === 'Y') {
                    if ($oProduct->manufacturerid == $iManufacturerId) {
                        if (!\in_array($oProduct->productid, $aProducts)) {
                            $aProducts[] = $oProduct->productid;
                        }
                    }
                }
            }
        }
    }

    $aVerifyStatuses = VerificationStatusModel::objects()->all();

    $smarty->assign('aVerifyStatuses',$aVerifyStatuses);
    $smarty->assign('aManufacturers',$aManufacturers);
    $smarty->assign('pager', $pager);

}

$smarty->assign("main","product_verification");
