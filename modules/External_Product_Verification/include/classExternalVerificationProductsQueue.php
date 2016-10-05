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
    private $aVerificatorResults = null;

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

    public function getProductImage()
    {
        return $this->getField('product_image');
    }

    public function getProductName()
    {
        return $this->getField('product_names');
    }

    public function getProductDescription()
    {
        return $this->getField('product_description');
    }

    public function getPackQtyAmazon()
    {
        return $this->getField('pack_qty_amazon');
    }

    public function getPackQtyWebsite()
    {
        return $this->getField('pack_qty_website');
    }

    public function getProductEntity()
    {
        if (is_null($this->oProduct))
            $this->oProduct = new classProduct(['productid'=>$this->getProductId()]);
        return $this->oProduct;
    }

    public function getCrossVerifyCount()
    {
        return intval($this->getField('cross_verify_count'));
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
        addCondition("status in ('".self::PRODUCT_QUEUE_STATUS_IN_ETALON_MATCH."','".self::PRODUCT_QUEUE_STATUS_IN_ETALON_NOT_MATCH."','".self::PRODUCT_QUEUE_STATUS_IN_ETALON_NOT_FOUND."')")->Execute()->getQueryResultFirst();
        return intval($aQueues['cnt']);
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

    public static function getVerificationResultsProducts($limit = 50)
    {
        $aResults = [];
        $oSQL = new classSQLBuilder();
        $oSQL->addSelect('*')->addFromTable('external_verification_products_queue')->
            addCondition('cross_verify_count = 2')->
            addOrderBy('cross_verify_count DESC')->setLimit($limit);
        $aVerificationResults = $oSQL->Execute()->getQueryResult();
        if (!empty($aVerificationResults)) {
            foreach ($aVerificationResults as $aVerificationResult) {
                $aResults[] = classExternalVerificationProductsQueue::model()->fill($aVerificationResult);
            }
        }
        return $aResults;
    }

    public function getVerificatorsResults()
    {
        if (is_null($this->aVerificatorResults)) {
            $this->oSQL->init()->addSelect('*')->addFromTable('external_verification_products')->
                addCondition('productid = '.$this->getProductId())->addCondition('action IN ("' . implode('","', classExternalVerificationBatch::$aProductStatuses['processed']) . '")');
            $aResults = $this->oSQL->Execute()->getQueryResult();
            if (!empty($aResults)) {
                foreach($aResults as $aVerificationProduct) {
                    $this->aVerificatorResults[] = classExternalVerificationProducts::model()->fill($aVerificationProduct);
                }
            }
        }
        return $this->aVerificatorResults;
    }
}