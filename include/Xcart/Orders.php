<?php
namespace Xcart;

class Orders extends Order
{
    private $aOrders = [];

    public function __construct($aOrderData = null)
    {
        $this->sPrimaryTable = "orders";
        $this->sPrimaryKeyFiled = "orderid";

        parent::__construct($aOrderData);
    }

    public static function getInstance($iId = null){
        return new self($iId);
    }

    /**
     * @return Order[]
     */
    public function getOrdersWithProductsForVerification() {
        return Order::model()->findAll(SQLBuilder::getInstance()->addCondition("vn_status != '".Order::ORDER_VERIFICATION_STATUS_PRODUCT_VERIFIED."'"));
    }

    /**
     * @return Order[]
     */
    public function getOrdersByProductId($iProduct) {
        $aResult = [];
        $aOrders = func_query("SELECT xo.*
                                FROM xcart_orders xo INNER JOIN xcart_order_details USING (orderid)
                                WHERE productid = $iProduct AND vn_status != '".self::ORDER_VERIFICATION_STATUS_PRODUCT_VERIFIED."'");
        foreach ($aOrders as $aOrder) {
            $aResult[] = Order::model()->fill($aOrder);
        }
        return $aResult;
    }

}