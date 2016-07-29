<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";
require_once $xcart_dir . "/include/class/classOrderTransaction.php";

class classOrderTransactions extends classData
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['id'];
        $this->sPrimaryTable = 'order_transactions';
        parent::__construct($aParams);

    }

    public function getOrderTransactionsByOrderIdAndStatus($iOrderId, $Status = [])
    {
        $aOrderTransactions = [];

        $this->oSQL->addSelect('*')->addFromTable($this->sPrimaryTable)->addCondition("orderid = " .$iOrderId);
        if (!empty($Status) && is_array($Status)) {
            $this->oSQL->addCondition('transaction_status IN '.explode(',',$Status));
        }
        $aRes = $this->oSQL->Execute();
        if (!empty($aRes)) {
            foreach ($aRes as $aOrderTransaction) {
                $oOrderTransaction = new classOrderTransaction();
                $oOrderTransaction->fillPrimaryTableValues($aOrderTransaction);
                $aOrderTransactions[] = $oOrderTransaction;
            }
        }
        return $aOrderTransactions;
    }
}