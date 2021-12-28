<?php
namespace Xcart;

/**
 * @deprecated deprecated class
 */
class Pricing extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['priceid', 'quantity'];
        $this->sPrimaryTable = 'pricing';
        parent::__construct($aParams);

    }

    public function getQuantity()
    {
        return $this->getField('quantity');
    }

    public function getPrice()
    {
        return $this->getField('price');
    }

    public function getPriceTableValues()
    {
        return $this->aPrimaryTableValue;
    }
}