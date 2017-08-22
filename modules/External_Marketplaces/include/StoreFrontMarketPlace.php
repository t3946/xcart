<?php
namespace Xcart\External_Marketplaces;
use Modules\Product\Models\ProductModel;
use Xcart\Connection;
use Xcart\Data;

abstract class StoreFrontMarketPlace extends Data
{
    protected $iProductsBatchCount = 0;
    protected $iInventoryBatchCount = 0;
    public $aProducts = [];
    protected $aInventory = [];
    private $oExternalMarketPlace = null;
    protected $oService = null;
    protected $aMerchantResponse = [];

    public function __construct($aExternalMarketPlace = null)
    {
        $this->sPrimaryTable = "storefronts_external_marketplaces";
        $this->aPrimaryKeys = ['marketplace_id', 'storefront_id'];

        parent::__construct($aExternalMarketPlace);
        $this->fetchExternalMarketPlace();
    }

    abstract public function addProductToBatch(ProductModel $oProduct, $update_type, $googleOneRow = "", $sExtraLog = "N");
    abstract public function submitInventoryBatch($debug_mode = 'N', $extra_log = 'N');
    abstract public function submitProductsBatch($debug_mode = 'N', $extra_log = 'N');
    abstract public function checkMarketplaceRestrictions(ProductModel $oProduct);

    private function fetchExternalMarketPlace()
    {
        if (empty($this->oExternalMarketPlace)) {
            $this->oExternalMarketPlace = new ExternalMarketPlace(['id'=>$this->getField('marketplace_id')]);
        }
        return $this;
    }

    public function getStoreFrontId()
    {
        return $this->getField('storefront_id');
    }

    public function getInventoryBatchCount()
    {
        return $this->getField('inventory_batch_count');
    }

    public function getCurrentInventoryBatchCount()
    {
        return $this->iInventoryBatchCount;
    }

    public function getProductsBatchCount()
    {
        return $this->getField('products_batch_count');
    }

    public function getCurrentProductsBatchCount()
    {
        return $this->iProductsBatchCount;
    }

    public function getP0()
    {
        return $this->getField('P0');
    }

    public function getP1()
    {
        return $this->getField('P1');
    }

    public function getP2()
    {
        return $this->getField('P2');
    }

    public function getFTPDomain()
    {
        return $this->getField('ftp_domain');
    }

    public function getFTPLogin()
    {
        return $this->getField('ftp_login');
    }

    public function getFTPPassword()
    {
        return $this->getField('ftp_password');
    }

    public function getFTPPath()
    {
        return $this->getField('ftp_path');
    }

    public function getFileNameSuffix()
    {
        return $this->getField('export_filename_suffix');
    }


    /**
     * @return ExternalMarketPlace
     */
    public function getExternalMarketPlaceEntity()
    {
        if (empty($this->oExternalMarketPlace))
            $this->fetchExternalMarketPlace();
        return $this->oExternalMarketPlace;
    }

    /**
     * @param int $iStoreFrontId
     * @return StoreFrontMarketPlace[]
     */
    public static function getMarketPlacesByStoreFront($iStoreFrontId)
    {
        $aMP = [];
        $aMarketPlaces = Connection::getInstance()->fetchAll("SELECT marketplace_id FROM xcart_storefronts_external_marketplaces WHERE storefront_id = {$iStoreFrontId}");
        if (!empty($aMarketPlaces)) {
            foreach ($aMarketPlaces as $aMarketPlaceId) {
                $aMP[] = ExternalMarketPlace::getExternalMarketPlace($aMarketPlaceId['marketplace_id'], $iStoreFrontId);
            }
        }
        return $aMP;
    }

    public function checkProductExcludedMarketPlace($iProductId)
    {
        $bResult = true;
        $aFound = func_query_column("SELECT xp.marketplace_id
              FROM " . self::$sql_tbl['products_disabled_marketplaces'] . " xp
                   INNER JOIN " . self::$sql_tbl['products'] . " xp1
                      ON (xp1.productid = xp.resource_id AND xp.resource_type = 'P' OR
                          xp1.brandid = xp.resource_id AND xp.resource_type = 'B' OR
                          xp1.manufacturerid = xp.resource_id AND xp.resource_type = 'D') AND
                         xp1.productid = $iProductId");
        if (!empty($aFound) && in_array($this->getField('marketplace_id'),$aFound)) $bResult = false;
        return $bResult;
    }

    public function setInventoryBatchCount($iValue)
    {
        $this->iInventoryBatchCount = $iValue;
        return $this;
    }

    public function setInventory($aValues)
    {
        $this->aInventory = $aValues;
        return $this;
    }

    public function getInventory()
    {
        return $this->aInventory;
    }

    public function setProductsBatchCount($iValue)
    {
        $this->iProductsBatchCount = $iValue;
        return $this;
    }

    public function setProducts($aValues)
    {
        $this->aProducts = $aValues;
        return $this;
    }

    public function getProducts()
    {
        return $this->aProducts;
    }

    public function restoreQueue($products, $mode)
    {
        foreach ($products as $item) {
            $count = func_query_first_cell("SELECT COUNT(*) as count FROM " . self::$sql_tbl['cidev_updated_products'] . " WHERE resourceid='" . $item['productid'] . "' AND type=" . $mode . ";");
            if ($count == 0)
                db_query("INSERT INTO " . self::$sql_tbl['cidev_updated_products'] . " (`resourceid`,`type`,`time_stamp`,`source`) VALUES( '" . $item['productid'] . "', " . $mode . ", " . time() . ", 're-queue' )");
        }
    }

    public function getUpdateExpiredBeforeDays()
    {
        return $this->getField('update_expired_before');
    }

    public function getUpdateMaxExpiredProductsPerDay()
    {
        return $this->getField('update_max_expired_products_per_day');
    }

    public function getGoogleOneRow(ProductModel $oProduct, $type, $sExtraLog)
    {
        $result = null;

        if (in_array($type, ['1', '1,2', '2,1'])) {
            $result = GetGoogleBaseOneRow($oProduct->productid, "main_google", $sExtraLog);
        }
        return $result;
    }

}