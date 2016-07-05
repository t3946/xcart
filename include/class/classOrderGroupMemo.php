<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";

class classOrderGroupMemo extends classData
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['orderid', 'manufacturerid', 'memo_number'];
        $this->sPrimaryTable = 'order_group_memos';
        parent::__construct($aParams);
    }
}