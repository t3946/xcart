<?php
namespace Xcart;

class Manufacturer extends CloneData
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

    public function getManufacturerName()
    {
        return $this->getField('manufacturer');
    }

    public function getManufacturerCode()
    {
        return $this->getField('code');
    }

}