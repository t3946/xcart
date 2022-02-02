<?php

namespace Xcart;

/**
 * @deprecated deprecated class
 */
class OrderEntryMail extends Mail
{
    /**
     * @var OrderGroup
     */
    protected $oOrderGroup = null;

    private $aOrderData = null;

    public function setOrderGroup(OrderGroup $oOrderGroup)
    {
        $this->oOrderGroup = $oOrderGroup;
        return $this;
    }

    public function setOrderData($aData)
    {
        $this->aOrderData = $aData;
        return $this;
    }

}