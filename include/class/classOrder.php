<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classCloneData.php";
require_once $xcart_dir . "/include/class/classOrderDetail.php";
require_once $xcart_dir . "/include/class/classOrderGroup.php";
require_once $xcart_dir . "/include/class/classProducts.php";
require_once $xcart_dir . "/include/class/classProduct.php";
require_once $xcart_dir . "/include/class/classManufacturers.php";
require_once $xcart_dir . "/include/class/classOrderTransactions.php";
require_once $xcart_dir . "/include/class/classSQLBuilder.php";

class classOrder extends classCloneData
{
    const ORDER_VERIFICATION_STATUS_PRODUCT_VERIFIED = 'PV';
    const ORDER_VERIFICATION_STATUS_PRODUCT_PROBLEM_FOUND = 'PF';
    const ORDER_VERIFICATION_STATUS_PRODUCT_IN_PROGRESS = 'IP';
    const ORDER_VERIFICATION_STATUS_PRODUCT_NOT_YET_STARTED = 'NS';

    const ADMIN_ORDER_MODIFY_URL = '/admin/order.php?orderid=%d';

    private $aOrderDetails = [];
    private $aOrderGroups = [];
    /**
     * @var classProduct[]
     */
    private $aOrderProducts = null;
    private $aOrderProductsManufactueres = [];

    public function __construct($aOrderData = null)
    {
        $this->sPrimaryTable = "orders";
        $this->sPrimaryKeyFiled = "orderid";

        parent::__construct($aOrderData);
    }

    public static function getInstance($iId = null)
    {
        return new self($iId);
    }

    private function fetchOrderDetails()
    {
        if (empty($this->aOrderDetails)) {
            $aOrderDetails = func_query("SELECT * FROM " . self::$sql_tbl['order_details'] . " WHERE " . $this->sPrimaryKeyFiled . " = " . $this->primaryKeyValue);
            if (!empty($aOrderDetails) && is_array($aOrderDetails)) {
                foreach ($aOrderDetails as $aOrderDetail) {
                    $this->aOrderDetails[] = new classOrderDetail($aOrderDetail);
                }
            }
        }
        return $this;
    }

    private function fetchOrderGroups()
    {
        if (empty($this->aOrderGroups)) {
            $aOrderGroups = func_query("SELECT * FROM " . self::$sql_tbl['order_groups'] . " WHERE " . $this->sPrimaryKeyFiled . " = " . $this->primaryKeyValue);
            if (!empty($aOrderGroups) && is_array($aOrderGroups)) {
                foreach ($aOrderGroups as $aOrderGroup) {
                    $oOrderGroup = new classOrderGroup();
                    $oOrderGroup->fillPrimaryTableValues($aOrderGroup);
                    $this->aOrderGroups[] = $oOrderGroup;
                }
            }
        }
        return $this;
    }

    /**
     * @return classOrderDetail[]
     */
    public function getOrderDetails()
    {
        $this->fetchOrderDetails();
        return $this->aOrderDetails;
    }

    /**
     * @return classOrderGroup[]
     */
    public function getOrderGroups()
    {
        $this->fetchOrderGroups();
        return $this->aOrderGroups;
    }

    public function getOrderGroupsCount()
    {
        $this->fetchOrderGroups();
        return count($this->aOrderGroups);
    }

    private function fetchOrderProductsManufacturers()
    {
        if (!empty($this->aOrderProductsManufactueres) && is_array($this->aOrderProductsManufactueres)) {
            $oManufacturers = new classManufacturers();
            $aManufacturersInfo = $oManufacturers->getMainufacturersInfo($this->aOrderProductsManufactueres);
            foreach ($this->aOrderProducts as &$oOrderProduct) {
                $key = array_search($oOrderProduct->getField('manufacturerid'), array_column($aManufacturersInfo, 'manufacturerid'));
                if ($key !== false) {
                    $oOrderProduct->setProductManufacturer($aManufacturersInfo[$key]);
                }
            }
        }
    }

    private function fetchOrderProducts()
    {
        if (is_null($this->aOrderProducts)) {
            $aProductIds = [];
            $aOrderDetails = $this->getOrderDetails();
            if (!empty($aOrderDetails) && is_array($aOrderDetails)) {
                $oProducts = new classProducts();
                foreach ($aOrderDetails as $oOrderDetail) {
                    $aProductIds[] = $oOrderDetail->getField('productid');
                }
                $aProducts = $oProducts->getProductsInfo($aProductIds);
                if (!empty($aProducts) && is_array($aProducts)) {
                    $this->aOrderProductsManufactueres = [];
                    foreach ($aProducts as $aProduct) {
                        $oProduct = new classProduct($aProduct);
                        if (!in_array($oProduct->getField('manufacturerid'), $this->aOrderProductsManufactueres))
                            $this->aOrderProductsManufactueres[] = $oProduct->getField('manufacturerid');
                        $this->aOrderProducts[] = $oProduct;
                    }
                    $this->fetchOrderProductsManufacturers();
                }
            }
        }
        return $this;
    }

    /**
     * @return classProduct[]
     */
    public function getOrderProducts()
    {
        $this->fetchOrderProducts();
        return $this->aOrderProducts;
    }

    public function unsetOrderProduct($iProductId)
    {
        if (!empty($this->aOrderProducts)) {
            foreach ($this->aOrderProducts as $key => $oProduct) {

                if ($oProduct->getField('productid') == $iProductId) {
                    // echo $oProduct->getField('productid')."\n".$iProductId."\n";
                    unset($this->aOrderProducts[$key]);
                }
            }
        }
    }

