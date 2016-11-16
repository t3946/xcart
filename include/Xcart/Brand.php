<?php

namespace Xcart;


class Brand extends Data
{
    private $iCount = 0;

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['brandid'];
        $this->sPrimaryTable = 'brands';
        parent::__construct($aParams);
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

    public function getBrandId()
    {
        return $this->getField('brandid');
    }

    public function getBrandName()
    {
        return $this->getField('brand');
    }
}