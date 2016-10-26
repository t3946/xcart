<?php
namespace External_MarketPlace;
use Xcart\Data;


class ExternalMarketPlace extends Data
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
        global $sql_tbl;
        self::$sql_tbl = $sql_tbl;
        $aMP = [];
        $aMarketPlaces = func_query("SELECT * FROM " . self::$sql_tbl['products_external_marketplaces'] . " ORDER BY marketplace_name");

        if (!empty($aMarketPlaces)) {
            foreach ($aMarketPlaces as $aMarketPlace) {
                $sProcessorClass = __CLASS__;
                /** @var classStoreFrontMarketPlace $oProcessor */
                $oProcessor = new $sProcessorClass();
                $oProcessor->fill($aMarketPlace);
                $aMP[] = $oProcessor;
            }
        }
        return $aMP;
    }

    /**
     * @param int $iMarketPlaceId
     * @param int $iStoreFrontId
     * @return classStoreFrontMarketPlace
     */
    public static function getExternalMarketPlace($iMarketPlaceId, $iStoreFrontId)
    {
        $oProcessor = null;
        global $xcart_dir, $sql_tbl;
        self::$sql_tbl = $sql_tbl;
        $aMarketPlace = func_query_first("SELECT * FROM " . self::$sql_tbl['products_external_marketplaces'] . " WHERE id = $iMarketPlaceId");
        if (!empty($aMarketPlace)) {
            $sProcessorClass = $aMarketPlace['processor_class'];
            $oProcessor = new $sProcessorClass(['marketplace_id'=>$iMarketPlaceId, 'storefront_id'=>$iStoreFrontId]);
        }
        return $oProcessor;
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
                    $oStoreMarketPlace = $this->getExternalMarketPlace($this->getMarketPlaceId(),$aMarketPlace['storefront_id']);
                    $oStoreMarketPlace->fill($aMarketPlace);
                    $this->aStoreMarketPlaces[] = $oStoreMarketPlace;
                }
            }
        }
        return $this->aStoreMarketPlaces;
    }


}