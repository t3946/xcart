<?php

global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";

class classCustomer extends classData
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['login'];
        $this->sPrimaryTable = 'customers';
        parent::__construct($aParams);
    }

    public function getCustomerFullName()
    {
        return $this->getField('b_title').$this->getField('b_firstname');
    }

    public function getCustomerLogin()
    {
        return $this->getField('login');
    }
}