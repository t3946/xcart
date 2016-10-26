<?php
namespace Xcart;

class OrderAmazonDetails extends Data
{
    const TYPE_REFUND = 'Refund';
    const TYPE_FEE = 'Fee';
    /**
     * @var classOrderAmazonDetail[]
     */
    private $aAmazonDetails = [];

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['AmazonShipmentID', 'SKU'];
        $this->sPrimaryTable = 'order_amazon_details';
        parent::__construct($aParams);
    }

    public function countOrderAmazonDetails()
    {
        $count = 0;
        if (!empty($this->aAmazonDetails))
            $count = count($this->aAmazonDetails);
        return $count;
    }

    /**
     * @return bool
     */
    public function isRefundExists()
    {
        if (!empty($this->aAmazonDetails)) {
            foreach ($this->aAmazonDetails as $oAmazonDetail) {
                if ($oAmazonDetail->getField('type') == self::TYPE_REFUND) return true;
            }
        }
        return false;
    }

    public function getOrderAmazonDetails($aParams = [])
    {
        $this->aAmazonDetails = classOrderAmazonDetail::model()->findAll(
            classSQLBuilder::getInstance()->
                addCondition('orderid = '. $aParams['orderid'])->
                addCondition('manufacturerid = '. $aParams['manufacturerid']));
        return $this;
    }

    public function getOrderAmazonRefund()
    {
        $fRes = 0;
        if (!empty($this->aAmazonDetails)) {
            foreach ($this->aAmazonDetails as $oAmazonDetail) {
                $fRes += floatval($oAmazonDetail->getField('Refund'));
            }
        }
        return $fRes;
    }

    public function getOrderAmazonShipping()
    {
        $fRes = 0;
        if (!empty($this->aAmazonDetails)) {
            foreach ($this->aAmazonDetails as $oAmazonDetail) {
                $fRes += floatval($oAmazonDetail->getField('Shipping'));
            }
        }
        return $fRes;
    }

    public function getOrderAmazonPrincipalRefund()
    {
        $fRes = 0;
        if (!empty($this->aAmazonDetails)) {
            foreach ($this->aAmazonDetails as $oAmazonDetail) {
                $fRes += floatval($oAmazonDetail->getField('PrincipalRefund'));
            }
        }
        return $fRes;
    }

    public function getOrderAmazonShippingRefund()
    {
        $fRes = 0;
        if (!empty($this->aAmazonDetails)) {
            foreach ($this->aAmazonDetails as $oAmazonDetail) {
                $fRes += floatval($oAmazonDetail->getField('ShippingRefund'));
            }
        }
        return $fRes;
    }

    public function getOrderAmazonPrincipal()
    {
        $fRes = 0;
        if (!empty($this->aAmazonDetails)) {
            foreach ($this->aAmazonDetails as $oAmazonDetail) {
                $fRes += floatval($oAmazonDetail->getField('Principal'));
            }
        }
        return $fRes;
    }

    public function getOrderAmazonCommission()
    {
        $fRes = 0;
        if (!empty($this->aAmazonDetails)) {
            foreach ($this->aAmazonDetails as $oAmazonDetail) {
                $fRes += floatval($oAmazonDetail->getField('AmazonCommission'));
            }
        }
        return $fRes;
    }

    public function getOrderAmazonFBAPerOrderFulfillmentFee()
    {
        $fRes = 0;
        if (!empty($this->aAmazonDetails)) {
            foreach ($this->aAmazonDetails as $oAmazonDetail) {
                $fRes += floatval($oAmazonDetail->getField('FBAPerOrderFulfillmentFee'));
            }
        }
        return $fRes;
    }

    public function getOrderAmazonFBAPerUnitFulfillmentFee()
    {
        $fRes = 0;
        if (!empty($this->aAmazonDetails)) {
            foreach ($this->aAmazonDetails as $oAmazonDetail) {
                $fRes += floatval($oAmazonDetail->getField('FBAPerUnitFulfillmentFee'));
            }
        }
        return $fRes;
    }

    public function getOrderAmazonFBATransportationFee()
    {
        $fRes = 0;
        if (!empty($this->aAmazonDetails)) {
            foreach ($this->aAmazonDetails as $oAmazonDetail) {
                $fRes += floatval($oAmazonDetail->getField('FBATransportationFee'));
            }
        }
        return $fRes;
    }

    public function getOrderAmazonFBAWeightBasedFee()
    {
        $fRes = 0;
        if (!empty($this->aAmazonDetails)) {
            foreach ($this->aAmazonDetails as $oAmazonDetail) {
                $fRes += floatval($oAmazonDetail->getField('FBAWeightBasedFee'));
            }
        }
        return $fRes;
    }
}