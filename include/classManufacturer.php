<?php
global $xcart_dir;
require_once $xcart_dir."/include/xcart/CloneData.php";
use Xcart\CloneData;

class classManufacturer extends CloneData
{
    const ADMIN_MANUFACTURER_MODIFY_URL = '/admin/manufacturers.php?manufacturerid=%d';

    public function __construct($iId = null)
    {
        $this->sPrimaryTable = "manufacturers";
        $this->sPrimaryKeyFiled = "manufacturerid";

        parent::__construct($iId);
    }

    public function getManufacturerModifyURL()
    {
        return sprintf(self::ADMIN_MANUFACTURER_MODIFY_URL, $this->getField($this->sPrimaryKeyFiled));
    }
}