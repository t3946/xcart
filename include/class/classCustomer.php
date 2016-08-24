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
        $aCustomers = $oSQL->addSelect('*')->addFromTable('customers')->addCondition("usertype='" . $sType . "'")->addCondition("status='Y'")->
        addCondition("activity='Y'")->addOrderBy('b_firstname')->Execute()->getQueryResult();
        if (!empty($aCustomers)) {
            foreach ($aCustomers as $aCustomer) {
                $OCustomer = new classCustomer();
                $OCustomer->fillPrimaryTableValues($aCustomer);
                $aOCustomers[] = $OCustomer;
            }
        }
        return $aOCustomers;
    }

    public function isCustomerUseSecureData()
    {
        $sLogin = $this->getCustomerLogin();
        if (empty($sLogin)) return false;
        $aCustomers = $this->oSQL->init()->addSelect('*')->addFromTable('secure_data_users')->addCondition("login='" . $sLogin . "'")->Execute()->getQueryResult();
        return !empty($aCustomers);
    }

    public function getCustomerSecureData()
    {
        $aCustomerData = [];
        $sLogin = $this->getCustomerLogin();
        if (empty($sLogin)) return false;
        $aCustomersData = $this->oSQL->init()->addSelect('xs.*')->addFromTable('secure_data', 'xs')->addInnerJoin('secure_data_users', 'sd', 'sd.secure_data_id = xs.id')->
        addCondition("sd.login='" . $sLogin . "'")->addOrderBy('xs.orderby')->Execute()->getQueryResult();
        if (!empty($aCustomersData)) {
            foreach ($aCustomersData as &$aCustomerData) {
                $aCustomerData['data'] = stripslashes(text_decrypt($aCustomerData['data']));
            }
        }
        return ($aCustomersData);
    }

    public function getAmazonLastVerifyDate()
    {
        $oDate = null;
        $aDate = $this->oSQL->init()->addSelect('value')->addFromTable('external_verification_products')->addCondition("login='" . $this->getCustomerLogin() . "'")->
        addCondition("action IN('match','not_match','not_sure')")->addOrderBy('value DESC')->setLimit(1)->Execute()->getQueryResult();
        if (!empty($aDate)) {
            $aD = reset($aDate);
            $oDate = new DateTime();
            $oDate->setTimestamp($aD['value']);
        }
        return $oDate;
    }

    public function getAmazonProductProcessedCount()
    {
        $aCount = $this->oSQL->init()->addSelect('count(1)', 'product_count')->addFromTable('external_verification_products')->addCondition("login='" . $this->getCustomerLogin() . "'")->
        addCondition("action IN('match','not_match','not_sure')")->Execute()->getQueryResult();
        $aC = reset($aCount);
        return $aC['product_count'];
    }

    public function getAmazonProductNotSureCount()
    {
        $aCount = $this->oSQL->init()->addSelect('count(1)', 'product_count')->addFromTable('external_verification_products')->addCondition("login='" . $this->getCustomerLogin() . "'")->
        addCondition("action IN('not_sure')")->Execute()->getQueryResult();
        $aC = reset($aCount);
        return $aC['product_count'];
    }
}