<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";

class classOrderTransaction extends classData
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['id'];
        $this->sPrimaryTable = 'order_transactions';
        parent::__construct($aParams);

    }


}