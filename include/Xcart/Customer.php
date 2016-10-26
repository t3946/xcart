<?php
namespace Xcart;

class Customer extends Data
{
    const LINK_TO_MODIFY = '/admin/user_modify.php?user=%s&usertype=%s';

    private $iAmazonBatchesCompletedCount = null;
    private $iAmazonBatchesPaidCount = null;
    private $iAmazonBatchesNotSureCount = null;
    private $iAmazonBatchesInProgressCount = null;
    private $iAmazonBatchesProcessedCount = null;

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

    public function getCustomerPassword()
    {
        return $this->getField('password');
    }

    public function getCustomerPasswordDecripted()
    {
        return text_decrypt($this->getField('password'));
    }

    public function getCustomerURL()
    {
        return $this->getField('url');
    }

    public function getCustomerUserType()
    {
        return $this->getField('usertype');
    }

    public function getCustomerModifyLink()
    {
        return sprintf(self::LINK_TO_MODIFY,$this->getCustomerLogin(),$this->getCustomerUserType());
    }

    public static function getCustomersByType($sType, $active = 'Y')
    {
        $aOCustomers = [];
        $oSQL = classSQLBuilder::getInstance()->addSelect('*')->addFromTable('customers')->addCondition("usertype='" . $sType . "'");
        if ($active != 'all') {
            $oSQL->addCondition("status='$active'")->addCondition("activity='$active'");
        }
        $aCustomers = $oSQL->addOrderBy('firstname')->Execute()->getQueryResult();
        if (!empty($aCustomers)) {
            foreach ($aCustomers as $aCustomer) {
                $OCustomer = new classCustomer();
                $OCustomer->fill($aCustomer);
                $aOCustomers[] = $OCustomer;
            }
        }
        return $aOCustomers;
    }

    public function isCustomerUseSecureData()
    {
        $sLogin = $this->getCustomerLogin();
        if (empty($sLogin)) return false;
        $aCustomers = classSQLBuilder::getInstance()->addSelect('*')->addFromTable('secure_data_users')->addCondition("login='" . $sLogin . "'")->Execute()->getQueryResult();
        return !empty($aCustomers);
    }

    public function getCustomerSecureData()
    {
        $aCustomerData = [];
        $sLogin = $this->getCustomerLogin();
        if (empty($sLogin)) return false;
        $aCustomersData = classSQLBuilder::getInstance()->addSelect('xs.*')->addFromTable('secure_data', 'xs')->addInnerJoin('secure_data_users', 'sd', 'sd.secure_data_id = xs.id')->
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
        $aDate = classSQLBuilder::getInstance()->addSelect('value')->addFromTable('external_verification_products')->addCondition("login='" . $this->getCustomerLogin() . "'")->
        addCondition('action IN("'.implode('","',classExternalVerificationBatch::$aProductStatuses['processed']).'")')->addOrderBy('value DESC')->setLimit(1)->Execute()->getQueryResult();
        if (!empty($aDate)) {
            $aD = reset($aDate);
            $oDate = new DateTime();
            $oDate->setTimestamp($aD['value']);
        }
        return $oDate;
    }

    public function getAmazonProductProcessedCount()
    {
        if (is_null($this->iAmazonBatchesProcessedCount)) {
            $aCount = classSQLBuilder::getInstance()->addSelect('count(1)', 'product_count')->addFromTable('external_verification_products')->addCondition("login='" . $this->getCustomerLogin() . "'")->
            addCondition('action IN("' . implode('","', classExternalVerificationBatch::$aProductStatuses['processed']) . '")')->Execute()->getQueryResult();
            $aC = reset($aCount);
            $this->iAmazonBatchesProcessedCount = $aC['product_count'];
        }
        return $this->iAmazonBatchesProcessedCount;
    }

    public function getAmazonProductNotSureCount()
    {
        if (is_null($this->iAmazonBatchesNotSureCount)) {
            $aCount = classSQLBuilder::getInstance()->addSelect('count(1)', 'product_count')->addFromTable('external_verification_products')->addCondition("login='" . $this->getCustomerLogin() . "'")->
            addCondition("action IN('not_sure')")->Execute()->getQueryResult();
            $aC = reset($aCount);
            return $aC['product_count'];
        }
        return $this->iAmazonBatchesNotSureCount;
    }

