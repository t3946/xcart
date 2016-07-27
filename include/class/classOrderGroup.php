<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";
require_once $xcart_dir . "/include/class/classOrders.php";
require_once $xcart_dir . "/include/class/classProduct.php";
require_once $xcart_dir . "/include/class/classShipping.php";
require_once $xcart_dir . "/include/class/classPaymentMethod.php";
require_once $xcart_dir . "/include/class/classOrderGroupInvoices.php";
require_once $xcart_dir . "/include/class/classOrderGroupMemos.php";
require_once $xcart_dir . "/include/class/classOrderRefundGroups.php";
require_once $xcart_dir . "/include/class/classOrderAmazonDetails.php";
require_once $xcart_dir . "/include/class/classAmazonMWS.php";

class classOrderGroup extends classData
{
    const RECONCILED_NONE = 0;
    const RECONCILED_FULLY = 1;
    const RECONCILED_PARTIAL = 2;

    /**
     * @var classOrder
     */
    private $oOrder;
    /**
     * @var classOrderGroupInvoice[]
     */
    private $oOrderInvoices = [];
    /**
     * @var classOrderGroupMemo[]
     */
    private $oOrderMemos = [];
    /**
     * @var classOrderRefundGroup[]
     */
    private $oOrderRefunds = [];
    /**
     * @var classOrderAmazonDetail[]
     */
    private $oOrderAmazonDetails = [];

    private $oPaymentMethod = null;
    /**
     * @var classProduct[]
     */
    private $oOrderGroupProducts = [];

