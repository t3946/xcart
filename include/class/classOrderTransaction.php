<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";
require_once $xcart_dir . "/include/class/classPaypal.php";
require_once $xcart_dir . "/include/class/classOrders.php";
require_once $xcart_dir . "/include/class/classTransactionLog.php";

class classOrderTransaction extends classData
{
    const TRANSACTION_FAILED_TEXT = "Capture transaction ID:%s - failed\n";
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
                $this->oTransactionLog->insertTransactionLog($this);
                throw new Exception(sprintf(self::TRANSACTION_FAILED_TEXT, $this->getField('transaction_id')));
            }
            /** @var classOrder $oOrder */
            $oOrder = classOrder::model(['orderid' => $this->getField('orderid')]);

            $aData["amount"]["currency"] = $oOrder->getOrderCurrency();
            $aData["amount"]["total"] = number_format($fCaptureSumma, 2);
            $aData["is_final_capture"] = true;

            $this->aTransactionResult = $oPaypal->captureTransaction($this->getField('transaction_id'), $aData);

            if (!empty($this->aTransactionResult["id"])) {
                $this->oTransactionLog->addNewLine("Transaction: " . $this->getField('transaction_id') . " -> " . $this->aTransactionResult["id"]);
                $this->updateFields(['transaction_id' => $this->aTransactionResult['id'],
                    'transaction_amount' => $this->aTransactionResult["amount"]["total"],
                    'date' => time(),
                    'login' => $login,
                    'transaction_status' => $this->aTransactionResult['state'],
                    'transaction_response' => addslashes(serialize($this->getTransactionResult()))
                ]);
            }
            $this->oTransactionLog->insertTransactionLog($this);
            $this->oTransactionLog->insertOrderLog($this->getField('orderid'));

            if (empty($this->aTransactionResult["id"])) {
                $log = nl2br(sprintf(self::TRANSACTION_FAILED_TEXT, $this->getField('transaction_id')));
                $log .= addslashes(serialize($this->getTransactionResult()));
                throw new Exception($log);

            }
        }
        return $this;
    }

    public function getTransactionState()
    {
        return $this->aTransactionResult['state'];
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