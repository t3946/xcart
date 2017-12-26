<?php
namespace Xcart;

class OrderDetail extends Data
{
    /**
     * @var Product
     */
    private $oProduct = null;
    /**
     * @var Order
     */
    private $oOrder = null;

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['itemid'];
        $this->sPrimaryTable = 'order_details';
        parent::__construct($aParams);
    }

    /**
     * @param int $iOrderId
     * @param int $iProductId
     * @return OrderDetail[]
     */
    public static function getOrderDetailsByOrderIdAndProductId($iOrderId, $iProductId)
    {
        $oOrderDetails = [];
        $aOrderDetails = func_query("SELECT * FROM " . self::$sql_tbl['order_details'] . " WHERE orderid = $iOrderId AND productid = $iProductId");
        foreach ($aOrderDetails as $aOrderDetail) {
            $oOrderDetail = new OrderDetail();
            $oOrderDetail->fill($aOrderDetail);
            $oOrderDetails[] = $oOrderDetail;
        }
        return $oOrderDetails;
    }

    public function getAmount()
    {
        return intval($this->getField('amount'));
    }

    public function getPrice()
    {
//        return $this->getExtraPrice() ?: $this->getField('price');
        return $this->getField('price');
    }

//    public function getOriginalPrice()
//    {
//        return $this->getExtraOriginalPrice() ?: $this->getPrice();
//    }

//    public function getExtraPrice()
//    {
//        $extra_data = $this->getExtraData();
//
//        if (!empty($extra_data['display']) && !empty($extra_data['display']['discounted_price'])) {
//            return floatval($extra_data['display']['discounted_price']) / $this->getAmount();
//        }
//
//        return 0;
//    }
//    public function getExtraOriginalPrice()
//    {
//        $extra_data = $this->getExtraData();
//
//        if (!empty($extra_data['original_price'])) {
//            return floatval($extra_data['original_price']);
//        }
//
//        return 0;
//    }

    public function getOrderId()
    {
        return $this->getField('orderid');
    }

    public function getOrderDetailId()
    {
        return $this->getField('itemid');
    }

    public function isRetailTrustEnabled()
    {
        return ($this->getField('retail_trust_item') == 'Y') ? true : false;
    }

    public function getOrderDetailProduct()
    {
        if (is_null($this->oProduct)) {
            if ($this->getField('productid')) {
                $this->oProduct = new Product(['productid' => $this->getField('productid')]);
            }
        }
        return $this->oProduct;
    }

    public function getRetailTrustPrice()
    {
        return floatval($this->getField('retail_trust_price'));
    }

    public function getRetailTrustGross()
    {
        return floatval($this->getRetailTrustPrice());
    }

    public function getTotalProductPrice()
    {
        return floatval($this->getPrice() * $this->getAmount());
    }

    public function getExtraData()
    {
        return unserialize($this->getField('extra_data'));
    }

    public function getProductHST()
    {
        $aExtraData = unserialize($this->getField('extra_data'));
        return floatval($aExtraData['taxes']['HST']['tax_value']);
    }

    public function getProductPST()
    {
        $aExtraData = unserialize($this->getField('extra_data'));
        return floatval(floatval($aExtraData['taxes']['GST']['tax_value']) + floatval($aExtraData['taxes']['PST']['tax_value']));
    }

    public function removeRetailTrust()
    {
        $fRetailTrust = $this->calculateRetailTrustPrice();
        $this->updateFields(['retail_trust_item' => 'N', 'retail_trust_price' => 0]);
        Logs::_log('orders', $this->getOrderId(), 'X', sprintf('Retail Trust $%s for %s - Removed', $fRetailTrust, $this->getOrderDetailProduct()->getSKU()));
    }

    public function addRetailTrust()
    {
        if (!$this->isRetailTrustEnabled() && $this->getOrderDetailProduct()->isRetailTrustEnabled() && $this->getOrderInstance()->getPaymentMethodInstance()->getMaximumReAuthorizationMultiplier() > 1) {
            $fRetailTrust = $this->calculateRetailTrustPrice();
            $this->updateFields(['retail_trust_item' => 'Y', 'retail_trust_price' => $fRetailTrust]);
            Logs::_log('orders', $this->getOrderId(), 'X', sprintf('Retail Trust $%s for %s - Added', $fRetailTrust, $this->getOrderDetailProduct()->getSKU()));
        }
    }

    public function calculateRetailTrustPricePerProduct()
    {
        return floatval(round(($this->getPrice() * ($this->getOrderInstance()->getPaymentMethodInstance()->getMaximumReAuthorizationMultiplier() - 1)), 2));
    }

    public function calculateRetailTrustPrice()
    {
        return floatval($this->getTotalProductPrice() * ($this->getOrderInstance()->getPaymentMethodInstance()->getMaximumReAuthorizationMultiplier() - 1));
    }

    private function fetchOrderInstance()
    {
        $this->oOrder = Order::model(['orderid' => $this->getField('orderid')]);
    }

    /**
     * @return Order
     */
    public function getOrderInstance()
    {
        if (is_null($this->oOrder)) {
            $this->fetchOrderInstance();
        }
        return $this->oOrder;
    }

    public function getCostToUs()
    {
        $fCostToUs = floatval($this->getField('item_cost_to_us'));
        if (!$fCostToUs) {
            $fCostToUs = $this->getOrderDetailProduct()->getProductCostToUs();
        }
        return floatval($fCostToUs * $this->getAmount());
    }
}