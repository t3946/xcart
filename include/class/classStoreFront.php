<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";

class classStoreFront extends classData
{
    private $Enable_CDN = null;
    private $CDN_domain = null;
    private $CompanyName = null;

    private $sConfigTable = null;

    public function __construct($iId = null)
    {
        $this->sPrimaryTable = "storefronts";
        $this->aPrimaryKeys = ["storefrontid"];

        parent::__construct($iId);

        $this->_init();
    }

    private function _init()
    {
        if ($this->getStoreFrontId() > 0) {
            $this->sConfigTable = self::$sql_tbl['storefronts_config'];
        } else {
            $this->sConfigTable = self::$sql_tbl['config'];
            $this->fillPrimaryTableValues(['storefrontid'=>0, 'domain'=>MAIN_SF_DOMAIN]);
        }
    }

    public function getStoreFrontByProductId($iProductId)
    {
        $obj = null;
        $sF = func_query_first("SELECT sfid FROM xcart_products_sf psf WHERE psf.productid = $iProductId");
        if (!empty($sF)) {
            if ($sF['sfid'] != 0)
                $obj = new classStoreFront(['storefrontid'=>$sF['sfid']]);
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
        $addSQL = '';
        $this->_init();
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

    public function getDomain() {
        return $this->getField('domain');
    }

    public function setCDNDisable()
    {
        $this->Enable_CDN = false;
    }

    public function fetchCompanyName()
    {
        $this->_init();
        if (empty($this->CompanyName)){
            if ($this->getStoreFrontId() > 0) {
                $addSQL = ' AND storefrontid=' . $this->getStoreFrontId();
            }
            $this->CompanyName = func_query_first_cell("SELECT value FROM " . $this->sConfigTable . " WHERE name='company_name' ".$addSQL);
        }

    }

    public function getCompanyName()
    {
        $this->fetchCompanyName();
        return $this->CompanyName;
    }
}