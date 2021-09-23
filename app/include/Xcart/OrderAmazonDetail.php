<?php
namespace Xcart;

class OrderAmazonDetail extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['AmazonShipmentID', 'SKU'];
        $this->sPrimaryTable = 'order_amazon_details';
        parent::__construct($aParams);
    }
}