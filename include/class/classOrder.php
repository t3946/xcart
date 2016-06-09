<?php
global $xcart_dir;
require_once $xcart_dir."/include/class/classCloneData.php";
require_once $xcart_dir."/include/class/classOrderDetail.php";
require_once $xcart_dir."/include/class/classProducts.php";
require_once $xcart_dir."/include/class/classProduct.php";
require_once $xcart_dir."/include/class/classManufacturers.php";

class classOrder extends classCloneData
{
    const ORDER_STATUS_PRODUCT_VERIFIED = 'PV';
    const ORDER_STATUS_PRODUCT_PROBLEM_FOUND = 'PF';
    const ORDER_STATUS_PRODUCT_IN_PROGRESS = 'IP';
    const ORDER_STATUS_PRODUCT_NOT_YET_STARTED = 'NS';

    const ADMIN_ORDER_MODIFY_URL = '/admin/order.php?orderid=%d';

    private $aOrderDetails = [];
    /**
     * @var classProduct[]
     */
    private $aOrderProducts = [];
    private $aOrderProductsManufactueres = [];

    public function __construct($aOrderData = null)
    {
        $this->sPrimaryTable = "orders";
        $this->sPrimaryKeyFiled = "orderid";

        parent::__construct($aOrderData);
    }

    private function fetchOrderDetails() {
        if (empty($this->aOrderDetails)) {
            $aOrderDetails = func_query("SELECT * FROM ".self::$sql_tbl['order_details']." WHERE ".$this->sPrimaryKeyFiled." = ".$this->primaryKeyValue);
            if (!empty($aOrderDetails) && is_array($aOrderDetails)) {
                foreach ($aOrderDetails as $aOrderDetail) {
                    $this->aOrderDetails[] = new classOrderDetail($aOrderDetail);
                }
            }
        }
        return $this;
    }

    /**
     * @return classOrderDetail[]
     */
    public function getOrderDetails () {
        $this->fetchOrderDetails();
        return $this->aOrderDetails;
    }

    private function fetchOrderProductsManufacturers() {
        if (!empty($this->aOrderProductsManufactueres) && is_array($this->aOrderProductsManufactueres)) {
            $oManufacturers = new classManufacturers();
            $aManufacturersInfo = $oManufacturers->getMainufacturersInfo($this->aOrderProductsManufactueres);
            foreach ($this->aOrderProducts as &$oOrderProduct) {
                $key = array_search($oOrderProduct->getField('manufacturerid'), array_column($aManufacturersInfo, 'manufacturerid'));
                if ($key!== false) {
                    $oOrderProduct->setProductManufacturer($aManufacturersInfo[$key]);
                }
            }
        }
    }

    private function fetchOrderProducts() {
       if (empty($this->aOrderProducts)) {
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
    public function getOrderProducts () {
        $this->fetchOrderProducts();
        return $this->aOrderProducts;
    }

    public function getDisplayOrderNumber() {
        return $this->getField('order_prefix').$this->getField($this->sPrimaryKeyFiled);
    }

    public function getOrderModifyURL() {
        return sprintf(self::ADMIN_ORDER_MODIFY_URL,$this->getField($this->sPrimaryKeyFiled));
    }

}