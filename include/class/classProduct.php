<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classCloneData.php";
require_once $xcart_dir . "/include/class/classManufacturer.php";
require_once $xcart_dir . "/include/class/classStoreFront.php";

class classProduct extends classCloneData
{
    const ADMIN_PRODUCT_MODIFY_URL = '/admin/product_modify.php?productid=%d&sf=%d';


    private $oManufacturer;
    private $oStoreFront;

    public function __construct($iId = null)
    {
        $this->sPrimaryTable = "products";
        $this->sPrimaryKeyFiled = "productid";

        parent::__construct($iId);
    }

    public function getManfacturerClass($iManufacurerId = null)
    {
        if (!is_null($iManufacurerId))
            return new classManufacturer($iManufacurerId);
        else {
            if (is_null($this->oManufacturer)) {
                $this->oManufacturer = new classManufacturer($this->aPrimaryTableValue['manufacturerid']);
            }
            return $this->oManufacturer;
        }
    }

    public function getMPN()
    {
        if (strpos($this->getField('productcode'), $this->getManfacturerClass()->getField('code')) == 0)
            return preg_replace("/^(" . $this->getManfacturerClass()->getField('code') . "-)/i", "", $this->getField('productcode'));
        return "";
    }

    public function getStoreFront()
    {
        if (is_null($this->oStoreFront)) {
            $this->oStoreFront = new classStoreFront();
        }
        return $this->oStoreFront;
    }

    public function setProductManufacturer($aManufacturerInfo)
    {
        if (!empty($aManufacturerInfo) && is_array($aManufacturerInfo)) {
            $this->oManufacturer = new classManufacturer($aManufacturerInfo);
        }
        return $this;
    }

    public function getProductModifyURL()
    {
        return sprintf(self::ADMIN_PRODUCT_MODIFY_URL, $this->getField($this->sPrimaryKeyFiled), $this->getStoreFront()->getStoreFrontByProductId($this->primaryKeyValue)->getField('storefrontid'));
    }

    public function getProductFrontURL()
    {
        return func_clean_url_get('P', $this->getField($this->sPrimaryKeyFiled));
    }

    public function getProductURLOnDistributorWebSite()
    {
        $sWebsiteProduct = $this->getManfacturerClass()->getField('d_website_search_for_sku_url');
        if (empty($sWebsiteProduct))
            $sWebsiteProduct = $this->getManfacturerClass()->getField('url');
        return str_replace('{{mpn}}', $this->getMPN(), $sWebsiteProduct);
    }

    public function getProductLastVerifyDate()
    {
        $iDate = $this->getField('last_verify_date');
        if (!empty($iDate)) {
            $oDatetime = new DateTime();
            $oDatetime->setTimestamp($iDate);
            return $oDatetime;
        }
        return false;
    }

    public static function getProductVerificationStatuses() {
        return func_query("SELECT * FROM ".self::$sql_tbl['product_verification_statuses']." ORDER BY orderby ASC");
    }

}