    /**
     * @var classShipping
     */
    private $oShippingMethod = null;

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['orderid', 'manufacturerid'];
        $this->sPrimaryTable = 'order_groups';
        parent::__construct($aParams);

    }

    private function fetchOrderInstance()
    {
        $this->oOrder = new classOrder($this->getField('orderid'));
    }

    private function fetchPaymentMethodInstance()
    {
        $oPay = new classPaymentMethod(['paymentid' => $this->getField('acc_paymentid')]);
        $this->oPaymentMethod = $oPay->getPaymentMethodInstance(['paymentid' => $this->getField('acc_paymentid')]);
    }

    private function getTotalCostToUs()
    {
        $aCostToUs = func_query_first("SELECT sum(xo.item_cost_to_us) as cost_to_us_od, sum(xp.cost_to_us) as cost_to_us_pr
                                      FROM xcart_order_groups og
                                           INNER JOIN xcart_order_details xo USING (orderid)
                                           INNER JOIN xcart_products xp
                                              ON xp.productid = xo.productid AND
                                                 xp.manufacturerid = og.manufacturerid
                                     WHERE og.orderid = " . $this->getField('orderid'));
        $fCostToUs = $aCostToUs['cost_to_us_od'];
        if (is_null($fCostToUs)) {
            $fCostToUs = $aCostToUs['cost_to_us_pr'];
        }
        return $fCostToUs;
    }

    /**
     * @return classOrderGroupInvoices
     */
    public function getOrderGroupInvoices()
    {
        if (empty($this->oOrderInvoices)) {
            $this->oOrderInvoices = new classOrderGroupInvoices();
            $this->oOrderInvoices = $this->oOrderInvoices->getOrderGroupInvoices(['orderid' => $this->getField('orderid'), 'manufacturerid' => $this->getField('manufacturerid')]);
        }
        return $this->oOrderInvoices;
    }

    /**
     * @return classOrderGroupMemos
     */
    public function getOrderGroupMemos()
    {
        if (empty($this->oOrderMemos)) {
            $this->oOrderMemos = new classOrderGroupMemos();
            $this->oOrderMemos = $this->oOrderMemos->getOrderGroupMemos(['orderid' => $this->getField('orderid'), 'manufacturerid' => $this->getField('manufacturerid')]);
        }
        return $this->oOrderMemos;
    }

    /**
     * @return classOrderRefundGroups
     */
    public function getOrderRefundGroups()
    {
        if (empty($this->oOrderRefunds)) {
            $this->oOrderRefunds = new classOrderRefundGroups();
            $this->oOrderRefunds = $this->oOrderRefunds->getOrderRefundGroups(['orderid' => $this->getField('orderid'), 'manufacturerid' => $this->getField('manufacturerid')]);
        }
        return $this->oOrderRefunds;
    }

    /**
     * @return classOrderAmazonDetails
     */
    public function getOrderAmazonDetails()
    {
        if (empty($this->oOrderAmazonDetails)) {
            $this->oOrderAmazonDetails = new classOrderAmazonDetails();
            $this->oOrderAmazonDetails = $this->oOrderAmazonDetails->getOrderAmazonDetails(['orderid' => $this->getField('orderid'), 'manufacturerid' => $this->getField('manufacturerid')]);
        }
        return $this->oOrderAmazonDetails;
    }

    /**
     * @return classOrder
     */
    public function getOrderInstance()
    {
        if (empty($this->oOrder)) {
            $this->fetchOrderInstance();
        }
        return $this->oOrder;
    }

    /**
     * @return classPaymentMethod
     */
    public function getPaymentMethodInstance()
    {
        if (empty($this->oPaymentMethod)) {
            $this->fetchPaymentMethodInstance();
        }
        return $this->oPaymentMethod;
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
     * @return classOrderGroup
     */
    public function initAccountingHST()
    {
        $this->setField('accounting_gst_0', floatval($this->getField('total_gst')));
        return $this;
    }

    /**
     * @return classOrderGroup
     */
    public function initAccountingPST()
    {
        $this->setField('accounting_pst_0', floatval($this->getField('total_pst')));
        return $this;
    }

    /**
     * @return classOrderGroup
     */
    public function initAccountingGross()
    {
        $this->setField('accounting_gross_0', floatval($this->getField('total_gross')));
        return $this;
    }

    /**
     * @return classOrderGroup
     */
    public function initAccountingGrossCostToUs()
    {
        $this->setField('accounting_gross_1_cost_to_us', floatval($this->getTotalCostToUs()));
        $this->recalculateAccountingCostToUs();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return classOrderGroup
     */
    public function addAccountingPST($fSumma)
    {
        $this->setField('accounting_pst_0', floatval($this->getField('accounting_pst_0')) + floatval($fSumma));
        $this->recalculateAccountingNet();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return classOrderGroup
     */
    public function addAccountingHST($fSumma)
    {
        $this->setField('accounting_gst_0', floatval($this->getField('accounting_gst_0')) + floatval($fSumma));
        $this->recalculateAccountingNet();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return classOrderGroup
     */
    public function addAccountingNet($fSumma)
    {
        $this->setField('accounting_net_0', floatval($this->getField('total_net')) + floatval($fSumma));
        return $this;
    }

    /**
     * @param float $fSumma
     * @return classOrderGroup
     */
    public function addAccountingGross($fSumma)
    {
        $this->setField('accounting_gross_0', floatval($this->getField('total_gross')) + floatval($fSumma));
        $this->recalculateAccountingNet();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return classOrderGroup
     */
    public function setAccountingGross($fSumma)
    {
        $this->setField('accounting_gross_0', floatval($fSumma));
        $this->recalculateAccountingNet();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return classOrderGroup
     */
    public function addAccountingNetCostToUs($fSumma)
    {
        $this->setField('accounting_net_1_cost_to_us', floatval($this->getField('accounting_net_1_cost_to_us')) + floatval($fSumma));
        return $this;
    }

    /**
     * @param float $fSumma
     * @return classOrderGroup
     */
    public function addAccountingGrossCostToUs($fSumma)
    {
        $this->setField('accounting_gross_1_cost_to_us', floatval($this->getField('accounting_gross_1_cost_to_us')) + floatval($fSumma));
        $this->recalculateAccountingCostToUs();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return classOrderGroup
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
     * @return classOrderGroup
     */
    public function addAccountingPSTCostToUs($fSumma)
    {
        $this->setField('accounting_pst_1_cost_to_us', floatval($this->getField('accounting_pst_1_cost_to_us')) + floatval($fSumma));
        $this->recalculateAccountingCostToUs();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return classOrderGroup
     */
    public function addAccountingHSTCostToUs($fSumma)
    {
        $this->setField('accounting_gst_1_cost_to_us', floatval($this->getField('accounting_gst_1_cost_to_us')) + floatval($fSumma));
        $this->recalculateAccountingCostToUs();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return classOrderGroup
     */
    public function setAccountingHSTCostToUs($fSumma)
    {
        $this->setField('accounting_gst_1_cost_to_us', floatval($fSumma));
        $this->recalculateAccountingCostToUs();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return classOrderGroup
     */
    public function addAccountingNetShipping($fSumma)
    {
        $this->setField('accounting_net_2_shipping', floatval($this->getField('accounting_net_2_shipping')) + floatval($fSumma));
        return $this;
    }

    /**
     * @param float $fSumma
     * @return classOrderGroup
     */
    public function addAccountingGrossShipping($fSumma)
    {
        $this->setField('accounting_gross_2_shipping', floatval($this->getField('accounting_gross_2_shipping')) + floatval($fSumma));
        $this->recalculateAccountingShipping();
        return $this;
    }

    /**
     * @return classOrderGroup
     */
    public function initAccountingNetShipping()
    {
        $this->setField('accounting_net_2_shipping', 0);
        return $this;
    }

    /**
     * @return classOrderGroup
     */
    public function initAccountingGrossShipping()
    {
        $this->setField('accounting_gross_2_shipping', 0);
        return $this;
    }

    /**
     * @return classOrderGroup
     */
    public function initAccountingPSTShipping()
    {
        $this->setField('accounting_pst_2_shipping', 0);
        return $this;
    }

    /**
     * @return classOrderGroup
     */
    public function initAccountingHSTShipping()
    {
        $this->setField('accounting_gst_2_shipping', 0);
        return $this;
    }

    /**
     * @param float $fSumma
     * @return classOrderGroup
     */
    public function setAccountingGrossShipping($fSumma)
    {
        $this->setField('accounting_gross_2_shipping', floatval($fSumma));
        $this->recalculateAccountingShipping();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return classOrderGroup
     */
    public function addAccountingPSTShipping($fSumma)
    {
        $this->setField('accounting_pst_2_shipping', floatval($this->getField('accounting_pst_2_shipping')) + floatval($fSumma));
        $this->recalculateAccountingShipping();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return classOrderGroup
     */
    public function addAccountingHSTShipping($fSumma)
    {
        $this->setField('accounting_gst_2_shipping', floatval($this->getField('accounting_gst_2_shipping')) + floatval($fSumma));
        $this->recalculateAccountingShipping();
        return $this;
    }

    /**
     * @param float $fRefundSumma
     * @return classOrderGroup
     */
    public function addAccountingNetRefundToCustomer($fRefundSumma)
    {
        $this->setField('accounting_net_3_ref_to_cust', $this->getField('accounting_net_3_ref_to_cust') + abs(floatval($fRefundSumma)));
        return $this;
    }

    /**
     * @param float $fRefundSumma
     * @return classOrderGroup
     */
    public function addAccountingGrossRefundToCustomer($fRefundSumma)
    {
        $this->setField('accounting_gross_3_ref_to_cust', $this->getField('accounting_gross_3_ref_to_cust') + abs(floatval($fRefundSumma)));
        $this->recalculateAccountingRefundToCustomer();
        return $this;
    }

    /**
     * @param float $fRefundSumma
     * @return classOrderGroup
     */
    public function setAccountingGrossRefundToCustomer($fRefundSumma)
    {
        $this->setField('accounting_gross_3_ref_to_cust', abs(floatval($fRefundSumma)));
        $this->recalculateAccountingRefundToCustomer();
        return $this;
    }

    /**
     * @param float $fRefundSumma
     * @return classOrderGroup
     */
    public function setAccountingGrossRefundToUs($fRefundSumma)
    {
        $this->setField('accounting_gross_4_ref_to_us', abs(floatval($fRefundSumma)));
        $this->recalculateAccountingRefundToUs();
        return $this;
    }

    /**
     * @return classOrderGroup
     */
    public function initAccountingNetRefundToCustomer()
    {
        $this->setField('accounting_net_3_ref_to_cust', 0);
        return $this;
    }

    /**
     * @return classOrderGroup
     */
    public function initAccountingGrossRefundToCustomer()
    {
        $this->setField('accounting_gross_3_ref_to_cust', 0);
        return $this;
    }

    /**
     * @return classOrderGroup
     */
    public function initAccountingPSTRefundToCustomer()
    {
        $this->setField('accounting_pst_3_ref_to_cust', 0);
        return $this;
    }

    /**
     * @return classOrderGroup
     */
    public function initAccountingHSTRefundToCustomer()
    {
        $this->setField('accounting_gst_3_ref_to_cust', 0);
        return $this;
    }

    /**
     * @return classOrderGroup
     */
    public function initAccountingNetRefundToUs()
    {
        $this->setField('accounting_net_4_ref_to_us', 0);
        return $this;
    }

    /**
     * @return classOrderGroup
     */
    public function initAccountingGrossRefundToUs()
    {
        $this->setField('accounting_gross_4_ref_to_us', 0);
        return $this;
    }

    /**
     * @return classOrderGroup
     */
    public function initAccountingPSTRefundToUs()
    {
        $this->setField('accounting_pst_4_ref_to_us', 0);
        $this->recalculateAccountingRefundToUs();
        return $this;
    }

    /**
     * @return classOrderGroup
     */
    public function initAccountingHSTRefundToUs()
    {
        $this->setField('accounting_gst_4_ref_to_us', 0);
        $this->recalculateAccountingRefundToUs();
        return $this;
    }

    /**
     * @param float $fRefundSumma
     * @return classOrderGroup
     */
    public function addAccountingPSTRefundToCustomer($fRefundSumma)
    {
        $this->setField('accounting_pst_3_ref_to_cust', $this->getField('accounting_pst_3_ref_to_cust') + abs(floatval($fRefundSumma)));
        $this->recalculateAccountingRefundToCustomer();
        return $this;
    }

    /**
     * @param float $fRefundSumma
     * @return classOrderGroup
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
     * @return classOrderGroup
     */
    public function setAccountingHSTRefundToCustomer($fRefundSumma)
    {
        $this->setField('accounting_gst_3_ref_to_cust', abs(floatval($fRefundSumma)));
        $this->recalculateAccountingRefundToCustomer();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return classOrderGroup
     */
    public function addAccountingNetRefundToUs($fSumma)
    {
        $this->setField('accounting_net_4_ref_to_us', floatval($this->getField('accounting_net_4_ref_to_us')) + floatval($fSumma));
        return $this;
    }

    /**
     * @param float $fSumma
     * @return classOrderGroup
     */
    public function addAccountingGrossRefundToUs($fSumma)
    {
        $this->setField('accounting_gross_4_ref_to_us', floatval($this->getField('accounting_gross_4_ref_to_us')) + floatval($fSumma));
        $this->recalculateAccountingRefundToUs();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return classOrderGroup
     */
    public function addAccountingPSTRefundToUs($fSumma)
    {
        $this->setField('accounting_pst_4_ref_to_us', floatval($this->getField('accounting_pst_4_ref_to_us')) + floatval($fSumma));
        $this->recalculateAccountingRefundToUs();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return classOrderGroup
     */
    public function addAccountingHSTRefundToUs($fSumma)
    {
        $this->setField('accounting_gst_4_ref_to_us', floatval($this->getField('accounting_gst_4_ref_to_us')) + floatval($fSumma));
        $this->recalculateAccountingRefundToUs();
        return $this;
    }

    /**
     * @param float $fSumma
     * @return classOrderGroup
     */
    public function setAccountingHSTRefundToUs($fSumma)
    {
        $this->setField('accounting_gst_4_ref_to_us', floatval($fSumma));
        $this->recalculateAccountingRefundToUs();
        return $this;
    }

    /**
     * @return classOrderGroup
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
     * @return classOrderGroup
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
     * @return classOrderGroup
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
     * @return classOrderGroup
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
            if ($this->getOrderInstance()->isOrderAmazon()) $this->recalculateAccountingAmazon(); else {
                $this
                    ->setAccountingGross($this->getPaymentMethodInstance()->getSumAfterProcessorFee($this->getField('total_gross')))
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
        }
        return $this;
    }

    public function recalculateAccountingAmazon()
    {
        $fRefund = $fPrincipalRefund = $fShippingRefund = $fShipping = $FBAPerOrderFulfillmentFee = $FBAPerUnitFulfillmentFee = $FBATransportationFee = $FBAWeightBasedFee = $AmazonCommission = 0;
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
        }
        $sAmazonChanell = $this->getOrderInstance()->getAmazonChanell();
        switch ($sAmazonChanell) {
            case 'MFN' :
                $this
                    ->setAccountingGross($this->getPaymentMethodInstance()->getSumAfterProcessorFee($this->getField('total_gross')))
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
                        $AmazonCommission)->initAccountingGrossCostToUs()
                    ->setAccountingGrossShipping($fShipping + $FBATransportationFee);
                if ($this->getOrderAmazonDetails()->isRefundExists())
                    $this->setAccountingGrossRefundToUs($this->getAccountingGrossCostToUs() + abs($fRefund + $fPrincipalRefund) + abs($fShippingRefund));

                break;
        }

        $this
            ->setAccountingGrossRefundToCustomer(abs($fPrincipalRefund + $fShippingRefund))
            ->recalculateAccountingProfit()
            ->updateAccounting();
    }

    public function updateAccounting()
    {
        func_array2update($this->sPrimaryTable, $this->aPrimaryTableValue, 'orderid = ' . $this->getField('orderid') . ' and manufacturerid = ' . $this->getField('manufacturerid'));
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
            $aProducts = $this->oSQL->init()->
            addSelect('*')->
            addFromTable('products', 'p')->
            addInnerJoin('order_details', 'od', 'od.productid=p.productid AND orderid = ' . $this->getField('orderid'))->
            addCondition('p.manufacturerid = ' . $this->getField('manufacturerid'))->Execute()->getQueryResult();
            if (!empty($aProducts)) {
                foreach ($aProducts as $aProduct) {
                    $oProduct = new classProduct($aProduct);
                    $this->oOrderGroupProducts[] = $oProduct;
                }
            }
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
                $aOrderDetails = classOrderDetail::getOrderDetailsByOrderIdAndProductId($this->getField('orderid'), $oProduct->getField('productid'));

                foreach ($aOrderDetails as $oOrderDetail) {
                    $iAmount += $oOrderDetail->getField('amount');
                }
                if ($oProduct->getField('amazon_fba_avail') >= $iAmount) {
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
        $this->updateField('amz_customer_notes', $sAmazonShipmentNotes);
    }

    public function updateAmazonShipmentWithNotes($sAmazonShipmentNotes)
    {
        $this->updateField('amz_send_with_notes', $sAmazonShipmentNotes);
    }

    public function getAmazonShipmentNotes()
    {
        return $this->getField('amz_customer_notes');
    }

    public function shipOrderGroupByAmazon()
    {
        $oAmazon = new classAmazonMWS('FBAOutboundServiceMWS_Client','/FulfillmentOutboundShipment/2010-10-01/');
        $oAmazon->shipOrderGroupByAmazon($this);
    }

    public function getAmazonShippingOrderId()
    {
        return $this->getOrderInstance()->getOrderPrefix().$this->getField('orderid') . '-' . $this->getField('manufacturerid');
    }

    public function getShippingInstance()
    {
        if (empty($this->oShippingMethod)) {
            $this->oShippingMethod = new classShipping($this->getField('shippingid'));
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

    public function getOrderId() {
        return $this->getField('orderid');
    }

    public function getManufacturerId() {
        return $this->getField('manufacturerid');
    }

}