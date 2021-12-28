<?php
namespace Xcart;

/**
 * @deprecated deprecated class
 */
class OrderGroups extends OrderGroup
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['orderid', 'manufacturerid'];
        $this->sPrimaryTable = 'order_groups';
        parent::__construct($aParams);

    }
}