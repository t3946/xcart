<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";
require_once $xcart_dir . "/include/class/payments/classPaymentMethodpayment_offline.php";
require_once $xcart_dir . "/include/class/payments/classPaymentMethodpayment_cc.php";


class classPaymentMethod extends classData
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['paymentid'];
        $this->sPrimaryTable = 'payment_methods';
        parent::__construct($aParams);
    }

    public function isPaymentMethodSet()
    {
        $bResult = $this->getField('paymentid');
        return (!empty($bResult));
    }

    public function getPaymentMethodInstance($aParams)
    {
        if (intval($this->getField('paymentid')) > 0) {
            $sPaymentClassName = 'classPaymentMethod' . str_replace('.php', '', $this->getField('payment_script'));
            return new $sPaymentClassName($aParams);
        } else return $this;
    }

    public function getSumAfterProcessorFee($fSumma)
    {
        $fResult = $fSumma * (1 - $this->getField('acc_percent') / 100);
        $fResult -= $this->getField('acc_per_trans');
        return $fResult;
    }

    public function getSumAfterProcessorFeeRefund($fSumma)
    {
        $fResult = $fSumma * (1 - $this->getField('percent_ref') / 100);
        $fResult -= $this->getField('per_ref');
        return $fResult;
    }

    public function getProcessorFeeRefundPerTransaction()
    {
        return $this->getField('per_ref');
    }

    public function getMaximumReAuthorizationMultiplier()
    {
        return $this->getField('maximum_re_authorization_multiplier');
    }
}
