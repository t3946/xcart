<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";
require_once $xcart_dir . "/include/class/classPaypal.php";
require_once $xcart_dir . "/include/class/classOrder.php";
require_once $xcart_dir . "/include/class/classTransactionLog.php";

class classOrderTransaction extends classData
{
    private $aTransactionResult = [];
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['id'];
        $this->sPrimaryTable = 'order_transactions';
        parent::__construct($aParams);
        $this->oTransactionLog = new classTransactionLog();

    }

    /**
     * @param float $fSumma
     */
    public function captureTransaction($fCaptureSumma)
    {
        global $login;
        if ($fCaptureSumma <= $this->getTransactionAmount()) {
            $oPaypal = null;
            try {
                $oPaypal = new classPaypal();
            } catch (Exception $ex) {
                $this->oTransactionLog->addNewLine($ex->getMessage());
                return false;
            }
            $oOrder = new classOrder($this->getField('orderid'));

            $aData["amount"]["currency"] = $oOrder->getField("currency");
            $aData["amount"]["total"] = $fCaptureSumma;
            $data_arr["is_final_capture"] = true;

            $this->aTransactionResult = $oPaypal->captureTransaction($this->getField('transaction_id'), $aData);
            $sTransactionLog = $this->oTransactionLog->getLogText();

            if (!empty($this->aTransactionResult["id"])) {
                $this->oTransactionLog->addNewLine("Transaction: " . $this->getField('transaction_id') . " -> " . $this->aTransactionResult["id"]);
                $this->updateFields(['transaction_id'=>$this->aTransactionResult['id'],
                    'transaction_amount' => $this->aTransactionResult["amount"]["total"],
                    'date'=>time(),
                    'login'=>$login,
                    'transaction_status'=>$this->aTransactionResult['state'],
                    'transaction_response'=> addslashes(serialize($this->aTransactionResult))
                ]);
            } else {
                $capture_failed_flag = true;
            }
            $this->aTransactionResult['xcart_log'] = $sTransactionLog;
            $this->oTransactionLog->insertTransactionLog($this);
            $this->oTransactionLog->insertOrderLog($this->getField('orderid'));

        }
    }

    public function getTransactionAmount()
    {
        return floatval($this->getField('transaction_amount'));
    }

}