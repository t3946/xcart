<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";
require_once $xcart_dir . "/include/class/classProduct.php";
require_once $xcart_dir . "/include/class/classOrder.php";
require_once $xcart_dir . "/include/class/classOrderGroup.php";
require_once $xcart_dir . "/include/class/classLogs.php";

class classOrderDetail extends classData
{
    /**
     * @var classProduct
     */
    private $oProduct = null;
    /**
     * @var classOrder
     */
    private $oOrder = null;
    /**
     * @var classOrderGroup
     */
    private $oOrderGroup = null;

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['itemid'];
        $this->sPrimaryTable = 'order_details';
        parent::__construct($aParams);
    }

    /**
     * @param int $iOrderId
     * @param int $iProductId
     * @return classOrderDetail[]
     */
    public static function getOrderDetailsByOrderIdAndProductId($iOrderId, $iProductId)
    {
        $oOrderDetails = [];
        $aOrderDetails = func_query("SELECT * FROM " . self::$sql_tbl['order_details'] . " WHERE orderid = $iOrderId AND productid = $iProductId");
        foreach ($aOrderDetails as $aOrderDetail) {
            $oOrderDetail = new classOrderDetail();
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
        return $this->getField('price');
    }

    public function getOrderId()
    {
        return $this->getField('orderid');
    }

    public function getProductId()
    {
        return $this->getField('productid');
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
            if ($this->getProductId()) {
                $this->oProduct = new classProduct(['productid' => $this->getProductId()]);
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

    public function getOrderGroupInstance()
    {
        if (is_null($this->oOrderGroup))
            $this->oOrderGroup = classOrderGroup::getOrderGroupByOrderIdAndProductId($this->getOrderId(), $this->getProductId());
        return $this->oOrderGroup;
    }

    public function removeRetailTrust()
    {
        $fRetailTrust = $this->calculateRetailTrustPrice();
        $this->updateFields(['retail_trust_item' => 'N', 'retail_trust_price' => 0]);
        classLogs::_log('orders', $this->getOrderId(), 'X', sprintf('Retail Trust $%s for %s - Removed', $fRetailTrust, $this->getOrderDetailProduct()->getSKU()));
    }

    public function addRetailTrust()
    {
        if (!$this->isRetailTrustEnabled() && $this->getOrderDetailProduct()->isRetailTrustEnabled() && $this->getOrderInstance()->getPaymentMethodInstance()->getMaximumReAuthorizationMultiplier() > 1) {
            $fRetailTrust = $this->calculateRetailTrustPrice();
            $this->updateFields(['retail_trust_item' => 'Y', 'retail_trust_price' => $fRetailTrust]);
            classLogs::_log('orders', $this->getOrderId(), 'X', sprintf('Retail Trust $%s for %s - Added', $fRetailTrust, $this->getOrderDetailProduct()->getSKU()));
        }
    }

    public function calculateRetailTrustPricePerProduct()
    {
        return floatval(round(($this->getPrice() * ($this->getOrderInstance()->getPaymentMethodInstance()->getMaximumReAuthorizationMultiplier() - 1)), 2));
    }

    public function calculateRetailTrustPrice()
    {
        return floatval(round($this->calculateRetailTrustPricePerProduct() * $this->getAmount(), 2));
    }

    private function fetchOrderInstance()
    {
        $this->oOrder = classOrder::model(['orderid' => $this->getOrderId()]);
    }

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
        return $fCostToUs * $this->getAmount();
    }
}