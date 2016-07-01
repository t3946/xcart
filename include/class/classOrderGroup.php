<?php
global $xcart_dir;
require_once $xcart_dir."/include/class/classData.php";

class classOrderGroup extends classData
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['orderid', 'manufacturerid'];
        $this->sPrimaryTable = 'order_groups';
        parent::__construct($aParams);
    }

    public function getOrderInstance() {
        
    }


}