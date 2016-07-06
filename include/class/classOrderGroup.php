<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";
require_once $xcart_dir . "/include/class/classOrders.php";
require_once $xcart_dir . "/include/class/classPaymentMethod.php";
require_once $xcart_dir . "/include/class/classOrderGroupInvoices.php";
require_once $xcart_dir . "/include/class/classOrderGroupMemos.php";
require_once $xcart_dir . "/include/class/classOrderRefundGroups.php";
require_once $xcart_dir . "/include/class/classOrderAmazonDetails.php";

class classOrderGroup extends classData
{
    private $oOrder;
    private $oOrderInvoices = [];
    private $oOrderMemos = [];
    private $oOrderRefunds = [];
    private $oOrderAmazonDetails = [];

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


    public function getOrderGroupInvoices()
    {
        if (empty($this->oOrderInvoices)) {
            $this->oOrderInvoices = new classOrderGroupInvoices();
            $this->oOrderInvoices = $this->oOrderInvoices->getOrderGroupInvoices(['orderid' => $this->getField('orderid'), 'manufacturerid' => $this->getField('manufacturerid')]);
        }
        return $this->oOrderInvoices;
    }

    public function getOrderGroupMemos()
    {
        if (empty($this->oOrderMemos)) {
            $this->oOrderMemos = new classOrderGroupMemos();
            $this->oOrderMemos = $this->oOrderMemos->getOrderGroupMemos(['orderid' => $this->getField('orderid'), 'manufacturerid' => $this->getField('manufacturerid')]);
        }
        return $this->oOrderMemos;
    }

    public function getOrderRefundGroups()
    {
        if (empty($this->oOrderRefunds)) {
            $this->oOrderRefunds = new classOrderRefundGroups();
            $this->oOrderRefunds = $this->oOrderRefunds->getOrderRefundGroups(['orderid' => $this->getField('orderid'), 'manufacturerid' => $this->getField('manufacturerid')]);
        }
        return $this->oOrderRefunds;
    }

    public function getOrderAmazonDetails()
    {
        if (empty($this->oOrderAmazonDetails)) {
            $this->oOrderAmazonDetails = new classOrderAmazonDetails();
            $this->oOrderAmazonDetails = $this->oOrderAmazonDetails->getOrderAmazonDetails(['orderid' => $this->getField('orderid'), 'manufacturerid' => $this->getField('manufacturerid')]);
        }
        return $this->oOrderAmazonDetails;
    }

    public function getOrderInstance()
    {
        if (empty($this->oOrder)) {
            $this->fetchOrderInstance();
        }
        return $this->oOrder;
    }

    public function getPaymentMethodInstance()
    {
        if (empty($this->oPaymentMethod)) {
            $this->fetchPaymentMethodInstance();
        }
        return $this->oPaymentMethod;
    }

    public function initAccounting()
    {
        $this->initAccountingNet()->initAccountingHST()->initAccountingPST()->initAccountingGross();
        return $this;
    }

    public function initAccountingNet()
    {
        $this->setField('accounting_net_0', floatval($this->getField('total_net')));
        return $this;
    }

    public function initAccountingHST()
    {
        $this->setField('accounting_gst_0', floatval($this->getField('total_gst')));
        return $this;
    }

    public function initAccountingPST()
    {
        $this->setField('accounting_pst_0', floatval($this->getField('total_pst')));
        return $this;
    }

    public function initAccountingGross()
    {
        $this->setField('accounting_gross_0', floatval($this->getField('total_gross')));
        return $this;
    }

    public function initAccountingGrossCostToUs()
    {
        $this->setField('accounting_gross_1_cost_to_us', floatval($this->getTotalCostToUs()));
        $this->recalculateAccountingCostToUs();
        return $this;
    }

    public function addAccountingPST($fSumma)
    {
        $this->setField('accounting_pst_0', floatval($this->getField('accounting_pst_0')) + floatval($fSumma));
        $this->recalculateAccountingNet();
        return $this;
    }

    public function addAccountingHST($fSumma)
    {
        $this->setField('accounting_gst_0', floatval($this->getField('accounting_gst_0')) + floatval($fSumma));
        $this->recalculateAccountingNet();
        return $this;
    }

    public function addAccountingNet($fSumma)
    {
        $this->setField('accounting_net_0', floatval($this->getField('total_net')) + floatval($fSumma));
        return $this;
    }

