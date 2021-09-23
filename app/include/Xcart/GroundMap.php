<?php
namespace Xcart;

class GroundMap extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['zipcode'];
        $this->sPrimaryTable = 'ground_map';
        parent::__construct($aParams);
    }

}