<?php
global $xcart_dir;
require_once $xcart_dir."/include/class/classOrder.php";

class classOrders extends classOrder
{
    private $aOrders = [];

    public function __construct($aOrderData = [])
    {
        $this->aPrimaryKeys = ['orderid'];
        $this->sPrimaryTable = 'orders';

        parent::__construct($aOrderData);
    }

    public static function getInstance($iId = null){
        return new self($iId);
    }

    /**
     * @return classOrder[]
     */
    public function getOrdersWithProductsForVerification() {
        return classOrder::model()->findAll(classSQLBuilder::getInstance()->addCondition("vn_status != '".classOrder::ORDER_VERIFICATION_STATUS_PRODUCT_VERIFIED."'"));
    }

    /**
     * @return classOrder[]
     */
    public function getOrdersByProductId($iProduct) {
        $aResult = [];
        $aOrders = func_query("SELECT xo.*
                                FROM xcart_orders xo INNER JOIN xcart_order_details USING (orderid)
                                WHERE productid = $iProduct AND vn_status != '".self::ORDER_VERIFICATION_STATUS_PRODUCT_VERIFIED."'");
        foreach ($aOrders as $aOrder) {
            $aResult[] = classOrder::model()->fill($aOrder);
        }
        return $aResult;
    }

}