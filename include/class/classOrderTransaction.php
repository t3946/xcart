<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";
require_once $xcart_dir . "/include/class/classPaypal.php";
require_once $xcart_dir . "/include/class/classOrders.php";
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
     * @param $fCaptureSumma
     * @return classOrderTransaction
     * @throws Exception
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
                $this->oTransactionLog->insertTransactionLog($this, false);
                throw new Exception('Capture transaction ID:'.$this->getField('transaction_id').' - failed');
            }
            $oOrder = new classOrder($this->getField('orderid'));

            $aData["amount"]["currency"] = $oOrder->getField("currency");
            $aData["amount"]["total"] = $fCaptureSumma;
            $data_arr["is_final_capture"] = true;

            $this->aTransactionResult = $oPaypal->captureTransaction($this->getField('transaction_id'), $aData);

            if (!empty($this->aTransactionResult["id"])) {
                $this->oTransactionLog->addNewLine("Transaction: " . $this->getField('transaction_id') . " -> " . $this->aTransactionResult["id"]);
                $this->updateFields(['transaction_id'=>$this->aTransactionResult['id'],
                    'transaction_amount' => $this->aTransactionResult["amount"]["total"],
                    'date'=>time(),
                    'login'=>$login,
                    'transaction_status'=>$this->aTransactionResult['state'],
                    'transaction_response'=> $this->getTransactionResult()
                ]);
            }
            $this->oTransactionLog->insertTransactionLog($this);
            $this->oTransactionLog->insertOrderLog($this->getField('orderid'));

            if (empty($this->aTransactionResult["id"])) throw new Exception('Capture transaction ID:'.$this->getField('transaction_id').' - failed');
        }
        return $this;
    }

    public function getTransactionResult()
    {
        return $this->aTransactionResult;
    }

    public function getTransactionAmount()
    {
        return floatval($this->getField('transaction_amount'));
    }

}