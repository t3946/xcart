<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classOrderDetail.php";
require "./auth.php";

switch ($ajax_action) {
    case 'add_retail_trust':
        $aParams = [];
        if (!empty($params)) {
            foreach ($params as $param)
                $aParams[$param['name']][] = $param['value'];
        }
        if (!empty($aParams['retail_trust_order_id'])) {
            $iOrderId = (int) reset($aParams['retail_trust_order_id']);
        }
        if (!empty($aParams['retail_trust_item'])) {
            foreach ($aParams['retail_trust_item'] as $iRetailTrustItem) {
                $oOrderDetail = new classOrderDetail(['itemid'=> (int) $iRetailTrustItem]);
                if ($oOrderDetail->getOrderDetailId())
                    $oOrderDetail->addRetailTrust();
            }
        }
        break;

}