    public function addAccountingGross($fSumma)
    {
        $this->setField('accounting_gross_0', floatval($this->getField('total_gross')) + floatval($fSumma));
        $this->recalculateAccountingNet();
        return $this;
    }

    public function setAccountingGross($fSumma)
    {
        $this->setField('accounting_gross_0', floatval($fSumma));
        $this->recalculateAccountingNet();
        return $this;
    }

    public function addAccountingNetCostToUs($fSumma)
    {
        $this->setField('accounting_net_1_cost_to_us', floatval($this->getField('accounting_net_1_cost_to_us')) + floatval($fSumma));
        return $this;
    }

    public function addAccountingGrossCostToUs($fSumma)
    {
        $this->setField('accounting_gross_1_cost_to_us', floatval($this->getField('accounting_gross_1_cost_to_us')) + floatval($fSumma));
        $this->recalculateAccountingCostToUs();
        return $this;
    }

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

    public function addAccountingPSTCostToUs($fSumma)
    {
        $this->setField('accounting_pst_1_cost_to_us', floatval($this->getField('accounting_pst_1_cost_to_us')) + floatval($fSumma));
        $this->recalculateAccountingCostToUs();
        return $this;
    }

    public function addAccountingHSTCostToUs($fSumma)
    {
        $this->setField('accounting_gst_1_cost_to_us', floatval($this->getField('accounting_gst_1_cost_to_us')) + floatval($fSumma));
        $this->recalculateAccountingCostToUs();
        return $this;
    }

    public function setAccountingHSTCostToUs($fSumma)
    {
        $this->setField('accounting_gst_1_cost_to_us', floatval($fSumma));
        $this->recalculateAccountingCostToUs();
        return $this;
    }

    public function addAccountingNetShipping($fSumma)
    {
        $this->setField('accounting_net_2_shipping', floatval($this->getField('accounting_net_2_shipping')) + floatval($fSumma));
        return $this;
    }

    public function addAccountingGrossShipping($fSumma)
    {
        $this->setField('accounting_gross_2_shipping', floatval($this->getField('accounting_gross_2_shipping')) + floatval($fSumma));
        $this->recalculateAccountingShipping();
        return $this;
    }

    public function setAccountingGrossShipping($fSumma)
    {
        $this->setField('accounting_gross_2_shipping', floatval($fSumma));
        $this->recalculateAccountingShipping();
        return $this;
    }

    public function addAccountingPSTShipping($fSumma)
    {
        $this->setField('accounting_pst_2_shipping', floatval($this->getField('accounting_pst_2_shipping')) + floatval($fSumma));
        $this->recalculateAccountingShipping();
        return $this;
    }

    public function addAccountingHSTShipping($fSumma)
    {
        $this->setField('accounting_gst_2_shipping', floatval($this->getField('accounting_gst_2_shipping')) + floatval($fSumma));
        $this->recalculateAccountingShipping();
        return $this;
    }

    public function addAccountingNetRefundToCustomer($fRefundSumma)
    {
        $this->setField('accounting_net_3_ref_to_cust', $this->getField('accounting_net_3_ref_to_cust') + abs(floatval($fRefundSumma)));
        return $this;
    }

    public function addAccountingGrossRefundToCustomer($fRefundSumma)
    {
        $this->setField('accounting_gross_3_ref_to_cust', $this->getField('accounting_gross_3_ref_to_cust') + abs(floatval($fRefundSumma)));
        $this->recalculateAccountingRefundToCustomer();
        return $this;
    }

    public function setAccountingGrossRefundToCustomer($fRefundSumma)
    {
        $this->setField('accounting_gross_3_ref_to_cust', abs(floatval($fRefundSumma)));
        $this->recalculateAccountingRefundToCustomer();
        return $this;
    }

    public function setAccountingGrossRefundToUs($fRefundSumma)
    {
        $this->setField('accounting_gross_4_ref_to_us', abs(floatval($fRefundSumma)));
        $this->recalculateAccountingRefundToUs();
        return $this;
    }

    public function addAccountingPSTRefundToCustomer($fRefundSumma)
    {
        $this->setField('accounting_pst_3_ref_to_cust', $this->getField('accounting_pst_3_ref_to_cust') + abs(floatval($fRefundSumma)));
        $this->recalculateAccountingRefundToCustomer();
        return $this;
    }

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

