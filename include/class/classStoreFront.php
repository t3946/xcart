<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classCloneData.php";

class classStoreFront extends classCloneData
{
    private $Enable_CDN = null;
    private $CDN_domain = null;

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

    public function fetchCDNSettings()
    {
        if (is_null($this->Enable_CDN)) {
            $CDNEnabled = func_query_first_cell("SELECT value FROM " . self::$sql_tbl['storefronts_config'] . " WHERE name='Enable_CDN' AND storefrontid=" . $this->getField('storefrontid'));
            if ($CDNEnabled == 'Y')
                $this->Enable_CDN = true;
        }
        if ($this->Enable_CDN) {
            $this->CDN_domain = func_query_first_cell("SELECT value FROM " . self::$sql_tbl['storefronts_config'] . " WHERE name='CDN_domain' AND storefrontid=" . $this->getField('storefrontid'));
        }
    }

    public function isCDNEnable()
    {
        $this->fetchCDNSettings();
        return $this->Enable_CDN;
    }

    public function getCDNURL()
    {
        $this->fetchCDNSettings();
        return $this->CDN_domain;
    }

    public function setCDNDisable()
    {
        $this->Enable_CDN = false;
    }
}