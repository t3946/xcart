<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";
require_once $xcart_dir . "/include/class/classProduct.php";
require_once $xcart_dir . "/include/class/classOrder.php";

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
            $oOrderDetail->fillPrimaryTableValues($aOrderDetail);
            $oOrderDetails[] = $oOrderDetail;
        }
        return $oOrderDetails;
    }

    public function getAmount()
    {
        return $this->getField('amount');
    }

    public function getPrice()
    {
        return $this->getField('price');
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
                $this->oProduct = new classProduct(['productid' => $this->getField('productid')]);
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

    public function removeRetailTrust()
    {
        $this->updateFields(['retail_trust_item' => 'N', 'retail_trust_price' => 0]);
    }

    public function addRetailTrust()
    {
        if (!$this->isRetailTrustEnabled() && $this->getOrderDetailProduct()->isRetailTrustEnabled()) {
            $this->updateFields(['retail_trust_item' => 'Y', 'retail_trust_price' => $this->calculateRetailTrustPrice()]);
        }
    }

    public function calculateRetailTrustPrice()
    {
        return floatval($this->getTotalProductPrice() * (1-$this->getOrderInstance()->getPaymentMethodInstance()->getMaximumReAuthorizationMultiplier()));
    }

    private function fetchOrderInstance()
    {
        $this->oOrder = new classOrder($this->getField('orderid'));
    }

    public function getOrderInstance()
    {
        if (is_null($this->oOrder)) {
            $this->fetchOrderInstance();
        }
        return $this->oOrder;
    }
}