<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classCloneData.php";
require_once $xcart_dir . "/include/class/classManufacturer.php";
require_once $xcart_dir . "/include/class/classStoreFront.php";
require_once $xcart_dir . "/include/class/classOrders.php";
require_once $xcart_dir . "/include/class/classProductVerifiactionStatus.php";

class classProduct extends classCloneData
{
    const ADMIN_PRODUCT_MODIFY_URL = '/admin/product_modify.php?productid=%d&sf=%d';

    const PRODUCT_STATUS_NOT_VERIFY = 0;
    const PRODUCT_STATUS_PROBLEM_NOT_FIXED = 1;
    const PRODUCT_STATUS_PROBLEM_FIXED = 2;
    const PRODUCT_STATUS_VERIFY = 3;


    private $oManufacturer;
    private $oStoreFront;
    private $aProductVerificationHistoryLast = [];

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

    public function getHTMLShot() {
        $sProductPage = file_get_contents($this->getProductFrontURL());
        return $sProductPage;
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
        $iDate = (int)$this->getField('last_verify_date');
        if (!empty($iDate)) {
            $oDatetime = new DateTime();
            $oDatetime->setTimestamp($iDate);
            return $oDatetime;
        }
        return false;
    }

    public static function getProductVerificationStatuses()
    {
        return func_query("SELECT * FROM " . self::$sql_tbl['product_verification_statuses'] . " ORDER BY orderby ASC");
    }

    public function getProductVerificationHistoryLastNote()
    {
        $sResult = '';
        $this->aProductVerificationHistoryLast = func_query_first("SELECT * FROM " . self::$sql_tbl['product_verification_history'] . " WHERE productid = " . $this->primaryKeyValue . " ORDER BY timestamp DESC");
        if (!empty($this->aProductVerificationHistoryLast)) {
            $sResult = stripslashes($this->aProductVerificationHistoryLast['verification_note']);
        }
        return $sResult;
    }

    public function changeVerificationStatus($iStatusId, $sNote='', $add2History = true, $aOrders)
    {
        global $login;
        $bResult['result'] = false;
        if ($this->getField('verification_statusid') != $iStatusId) {
            $aUpdateParams = ['verification_statusid' => $iStatusId];
            $oDatetime = new DateTime();
            if ($iStatusId == self::PRODUCT_STATUS_VERIFY) {
                $aUpdateParams['last_verify_date'] =  $oDatetime->getTimestamp();
            }
            $res = func_array2update($this->sPrimaryTable, $aUpdateParams, 'productid = ' . $this->primaryKeyValue);

            if ($res) {
                if ($add2History) {
                    $aInsertArray = ['productid' => $this->primaryKeyValue,
                    'verification_note' => addslashes($sNote),
                    'timestamp' => $oDatetime->getTimestamp(),
                    'username' => $login,
                    'oldstatusid' => $this->getField('verification_statusid'),
                    'newstatusid' => $iStatusId];
                    func_array2insert('product_verification_history', $aInsertArray);
                }
                $bResult['result'] = true;

                $this->setField('last_verify_date', $aUpdateParams['last_verify_date']);

                if (!empty($aOrders) && ($iStatusId == self::PRODUCT_STATUS_PROBLEM_NOT_FIXED || $iStatusId == self::PRODUCT_STATUS_PROBLEM_FIXED )) {
                    foreach ($aOrders as $iOrderId) {
                        $oVerificationStatusNew = new classProductVerificationStatus($iStatusId);
                        $oVerificationStatusOld = new classProductVerificationStatus($this->getField('verification_statusid'));
                        $sLogMessage = "<b>".$this->getField('productcode')."</b> product verification status: ".$oVerificationStatusOld->getField('name')." -> ".$oVerificationStatusNew->getField('name')."\n";
                        if (!empty($sNote)) $sLogMessage.= 'Problem/fix description: '.$sNote;
                        func_log_order($iOrderId, 'X', nl2br($sLogMessage));
                    }
                }
                $this->setField('verification_statusid', $aUpdateParams['verification_statusid']);

            } else $bResult['error'] = 'Status not updated';
        } else {
            $bResult['error'] = 'Status not changed. New status = Old status';
        }
        return $bResult;

    }

}