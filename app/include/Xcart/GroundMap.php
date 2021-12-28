<?php
namespace Xcart;

/**
 * @deprecated deprecated class
 */
class GroundMap extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['zipcode'];
        $this->sPrimaryTable = 'ground_map';
        parent::__construct($aParams);
    }

}