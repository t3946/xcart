<?php
global $xcart_dir;
require_once $xcart_dir."/include/class/classCloneData.php";

class classOrderDetail extends classCloneData
{
    public function __construct($aOrderDetailsData = null)
    {
        $this->sPrimaryTable = "order_details";
        $this->sPrimaryKeyFiled = "itemid";

        parent::__construct($aOrderDetailsData);
    }

    /**
     * @param int $iOrderId
     * @param int $iProductId
     * @return classOrderDetail[]
     */
    public static function getOrderDetailsByOrderIdAndProductId($iOrderId, $iProductId){
        $oOrderDetail = [];
        $aOrderDetails = func_query("SELECT * FROM " . self::$sql_tbl['order_details'] . " WHERE orderid = $iOrderId AND productid = $iProductId");
        foreach ($aOrderDetails as $aOrderDetail) {
            $oOrderDetail[] = new classOrderDetail($aOrderDetail);
        }
        return $oOrderDetail;
    }

    public function getAmount() {
        return $this->getField('amount');
    }
}