    public function setAccountingHSTRefundToCustomer($fRefundSumma)
    {
        $this->setField('accounting_gst_3_ref_to_cust', abs(floatval($fRefundSumma)));
        $this->recalculateAccountingRefundToCustomer();
        return $this;
    }

    public function addAccountingNetRefundToUs($fSumma)
    {
        $this->setField('accounting_net_4_ref_to_us', floatval($this->getField('accounting_net_4_ref_to_us')) + floatval($fSumma));
        return $this;
    }

    public function addAccountingGrossRefundToUs($fSumma)
    {
        $this->setField('accounting_gross_4_ref_to_us', floatval($this->getField('accounting_gross_4_ref_to_us')) + floatval($fSumma));
        $this->recalculateAccountingRefundToUs();
        return $this;
    }

    public function addAccountingPSTRefundToUs($fSumma)
    {
        $this->setField('accounting_pst_4_ref_to_us', floatval($this->getField('accounting_pst_4_ref_to_us')) + floatval($fSumma));
        $this->recalculateAccountingRefundToUs();
        return $this;
    }

    public function addAccountingHSTRefundToUs($fSumma)
    {
        $this->setField('accounting_gst_4_ref_to_us', floatval($this->getField('accounting_gst_4_ref_to_us')) + floatval($fSumma));
        $this->recalculateAccountingRefundToUs();
        return $this;
    }

