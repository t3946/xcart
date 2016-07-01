<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";
require_once $xcart_dir . "/include/class/classOrders.php";
require_once $xcart_dir . "/include/class/classPaymentMethod.php";

class classOrderGroup extends classData
{
    private $oOrder;

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

    public function addAccountingNet($fSumma)
    {
        $this->setField('accounting_net_0', floatval($this->getField('total_net')) + floatval($fSumma));
        return $this;
    }

    public function addAccountingGross($fSumma)
    {
        $this->setField('accounting_gross_0', floatval($this->getField('total_gross')) + floatval($fSumma));
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
        return $this;
    }

    public function addAccountingNetRefundToCustomer($fRefundSumma)
    {
        $this->setField('accounting_net_3_ref_to_cust', $this->getField('accounting_gross_3_ref_to_cust') + abs(floatval($fRefundSumma)));
        return $this;
    }

    public function addAccountingGrossRefundToCustomer($fRefundSumma)
    {
        $this->setField('accounting_gross_3_ref_to_cust', $this->getField('accounting_gross_3_ref_to_cust') + abs(floatval($fRefundSumma)));
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
            $fProfitMargin = round(floatval($this->getField('accounting_net_5_profit')) / floatval($this->getField('accounting_net_0'))*100,2);
        else $fProfitMargin = 0;
        $this->setField('profit_margin', $fProfitMargin);
        return $this;
    }

    public function recalculateAccounting() {
        $this->calculateAccountingNetProfit()
             ->calculateAccountingGrossProfit()
             ->calculateProfitMargin();
    }

    public function printAccounting() {
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

        $s.='<tr>';
        $s .= '<td>Net</td>';
        $s .= '<td>'.$this->getField('total_net').'</td>';
        $s .= '<td>'.$this->getField('accounting_net_0').'</td>';
        $s .= '<td>'.$this->getField('accounting_net_1_cost_to_us').'</td>';
        $s .= '<td>'.$this->getField('accounting_net_2_shipping').'</td>';
        $s .= '<td>'.$this->getField('accounting_net_3_ref_to_cust').'</td>';
        $s .= '<td>'.$this->getField('accounting_net_4_ref_to_us').'</td>';
        $s .= '<td>'.$this->getField('accounting_net_5_profit').'</td>';
        $s .= '<td>'.$this->getField('profit_margin').'%</td>';

        $s.='</tr>';
        $s.='<tr>';
        $s .= '<td>Gross</td>';
        $s .= '<td>'.$this->getField('total_gross').'</td>';
        $s .= '<td>'.$this->getField('accounting_gross_0').'</td>';
        $s .= '<td>'.$this->getField('accounting_gross_1_cost_to_us').'</td>';
        $s .= '<td>'.$this->getField('accounting_gross_2_shipping').'</td>';
        $s .= '<td>'.$this->getField('accounting_gross_3_ref_to_cust').'</td>';
        $s .= '<td>'.$this->getField('accounting_gross_4_ref_to_us').'</td>';
        $s .= '<td>'.$this->getField('accounting_gross_5_profit').'</td>';
        $s .= '<td></td>';

        $s.='</tr>';

        $s .= '</tr> </table>';
        echo $s;
    }

}