<?php
namespace Xcart;

class Order extends Data
{
    const ORDER_VERIFICATION_STATUS_PRODUCT_VERIFIED = 'PV';
    const ORDER_VERIFICATION_STATUS_PRODUCT_PROBLEM_FOUND = 'PF';
    const ORDER_VERIFICATION_STATUS_PRODUCT_IN_PROGRESS = 'IP';
    const ORDER_VERIFICATION_STATUS_PRODUCT_NOT_YET_STARTED = 'NS';

    const ADMIN_ORDER_MODIFY_URL = '/admin/order.php?orderid=%d';

    /**
     * @var OrderDetail[]
     */
    private $aOrderDetails = null;
    /**
     * @var OrderGroup[]
     */
    private $aOrderGroups = null;
    /**
     * @var Product[]
     */
    private $aOrderProducts = null;
    /**
     * @var Customer[]
     */
    private $oCustomer = null;


    private $aOrderProductsManufactueres = null;
    private $aAdditionalFees = null;
    private $oPaymentMethod = null;

    public function __construct($aOrderData = null)
    {
        $this->aPrimaryKeys = ['orderid'];
        $this->sPrimaryTable = 'orders';

        parent::__construct($aOrderData);
    }


    private function fetchOrderGroups()
    {
        if (is_null($this->aOrderGroups)) {
            $this->aOrderGroups = OrderGroup::model()->findAll(SQLBuilder::getInstance()->addCondition('orderid = ' . $this->getOrderId()));
        }
        return $this;
    }

    /**
     * @return OrderDetail[]
     */
    public function getOrderDetails()
    {
        if (empty($this->aOrderDetails)) {
            $this->aOrderDetails = OrderDetail::model()->findAll(SQLBuilder::getInstance()->addCondition('orderid = ' . $this->getOrderId()));
        }
        return $this->aOrderDetails;
    }

    /**
     * @return OrderDetail[]
     */
    public function getOrderDetailsWithRetailTrust()
    {
        $aResult = [];
        $this->getOrderDetails();
        if (!empty($this->aOrderDetails)) {
            foreach ($this->aOrderDetails as $oOrderDetail) {
                if ($oOrderDetail->isRetailTrustEnabled())
                    $aResult[] = $oOrderDetail;
            }
        }
        return $aResult;
    }

    public function getOrderRetailTrustPrice()
    {
        $fResult = 0;
        $aRetaiTrust = $this->getOrderDetailsWithRetailTrust();
        if (!empty($aRetaiTrust)) {
            foreach ($aRetaiTrust as $oRetailTrust) {
                $fResult += floatval($oRetailTrust->getRetailTrustPrice());
            }
        }
        return $fResult;
    }

    public function getOrderRetailTrustGross()
    {
        $fResult = 0;
        $aRetaiTrust = $this->getOrderDetailsWithRetailTrust();
        if (!empty($aRetaiTrust)) {
            foreach ($aRetaiTrust as $oRetailTrust) {
                $fResult += floatval($oRetailTrust->getRetailTrustGross());
            }
        }
        return $fResult;
    }

    /**
     * @return Product
     */
    public function getOrderDetailsProductsWithRetailTrust()
    {
        $aResult = [];
        $this->getOrderDetails();
        if (!empty($this->aOrderDetails)) {
            foreach ($this->aOrderDetails as $oOrderDetail) {
                if ($oOrderDetail->getOrderDetailProduct()->isRetailTrustEnabled())
                    $aResult[] = $oOrderDetail->getOrderDetailProduct();
            }
        }
        return $aResult;
    }

