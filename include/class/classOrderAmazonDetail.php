<?php

global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";

class classOrderAmazonDetail extends classData
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['AmazonShipmentID', 'SKU'];
        $this->sPrimaryTable = 'order_amazon_details';
        parent::__construct($aParams);
    }
}