<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";
require_once $xcart_dir . "/include/class/classProduct.php";
require_once $xcart_dir . "/modules/External_Product_Verification/include/classExternalVerificationBatches.php";

class classExternalVerificationProducts extends classData
{
    private $oProduct = null;
    private $oCustomer = null;

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['productid','batch_id','action'];
        $this->sPrimaryTable = 'external_verification_products';
        parent::__construct($aParams);
    }

    public function getProductEntity()
    {
        if (is_null($this->oProduct))
            $this->oProduct = classProduct::model(['productid'=>$this->getProductId()]);
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
            $this->oCustomer = classCustomer::model(['login'=>$this->getLogin()]);
        }
        return $this->oCustomer;
    }

    public function getAsin()
    {
        $sAsin = '';
        if (in_array($this->getAction(),classExternalVerificationBatch::$aProductStatuses['processed'])) {
            $oAsin = classExternalVerificationProducts::model(['productid'=>$this->getProductId(), 'batch_id'=>$this->getBatchId(), 'action'=>'asin_on_amazon']);
            if ($oAsin->getProductId()) {
               $sAsin = $oAsin->getValue();
            }
        }
        return $sAsin;
    }

    public function getAction()
    {
        return $this->getField('action');
    }

    public function getProductImage()
    {
        $sImage = '';
        $oImage = classExternalVerificationProducts::model(['productid'=>$this->getProductId(), 'batch_id'=>$this->getBatchId(), 'action'=>'product_image']);
        if ($oImage->getProductId()) {
            $sImage = $oImage->getValue();
        }
        return $sImage;
    }
}