<?php
namespace Xcart;

class OrderGroupMemo extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['orderid', 'manufacturerid', 'memo_number'];
        $this->sPrimaryTable = 'order_group_memos';
        parent::__construct($aParams);
    }
}