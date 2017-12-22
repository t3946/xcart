<?php
namespace Xcart;

class OrderRefundGroup extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['orderid', 'manufacturerid'];
        $this->sPrimaryTable = 'refund_groups';
        parent::__construct($aParams);
    }
}