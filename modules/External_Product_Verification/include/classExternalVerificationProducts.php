<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";
require_once $xcart_dir . "/include/class/classProduct.php";
require_once $xcart_dir . "/modules/External_Product_Verification/include/classExternalVerificationBatches.php";

class classExternalVerificationProducts extends classData
{
    private $oProduct = null;

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['productid','batch_id','action'];
        $this->sPrimaryTable = 'external_verification_products';
        parent::__construct($aParams);
    }

    public function getProductEntity()
    {
        if (is_null($this->oProduct))
            $this->oProduct = new classProduct(['productid'=>$this->getProductId()]);
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

    public function getAsin()
    {
        $sAsin = '';
        if (in_array($this->getAction(),classExternalVerificationBatch::$aProductStatuses['processed'])) {
            $oAsin = new classExternalVerificationProducts(['productid'=>$this->getProductId(), 'batch_id'=>$this->getBatchId(), 'action'=>'asin_on_amazon']);
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
}