<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classCloneData.php";

class classStoreFront extends classCloneData
{
    private $Enable_CDN = null;
    private $CDN_domain = null;

    private $sConfigTable = null;

    public function __construct($iId = null)
    {
        $this->sPrimaryTable = "storefronts";
        $this->sPrimaryKeyFiled = "storefrontid";

        parent::__construct($iId);

        $this->_init();
    }

    private function _init()
    {
        if ($this->getStoreFrontId() > 0) {
            $this->sConfigTable = self::$sql_tbl['storefronts_config'];
        } else
            $this->sConfigTable = self::$sql_tbl['config'];
    }

    public function getStoreFrontByProductId($iProductId)
    {
        $obj = null;
        $sF = func_query_first("SELECT sfid FROM xcart_products_sf psf WHERE psf.productid = $iProductId");
        if (!empty($sF)) {
            if ($sF['sfid'] != 0)
                $obj = new classStoreFront($sF['sfid']);
            else {
                $obj = new classStoreFront();
                $obj->setField('storefrontid', $sF['sfid']);
            }
        }
        return $obj;
    }

    public function getStoreFrontId()
    {
        return $this->getField('storefrontid');
    }

    public function fetchCDNSettings()
    {
        if ($this->getStoreFrontId() > 0) {
            $addSQL = ' AND storefrontid=' . $this->getStoreFrontId();
        }

        if (is_null($this->Enable_CDN)) {
            $CDNEnabled = func_query_first_cell("SELECT value FROM " . $this->sConfigTable . " WHERE name='Enable_CDN' " . $addSQL);
            if ($CDNEnabled == 'Y')
                $this->Enable_CDN = true;
        }
        if ($this->Enable_CDN) {
            $this->CDN_domain = func_query_first_cell("SELECT value FROM " . $this->sConfigTable . " WHERE name='CDN_domain' ".$addSQL);
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