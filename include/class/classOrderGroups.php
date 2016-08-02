<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classOrderGroup.php";

class classOrderGroups extends classOrderGroup
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['orderid', 'manufacturerid'];
        $this->sPrimaryTable = 'order_groups';
        parent::__construct($aParams);

    }
}