    public function setAccountingHSTRefundToUs($fSumma)
    {
        $this->setField('accounting_gst_4_ref_to_us', floatval($fSumma));
        $this->recalculateAccountingRefundToUs();
        return $this;
    }

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
        if ($this->getPaymentMethodInstance()->isPaymentMethodSet()) {
            if ($this->getOrderInstance()->isOrderAmazon()) $this->recalculateAccountingAmazon(); else {
                $this
                    ->setAccountingGross($this->getPaymentMethodInstance()->getSumAfterProcessorFee($this->getField('total_gross')))
                    ->initAccountingHST()
                    ->initAccountingPST();

                if ($this->getOrderGroupInvoices()->countOrderGroupInvoices() > 0) {
                    $this->setAccountingGrossCostToUs($this->getOrderGroupInvoices()->getOrderGroupInvoicesProductTotal());
                    $this->setAccountingGrossShipping($this->getOrderGroupInvoices()->getOrderGroupInvoicesShippingTotal());
                    $this->setAccountingHSTCostToUs($this->getOrderGroupInvoices()->getOrderGroupInvoicesHST());
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
        $fRefund = $fPrincipalRefund = $fShippingRefund = $fShipping = $FBAPerOrderFulfillmentFee = $FBAPerUnitFulfillmentFee = $FBAWeightBasedFee = $AmazonCommission =0;
        if ($this->getOrderAmazonDetails()->countOrderAmazonDetails() > 0) {
            $fRefund = $this->getOrderAmazonDetails()->getOrderAmazonRefund();
            $fPrincipalRefund = $this->getOrderAmazonDetails()->getOrderAmazonPrincipalRefund();
            $fShippingRefund = $this->getOrderAmazonDetails()->getOrderAmazonShippingRefund();
            $fShipping = $this->getOrderAmazonDetails()->getOrderAmazonShipping();
            $FBAPerOrderFulfillmentFee = $this->getOrderAmazonDetails()->getOrderAmazonFBAPerOrderFulfillmentFee();
            $FBAPerUnitFulfillmentFee = $this->getOrderAmazonDetails()->getOrderAmazonFBAPerUnitFulfillmentFee();
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
                    $this->setAccountingGrossCostToUs($this->getOrderGroupInvoices()->getOrderGroupInvoicesProductTotal());
                    $this->setAccountingGrossShipping($this->getOrderGroupInvoices()->getOrderGroupInvoicesShippingTotal());
                    $this->setAccountingHSTCostToUs($this->getOrderGroupInvoices()->getOrderGroupInvoicesHST());
                }
                $this->setAccountingGrossRefundToUs(abs($fRefund + $fPrincipalRefund) + abs($fShippingRefund));
                if ($this->getOrderGroupMemos()->countOrderGroupMemos() > 0) {
                    $this->addAccountingHSTRefundToUs($this->getOrderGroupMemos()->getOrderGroupMemoRefToUsHST());
                    $this->addAccountingGrossRefundToUs($this->getOrderGroupMemos()->getOrderGroupMemoRefToUsTotal());
                }
                break;
            case 'AFN' :
                $this
                    ->addAccountingGross(
                        $FBAPerOrderFulfillmentFee +
                        $FBAPerUnitFulfillmentFee +
                        $FBAWeightBasedFee +
                        $AmazonCommission)->initAccountingGrossCostToUs()
                    ->setAccountingGrossShipping($fShipping)
                    ->setAccountingGrossRefundToUs($this->getAccountingGrossCostToUs() + abs($fRefund + $fPrincipalRefund) + abs($fShippingRefund));
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

    public function printAccounting()
    {
        $s = '<table> <tr> ';

        $s .= '<td></td>';
        $s .= '<td>NETOrder</td>';
        $s .= '<td>NET</td>';
        $s .= '<td>Cost to us</td>';
        $s .= '<td>Shipping</td>';
        $s .= '<td>Ref to cust</td>';
        $s .= '<td>Ref to us</td>';
        $s .= '<td>Profit</td>';
        $s .= '<td>Profit margin</td>';

        $s .= '<tr>';
        $s .= '<td>Net</td>';
        $s .= '<td>' . $this->getField('total_net') . '</td>';
        $s .= '<td>' . $this->getField('accounting_net_0') . '</td>';
        $s .= '<td>' . $this->getField('accounting_net_1_cost_to_us') . '</td>';
        $s .= '<td>' . $this->getField('accounting_net_2_shipping') . '</td>';
        $s .= '<td>' . $this->getField('accounting_net_3_ref_to_cust') . '</td>';
        $s .= '<td>' . $this->getField('accounting_net_4_ref_to_us') . '</td>';
        $s .= '<td>' . $this->getField('accounting_net_5_profit') . '</td>';
        $s .= '<td>' . $this->getField('profit_margin') . '%</td>';

        $s .= '<tr>';
        $s .= '<td>HST</td>';
        $s .= '<td>' . $this->getField('total_gst') . '</td>';
        $s .= '<td>' . $this->getField('accounting_gst_0') . '</td>';
        $s .= '<td>' . $this->getField('accounting_gst_1_cost_to_us') . '</td>';
        $s .= '<td>' . $this->getField('accounting_gst_2_shipping') . '</td>';
        $s .= '<td>' . $this->getField('accounting_gst_3_ref_to_cust') . '</td>';
        $s .= '<td>' . $this->getField('accounting_gst_4_ref_to_us') . '</td>';
        $s .= '<td>' . $this->getField('accounting_gst_5_profit') . '</td>';
        $s .= '<td></td>';

        $s .= '<tr>';
        $s .= '<td>PST</td>';
        $s .= '<td>' . $this->getField('total_pst') . '</td>';
        $s .= '<td>' . $this->getField('accounting_pst_0') . '</td>';
        $s .= '<td>' . $this->getField('accounting_pst_1_cost_to_us') . '</td>';
        $s .= '<td>' . $this->getField('accounting_pst_2_shipping') . '</td>';
        $s .= '<td>' . $this->getField('accounting_pst_3_ref_to_cust') . '</td>';
        $s .= '<td>' . $this->getField('accounting_pst_4_ref_to_us') . '</td>';
        $s .= '<td>' . $this->getField('accounting_pst_5_profit') . '</td>';
        $s .= '<td></td>';

        $s .= '</tr>';
        $s .= '<tr>';
        $s .= '<td>Gross</td>';
        $s .= '<td>' . $this->getField('total_gross') . '</td>';
        $s .= '<td>' . $this->getField('accounting_gross_0') . '</td>';
        $s .= '<td>' . $this->getField('accounting_gross_1_cost_to_us') . '</td>';
        $s .= '<td>' . $this->getField('accounting_gross_2_shipping') . '</td>';
        $s .= '<td>' . $this->getField('accounting_gross_3_ref_to_cust') . '</td>';
        $s .= '<td>' . $this->getField('accounting_gross_4_ref_to_us') . '</td>';
        $s .= '<td>' . $this->getField('accounting_gross_5_profit') . '</td>';
        $s .= '<td></td>';

        $s .= '</tr>';

        $s .= '</tr> </table>';
        echo $s;
    }

}