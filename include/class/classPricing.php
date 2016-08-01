<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";

class classPricing extends classData
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['priceid', 'quantity'];
        $this->sPrimaryTable = 'pricing';
        parent::__construct($aParams);

    }
}