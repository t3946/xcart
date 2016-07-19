<?php

global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";

class classStoreFrontMarketPlace extends classData
{
    public function __construct($aExternalMarketPlace = null)
    {
        $this->sPrimaryTable = "storefronts_external_marketplaces";
        $this->aPrimaryKeys = ['marketplace_id', 'storefront_id'];

        parent::__construct($aExternalMarketPlace);
    }

    public function getStoreFrontId()
    {
        return $this->getField('storefront_id');
    }

    public function getInventoryBatchCount()
    {
        return $this->getField('inventory_batch_count');
    }

    public function getProductsBatchCount()
    {
        return $this->getField('products_batch_count');
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
}