<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";
require_once $xcart_dir . "/include/class/classOrderTransaction.php";

class classOrderTransactions extends classData
{
    /**
     * @var classOrderTransaction[]
     */
    private $aOrderTransactions = [];

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['id'];
        $this->sPrimaryTable = 'order_transactions';
        parent::__construct($aParams);

    }

    public function getOrderTransactionsByOrderIdAndStatus($iOrderId, $Status = [])
    {
        $aOrderTransactions = [];

        $this->oSQL->init();

        $this->oSQL->addSelect('*')->addFromTable($this->sPrimaryTable)->addCondition("orderid = " . $iOrderId);
        if (!empty($Status) && is_array($Status)) {
            $this->oSQL->addCondition("transaction_status IN ('" . implode("','", $Status)."')");
        }
        $aRes = $this->oSQL->Execute()->getQueryResult();
        if (!empty($aRes)) {
            foreach ($aRes as $aOrderTransaction) {
                $oOrderTransaction = new classOrderTransaction();
                $oOrderTransaction->fillPrimaryTableValues($aOrderTransaction);
                $aOrderTransactions[] = $oOrderTransaction;
            }
        }
        return $aOrderTransactions;
    }

    public function getTransactionsStates()
    {
        $aTransactionStates = [];
        if (!empty($this->aOrderTransactions)) {
            foreach ($this->aOrderTransactions as $oTransaction){
                $aTransactionStates[$oTransaction->getTransactionState()] = 1;
            }
        }
        return $aTransactionStates;
    }

    public function captureOrderAmount(classOrder $oOrder)
    {
        $fSummaToCapture = 0;
        $aOrderGroups = $oOrder->getOrderGroups();
        if (!empty($aOrderGroups)) {
            foreach ($aOrderGroups as $oOrderGroup) {
                $fSummaToCapture += $oOrderGroup->getTotalGross() - $oOrderGroup->getOrderRefundGroups()->getOrderRefundTotal();
            }
        }

        $aCompletedTransactions = $this->getOrderTransactionsByOrderIdAndStatus($oOrder->getOrderId(), ['completed','Pending']);
        if (!empty($aCompletedTransactions)){
            foreach ($aCompletedTransactions as $oCompletedTransaction){
                $fSummaToCapture-=$oCompletedTransaction->getTransactionAmount();
            }
        }

        if ($fSummaToCapture > 0) {
            $this->aOrderTransactions = $this->getOrderTransactionsByOrderIdAndStatus($oOrder->getOrderId(), ['AP',  'authorized']);
            if (empty($this->aOrderTransactions)) throw new Exception('Order transactions not found');
            try {
                if (!empty($this->aOrderTransactions)) {
                    foreach ($this->aOrderTransactions as $oOrderTransaction) {
                        $fToCapture = $fSummaToCapture;
                        if ($fSummaToCapture > $oOrderTransaction->getTransactionAmount())
                            $fToCapture = $oOrderTransaction->getTransactionAmount();
                        $oOrderTransaction->captureTransaction($fToCapture);
                        $fSummaToCapture -= $fToCapture;
                        if ($fSummaToCapture<=0) break;
                    }
                    $aTransactionStates = $this->getTransactionsStates();
                    if (!empty($aTransactionStates) && count($aTransactionStates) == 1 && $aTransactionStates['completed'])
                        foreach ($aOrderGroups as $oOrderGroup) {
                            $oOrderGroup->changeOrderGroupStatusCB('P');
                        }
                }

            }
            catch (Exception $ex) {
                throw $ex;
            }
        }
    }
}