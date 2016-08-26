<?php
global $xcart_dir;
require_once $xcart_dir."/include/class/classOrder.php";

class classOrders extends classOrder
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
     * @return classOrder[]
     */
    public function getOrdersWithProductsForVerification() {
        $this->aOrders = [];
        $aOrders = func_query("SELECT * FROM ".self::$sql_tbl['orders']." WHERE vn_status != '".self::ORDER_VERIFICATION_STATUS_PRODUCT_VERIFIED."' LIMIT 50");
        if (!empty($aOrders) && is_array($aOrders)) {
            foreach ($aOrders as $aOrder)
                $this->aOrders[] = new classOrder($aOrder);
        }
        return $this->aOrders;
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
            $aResult[] = classOrder::getInstance($aOrder);
        }
        return $aResult;
    }

}