    /**
     * @return OrderGroup[]
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
            $oManufacturers = new Manufacturers();
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
                $oProducts = new Products();
                foreach ($aOrderDetails as $oOrderDetail) {
                    $aProductIds[] = $oOrderDetail->getField('productid');
                }
                $aProducts = $oProducts->getProductsInfo($aProductIds);
                if (!empty($aProducts) && is_array($aProducts)) {
                    $this->aOrderProductsManufactueres = [];
                    foreach ($aProducts as $aProduct) {
                        $oProduct = new Product();
                        $oProduct->fill($aProduct);
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
     * @return Product[]
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
        return $this->getField('order_prefix') . $this->getOrderId();
    }

    public function getOrderModifyURL()
    {
        return sprintf(self::ADMIN_ORDER_MODIFY_URL, $this->getOrderId());
    }

    public function changeVerificationStatus($sNewStatus)
    {
        $bResult['result'] = true;
        $oNewStatus = OrderStatus::model(['code' => $sNewStatus]);
        $oOldStatus = OrderStatus::model(['code' => $this->getField('vn_status')]);
        if ($oNewStatus->getField('code') != $oOldStatus->getField('code')) {
            $this->updateField('vn_status', $sNewStatus);
            $log = "vn_status: " . $oOldStatus->getField('name') . " -> " . $oNewStatus->getField('name');
            func_log_order($this->getOrderId(), 'X', $log);
        }
        $this->setField('vn_status', $sNewStatus);
        return $bResult;
    }

    public function updateVerificationStatus()
    {
        $aOrderProducts = $this->getOrderProducts();
        if (!empty($aOrderProducts) && is_array($aOrderProducts)) {
            $iMaxStatus = $iMinStatus = null;
            foreach ($aOrderProducts as $oOrderProduct) {
                if ($oOrderProduct instanceof Product) {
                    $iVerifyStatus = $oOrderProduct->getField('verification_statusid');
                    if (is_null($iMinStatus)) $iMinStatus = $iVerifyStatus;
                    $iMaxStatus = max($iMaxStatus, $iVerifyStatus);
                    $iMinStatus = min($iMinStatus, $iVerifyStatus);
                }
            }
            if ($this->getAmazonChanell() == 'AFN') {
                $this->changeVerificationStatus(self::ORDER_VERIFICATION_STATUS_PRODUCT_VERIFIED);
            } elseif ($iMinStatus == $iMaxStatus) {
                switch ($iMaxStatus) {
                    case (Product::PRODUCT_STATUS_NOT_VERIFY) :
                        $this->changeVerificationStatus(self::ORDER_VERIFICATION_STATUS_PRODUCT_NOT_YET_STARTED);
                        break;
                    case (Product::PRODUCT_STATUS_PROBLEM_NOT_FIXED) :
                    case (Product::PRODUCT_STATUS_PROBLEM_FIXED) :
                        $this->changeVerificationStatus(self::ORDER_VERIFICATION_STATUS_PRODUCT_PROBLEM_FOUND);
                        break;
                    case (Product::PRODUCT_STATUS_VERIFY):
                        $this->changeVerificationStatus(self::ORDER_VERIFICATION_STATUS_PRODUCT_VERIFIED);
                        break;
                }

            } elseif ($iMinStatus == Product::PRODUCT_STATUS_NOT_VERIFY && $iMaxStatus > Product::PRODUCT_STATUS_NOT_VERIFY) {
                $this->changeVerificationStatus(self::ORDER_VERIFICATION_STATUS_PRODUCT_IN_PROGRESS);
            } elseif ($iMinStatus > Product::PRODUCT_STATUS_NOT_VERIFY && $iMaxStatus > Product::PRODUCT_STATUS_NOT_VERIFY) {
                $this->changeVerificationStatus(self::ORDER_VERIFICATION_STATUS_PRODUCT_PROBLEM_FOUND);
            }

        }
        return $this;
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


    public function getFirstName()
    {
        return $this->getField('firstname');
    }

    public function getEmail()
    {
        return $this->getField('email');
    }

    public function getShippingFirstName()
    {
        return $this->getField('s_firstname');
    }

    public function getShippingLastName()
    {
        return $this->getField('s_lastname');
    }

    public function getShippingCompany()
    {
        return $this->getField('s_company');
    }

    public function getShippingCity()
    {
        return $this->getField('s_city');
    }

    public function getShippingCounty()
    {
        return $this->getField('s_county');
    }

    public function getShippingState()
    {
        return $this->getField('s_state');
    }

    public function getShippingCountry()
    {
        return $this->getField('s_country');
    }

    public function getShippingAddress()
    {
        $row = [];
        list($row['s_address'], $row['s_address_2']) = explode("\n", $this->getField('s_address'), 2);
        return $row['s_address'];
    }

    public function getShippingAddress2()
    {
        $row = [];
        list($row['s_address'], $row['s_address_2']) = explode("\n", $this->getField('s_address'), 2);
        return $row['s_address_2'];
    }

    public function getShippingZipCode()
    {
        return $this->getField('s_zipcode');
    }

    public function getBillingCompany()
    {
        return $this->getField('b_company');
    }

    public function getBillingFirstName()
    {
        return $this->getField('b_firstname');
    }

    public function getBillingLastName()
    {
        return $this->getField('b_lastname');
    }

    public function getBillingCity()
    {
        return $this->getField('b_city');
    }

    public function getBillingCounty()
    {
        return $this->getField('b_county');
    }

    public function getBillingState()
    {
        return $this->getField('b_state');
    }

    public function getBillingCountry()
    {
        return $this->getField('b_country');
    }

    public function getBillingAddress()
    {
        $row = [];
        list($row['b_address'], $row['b_address_2']) = explode("\n", $this->getField('b_address'), 2);
        return $row['b_address'];
    }

    public function getBillingAddress2()
    {
        $row = [];
        list($row['b_address'], $row['b_address_2']) = explode("\n", $this->getField('b_address'), 2);
        return $row['b_address_2'];
    }

    public function getBillingZipCode()
    {
        return $this->getField('b_zipcode');
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
        $date = new \DateTime();
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
        $aOrderTransactions = new OrderTransactions();
        try {
            $aOrderTransactions->captureOrderAmount($this);
        } catch (\Exception $ex) {
            func_log_order($this->getOrderId(), 'X', $ex->getMessage(), $login);
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
        $oSQL = new SQLBuilder();
        $aQResult = $oSQL->init()->addSelect('status_id')->addFromTable('orders_additional_tags')->addCondition('orderid=' . $this->getOrderId())->addCondition('status_id=' . $iStatusId)->Execute()->getQueryResult();
        return !empty($aQResult);
    }

    public function getOrderAdditionalFee()
    {
        $fResult = 0;
        if (is_null($this->aAdditionalFees)) {
            $oSQL = new SQLBuilder();
            $this->aAdditionalFees = $oSQL->init()->addSelect('*')->addFromTable('order_additional_fee')->addCondition('orderid=' . $this->getOrderId())->Execute()->getQueryResult();
        }
        if (!empty($this->aAdditionalFees)) {
            foreach ($this->aAdditionalFees as $aAFee) {
                $fResult += floatval($aAFee['additional_fee_value']);
            }
        }
        return floatval($fResult);
    }

    public function getOrderShippingNet()
    {
        $fResult = 0;
        $this->fetchOrderGroups();
        if (!empty($this->aOrderGroups)) {
            foreach ($this->aOrderGroups as $oOrderGroup) {
                $fResult += $oOrderGroup->getShippingNet();
            }
        }
        return $fResult;
    }

    public function getOrderShippingGross()
    {
        $fResult = 0;
        $this->fetchOrderGroups();
        if (!empty($this->aOrderGroups)) {
            foreach ($this->aOrderGroups as $oOrderGroup) {
                $fResult += $oOrderGroup->getShippingGross();
            }
        }
        return $fResult;
    }

    public function getOrderShippingHST()
    {
        $fResult = 0;
        $this->fetchOrderGroups();
        if (!empty($this->aOrderGroups)) {
            foreach ($this->aOrderGroups as $oOrderGroup) {
                $fResult += $oOrderGroup->getShippingHST();
            }
        }
        return floatval($fResult + $this->getOrderShippingPST());
    }

    public function getOrderShippingPST()
    {
        $fResult = 0;
        $this->fetchOrderGroups();
        if (!empty($this->aOrderGroups)) {
            foreach ($this->aOrderGroups as $oOrderGroup) {
                $fResult += $oOrderGroup->getShippingPST();
            }
        }
        return $fResult;
    }

    public function getOrderTotalNet()
    {
        $fResult = 0;
        $this->fetchOrderGroups();
        if (!empty($this->aOrderGroups)) {
            foreach ($this->aOrderGroups as $oOrderGroup) {
                $fResult += $oOrderGroup->getTotalNet();
            }
        }
        return $fResult + $this->getOrderAdditionalFee();
    }

    public function getOrderTotalHST()
    {
        $fResult = 0;
        $this->fetchOrderGroups();
        if (!empty($this->aOrderGroups)) {
            foreach ($this->aOrderGroups as $oOrderGroup) {
                $fResult += $oOrderGroup->getTotalHST();
            }
        }
        return floatval($fResult + $this->getOrderTotalPST());
    }

    public function getOrderTotalPST()
    {
        $fResult = 0;
        $this->fetchOrderGroups();
        if (!empty($this->aOrderGroups)) {
            foreach ($this->aOrderGroups as $oOrderGroup) {
                $fResult += $oOrderGroup->getTotalPST();
            }
        }
        return $fResult;
    }

    public function getOrderTotalGross()
    {
        $fResult = 0;
        $this->fetchOrderGroups();
        if (!empty($this->aOrderGroups)) {
            foreach ($this->aOrderGroups as $oOrderGroup) {
                $fResult += $oOrderGroup->getTotalGross();
            }
        }
        return $fResult + $this->getOrderAdditionalFee();
    }

    public function getOrderCostToUs()
    {
        $fResult = 0;
        $this->fetchOrderGroups();
        if (!empty($this->aOrderGroups)) {
            foreach ($this->aOrderGroups as $oOrderGroup) {
                $fResult += $oOrderGroup->getTotalCostToUs();
            }
        }
        return $fResult;
    }

    public function getProductPriceNet()
    {
        $fResult = 0;
        $this->getOrderDetails();
        if (!empty($this->aOrderDetails)) {
            foreach ($this->aOrderDetails as $oOrderDetail) {
                $fResult += $oOrderDetail->getTotalProductPrice();
            }
        }
        return floatval($fResult);
    }

    public function getProductPriceHSTPST()
    {
        $fResult = 0;
        $this->getOrderDetails();
        if (!empty($this->aOrderDetails)) {
            foreach ($this->aOrderDetails as $oOrderDetail) {
                $fResult += $oOrderDetail->getProductHST();
                $fResult += $oOrderDetail->getProductPST();
            }
        }
        return floatval($fResult);
    }

    public function getProductPriceGross()
    {
        return floatval($this->getProductPriceNet() + $this->getProductPriceHSTPST());
    }

    public function getOrderGrandTotalNet()
    {
        return floatval($this->getOrderTotalNet() + $this->getOrderRetailTrustPrice());
    }

    public function getPaymentMethodId()
    {
        return $this->getField('paymentid');
    }

    public function getPaymentMethodInstance()
    {
        if (is_null($this->oPaymentMethod)) {
            $oPay = new PaymentMethod(['paymentid' => $this->getPaymentMethodId()]);
            $this->oPaymentMethod = $oPay->getPaymentMethodInstance(['paymentid' => $this->getPaymentMethodId()]);
        }
        return $this->oPaymentMethod;
    }

    public function reCalculateTotals()
    {
        $aOrderGroups = $this->getOrderGroups();
        if (!empty($aOrderGroups))
            foreach ($aOrderGroups as $oOrderGroup) {
                $oOrderGroup->reCalculateTotals();
            }
    }

    public function getPOPipelineInstance()
    {
        return POPipeLine::model()->find(SQLBuilder::getInstance()->addCondition('order_id=' . $this->getOrderId()));
    }

    public function getCustomerEntity()
    {
        if (is_null($this->oCustomer)) {
            $this->oCustomer = new Customer(['login'=>$this->getLogin()]);
        }
        return $this->oCustomer;
    }

    public function getLogin()
    {
        return $this->getField('login');
    }

    public function recalculateRetailTrust()
    {
        $this->_refresh();
        $fTotalRetailTrust = 0;
        $aOrderGroups = $this->getOrderGroups();
        if (!empty($aOrderGroups)) {
            foreach ($aOrderGroups as $oOrderGroup) {
                $fTotalRetailTrustGroup = 0;
                $aOrderDetails = $oOrderGroup->getOrderDetailsWithRetailTrust();
                if (!empty($aOrderDetails)) {
                    foreach ($aOrderDetails as $oOrderDetail) {
                        $fTotalRetailTrustGroup += $oOrderDetail->getRetailTrustPrice();
                    }
                    $oOrderGroup->addTotalNet($fTotalRetailTrustGroup)->addTotalGross($fTotalRetailTrustGroup)->_update();
                    $fTotalRetailTrust += $fTotalRetailTrustGroup;
                }
            }
            $this->addOrderTotaNet($fTotalRetailTrust)->addOrderTotalGross($fTotalRetailTrust)->addOrderTotal($fTotalRetailTrust)->_update();
            $this->_refresh();
        }
        return $this;
    }
}