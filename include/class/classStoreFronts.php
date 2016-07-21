<?php

global $xcart_dir;
require_once $xcart_dir . "/include/class/classCloneData.php";
require_once $xcart_dir . "/include/class/classStoreFront.php";

class classStoreFronts extends classData
{
    private $aStoreFronts = [];

    public function __construct($iId = null)
    {
        $this->sPrimaryTable = "storefronts";
        $this->sPrimaryKeyFiled = "storefrontid";

        parent::__construct($iId);
        $this->fetchStoreFronts();
    }

    public function fetchStoreFronts()
    {
        if (empty($this->aStoreFronts)) {
            $aStoreFronts = func_query("SELECT * FROM " . self::$sql_tbl['storefronts'] . " ORDER BY domain" );
            if (!empty($aStoreFronts)) {
                foreach ($aStoreFronts as $aStoreFront) {
                    $oStorefront = new classStoreFront();
                    $oStorefront->fillPrimaryTableValues($aStoreFront);
                    $this->aStoreFronts[] = $oStorefront;
                }
            }
        }
        return $this;
    }

    public function getStoreFrontsDomains() {
        $aResult = [];
        foreach ($this->aStoreFronts as $oStoreFront){
            $aResult[] = $oStoreFront->getDomain();
        }
        return $aResult;
    }

    public function getStoreFrontsIds() {
        $aResult = [];
        foreach ($this->aStoreFronts as $oStoreFront){
            $aResult[] = $oStoreFront->getStoreFrontId();
        }
        return $aResult;
    }

    public function getStoreFronts() {
        return $this->aStoreFronts;
    }
}