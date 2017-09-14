<?php
namespace Xcart;

class OrderTransactions extends Data
{
    /**
     * @var OrderTransaction[]
     */
    private $aOrderTransactions = [];

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['id'];
        $this->sPrimaryTable = 'order_transactions';
        parent::__construct($aParams);

    }

    /**
     * @return OrderTransaction[]
     */
    public static function getOrderTransactionsByOrderIdAndStatus($iOrderId, $Status = [])
    {
        $aOrderTransactions = [];

        $oSQL = SQLBuilder::getInstance();

        $oSQL->addSelect('*')->addFromTable('order_transactions')->addCondition("orderid = " . $iOrderId);
        if (!empty($Status) && is_array($Status)) {
            $oSQL->addCondition("type IN ('" . implode("','", $Status)."')");
        }
        $aRes = $oSQL->Execute()->getQueryResult();
        if (!empty($aRes)) {
            foreach ($aRes as $aOrderTransaction) {
                $oOrderTransaction = new OrderTransaction();
                $oOrderTransaction->fill($aOrderTransaction);
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

}