<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";

class classOrderRefundGroup extends classData
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['orderid', 'manufacturerid'];
        $this->sPrimaryTable = 'refund_groups';
        parent::__construct($aParams);
    }
}