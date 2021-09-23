<?php

namespace Xcart;


class FilterValue extends Data
{
    private $iCount = 0;

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['fv_id'];
        $this->sPrimaryTable = 'cidev_filter_values';
        parent::__construct($aParams);
    }

    public function getFilterValueId()
    {
        return $this->getField('fv_id');
    }

    public function getFilterValueName()
    {
        return $this->getField('fv_name');
    }

    public function setCount($iCnt)
    {
        $this->iCount = $iCnt;
        return $this;
    }

    public function getCount()
    {
        return $this->iCount;
    }
}