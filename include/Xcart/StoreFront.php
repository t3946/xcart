<?php
namespace Xcart;

class StoreFront extends Data
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
            $this->fill(['storefrontid'=>0, 'domain'=>MAIN_SF_DOMAIN]);
        }
    }

    public static function getStoreFrontByProductId($iProductId)
    {
        $obj = null;
        $sF = SQLBuilder::getInstance()->addSelect('sfid')->addFromTable('products_sf')->addCondition('productid='.$iProductId)->query_first()->getQueryResult();
        if (!empty($sF)) {
            if ($sF['sfid'] != 0)
                $obj = new StoreFront(['storefrontid'=>$sF['sfid']]);
            else {
                $obj = new StoreFront();
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

    public function getStoreFrontURL($protocol = '//')
    {
        return $protocol.$this->getDomain();
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

    public function getConfigValue($sName){
        $addSQL = '';
        $this->_init();
        if ($this->getStoreFrontId() > 0) {
            $addSQL = ' AND storefrontid=' . $this->getStoreFrontId();
        }
        $sValue = func_query_first_cell("SELECT value FROM " . $this->sConfigTable . " WHERE name='$sName' " . $addSQL);
        return $sValue;
    }

    public function updateConfigValue($sName, $sValue)
    {
        $this->_init();
        $aParam = ['name' => $sName];
        if ($this->getStoreFrontId() > 0) {
            $aParam['storefrontid'] = $this->getStoreFrontId();
        }
        Connection::getInstance()->update($this->sConfigTable, ['value' => $sValue], $aParam);
    }
}