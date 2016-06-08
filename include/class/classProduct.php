<?php
global $xcart_dir;
require_once $xcart_dir."/include/class/classCloneData.php";
require_once $xcart_dir."/include/class/classManufacturers.php";

class classProduct extends classCloneData
{
    private $oManufacturer;

    public function __construct($iId = null)
    {
        $this->sPrimaryTable = "products";
        $this->sPrimaryKeyFiled = "productid";

        parent::__construct($iId);
    }

    public function getManfacturerClass($iManufacurerId = null) {
        if (!is_null($iManufacurerId))
            return new classManufacturer($iManufacurerId);
        else {
            if (is_null($this->oManufacturer))
            {
                $this->oManufacturer = new classManufacturer($this->aPrimaryTableValue['manufacturerid']);
            }
            return $this->oManufacturer;
        }
    }
}