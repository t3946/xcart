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
}