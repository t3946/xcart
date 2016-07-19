<?php

global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";
require_once $xcart_dir . "/modules/External_Marketplaces/include/classStoreFrontMarketPlace.php";

class classExternalMarketPlace extends classData
{
    private $aStoreMarketPlaces = [];

    public function __construct($aExternalMarketPlace = null)
    {
        $this->sPrimaryTable = 'products_external_marketplaces';
        $this->aPrimaryKeys = ['id'];

        parent::__construct($aExternalMarketPlace);
    }

    public static function getExternalMarketPlaces()
    {
        global $xcart_dir, $sql_tbl;
        self::$sql_tbl = $sql_tbl;
        $aMP = [];
        $aMarketPlaces = func_query("SELECT * FROM " . self::$sql_tbl['products_external_marketplaces'] . " ORDER BY marketplace_name");

        if (!empty($aMarketPlaces)) {
            foreach ($aMarketPlaces as $aMarketPlace) {
                $sProcessorClass = $aMarketPlace['processor_class'];
                $sProcessorPath = $xcart_dir . "/modules/External_Marketplaces/include/marketplaces/" . $sProcessorClass . ".php";
                if (file_exists($sProcessorPath))
                    require_once $sProcessorPath;
                else $sProcessorClass = __CLASS__;
                $oProcessor = new $sProcessorClass();
                $oProcessor->fillPrimaryTableValues($aMarketPlace);
                $aMP[] = $oProcessor;
            }
        }
        return $aMP;
    }

    public function getMarketPlaceName()
    {
        return $this->getField('marketplace_name');
    }

    public function getMarketPlaceId()
    {
        return $this->getField('id');
    }

    public function getMarketPlaceProcessorClassName()
    {
        return $this->getField('processor_class');
    }

    public function getMarketPlaceStatus()
    {
        return $this->getField('active');
    }

    public function getMarketPlaceStatusesValues()
    {
        return ['Y', 'N'];
    }

    public function getStoreFrontMarketplaces()
    {
        if (empty($this->aStoreMarketPlaces)) {
            $aMarketPlaces = func_query("SELECT * FROM " . self::$sql_tbl['storefronts_external_marketplaces'] . " WHERE marketplace_id = " . $this->getMarketPlaceId());
            if (!empty($aMarketPlaces)) {
                foreach ($aMarketPlaces as $aMarketPlace) {
                    $oStoreMarketPlace = new classStoreFrontMarketPlace();
                    $oStoreMarketPlace->fillPrimaryTableValues($aMarketPlace);
                    $this->aStoreMarketPlaces[] = $oStoreMarketPlace;
                }
            }
        }
        return $this->aStoreMarketPlaces;
    }


}