    public function getDisplayOrderNumber()
    {
        return $this->getField('order_prefix') . $this->getField($this->sPrimaryKeyFiled);
    }

    public function getOrderModifyURL()
    {
        return sprintf(self::ADMIN_ORDER_MODIFY_URL, $this->getField($this->sPrimaryKeyFiled));
    }

    public static function getOrderStatusByCode($sCode)
    {
        return func_query_first("SELECT * FROM " . self::$sql_tbl['order_statuses'] . " WHERE code='$sCode'");
    }

    public function changeVerificationStatus($sNewStatus)
    {
        $bResult['result'] = true;
        $aNewStatus = self::getOrderStatusByCode($sNewStatus);
        $aOldStatus = self::getOrderStatusByCode($this->getField('vn_status'));
        if ($aNewStatus['code'] != $aOldStatus['code']) {
            $bResult['result'] = func_array2update($this->sPrimaryTable, ['vn_status' => $sNewStatus], 'orderid = ' . $this->primaryKeyValue);
            $log = "vn_status: ". $aOldStatus['name'] . " -> ". $aNewStatus['name'];
            func_log_order($this->primaryKeyValue, 'X', $log);
        }
        $this->setField('vn_status',$sNewStatus);
        return $bResult;
    }

    public function updateVerificationStatus()
    {
        $aOrderProducts = $this->getOrderProducts();
        if (!empty($aOrderProducts) && is_array($aOrderProducts)) {
            $iMaxStatus = $iMinStatus = null;
            foreach ($aOrderProducts as $oOrderProduct) {
                if ($oOrderProduct instanceof classProduct) {
                    $iVerifyStatus = $oOrderProduct->getField('verification_statusid');
                    if (is_null($iMinStatus)) $iMinStatus = $iVerifyStatus;
                    $iMaxStatus = max($iMaxStatus, $iVerifyStatus);
                    $iMinStatus = min($iMinStatus, $iVerifyStatus);
                }
            }

            if ($iMinStatus == $iMaxStatus) {
                switch ($iMaxStatus) {
                    case (classProduct::PRODUCT_STATUS_NOT_VERIFY) :
                        $this->changeVerificationStatus(self::ORDER_VERIFICATION_STATUS_PRODUCT_NOT_YET_STARTED);
                        break;
                    case (classProduct::PRODUCT_STATUS_PROBLEM_NOT_FIXED) :
                    case (classProduct::PRODUCT_STATUS_PROBLEM_FIXED) :
                        $this->changeVerificationStatus(self::ORDER_VERIFICATION_STATUS_PRODUCT_PROBLEM_FOUND);
                        break;
                    case (classProduct::PRODUCT_STATUS_VERIFY):
                        $this->changeVerificationStatus(self::ORDER_VERIFICATION_STATUS_PRODUCT_VERIFIED);
                        break;
                }

            } elseif ($iMinStatus == classProduct::PRODUCT_STATUS_NOT_VERIFY && $iMaxStatus > classProduct::PRODUCT_STATUS_NOT_VERIFY) {
                $this->changeVerificationStatus(self::ORDER_VERIFICATION_STATUS_PRODUCT_IN_PROGRESS);
            } elseif ($iMinStatus > classProduct::PRODUCT_STATUS_NOT_VERIFY && $iMaxStatus > classProduct::PRODUCT_STATUS_NOT_VERIFY) {
                $this->changeVerificationStatus(self::ORDER_VERIFICATION_STATUS_PRODUCT_PROBLEM_FOUND);
            }

        }
    }

    public function isOrderAmazon()
    {
        $sAmazonOrderId = $this->getField('amazonorderid');
        return !empty($sAmazonOrderId);
    }

    public function getAmazonChanell()
    {
        return $this->getField('amazon_fulfillment_channel');
    }

    public function recalculateAccounting()
    {
        $aOrderGroups = $this->getOrderGroups();
        if (!empty($aOrderGroups)) {
            foreach ($aOrderGroups as $oOrderGroup) {
                $oOrderGroup->recalculateAccounting();
            }
        }
    }

    public function getOrderCustomerNotes()
    {
        return $this->getField('customer_notes');
    }

    public function getClientShippingName()
    {
        $sClientName = '';
        $sTitle = $this->getField('s_title');
        if (!empty($sTitle))
            $sClientName .= $sTitle . ' ';
        return $sClientName . $this->getField('s_firstname');
    }

    public function getOrderDate($sFormat = null)
    {
        $date = new DateTime();
        $date->setTimestamp((int)$this->getField('date'));
        if (!empty($sFormat)) {
            return $date->format($sFormat);
        }
        return $date->getTimestamp();
    }

    /**
     * @return string
     */
    public function getOrderPrefix()
    {
        return $this->getField('order_prefix');
    }

    public function getOrderId()
    {
        return $this->getField('orderid');
    }

    public function captureOrderAmount()
    {
        global $login;
        $aOrderTransactions = new classOrderTransactions();
        try {
            $aOrderTransactions->captureOrderAmount($this);
        } catch (Exception $ex) {
            func_log_order($this->getOrderId(),'X',$ex->getMessage(),$login);
            return false;
        }
        return $this;
    }

    public function getOrderCurrency()
    {
        return $this->getField('currency');
    }

    public function isAttentionTagSet($iStatusId)
    {
        $oSQL = new classSQLBuilder();
        $aQResult = $oSQL->init()->addSelect('status_id')->addFromTable('orders_additional_tags')->addCondition('orderid='.$this->getOrderId())->addCondition('status_id='.$iStatusId)->Execute()->getQueryResult();
        return !empty($aQResult);
    }

}