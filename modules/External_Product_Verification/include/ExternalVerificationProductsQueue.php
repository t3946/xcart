<?php
namespace Xcart\External_Product_Verification;

use Xcart\Data;
use Xcart\SQLBuilder;
use Xcart\Product;

class ExternalVerificationProductsQueue extends Data
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
        $this->updateField('status', $sStatus);
        return $this;
    }

    public function setStatus($sStatus)
    {
        $this->setField('status', $sStatus);
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
            $this->oProduct = new Product(['productid' => $this->getProductId()]);
        return $this->oProduct;
    }

    public function getCrossVerifyCount()
    {
        return intval($this->getField('cross_verify_count'));
    }

    public static function getProductsQueueEtalon()
    {
        $aResult = [];
        $oSQL = new SQLBuilder();
        $aQueues = $oSQL->addSelect('*')->addFromTable('external_verification_products_queue')->
        addCondition("status in ('" . self::PRODUCT_QUEUE_STATUS_IN_ETALON_MATCH . "','" . self::PRODUCT_QUEUE_STATUS_IN_ETALON_NOT_MATCH . "','" . self::PRODUCT_QUEUE_STATUS_IN_ETALON_NOT_FOUND . "')")->addOrderBy('position')->Execute()->getQueryResult();
        if (!empty($aQueues)) {
            foreach ($aQueues as $aQueue) {
                $oQueue = new ExternalVerificationProductsQueue();
                $oQueue->fill($aQueue);
                $aResult[] = $oQueue;
            }
        }
        return $aResult;
    }

    public static function getProductsQueueEtalonCount()
    {
        $oSQL = new SQLBuilder();
        $aQueues = $oSQL->addSelect('count(1)', 'cnt')->addFromTable('external_verification_products_queue')->
        addCondition("status in ('" . self::PRODUCT_QUEUE_STATUS_IN_ETALON_MATCH . "','" . self::PRODUCT_QUEUE_STATUS_IN_ETALON_NOT_MATCH . "','" . self::PRODUCT_QUEUE_STATUS_IN_ETALON_NOT_FOUND . "')")->Execute()->getQueryResultFirst();
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
            $aAsins = explode(',', $sAsin);
        }
        return $aAsins;
    }

    public static function getVerificationResultsProductsWithNotSameVariants($aParams = null)
    {
        $aResults = $aActionFilter = [];

        $limit = 50;
        if (!empty($aParams['limit']) && is_numeric($aParams['limit'])) {
            $limit = (int)$aParams['limit'];
        }

        if (!empty($aParams['filter'])) {
            foreach ($aParams['filter'] as $sFilter) {
                switch ($sFilter) {
                    case 'asin' :
                        $aActionFilter[] = 'asin_on_amazon';
                        break;
                    case 'image' :
                        $aActionFilter[] = 'product_image';
                        break;
                    case 'name' :
                        $aActionFilter[] = 'product_names';
                        break;
                    case 'desc' :
                        $aActionFilter[] = 'product_description';
                        break;
                    case 'qty' :
                        $aActionFilter[] = 'qty_on_amazon';
                        $aActionFilter[] = 'qty_on_our_website';
                        break;
                }
            }
        }

        $oSQL = new SQLBuilder();
        $oSQL->addSelect('SQL_CALC_FOUND_ROWS xe.*')->
        addFromTable('external_verification_products_queue', 'xe')->
        addInnerJoin('external_verification_products', 'xp', 'xp.productid = xe.productid AND xp.action IN ("' . implode('","', ExternalVerificationBatch::$aProductStatuses['processed']) . '")')->
        addInnerJoin('external_verification_products', 'xp2', 'xp.productid = xp2.productid AND xp2.action IN ("' . implode('","', $aActionFilter) . '")')->
        addInnerJoin('external_verification_products', 'xp3', 'xp.productid = xp3.productid AND xp3.action IN ("' . implode('","', $aActionFilter) . '")')->
        addInnerJoin('external_verification_products', 'xp4', 'xp.productid = xp4.productid AND xp4.action IN ("arbitrage_confirmation")', 'LEFT JOIN')->
        addInnerJoin('external_verification_products', 'xp5', 'xp.productid = xp5.productid AND xp5.action IN ("arbitrage_asin")', 'LEFT JOIN')->
        addGroupBy('xe.productid')->addOrderBy('xp.value DESC')->setLimit($limit);
        if (!empty($aParams['batch_id']) && is_numeric($aParams['batch_id'])) {
            $oSQL->addCondition('batch_id=' . (int)$aParams['batch_id']);
        } else {
            $oSQL->addCondition('cross_verify_count = 2');
            $oSQL->addCondition('xp3.value != xp2.value');
            $oSQL->addCondition('xp3.action = xp2.action');
            $oSQL->addCondition('xp3.batch_id != xp2.batch_id');
            if (in_array('asin_on_amazon', $aActionFilter)) {
                $oSQL->addCondition('xp4.productid IS NULL');
                $oSQL->addCondition('xp5.productid IS NULL');
            }
        }
        $aVerificationResults = $oSQL->Execute()->getQueryResult();
        if (!empty($aVerificationResults)) {
            foreach ($aVerificationResults as $aVerificationResult) {
                $aResults[] = ExternalVerificationProductsQueue::model()->fill($aVerificationResult);
            }
        }
        return $aResults;
    }

    public static function getVerificationResultsProducts($aParams = null)
    {
        $aResults = [];

        $limit = 50;
        if (!empty($aParams['limit']) && is_numeric($aParams['limit']))
            $limit = (int)$aParams['limit'];
        $oSQL = new SQLBuilder();
        $oSQL->addSelect('xe.*')->
        addFromTable('external_verification_products_queue', 'xe')->
        addInnerJoin('external_verification_products', 'xp', 'xp.productid = xe.productid AND xp.action IN ("' . implode('","', ExternalVerificationBatch::$aProductStatuses['processed']) . '")')->
        addGroupBy('xe.productid')->addOrderBy('xp.value DESC')->setLimit($limit);
        if (!empty($aParams['batch_id']) && is_numeric($aParams['batch_id'])) {
            $oSQL->addCondition('batch_id=' . (int)$aParams['batch_id']);
        } else
            $oSQL->addCondition('cross_verify_count = 2');
        $aVerificationResults = $oSQL->Execute()->getQueryResult();
        if (!empty($aVerificationResults)) {
            foreach ($aVerificationResults as $aVerificationResult) {
                $aResults[] = ExternalVerificationProductsQueue::model()->fill($aVerificationResult);
            }
        }
        return $aResults;
    }

    public function getVerificatorsResults($iBatchId = null)
    {
        if (is_null($this->aVerificatorResults)) {
            $oSQL = SQLBuilder::getInstance();
            $oSQL->addSelect('*')->addFromTable('external_verification_products')->
            addCondition('productid = ' . $this->getProductId())->
            addCondition('action IN ("' . implode('","', ExternalVerificationBatch::$aProductStatuses['processed']) . '")');

            if (!is_null($iBatchId) && in_array($this->getStatus(), [self::PRODUCT_QUEUE_STATUS_IN_ETALON_MATCH, self::PRODUCT_QUEUE_STATUS_IN_ETALON_NOT_MATCH, self::PRODUCT_QUEUE_STATUS_IN_ETALON_NOT_FOUND])) {
                $oSQL->addCondition('batch_id=' . $iBatchId);
                $this->aVerificatorResults[] = ExternalVerificationProducts::model()->setAction($this->getStatus())->setValue(time())->setField('productid', $this->getProductId());
            }

            $aResults = $oSQL->Execute()->getQueryResult();
            if (!empty($aResults)) {
                foreach ($aResults as $aVerificationProduct) {
                    $this->aVerificatorResults[] = ExternalVerificationProducts::model()->fill($aVerificationProduct);
                }
            }
        }
        return $this->aVerificatorResults;
    }
}