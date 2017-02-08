<?php
namespace Xcart;

class Manufacturer extends Data
{
    const ADMIN_MANUFACTURER_MODIFY_URL = '/admin/manufacturers.php?manufacturerid=%d';


    public function __construct($iId = null)
    {
        $this->sPrimaryTable = 'manufacturers';
        $this->aPrimaryKeys = ['manufacturerid'];

        parent::__construct($iId);
    }

    public function getAdminUrl()
    {
        return sprintf(self::ADMIN_MANUFACTURER_MODIFY_URL, $this->getManufacturerId());
    }

    public function getManufacturerName()
    {
        return $this->getField('manufacturer');
    }

    public function getManufacturerCode()
    {
        return $this->getField('code');
    }

    public function getManufacturerId()
    {
        return $this->getField('manufacturerid');
    }

    public function getAmazonLeadtimetoship()
    {
        return $this->getField('amazon_leadtime_to_ship');
    }

}