    public function getAmazonBatchesInProgressCount()
    {
        if (is_null($this->iAmazonBatchesInProgressCount)) {
            $aCount = classSQLBuilder::getInstance()->addSelect('count(1)', 'batch_count')->addFromTable('external_verification_batches')->addCondition("login='" . $this->getCustomerLogin() . "'")->
            addCondition("batch_status IN('In progress')")->Execute()->getQueryResult();
            $aC = reset($aCount);
            $this->iAmazonBatchesInProgressCount = $aC['batch_count'];
        }
        return $this->iAmazonBatchesInProgressCount;
    }

    public function getAmazonBatches($sStatus = null)
    {
        $aB = [];
        $oSQL = classSQLBuilder::getInstance()->addSelect('*')->addFromTable('external_verification_batches')->addCondition("login='" . $this->getCustomerLogin() . "'");
        if (!empty($sStatus) && in_array($sStatus,['in progress','completed','paid'])) $oSQL->addCondition("batch_status IN('$sStatus')");
        $aBatches = $oSQL->Execute()->getQueryResult();
        if (!empty($aBatches)) {
            foreach ($aBatches as $aBatch) {
                $oBatch = new classExternalVerificationBatch();
                $oBatch->fill($aBatch);
                $aB[] = $oBatch;
            }
        }
        return $aB;
    }

    public function getAmazonBatchesCompletedCount()
    {
        if (is_null($this->iAmazonBatchesCompletedCount)){
            $aCount = classSQLBuilder::getInstance()->addSelect('count(1)', 'batch_count')->addFromTable('external_verification_batches')->addCondition("login='" . $this->getCustomerLogin() . "'")->
            addCondition("batch_status IN('Completed')")->Execute()->getQueryResult();
            $aC = reset($aCount);
            $this->iAmazonBatchesCompletedCount = $aC['batch_count'];
        }

        return $this->iAmazonBatchesCompletedCount;
    }


    public function getAmazonBatchesPaidCount()
    {
        if (is_null($this->iAmazonBatchesPaidCount)) {
            $aCount = classSQLBuilder::getInstance()->addSelect('count(1)', 'batch_count')->addFromTable('external_verification_batches')->addCondition("login='" . $this->getCustomerLogin() . "'")->
            addCondition("batch_status IN('Paid')")->Execute()->getQueryResult();
            $aC = reset($aCount);
            $this->iAmazonBatchesPaidCount = $aC['batch_count'];
        }
        return $this->iAmazonBatchesPaidCount;
    }

    public function getAmazonBatchesAverageSpeed()
    {
        $aCount = classSQLBuilder::getInstance()->addSelect('AVG(batch_product_speed)', 'batch_speed')->addFromTable('external_verification_batches')->addCondition("login='" . $this->getCustomerLogin() . "'")->
        addCondition('batch_product_speed > 0')->Execute()->getQueryResult();
        $aC = reset($aCount);
        return round(floatval($aC['batch_speed']));
    }

    public function getAmazonBatchesMaxNumber()
    {
        $aCount = classSQLBuilder::getInstance()->addSelect('max(batch_number)', 'max_number')->addFromTable('external_verification_batches')->addCondition("login='" . $this->getCustomerLogin() . "'")->
        Execute()->getQueryResult();
        $aC = reset($aCount);
        return $aC['max_number'];
    }

    public function sortByAmazonCompletedBatchesDesc($a, $b)
    {
        return $a->getAmazonBatchesCompletedCount() < $b->getAmazonBatchesCompletedCount();
    }

    public function isAmazonAccountSuspended()
    {
        $bResult = false;
        $aBatches = $this->getAmazonBatches();
        foreach ($aBatches as $oBatch) {
            if ($oBatch->isTestFailed()) {
                $bResult = true;
                break;
            }
        }
        return $bResult;
    }
    public function unblockAmazonAccount()
    {
        $aBatches = $this->getAmazonBatches();
        foreach ($aBatches as $oBatch) {
            if ($oBatch->isTestFailed()) {
                $oBatch->updateFields(['test_failed'=>'N', 'is_test'=>'U']);
            }
        }
    }
}