<?php
require "./auth.php";
global $xcart_dir, $config;
require_once $xcart_dir . '/include/class/classOrderDetail.php';
require_once $xcart_dir.'/include/class/classMail.php';
require_once $xcart_dir.'/include/class/classOrderStatusNotification.php';
require_once $xcart_dir.'/include/class/classOrder.php';


switch ($ajax_action) {
    case 'add_retail_trust':
        $aResult = $aParams = [];
        $fRetailTrustSumma = 0;
        if (!empty($params)) {
            foreach ($params as $param)
                $aParams[$param['name']][] = $param['value'];
        }
        if (!empty($aParams['retail_trust_order_id'])) {
            $iOrderId = (int)reset($aParams['retail_trust_order_id']);
        }
        if (!empty($aParams['retail_trust_item'])) {

            foreach ($aParams['retail_trust_item'] as $iRetailTrustItem) {
                $oOrderDetail = new classOrderDetail(['itemid' => (int)$iRetailTrustItem]);
                if ($oOrderDetail->getOrderDetailId()) {
                    $oOrderDetail->addRetailTrust();
                    $fRetailTrustSumma += $oOrderDetail->getRetailTrustPrice();
                }
            }
        }
        if ($fRetailTrustSumma > 0) {

            $oOrder = new classOrder(['orderid'=>$iOrderId]);
            $oOrder->recalculateRetailTrust();

            $oOrderNotification = new classOrderStatusNotification(['code'=>'Q']);
            if ($oOrderNotification->isEnabled()) {
                $oOrderNotification->prepareMail($oOrder)->sendEmail();
            }

            $smarty->assign('value', $fRetailTrustSumma);

            $aResult['retail_price'] = func_display("currency.tpl",$smarty, false);
            print(json_encode($aResult));
        }
        break;

}