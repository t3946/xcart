<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";

class classHTMLShot extends classData
{
    public function __construct($iId = null)
    {
        $this->sPrimaryTable = "product_htmlshot";
        $this->sPrimaryKeyFiled = "id";

        parent::__construct($iId);
    }

    public function createHTMLShot($oProduct)
    {
        $oProduct->getImagesD();
        var_dump($oProduct);
    }
}