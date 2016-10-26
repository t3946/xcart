<?php
namespace Xcart;

class CidevAmazonFbaProducts extends Data
{
    public function __construct($iId = null)
    {
        $this->sPrimaryTable = 'cidev_amazon_fba_products';
        $this->aPrimaryKeys = ['id'];

        parent::__construct($iId);
    }

    public function getReportPeriod()
    {
        $oDate =  new DateTime();
        $oDate->setTimestamp($this->getField('report_date'))->modify('first day of this month');
        return $oDate;
    }
}