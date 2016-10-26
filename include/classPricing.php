<?php
global $xcart_dir;
require_once $xcart_dir."/include/xcart/Data.php";
use Xcart\Data;

class classPricing extends Data
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