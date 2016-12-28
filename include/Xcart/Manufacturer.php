<?php
namespace Xcart;

class Manufacturer extends Data
{
    const ADMIN_MANUFACTURER_MODIFY_URL = '/admin/manufacturers.php?manufacturerid=%d';

    private $iAmazonLeadTime = null;

    public function __construct($iId = null)
    {
        $this->sPrimaryTable = 'manufacturers';
        $this->aPrimaryKeys = ['manufacturerid'];

        parent::__construct($iId);
    }

    public function getManufacturerModifyURL()
    {
        return sprintf(self::ADMIN_MANUFACTURER_MODIFY_URL, $this->getField($this->getManufacturerId()));
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
        if (is_null($this->iAmazonLeadTime)) {
            $aResult = SQLBuilder::getInstance()->
            addSelect("cidev_get_amazon_fulfillment_latency('" . $this->getManufacturerCode() . "')", 'aleadtime')->
            addFromTable('manufacturers')->
            addCondition('manufacturerid='.$this->getManufacturerId())->
            query_first()->getQueryResult();
            $this->iAmazonLeadTime = $aResult['aleadtime'];
        }
        return $this->iAmazonLeadTime;
    }

}