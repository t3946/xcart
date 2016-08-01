<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";

class classPricing extends classData
{
    public function __construct($iId = null)
    {
        $this->sPrimaryTable = "pricing";
        $this->sPrimaryKeyFiled = "id";

        parent::__construct($iId);
    }
}