<?php

global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";
require_once $xcart_dir . "/include/class/classSQLBuilder.php";

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
        return $this->getField('b_title') . $this->getField('b_firstname');
    }

    public function getCustomerLogin()
    {
        return $this->getField('login');
    }

    public static function getCustomersByType($sType)
    {
        $aOCustomers = [];
        $oSQL = new classSQLBuilder();
        $aCustomers = $oSQL->addSelect('*')->addFromTable('customers')->addCondition("usertype='" . $sType."'")->addCondition("status='Y'")->
        addCondition("activity='Y'")->addOrderBy('b_firstname')->Execute()->getQueryResult();
        if (!empty($aCustomers)){
            foreach ($aCustomers as $aCustomer) {
                $OCustomer = new classCustomer();
                $OCustomer->fillPrimaryTableValues($aCustomer);
                $aOCustomers[] = $OCustomer;
            }
        }
        return $aOCustomers;
    }
}