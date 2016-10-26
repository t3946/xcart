<?php
namespace External_MarketPlace;
use Xcart\Data;

class DisabledMarketPlace extends Data
{
    public function __construct($aExternalMarketPlace = null)
    {
        $this->sPrimaryTable = 'products_disabled_marketplaces';
        $this->aPrimaryKeys = ['marketplace_id', 'resource_id', 'resource_type'];

        parent::__construct($aExternalMarketPlace);
    }

    public function addDisabledMarketPlace()
    {
        $InsertArray = [
            'marketplace_id' => $this->getField('marketplace_id'),
            'resource_id' => $this->getField('resource_id'),
            'resource_type' => $this->getField('resource_type')
        ];
        if (!func_array2insert('products_disabled_marketplaces', $InsertArray, true, true)) {
            func_array2update('products_disabled_marketplaces', $InsertArray, 'marketplace_id = ' . $this->getField('marketplace_id') . ' AND resource_id = ' . $this->getField('resource_id') . ' AND resource_type = \'' . $this->getField('resource_type') . '\'');
        }
        return $this;
    }

    public function deleteDisabledMarketPlace()
    {
        db_query("DELETE FROM " . self::$sql_tbl['products_disabled_marketplaces'] . " WHERE marketplace_id = " . $this->getField('marketplace_id') . " AND resource_id = " . $this->getField('resource_id') . " AND resource_type = '" . $this->getField('resource_type') . "'");
    }

    public static function deleteAllDisabledMarketPlace($iResource_id, $sResourceType)
    {
        global $sql_tbl;
        self::$sql_tbl = $sql_tbl;
        db_query("DELETE FROM " . self::$sql_tbl['products_disabled_marketplaces'] . " WHERE resource_id = " . $iResource_id . " AND resource_type = '" . $sResourceType . "'");
    }

    public static function getDisabledMarketPlaces($iResource_id, $sResourceType)
    {
        global $sql_tbl;
        self::$sql_tbl = $sql_tbl;
        return func_query_column("SELECT marketplace_id FROM " . self::$sql_tbl['products_disabled_marketplaces'] . " WHERE resource_id = $iResource_id AND resource_type = '$sResourceType'");
    }
}