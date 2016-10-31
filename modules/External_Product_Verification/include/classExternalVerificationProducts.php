<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";
require_once $xcart_dir . "/include/class/classProduct.php";
require_once $xcart_dir . "/modules/External_Product_Verification/include/classExternalVerificationBatches.php";

class classExternalVerificationProducts extends classData
{
    const AMAZON_PRODUCT_LINK = 'https://www.amazon.com/dp/%s/';

    private $oProduct = null;
    private $oCustomer = null;

    private $sAsin = null;
    private $ProductImage = null;
    private $ProductName = null;
    private $ProductDescription = null;
    private $QtyOnAmazon = null;
    private $QtyOnOurWebSite = null;
    private $sComment = null;
    private static $aActionsName = ['match' => 'Match', 'not_match' => 'Does NOT match', 'not_found' => 'Not found', 'not_sure' => 'Not sure'];
    private static $aQuestionsName = ['different' => 'Different', 'same' => 'Same', 'contradict' => 'Contradict', 'not_contradict' => 'Not'];

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['productid', 'batch_id', 'action'];
        $this->sPrimaryTable = 'external_verification_products';
        parent::__construct($aParams);
    }

    public function getProductEntity()
    {
        if (is_null($this->oProduct))
            $this->oProduct = classProduct::model(['productid' => $this->getProductId()]);
        return $this->oProduct;
    }

    public function getProductId()
    {
        return $this->getField('productid');
    }

    public function getBatchId()
    {
        return $this->getField('batch_id');
    }

    public function getValue()
    {
        return $this->getField('value');
    }

    public function getValueAsDateTime()
    {
        $oDateTime = new DateTime();
        $oDateTime->setTimestamp($this->getValue());
        return $oDateTime;
    }

    public function getLogin()
    {
        return $this->getField('login');
    }

    public function getCustomerEntity()
    {
        if (is_null($this->oCustomer)) {
            $this->oCustomer = classCustomer::model(['login' => $this->getLogin()]);
        }
        return $this->oCustomer;
    }

    public function getAsin()
    {
        if (is_null($this->sAsin)) {
            $this->sAsin = '';
            if ($this->getBatchId()) {
                if (in_array($this->getAction(), classExternalVerificationBatch::$aProductStatuses['processed'])) {
                    $oAsin = classExternalVerificationProducts::model(['productid' => $this->getProductId(), 'batch_id' => $this->getBatchId(), 'action' => 'asin_on_amazon']);
                    if ($oAsin->getProductId()) {
                        $this->sAsin = $oAsin->getValue();
                    }
                }
            } else {
                $oEtalonImage = classExternalVerificationProductsQueue::model(['productid' => $this->getProductId()]);
                $this->sAsin = implode(',', $oEtalonImage->getAsin());
            }
        }
        return $this->sAsin;
    }

    public function getComment()
    {
        if (is_null($this->sComment)) {
            $this->sComment = '';
            if (in_array($this->getAction(), classExternalVerificationBatch::$aProductStatuses['processed'])) {
                $oAsin = classExternalVerificationProducts::model(['productid' => $this->getProductId(), 'batch_id' => $this->getBatchId(), 'action' => 'comments_if_not']);
                if ($oAsin->getProductId()) {
                    $this->sComment = $oAsin->getValue();
                }
            }
        }
        return $this->sComment;
    }

    public function getAction()
    {
        return $this->getField('action');
    }

    public function setAction($sAction)
    {
        switch ($sAction) {
            case classExternalVerificationProductsQueue::PRODUCT_QUEUE_STATUS_IN_ETALON_MATCH :
                $sAction = 'match';
                break;
            case classExternalVerificationProductsQueue::PRODUCT_QUEUE_STATUS_IN_ETALON_NOT_MATCH :
                $sAction = 'not_match';
                break;
            case classExternalVerificationProductsQueue::PRODUCT_QUEUE_STATUS_IN_ETALON_NOT_FOUND :
                $sAction = 'not_found';
                break;
        }
        return $this->setField('action', $sAction);

    }

    public function setValue($sValue)
    {
        return $this->setField('value', $sValue);
    }

    public function getActionDisplayName()
    {
        return self::$aActionsName[$this->getAction()];
    }

    public function getProductImage()
    {
        if (is_null($this->ProductImage)) {
            if ($this->getBatchId()) {
                $oImage = classExternalVerificationProducts::model(['productid' => $this->getProductId(), 'batch_id' => $this->getBatchId(), 'action' => 'product_image']);
                if ($oImage->getProductId()) {
                    $this->ProductImage = self::$aQuestionsName[$oImage->getValue()];
                }
            } else {
                $oEtalonImage = classExternalVerificationProductsQueue::model(['productid' => $this->getProductId()]);
                $this->ProductImage = self::$aQuestionsName[$oEtalonImage->getProductImage()];
            }

        }
        return $this->ProductImage;
    }

    public function getProductName()
    {
        if (is_null($this->ProductName)) {
            if ($this->getBatchId()) {
                $oImage = classExternalVerificationProducts::model(['productid' => $this->getProductId(), 'batch_id' => $this->getBatchId(), 'action' => 'product_names']);
                if ($oImage->getProductId()) {
                    $this->ProductName = self::$aQuestionsName[$oImage->getValue()];
                }
            } else {
                $oEtalonImage = classExternalVerificationProductsQueue::model(['productid' => $this->getProductId()]);
                $this->ProductName = self::$aQuestionsName[$oEtalonImage->getProductName()];
            }
        }
        return $this->ProductName;
    }

    public function getProductDescription()
    {
        if (is_null($this->ProductDescription)) {
            if ($this->getBatchId()) {
                $oImage = classExternalVerificationProducts::model(['productid' => $this->getProductId(), 'batch_id' => $this->getBatchId(), 'action' => 'product_description']);
                if ($oImage->getProductId()) {
                    $this->ProductDescription = self::$aQuestionsName[$oImage->getValue()];
                }
            } else {
                $oEtalonImage = classExternalVerificationProductsQueue::model(['productid' => $this->getProductId()]);
                $this->ProductDescription = self::$aQuestionsName[$oEtalonImage->getProductDescription()];
            }
        }
        return $this->ProductDescription;
    }

    public function getQtyOnAmazon()
    {
        if (is_null($this->QtyOnAmazon)) {
            if ($this->getBatchId()) {
                $oImage = classExternalVerificationProducts::model(['productid' => $this->getProductId(), 'batch_id' => $this->getBatchId(), 'action' => 'qty_on_amazon']);
                if ($oImage->getProductId()) {
                    $this->QtyOnAmazon = $oImage->getValue();
                }
            } else {
                $oEtalonImage = classExternalVerificationProductsQueue::model(['productid' => $this->getProductId()]);
                $this->QtyOnAmazon = $oEtalonImage->getPackQtyAmazon();
            }
        }
        return $this->QtyOnAmazon;
    }

    public function getQtyOnOurWebSite()
    {
        if (is_null($this->QtyOnOurWebSite)) {
            if ($this->getBatchId()) {
                $oImage = classExternalVerificationProducts::model(['productid' => $this->getProductId(), 'batch_id' => $this->getBatchId(), 'action' => 'qty_on_our_website']);
                if ($oImage->getProductId()) {
                    $this->QtyOnOurWebSite = $oImage->getValue();
                }
            } else {
                $oEtalonImage = classExternalVerificationProductsQueue::model(['productid' => $this->getProductId()]);
                $this->QtyOnOurWebSite = $oEtalonImage->getPackQtyWebsite();
            }
        }
        return $this->QtyOnOurWebSite;
    }

    public function getAmazonProductLink()
    {
        return sprintf(self::AMAZON_PRODUCT_LINK, $this->getAsin());
    }
}