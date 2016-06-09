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

    /**
     * @return classOrder[]
     */
    public function getOrdersWithProductsForVerification() {
        $this->aOrders = [];
        $aOrders = func_query("SELECT * FROM ".self::$sql_tbl['orders']." WHERE vn_status != '".self::ORDER_STATUS_PRODUCT_VERIFIED."'");
        if (!empty($aOrders) && is_array($aOrders)) {
            foreach ($aOrders as $aOrder)
                $this->aOrders[] = new classOrder($aOrder);
        }
        return $this->aOrders;
    }

}