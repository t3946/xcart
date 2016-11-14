<?php
namespace Xcart;

class OrderGroup extends Data
{
    const RECONCILED_NONE = 0;
    const RECONCILED_FULLY = 1;
    const RECONCILED_PARTIAL = 2;
    /**
     * @var Order
     */
    private $oOrder = null;
    /**
     * @var OrderGroupInvoices
     */
    private $oOrderInvoices = null;
    /**
     * @var OrderGroupMemos
     */
    private $oOrderMemos = null;
    /**
     * @var OrderRefundGroups
     */
    private $oOrderRefunds = null;
    /**
     * @var OrderAmazonDetails
     */
    private $oOrderAmazonDetails = null;

    private $oPaymentMethod = null;
    /**
     * @var Product[]
     */
    private $oOrderGroupProducts = [];

    /**
     * @var Shipping
     */
    private $oShippingMethod = null;

    /**
     * @var OrderDetail
     */
    private $aOrderDetails = null;

    private $availPaymentMethods = [];

    private $fCostToUs = null;

    private $oManufacturer = null;

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['orderid', 'manufacturerid'];
        $this->sPrimaryTable = 'order_groups';
        parent::__construct($aParams);

    }

    private function fetchOrderInstance()
    {
        $this->oOrder = Order::model(['orderid' => $this->getField('orderid')]);
    }

    public function getPaymentMethodId()
    {
        return $this->getField('acc_paymentid');
    }

    private function fetchPaymentMethodInstance()
    {
        $oPay = new PaymentMethod(['paymentid' => $this->getPaymentMethodId()]);
        $this->oPaymentMethod = $oPay->getPaymentMethodInstance(['paymentid' => $this->getPaymentMethodId()]);
    }

    public function getTotalGross()
    {
        return floatval($this->getField('total_gross'));
    }

    public function getTotalNet()
    {
        return floatval($this->getField('total_net'));
    }

    public function getTotalHST()
    {
        return floatval($this->getField('total_gst'));
    }

    public function getTotalPST()
    {
        return floatval($this->getField('total_pst'));
    }

    public function setTotalNet($fSumma)
    {
        $this->setField('total_net', $fSumma);
        return $this;
    }

    public function setTotalHST($fSumma)
    {
        $this->setField('total_gst', $fSumma);
        return $this;
    }

    public function setTotalPST($fSumma)
    {
        $this->setField('total_pst', $fSumma);
        return $this;
    }

    public function setTotalGross($fSumma)
    {
        $this->setField('total_gross', $fSumma);
        return $this;
    }

    public function getShippingGross()
    {
        return floatval($this->getField('shipping_gross'));
    }

    public function getShippingNet()
    {
        return floatval($this->getField('shipping_net'));
    }

    public function getShippingHST()
    {
        return floatval($this->getField('shipping_gst'));
    }

    public function getShippingPST()
    {
        return floatval($this->getField('shipping_pst'));
    }

    public function getTotalCostToUs()
    {
        if (empty($this->fCostToUs)) {
            $aCostToUs = func_query_first("SELECT sum(xo.item_cost_to_us*xo.amount) as cost_to_us_od, sum(xp.cost_to_us*xo.amount) as cost_to_us_pr
                                      FROM xcart_order_groups og
                                           INNER JOIN xcart_order_details xo USING (orderid)
                                           INNER JOIN xcart_products xp
                                              ON xp.productid = xo.productid AND
                                                 xp.manufacturerid = og.manufacturerid
                                     WHERE og.orderid = " . $this->getOrderId() . " AND og.manufacturerid = " . $this->getManufacturerId());
            $fCostToUs = floatval($aCostToUs['cost_to_us_od']);
            if (is_null($fCostToUs) || $fCostToUs == 0) {
                $fCostToUs = $aCostToUs['cost_to_us_pr'];
            }
            $this->fCostToUs = floatval($fCostToUs);
        }
        return $this->fCostToUs;
    }

    /**
     * @return OrderGroupInvoices
     */
    public function getOrderGroupInvoices()
    {
        if (is_null($this->oOrderInvoices)) {
            $this->oOrderInvoices = OrderGroupInvoices::model()->getOrderGroupInvoices(['orderid' => $this->getOrderId(), 'manufacturerid' => $this->getManufacturerId()]);
        }
        return $this->oOrderInvoices;
    }

    /**
     * @return OrderGroupMemos
     */
    public function getOrderGroupMemos()
    {
        if (is_null($this->oOrderMemos)) {
            $this->oOrderMemos = OrderGroupMemos::model()->getOrderGroupMemos(['orderid' => $this->getOrderId(), 'manufacturerid' => $this->getManufacturerId()]);
        }
        return $this->oOrderMemos;
    }

    /**
     * @return OrderRefundGroups
     */
    public function getOrderRefundGroups()
    {
        if (is_null($this->oOrderRefunds)) {
            $this->oOrderRefunds = OrderRefundGroups::model()->getOrderRefundGroups(['orderid' => $this->getOrderId(), 'manufacturerid' => $this->getManufacturerId()]);
        }
        return $this->oOrderRefunds;
    }

    /**
     * @return OrderAmazonDetails
     */
    public function getOrderAmazonDetails()
    {
        if (is_null($this->oOrderAmazonDetails)) {
            $this->oOrderAmazonDetails = OrderAmazonDetails::model()->getOrderAmazonDetails(['orderid' => $this->getOrderId(), 'manufacturerid' => $this->getManufacturerId()]);
        }
        return $this->oOrderAmazonDetails;
    }

    /**
     * @return Order
     */
    public function getOrderInstance()
    {
        if (is_null($this->oOrder)) {
            $this->fetchOrderInstance();
        }
        return $this->oOrder;
    }

    /**
     * @return PaymentMethod
     */
    public function getPaymentMethodInstance()
    {
        if (empty($this->oPaymentMethod)) {
            $this->fetchPaymentMethodInstance();
        }
        return $this->oPaymentMethod;
    }

    public function getPaymentMethodsAvailForOrderGroup()
    {
        if (empty($this->availPaymentMethods)) {
            $oSQL = SQLBuilder::getInstance()->addSelect('paymentid, payment_method')->addFromTable('payment_methods')->addCondition("acc_proc='Y'")->addOrderBy('orderby');
            if ($this->getOrderInstance()->getAmazonChanell())
                $oSQL->addCondition("order_tag_preference = '" . $this->getOrderInstance()->getAmazonChanell() . "'");
            $aPaymentMethods = $oSQL->Execute()->getQueryResult();
            if (!empty($aPaymentMethods)) {
                foreach ($aPaymentMethods as $aPaymentMethod) {
                    $this->availPaymentMethods[$aPaymentMethod['paymentid']] = $aPaymentMethod['payment_method'];
                }
            }
        }

        return $this->availPaymentMethods;
    }

    public function initAccounting()
    {
        $this->initAccountingNet()->initAccountingHST()->initAccountingPST()->initAccountingGross()->
        initAccountingGrossRefundToCustomer()->initAccountingNetRefundToCustomer()->initAccountingPSTRefundToCustomer()->initAccountingHSTRefundToCustomer()->
        initAccountingGrossRefundToUs()->initAccountingNetRefundToUs()->initAccountingPSTRefundToUs()->initAccountingHSTRefundToUs()->
        initAccountingGrossShipping()->initAccountingNetShipping()->initAccountingPSTShipping()->initAccountingHSTShipping();
    }

    public function initAccountingNet()
    {
        $this->setField('accounting_net_0', floatval($this->getField('total_net')));
        return $this;
    }

    /**
     * @return OrderGroup
     */
    public function initAccountingHST()
    {
        $this->setField('accounting_gst_0', floatval($this->getField('total_gst')));
        return $this;
    }

    /**
     * @return OrderGroup
     */
    public function initAccountingPST()
    {
        $this->setField('accounting_pst_0', floatval($this->getField('total_pst')));
        return $this;
    }

    /**
     * @return OrderGroup
     */
    public function initAccountingGross()
    {
        $this->setField('accounting_gross_0', $this->getTotalGross());
        return $this;
    }

    /**
     * @return OrderGroup
     */
    public function initAccountingGrossCostToUs()
    {
        $this->setField('accounting_gross_1_cost_to_us', $this->getTotalCostToUs());
        $this->recalculateAccountingCostToUs();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return OrderGroup
     */
    public function addAccountingPST($fSumma)
    {
        $this->setField('accounting_pst_0', floatval($this->getField('accounting_pst_0')) + floatval($fSumma));
        $this->recalculateAccountingNet();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return OrderGroup
     */
    public function addAccountingHST($fSumma)
    {
        $this->setField('accounting_gst_0', floatval($this->getField('accounting_gst_0')) + floatval($fSumma));
        $this->recalculateAccountingNet();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return OrderGroup
     */
    public function addAccountingNet($fSumma)
    {
        $this->setField('accounting_net_0', floatval($this->getField('total_net')) + floatval($fSumma));
        return $this;
    }

    /**
     * @param float $fSumma
     * @return OrderGroup
     */
    public function addAccountingGross($fSumma)
    {
        $this->setField('accounting_gross_0', $this->getTotalGross() + floatval($fSumma));
        $this->recalculateAccountingNet();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return OrderGroup
     */
    public function setAccountingGross($fSumma)
    {
        $this->setField('accounting_gross_0', floatval($fSumma));
        $this->recalculateAccountingNet();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return OrderGroup
     */
    public function addAccountingNetCostToUs($fSumma)
    {
        $this->setField('accounting_net_1_cost_to_us', floatval($this->getField('accounting_net_1_cost_to_us')) + floatval($fSumma));
        return $this;
    }

    /**
     * @param float $fSumma
     * @return OrderGroup
     */
    public function addAccountingGrossCostToUs($fSumma)
    {
        $this->setField('accounting_gross_1_cost_to_us', floatval($this->getField('accounting_gross_1_cost_to_us')) + floatval($fSumma));
        $this->recalculateAccountingCostToUs();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return OrderGroup
     */
    public function setAccountingGrossCostToUs($fSumma)
    {
        $this->setField('accounting_gross_1_cost_to_us', floatval($fSumma));
        $this->recalculateAccountingCostToUs();
        return $this;
    }

    public function getAccountingGrossCostToUs()
    {
        return $this->getField('accounting_gross_1_cost_to_us');
    }

    /**
     * @param float $fSumma
     * @return OrderGroup
     */
    public function addAccountingPSTCostToUs($fSumma)
    {
        $this->setField('accounting_pst_1_cost_to_us', floatval($this->getField('accounting_pst_1_cost_to_us')) + floatval($fSumma));
        $this->recalculateAccountingCostToUs();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return OrderGroup
     */
    public function addAccountingHSTCostToUs($fSumma)
    {
        $this->setField('accounting_gst_1_cost_to_us', floatval($this->getField('accounting_gst_1_cost_to_us')) + floatval($fSumma));
        $this->recalculateAccountingCostToUs();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return OrderGroup
     */
    public function setAccountingHSTCostToUs($fSumma)
    {
        $this->setField('accounting_gst_1_cost_to_us', floatval($fSumma));
        $this->recalculateAccountingCostToUs();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return OrderGroup
     */
    public function addAccountingNetShipping($fSumma)
    {
        $this->setField('accounting_net_2_shipping', floatval($this->getField('accounting_net_2_shipping')) + floatval($fSumma));
        return $this;
    }

    /**
     * @param float $fSumma
     * @return OrderGroup
     */
    public function addAccountingGrossShipping($fSumma)
    {
        $this->setField('accounting_gross_2_shipping', floatval($this->getField('accounting_gross_2_shipping')) + floatval($fSumma));
        $this->recalculateAccountingShipping();
        return $this;
    }

    /**
     * @return OrderGroup
     */
    public function initAccountingNetShipping()
    {
        $this->setField('accounting_net_2_shipping', 0);
        return $this;
    }

    /**
     * @return OrderGroup
     */
    public function initAccountingGrossShipping()
    {
        $this->setField('accounting_gross_2_shipping', 0);
        return $this;
    }

    /**
     * @return OrderGroup
     */
    public function initAccountingPSTShipping()
    {
        $this->setField('accounting_pst_2_shipping', 0);
        return $this;
    }

    /**
     * @return OrderGroup
     */
    public function initAccountingHSTShipping()
    {
        $this->setField('accounting_gst_2_shipping', 0);
        return $this;
    }

    /**
     * @param float $fSumma
     * @return OrderGroup
     */
    public function setAccountingGrossShipping($fSumma)
    {
        $this->setField('accounting_gross_2_shipping', floatval($fSumma));
        $this->recalculateAccountingShipping();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return OrderGroup
     */
    public function addAccountingPSTShipping($fSumma)
    {
        $this->setField('accounting_pst_2_shipping', floatval($this->getField('accounting_pst_2_shipping')) + floatval($fSumma));
        $this->recalculateAccountingShipping();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return OrderGroup
     */
    public function addAccountingHSTShipping($fSumma)
    {
        $this->setField('accounting_gst_2_shipping', floatval($this->getField('accounting_gst_2_shipping')) + floatval($fSumma));
        $this->recalculateAccountingShipping();
        return $this;
    }

    /**
     * @param float $fRefundSumma
     * @return OrderGroup
     */
    public function addAccountingNetRefundToCustomer($fRefundSumma)
    {
        $this->setField('accounting_net_3_ref_to_cust', $this->getField('accounting_net_3_ref_to_cust') + abs(floatval($fRefundSumma)));
        return $this;
    }

    /**
     * @param float $fRefundSumma
     * @return OrderGroup
     */
    public function addAccountingGrossRefundToCustomer($fRefundSumma)
    {
        $this->setField('accounting_gross_3_ref_to_cust', $this->getField('accounting_gross_3_ref_to_cust') + abs(floatval($fRefundSumma)));
        $this->recalculateAccountingRefundToCustomer();
        return $this;
    }

    /**
     * @param float $fRefundSumma
     * @return OrderGroup
     */
    public function setAccountingGrossRefundToCustomer($fRefundSumma)
    {
        $this->setField('accounting_gross_3_ref_to_cust', abs(floatval($fRefundSumma)));
        $this->recalculateAccountingRefundToCustomer();
        return $this;
    }

    /**
     * @param float $fRefundSumma
     * @return OrderGroup
     */
    public function setAccountingGrossRefundToUs($fRefundSumma)
    {
        $this->setField('accounting_gross_4_ref_to_us', abs(floatval($fRefundSumma)));
        $this->recalculateAccountingRefundToUs();
        return $this;
    }

    /**
     * @return OrderGroup
     */
    public function initAccountingNetRefundToCustomer()
    {
        $this->setField('accounting_net_3_ref_to_cust', 0);
        return $this;
    }

    /**
     * @return OrderGroup
     */
    public function initAccountingGrossRefundToCustomer()
    {
        $this->setField('accounting_gross_3_ref_to_cust', 0);
        return $this;
    }

    /**
     * @return OrderGroup
     */
    public function initAccountingPSTRefundToCustomer()
    {
        $this->setField('accounting_pst_3_ref_to_cust', 0);
        return $this;
    }

    /**
     * @return OrderGroup
     */
    public function initAccountingHSTRefundToCustomer()
    {
        $this->setField('accounting_gst_3_ref_to_cust', 0);
        return $this;
    }

    /**
     * @return OrderGroup
     */
    public function initAccountingNetRefundToUs()
    {
        $this->setField('accounting_net_4_ref_to_us', 0);
        return $this;
    }

    /**
     * @return OrderGroup
     */
    public function initAccountingGrossRefundToUs()
    {
        $this->setField('accounting_gross_4_ref_to_us', 0);
        return $this;
    }

    /**
     * @return OrderGroup
     */
    public function initAccountingPSTRefundToUs()
    {
        $this->setField('accounting_pst_4_ref_to_us', 0);
        $this->recalculateAccountingRefundToUs();
        return $this;
    }

    /**
     * @return OrderGroup
     */
    public function initAccountingHSTRefundToUs()
    {
        $this->setField('accounting_gst_4_ref_to_us', 0);
        $this->recalculateAccountingRefundToUs();
        return $this;
    }

    /**
     * @param float $fRefundSumma
     * @return OrderGroup
     */
    public function addAccountingPSTRefundToCustomer($fRefundSumma)
    {
        $this->setField('accounting_pst_3_ref_to_cust', $this->getField('accounting_pst_3_ref_to_cust') + abs(floatval($fRefundSumma)));
        $this->recalculateAccountingRefundToCustomer();
        return $this;
    }

    /**
     * @param float $fRefundSumma
     * @return OrderGroup
     */
    public function setAccountingPSTRefundToCustomer($fRefundSumma)
    {
        $this->setField('accounting_pst_3_ref_to_cust', abs(floatval($fRefundSumma)));
        $this->recalculateAccountingRefundToCustomer();
        return $this;
    }

    public function addAccountingHSTRefundToCustomer($fRefundSumma)
    {
        $this->setField('accounting_gst_3_ref_to_cust', $this->getField('accounting_gst_3_ref_to_cust') + abs(floatval($fRefundSumma)));
        $this->recalculateAccountingRefundToCustomer();
        return $this;
    }

    /**
     * @param float $fRefundSumma
     * @return OrderGroup
     */
    public function setAccountingHSTRefundToCustomer($fRefundSumma)
    {
        $this->setField('accounting_gst_3_ref_to_cust', abs(floatval($fRefundSumma)));
        $this->recalculateAccountingRefundToCustomer();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return OrderGroup
     */
    public function addAccountingNetRefundToUs($fSumma)
    {
        $this->setField('accounting_net_4_ref_to_us', floatval($this->getField('accounting_net_4_ref_to_us')) + floatval($fSumma));
        return $this;
    }

    /**
     * @param float $fSumma
     * @return OrderGroup
     */
    public function addAccountingGrossRefundToUs($fSumma)
    {
        $this->setField('accounting_gross_4_ref_to_us', floatval($this->getField('accounting_gross_4_ref_to_us')) + floatval($fSumma));
        $this->recalculateAccountingRefundToUs();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return OrderGroup
     */
    public function addAccountingPSTRefundToUs($fSumma)
    {
        $this->setField('accounting_pst_4_ref_to_us', floatval($this->getField('accounting_pst_4_ref_to_us')) + floatval($fSumma));
        $this->recalculateAccountingRefundToUs();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return OrderGroup
     */
    public function addAccountingHSTRefundToUs($fSumma)
    {
        $this->setField('accounting_gst_4_ref_to_us', floatval($this->getField('accounting_gst_4_ref_to_us')) + floatval($fSumma));
        $this->recalculateAccountingRefundToUs();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return OrderGroup
     */
    public function setAccountingHSTRefundToUs($fSumma)
    {
        $this->setField('accounting_gst_4_ref_to_us', floatval($fSumma));
        $this->recalculateAccountingRefundToUs();
        return $this;
    }

    public function getAccountingNetProfit()
    {
        return floatval($this->getField('accounting_net_5_profit'));
    }

    /**
     * @return OrderGroup
     */
    public function calculateAccountingNetProfit()
    {
        $this->setField('accounting_net_5_profit', (
            $this->getField('accounting_net_0') -
            $this->getField('accounting_net_1_cost_to_us') -
            $this->getField('accounting_net_2_shipping') -
            $this->getField('accounting_net_3_ref_to_cust') +
            $this->getField('accounting_net_4_ref_to_us')));
        return $this;
    }

    public function calculateAccountingPSTProfit()
    {
        $this->setField('accounting_pst_5_profit', (
            $this->getField('accounting_pst_0') -
            $this->getField('accounting_pst_1_cost_to_us') -
            $this->getField('accounting_pst_2_shipping') -
            $this->getField('accounting_pst_3_ref_to_cust') +
            $this->getField('accounting_pst_4_ref_to_us')));
        return $this;
    }

    /**
     * @return OrderGroup
     */
    public function calculateAccountingHSTProfit()
    {
        $this->setField('accounting_gst_5_profit', (
            $this->getField('accounting_gst_0') -
            $this->getField('accounting_gst_1_cost_to_us') -
            $this->getField('accounting_gst_2_shipping') -
            $this->getField('accounting_gst_3_ref_to_cust') +
            $this->getField('accounting_gst_4_ref_to_us')));
        return $this;
    }

    /**
     * @return OrderGroup
     */
    public function calculateAccountingGrossProfit()
    {
        $this->setField('accounting_gross_5_profit', (
            $this->getField('accounting_gross_0') -
            $this->getField('accounting_gross_1_cost_to_us') -
            $this->getField('accounting_gross_2_shipping') -
            $this->getField('accounting_gross_3_ref_to_cust') +
            $this->getField('accounting_gross_4_ref_to_us')));
        return $this;
    }

    public function calculateProfitMargin()
    {
        if (floatval($this->getField('accounting_gross_0') != 0))
            $fProfitMargin = round(floatval($this->getField('accounting_net_5_profit')) / floatval($this->getField('accounting_net_0')) * 100, 2);
        else $fProfitMargin = 0;
        $this->setField('profit_margin', $fProfitMargin);
        return $this;
    }

    public function recalculateAccountingNet()
    {
        $this->setField('accounting_net_0',
            floatval($this->getField('accounting_gross_0')) -
            floatval($this->getField('accounting_gst_0')) -
            floatval($this->getField('accounting_pst_0')));
    }

    public function recalculateAccountingCostToUs()
    {
        $this->setField('accounting_net_1_cost_to_us',
            floatval($this->getField('accounting_gross_1_cost_to_us')) -
            floatval($this->getField('accounting_gst_1_cost_to_us')) -
            floatval($this->getField('accounting_pst_1_cost_to_us')));
    }

    public function recalculateAccountingShipping()
    {
        $this->setField('accounting_net_2_shipping',
            floatval($this->getField('accounting_gross_2_shipping')) -
            floatval($this->getField('accounting_gst_2_shipping')) -
            floatval($this->getField('accounting_pst_2_shipping')));
    }

    public function recalculateAccountingRefundToCustomer()
    {
        $this->setField('accounting_net_3_ref_to_cust',
            floatval($this->getField('accounting_gross_3_ref_to_cust')) -
            floatval($this->getField('accounting_pst_3_ref_to_cust')) -
            floatval($this->getField('accounting_gst_3_ref_to_cust')));
    }

    public function recalculateAccountingRefundToUs()
    {
        $this->setField('accounting_net_4_ref_to_us',
            floatval($this->getField('accounting_gross_4_ref_to_us')) -
            floatval($this->getField('accounting_pst_4_ref_to_us')) -
            floatval($this->getField('accounting_gst_4_ref_to_us')));
    }

    /**
     * @return OrderGroup
     */
    public function recalculateAccountingProfit()
    {
        $this->calculateAccountingNetProfit()
            ->calculateAccountingPSTProfit()
            ->calculateAccountingHSTProfit()
            ->calculateAccountingGrossProfit()
            ->calculateProfitMargin();
        return $this;
    }

    public function recalculateAccounting()
    {
        $this->initAccounting();
        if ($this->getPaymentMethodInstance()->isPaymentMethodSet()) {
            if ($this->getOrderInstance()->isOrderAmazon() || $this->isOrderGroupShippedByAmazon()) $this->recalculateAccountingAmazon(); else {
                $this
                    ->setAccountingGross($this->getPaymentMethodInstance()->getSumAfterProcessorFee($this->getTotalGross()))
                    ->initAccountingHST()
                    ->initAccountingPST();

                if ($this->getOrderGroupInvoices()->countOrderGroupInvoices() > 0) {
                    $this->setAccountingGrossCostToUs($this->getOrderGroupInvoices()->getOrderGroupInvoicesProductTotal())->
                    setAccountingGrossShipping($this->getOrderGroupInvoices()->getOrderGroupInvoicesShippingTotal())->
                    setAccountingHSTCostToUs($this->getOrderGroupInvoices()->getOrderGroupInvoicesHST());
                }

                if ($this->getOrderGroupMemos()->countOrderGroupMemos() > 0) {
                    $this->setAccountingHSTRefundToUs($this->getOrderGroupMemos()->getOrderGroupMemoRefToUsHST());
                    $this->setAccountingGrossRefundToUs($this->getOrderGroupMemos()->getOrderGroupMemoRefToUsTotal());
                }

                if ($this->getOrderRefundGroups()->countOrderRefundGroups() > 0) {
                    $this
                        ->setAccountingPSTRefundToCustomer($this->getOrderRefundGroups()->getOrderRefundPST())
                        ->setAccountingHSTRefundToCustomer($this->getOrderRefundGroups()->getOrderRefundHST());

                    $totalRefund = $this->getPaymentMethodInstance()->getSumAfterProcessorFeeRefund($this->getOrderRefundGroups()->getOrderRefundTotal());
                    $this->setAccountingGrossRefundToCustomer($totalRefund);
                }

                $this->recalculateAccountingProfit()->updateAccounting();
            }
            $this->setAttentionTagMoneyLost();
        }
        return $this;
    }

    public function setAttentionTagMoneyLost()
    {
        if ($this->getAccountingNetProfit() < 0) {
            global $config;
            if (!$this->getOrderInstance()->isAttentionTagSet($config["Attention_tags_invoices"]["tag_for_PROFIT_LT_0"])) {
                $oAttentionTag = new AttentionTag(['status_id' => $config["Attention_tags_invoices"]["tag_for_PROFIT_LT_0"]]);
                $aInsertArray = ['orderid' => $this->getOrderId(), 'status_id' => $oAttentionTag->getStatusId()];
                func_array2insert('orders_additional_tags', $aInsertArray, true);
                $sLog = "Attention tag added: " . $oAttentionTag->getStatus() . "\n";
                Logs::_log('orders', $this->getOrderId(), 'X', $sLog);
            }
        }
    }

    public function recalculateAccountingAmazon()
    {
        $fRefund = $fPrincipalRefund = $fShippingRefund = $fShipping = $FBAPerOrderFulfillmentFee = $FBAPerUnitFulfillmentFee = $FBATransportationFee = $FBAWeightBasedFee = $AmazonCommission = $ShippingFee= 0;
        if ($this->getOrderAmazonDetails()->countOrderAmazonDetails() > 0) {
            $fRefund = $this->getOrderAmazonDetails()->getOrderAmazonRefund();
            $fPrincipalRefund = $this->getOrderAmazonDetails()->getOrderAmazonPrincipalRefund();
            $fShippingRefund = $this->getOrderAmazonDetails()->getOrderAmazonShippingRefund();
            $fShipping = $this->getOrderAmazonDetails()->getOrderAmazonShipping();
            $FBAPerOrderFulfillmentFee = $this->getOrderAmazonDetails()->getOrderAmazonFBAPerOrderFulfillmentFee();
            $FBAPerUnitFulfillmentFee = $this->getOrderAmazonDetails()->getOrderAmazonFBAPerUnitFulfillmentFee();
            $FBATransportationFee = $this->getOrderAmazonDetails()->getOrderAmazonFBATransportationFee();
            $FBAWeightBasedFee = $this->getOrderAmazonDetails()->getOrderAmazonFBAWeightBasedFee();
            $AmazonCommission = $this->getOrderAmazonDetails()->getOrderAmazonCommission();
            $ShippingFee = $this->getOrderAmazonDetails()->getOrderShippingFee();
        }
        $sAmazonChanell = $this->getOrderInstance()->getAmazonChanell();
        switch ($sAmazonChanell) {
            case 'MFN' :
                $this
                    ->setAccountingGross($this->getPaymentMethodInstance()->getSumAfterProcessorFee($this->getTotalGross()))
                    ->initAccountingHST()
                    ->initAccountingPST()
                    ->initAccountingGrossCostToUs();
                if ($this->getOrderGroupInvoices()->countOrderGroupInvoices() > 0) {
                    $this->setAccountingGrossCostToUs($this->getOrderGroupInvoices()->getOrderGroupInvoicesProductTotal())->
                    setAccountingGrossShipping($this->getOrderGroupInvoices()->getOrderGroupInvoicesShippingTotal())->
                    setAccountingHSTCostToUs($this->getOrderGroupInvoices()->getOrderGroupInvoicesHST());
                }
                $this->setAccountingGrossRefundToUs(abs($fRefund + $fPrincipalRefund) + abs($fShippingRefund));
                if ($this->getOrderGroupMemos()->countOrderGroupMemos() > 0) {
                    $this->addAccountingHSTRefundToUs($this->getOrderGroupMemos()->getOrderGroupMemoRefToUsHST())->
                    addAccountingGrossRefundToUs($this->getOrderGroupMemos()->getOrderGroupMemoRefToUsTotal());
                }
                break;
            case 'AFN' :
                $this
                    ->addAccountingGross(
                        $FBAPerOrderFulfillmentFee +
                        $FBAPerUnitFulfillmentFee +
                        $FBAWeightBasedFee +
                        $AmazonCommission +
                        $ShippingFee)->initAccountingGrossCostToUs()
                    ->setAccountingGrossShipping($fShipping + abs($FBATransportationFee));
                if ($this->getOrderAmazonDetails()->isRefundExists())
                    $this->setAccountingGrossRefundToUs($this->getAccountingGrossCostToUs() + abs($fRefund + $fPrincipalRefund) + abs($fShippingRefund));
                else $this->addAccountingGrossRefundToUs(abs($fRefund));

                break;

            default :
                $this->setAccountingGross($this->getPaymentMethodInstance()->getSumAfterProcessorFee($this->getTotalGross()))->initAccountingGrossCostToUs()
                    ->setAccountingGrossShipping(abs($FBAPerOrderFulfillmentFee +
                            $FBAPerUnitFulfillmentFee +
                            $FBAWeightBasedFee +
                            $AmazonCommission + $FBATransportationFee) + $fShipping + $ShippingFee);
                if ($this->getOrderAmazonDetails()->isRefundExists())
                    $this->setAccountingGrossRefundToUs($this->getAccountingGrossCostToUs() + abs($fRefund + $fPrincipalRefund) + abs($fShippingRefund));
                else $this->addAccountingGrossRefundToUs(abs($fRefund));

                break;
        }

        $this
            ->setAccountingGrossRefundToCustomer(abs($fPrincipalRefund + $fShippingRefund))
            ->recalculateAccountingProfit()
            ->updateAccounting();
    }

    public function updateAccounting()
    {
        func_array2update($this->sPrimaryTable, $this->aPrimaryTableValue, 'orderid = ' . $this->getOrderId() . ' and manufacturerid = ' . $this->getManufacturerId());
    }

    public function getReconciledStatus()
    {
        $resStatus = self::RECONCILED_NONE;
        $iCountInvoices = $this->getOrderGroupInvoices()->countOrderGroupInvoices();
        $iCountReconciledInvoices = $this->getOrderGroupInvoices()->countOrderGroupInvoicesReconciled();
        if ($iCountInvoices > 0 && $iCountReconciledInvoices > 0) {
            if ($iCountInvoices == $iCountReconciledInvoices) $resStatus = self::RECONCILED_FULLY; else
                $resStatus = self::RECONCILED_PARTIAL;
        }
        return $resStatus;
    }

    public function getOrderGroupProducts()
    {
        if (empty($this->oOrderGroupProducts)) {
            if ($this->getOrderId())
                $this->oOrderGroupProducts = Product::model()->findAll(
                    SQLBuilder::getInstance()->
                    addInnerJoin('order_details', 'od', 'od.productid=main.productid AND orderid = ' . $this->getOrderId())->
                    addCondition('manufacturerid = ' . $this->getManufacturerId()));
        }

        return $this->oOrderGroupProducts;
    }

    public function checkFBAProductsAvailToShipping()
    {
        $bResult = false;
        $this->getOrderGroupProducts();
        if (!empty($this->oOrderGroupProducts)) {
            foreach ($this->oOrderGroupProducts as $oProduct) {
                $iAmount = 0;
                $aOrderDetails = OrderDetail::getOrderDetailsByOrderIdAndProductId($this->getOrderId(), $oProduct->getProductId());

                foreach ($aOrderDetails as $oOrderDetail) {
                    $iAmount += $oOrderDetail->getAmount();
                }
                if ($oProduct->getAmazonFBAAvail() >= $iAmount) {
                    $bResult = true;
                } else {
                    $bResult = false;
                    break;
                }
            }
        }
        return $bResult;
    }

    public function updateAmazonShipmentNotes($sAmazonShipmentNotes)
    {
        $this->updateField('amz_customer_notes', addslashes($sAmazonShipmentNotes));
    }

    public function updateAmazonShipmentWithNotes($sAmazonShipmentNotes)
    {
        $this->updateField('amz_send_with_notes', $sAmazonShipmentNotes);
    }

    public function getAmazonShipmentNotes()
    {
        return $this->getField('amz_customer_notes');
    }

    public function shipOrderGroupByAmazon($sAmazonShippingMethodSelect)
    {
        $oAmazon = new AmazonMWS('FBAOutboundServiceMWS_Client', '/FulfillmentOutboundShipment/2010-10-01/');
        return $oAmazon->shipOrderGroupByAmazon($this, $sAmazonShippingMethodSelect);
    }

    public function getAmazonShippingOrderId()
    {
        return $this->getOrderInstance()->getOrderPrefix() . $this->getOrderId() . '-' . $this->getManufacturerId();
    }

    public function getShippingInstance()
    {
        if (empty($this->oShippingMethod)) {
            $this->oShippingMethod = Shipping::model(['shippingid'=>$this->getField('shippingid')]);
        }
        return $this->oShippingMethod;
    }

    public function getShippingMethodName()
    {
        $this->getShippingInstance();
        $iShippingId = $this->oShippingMethod->getField('shippingid');
        if (empty($iShippingId))
            return $this->getShipping();
        else
            return $this->oShippingMethod->getName();
    }

    public function getShipping()
    {
        return $this->getField('shipping');
    }

    public function getShippingId()
    {
        return $this->getField('shippingid');
    }

    public function getOrderId()
    {
        return $this->getField('orderid');
    }

    public function getManufacturerId()
    {
        return $this->getField('manufacturerid');
    }

    /**
     * @return Manufacturer
     */
    public function getManufacturerEntity()
    {
        if (is_null($this->oManufacturer))
            $this->oManufacturer = new Manufacturer($this->getManufacturerId());
        return $this->oManufacturer;
    }

    public function changeOrderGroupStatusDC($sNewStatus)
    {
        $this->updateField('dc_status', $sNewStatus);
        return $this;
    }

    public function changeOrderGroupStatusCB($sNewStatus)
    {
        $this->updateField('cb_status', $sNewStatus);
        return $this;
    }

    public function changeOrderGroupStatusBD($sNewStatus)
    {
        $this->updateField('bd_status', $sNewStatus);
        return $this;
    }

    public function getOrderGroupStatusDC()
    {
        return $this->getField('dc_status');
    }

    public function getOrderGroupStatusCB()
    {
        return $this->getField('cb_status');
    }

    public function getOrderGroupStatusBD()
    {
        return $this->getField('bd_status');
    }

    public function isOrderGroupShippedByAmazon()
    {
        return ($this->getField('amz_fullfilment_order_placed') == 'Y');
    }

    public function getOrderDetails()
    {
        if (is_null($this->aOrderDetails)) {
            $this->aOrderDetails = OrderDetail::model()->findAll(SQLBuilder::getInstance()->
            addInnerJoin('products', 'p', "p.productid = main.productid AND p.manufacturerid = " . $this->getManufacturerId())->
            addCondition('orderid = ' . $this->getOrderId()));
        }
        return $this->aOrderDetails;
    }

    private function calculateTotalNet()
    {
        $this->setTotalNet($this->getTotalGross() - $this->getTotalHST() - $this->getTotalPST());
    }

    public function reCalculateTotals()
    {
        $aOrderDetails = $this->getOrderDetails();
        if (!empty($aOrderDetails)) {
            $this->setTotalGross(0)->setTotalNet(0);
            foreach ($aOrderDetails as $oOrderDetail) {
                $this->setTotalGross($this->getTotalGross() + ($oOrderDetail->getTotalProductPrice()));
            }
            $this->setTotalGross($this->getTotalGross() + $this->getShippingGross());
            $this->calculateTotalNet();
            $this->_save();
        }

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

    public function getRetailTrustTotalNet()
    {
        $fSumma = 0;
        $aOrderDetails = $this->getOrderDetailsWithRetailTrust();
        if (!empty($aOrderDetails)) {
            foreach ($aOrderDetails as $oOrderDetail) {
                $fSumma += $oOrderDetail->getRetailTrustPrice();
            }
        }
        return $fSumma;
    }

    public function getRetailTrustTotalGross()
    {
        $fSumma = 0;
        $aOrderDetails = $this->getOrderDetailsWithRetailTrust();
        if (!empty($aOrderDetails)) {
            foreach ($aOrderDetails as $oOrderDetail) {
                $fSumma += $oOrderDetail->getRetailTrustGross();
            }
        }
        return $fSumma;
    }

    public function addTotalNet($fSumma)
    {
        $this->setField('total_net', floatval($this->getField('total_net')) + $fSumma);
        return $this;
    }

    public function addTotalGross($fSumma)
    {
        $this->setField('total_gross', floatval($this->getField('total_gross')) + $fSumma);
        return $this;
    }
}