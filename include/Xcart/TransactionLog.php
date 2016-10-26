<?php
namespace Xcart;

class TransactionLog extends Data
{
    private $aLogLines = [];
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['id'];
        $this->sPrimaryTable = 'transaction_logs';
        parent::__construct($aParams);

    }

    public function addNewLine($sLine)
    {
        $this->aLogLines[] = $sLine;
    }

    public function getLogText()
    {
        $result = '';
        if (!empty($this->aLogLines)){
            $result = implode('\n',$this->aLogLines);
        }
        return nl2br($result);
    }

    public function getLogLines()
    {
        return $this->aLogLines;
    }

    public function insertTransactionLog(classOrderTransaction $oTransaction ) {
        global $login;
        $aResponse = $oTransaction->getTransactionResult();
        if (!empty($aResponse))
            $aResponse['xcart_log'] = $this->getLogText();
        else $aResponse = $this->getLogLines();
        $this->fill([
            'orderid'=>$oTransaction->getField('orderid'),
            'paymentid'=>$oTransaction->getField('paymentid'),
            'transaction_id'=>$oTransaction->getField('transaction_id'),
            'transaction_status'=>$oTransaction->getField('transaction_status'),
            'transaction_currency'=>$oTransaction->getField('transaction_currency'),
            'transaction_total'=>$oTransaction->getField('transaction_amount'),
            'date'=>time(),
            'login'=>$login,
            'transaction_log'=> addslashes(serialize($aResponse))
        ]);
        $this->_insert();
    }

    public function insertOrderLog($orderid)
    {
        global $login;
        if (!empty($this->aLogLines))
        {
            func_log_order($orderid, 'PP', $this->getLogText(), $login);
        }
    }

}