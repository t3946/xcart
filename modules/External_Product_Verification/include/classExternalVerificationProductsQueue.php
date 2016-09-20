<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classLogs.php";
require_once $xcart_dir . "/include/class/classProduct.php";
require_once $xcart_dir . "/include/class/classSQLBuilder.php";

class classExternalVerificationProductsQueue extends classData
{
    const PRODUCT_QUEUE_STATUS_IN_PROGRESS = 'in progress';
    const PRODUCT_QUEUE_STATUS_VERIFIED = 'verified';
    const PRODUCT_QUEUE_STATUS_IN_ETALON_MATCH = 'etalon_match';
    const PRODUCT_QUEUE_STATUS_IN_ETALON_NOT_MATCH = 'etalon_not_match';
    const PRODUCT_QUEUE_STATUS_IN_ETALON_NOT_FOUND = 'etalon_not_found';

    private $oProduct = null;

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['productid'];
        $this->sPrimaryTable = 'external_verification_products_queue';
        parent::__construct($aParams);
    }

    public function updateStatus($sStatus)
    {
        $this->updateField('status',$sStatus);
        return $this;
    }

    public function setStatus($sStatus)
    {
        $this->setField('status',$sStatus);
        return $this;
    }

    public function getStatus()
    {
        return $this->getField('status');
    }

    public function getProductId()
    {
        return $this->getField('productid');
    }

    public function getProductEntity()
    {
        if (is_null($this->oProduct))
            $this->oProduct = new classProduct(['productid'=>$this->getProductId()]);
        return $this->oProduct;
    }

    public function getCrossVerifyCount()
    {
        return $this->getField('cross_verify_count');
    }

    public static function getProductsQueueEtalon()
    {
        $aResult = [];
        $oSQL = new classSQLBuilder();
        $aQueues = $oSQL->addSelect('*')->addFromTable('external_verification_products_queue')->
        addCondition("status in ('".self::PRODUCT_QUEUE_STATUS_IN_ETALON_MATCH."','".self::PRODUCT_QUEUE_STATUS_IN_ETALON_NOT_MATCH."','".self::PRODUCT_QUEUE_STATUS_IN_ETALON_NOT_FOUND."')")->addOrderBy('position')->Execute()->getQueryResult();
        if (!empty($aQueues)) {
            foreach ($aQueues as $aQueue) {
                $oQueue = new classExternalVerificationProductsQueue();
                $oQueue->fillPrimaryTableValues($aQueue);
                $aResult[] = $oQueue;
            }
        }
        return $aResult;
    }

    public static function getProductsQueueEtalonCount()
    {
        $oSQL = new classSQLBuilder();
        $aQueues = $oSQL->addSelect('count(1)', 'cnt')->addFromTable('external_verification_products_queue')->
        addCondition("status in ('".self::PRODUCT_QUEUE_STATUS_IN_ETALON_MATCH."','".self::PRODUCT_QUEUE_STATUS_IN_ETALON_NOT_MATCH."','".self::PRODUCT_QUEUE_STATUS_IN_ETALON_NOT_FOUND."')")->Execute()->getQueryResult();
        $aRes = reset($aQueues);
        return intval($aRes['cnt']);
    }

    public function getPosition()
    {
        return intval($this->getField('position'));
    }

    public function getAsin()
    {
        $aAsins = [];
        $sAsin = $this->getField('asin');
        if (!empty($sAsin)) {
            $aAsins = explode(',',$sAsin);
        }
        return $aAsins;
    }
}