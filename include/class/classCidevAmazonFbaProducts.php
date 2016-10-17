<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";

class classCidevAmazonFbaProducts extends classData
{
    public function __construct($iId = null)
    {
        $this->sPrimaryTable = 'cidev_amazon_fba_products';
        $this->aPrimaryKeys = ['id'];

        parent::__construct($iId);
    }
}