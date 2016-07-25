<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";

class classStoreFront extends classData
{
    public function __construct($iId = null)
    {
        $this->sPrimaryTable = "storefronts";
        $this->sPrimaryKeyFiled = "storefrontid";

        parent::__construct($iId);
    }

    public function getStoreFrontByProductId($iProductId)
    {
        if (empty($this->aPrimaryTableValue))
        $this->fillPrimaryTableValues(
            func_query_first("SELECT sf.*
                            FROM xcart_storefronts sf
                      INNER JOIN xcart_products_sf psf ON sf.storefrontid = psf.sfid AND psf.productid = $iProductId"));
        return $this;
    }

    public function getDomain() {
        return $this->getField('domain');
    }

    public function getStoreFrontId() {
        return $this->getField('storefrontid');
    }
}