<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classCloneData.php";

class classStoreFront extends classCloneData
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
        $this->setPrimaryTableInfo(
            func_query_first("SELECT sf.*
                            FROM xcart_storefronts sf
                      INNER JOIN xcart_products_sf psf ON sf.storefrontid = psf.sfid AND psf.productid = $iProductId"));
        return $this;
    }
}