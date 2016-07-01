<?php
class classData
{
    protected static $sql_tbl = [];
    protected $aPrimaryKeys = [];
    protected $aPrimaryKeysValues = [];
    protected $aPrimaryTableValue;
    protected $sPrimaryTable;

    public function __construct($aParams = [])
    {
        global $sql_tbl;
        self::$sql_tbl = $sql_tbl;

        if (!empty($aParams)) {
            $this->aPrimaryKeysValues = array_intersect_key($aParams, array_flip($this->aPrimaryKeys));
            $this->fillPrimaryTableInfo();
        }
    }

    protected function fillPrimaryTableInfo()
    {
        if (!empty($this->aPrimaryKeysValues))
            $this->aPrimaryTableValue = func_query_first("SELECT * FROM " . self::$sql_tbl[$this->sPrimaryTable] . " WHERE " . str_replace('&',' AND ',http_build_query($this->aPrimaryKeysValues)));
    }

    /**
     * @param string $sFieldName
     * @return mixed|array
     */
    public function getField($sFieldName = null)
    {
        if (empty($sFieldName))
            $res = $this->getFields();
        else $res = $this->aPrimaryTableValue[$sFieldName];
        return $res;
    }

    public function getFields($aFields = array())
    {
        if (is_array($aFields)) {
            if (empty($aFields)) return $this->aPrimaryTableValue;
            return array_intersect_key($this->aPrimaryTableValue, array_flip($aFields));
        } else {
            return $this->aPrimaryTableValue;
        }
    }

    public function setField($sFieldName, $sNewValue)
    {
        $this->aPrimaryTableValue[$sFieldName] = $sNewValue;
    }
}