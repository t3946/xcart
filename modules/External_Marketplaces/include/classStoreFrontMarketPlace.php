<?php

global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";
require_once $xcart_dir . "/modules/External_Marketplaces/include/classExternalMarketPlace.php";

abstract class classStoreFrontMarketPlace extends classData
{
    protected $iProductsBatchCount = 0;
    protected $iInventoryBatchCount = 0;
    protected $aProducts = [];
    protected $aInventory = [];
    private $oExternalMarketPlace = null;
    protected $oService = null;

    public function __construct($aExternalMarketPlace = null)
    {
        $this->sPrimaryTable = "storefronts_external_marketplaces";
        $this->aPrimaryKeys = ['marketplace_id', 'storefront_id'];

        parent::__construct($aExternalMarketPlace);
        $this->fetchExternalMarketPlace();
    }

    abstract public function addProductToBatch($oProduct, $update_type, $sExtraLog = "N");
    abstract public function submitInventoryBatch($debug_mode = 'N', $extra_log = 'N');
    abstract public function submitProductsBatch($debug_mode = 'N', $extra_log = 'N');

    private function fetchExternalMarketPlace()
    {
        if (empty($this->oExternalMarketPlace)) {
            $this->oExternalMarketPlace = new classExternalMarketPlace(['id'=>$this->getField('marketplace_id')]);
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
     * @return classExternalMarketPlace
     */
    public function getExternalMarketPlaceEntity()
    {
        if (empty($this->oExternalMarketPlace))
            $this->fetchExternalMarketPlace();
        return $this->oExternalMarketPlace;
    }

    /**
     * @param int $iStoreFrontId
     * @return classStoreFrontMarketPlace[]
     */
    public static function getMarketPlacesByStoreFront($iStoreFrontId)
    {
        global $sql_tbl;
        self::$sql_tbl = $sql_tbl;
        $aMP = [];
        $aMarketPlaces = func_query_column("SELECT marketplace_id FROM " . self::$sql_tbl['storefronts_external_marketplaces'] . " WHERE storefront_id = $iStoreFrontId ");
        if (!empty($aMarketPlaces)) {
            foreach ($aMarketPlaces as $iMarketPlaceId) {
                /** @var int $iMarketPlaceId */
                $aMP[] = classExternalMarketPlace::getExternalMarketPlace($iMarketPlaceId, $iStoreFrontId);
            }
        }
        return $aMP;
    }

    protected function checkProductExcludedMarketPlace($iProductId)
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



}