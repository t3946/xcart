<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";

class classProductImage extends classData
{
    public function __construct($type, $iId = null)
    {
        $this->sPrimaryTable = "images_".$type;
        $this->sPrimaryKeyFiled = "imageid";

        parent::__construct($iId);
    }
}