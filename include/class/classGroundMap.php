<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";

class classGroundMap extends classData
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['zipcode'];
        $this->sPrimaryTable = 'ground_map';
        parent::__construct($aParams);
    }

}