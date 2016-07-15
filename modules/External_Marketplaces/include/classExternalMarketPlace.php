<?php

global $xcart_dir;
require_once $xcart_dir."/include/class/classData.php";

class classExternalMarketPlace extends classData
{
    public function __construct($aExternalMarketPlace = null)
    {
        $this->sPrimaryTable = "products_external_marketplaces";
        $this->sPrimaryKeyFiled = "id";

        parent::__construct($aExternalMarketPlace);
    }
}