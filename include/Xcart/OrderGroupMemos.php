<?php
namespace Xcart;

class OrderGroupMemos extends Data
{
    /**
     * @var OrderGroupMemo[]
     */
    private $aGroupMemos = [];

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['orderid', 'manufacturerid', 'memo_number'];
        $this->sPrimaryTable = 'order_group_memos';
        parent::__construct($aParams);
    }

    public static function getMemosStatusList()
    {
        return [
            "N" => "Not received",
            "A" => "Added",
            "U" => "Updated",
            "R" => "Reconciled"
        ];
    }

    public function getStatusName()
    {
        return self::getMemosStatusList()[$this->getField('status')];
    }

    public function getAsArray()
    {
        return $this->aGroupMemos;
    }

    public function countOrderGroupMemos() {
        $count = 0;
        if (!empty($this->aGroupMemos))
            $count = count($this->aGroupMemos);
        return $count;
    }

    public function getOrderGroupMemos($aParams = [])
    {
        $aRes = func_query("SELECT * FROM " . self::$sql_tbl[$this->sPrimaryTable] . " WHERE orderid = " . $aParams['orderid'] . " AND manufacturerid = " . $aParams['manufacturerid']);
        if (!empty($aRes)) {
            foreach ($aRes as $aGroupMemo) {
                $oGroupMemo = new OrderGroupInvoice();
                $oGroupMemo->fill($aGroupMemo);
                $this->aGroupMemos[] = $oGroupMemo;
            }
        }
        return $this;
    }

    public function getOrderGroupMemoRefToUsTotal()
    {
        $fRes = 0;
        if (!empty($this->aGroupMemos)) {
            foreach ($this->aGroupMemos as $oGroupMemo) {
                $fRes+=floatval($oGroupMemo->getField('ref_to_us_total'));
            }
        }
        return $fRes;
    }

    public function getOrderGroupMemoRefToUsHST()
    {
        $fRes = 0;
        if (!empty($this->aGroupMemos)) {
            foreach ($this->aGroupMemos as $oGroupMemo) {
                $fRes+=floatval($oGroupMemo->getField('ref_to_us_HST'));
            }
        }
        return $fRes;
    }
}