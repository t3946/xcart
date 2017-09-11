<?php
require "./auth.php";
global $config, $ajax_action;


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
                $oOrderDetail = new Xcart\OrderDetail(['itemid' => (int)$iRetailTrustItem]);
                if ($oOrderDetail->getOrderDetailId()) {
                    $oOrderDetail->addRetailTrust();
                    $fRetailTrustSumma += $oOrderDetail->getRetailTrustPrice();
                }
            }
        }
        if ($fRetailTrustSumma > 0) {

            $oOrder = new Xcart\Order(['orderid'=>$iOrderId]);
            $oOrder->recalculateRetailTrust();

            $oOrderNotification = new Xcart\OrderStatusNotification(['code'=>'Q']);
            if ($oOrderNotification->isEnabled()) {
                $oOrderNotification->setOrder($oOrder)->sendEmail();
            }

            $smarty->assign('value', $fRetailTrustSumma);

            $aResult['retail_price'] = func_display("currency.tpl",$smarty, false);
            print(json_encode($aResult));
        }
        break;
    case 'add_cart_group':
        if (isset($_POST['products'])) {
            $res = [];
            foreach ($_POST['products'] as $product_id => $product_info) {
                $action = "cart.php";
                $is_group = true;
                $productid = $product_id;
                $amount = $product_info['quantity'];
                include "ajax_add_to_cart.php";
                $res[] = $return;
                if (isset($return['error']) && $return['error'] == 'Y') {
                    break;
                }
            }
        }
        print(json_encode(end($res)));
        break;

}