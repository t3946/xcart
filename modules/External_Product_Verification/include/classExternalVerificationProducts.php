<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";
require_once $xcart_dir . "/include/class/classProduct.php";

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

    public function getValue()
    {
        return $this->getField('value');
    }

    public function getAction()
    {
        return $this->getField('action');
    }
}