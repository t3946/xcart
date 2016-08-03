<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classSQLBuilder.php";

class classData
{
    protected static $sql_tbl = [];
    protected $aPrimaryKeys = [];
    protected $aPrimaryKeysValues = [];
    protected $aPrimaryTableValue;
    protected $sPrimaryTable;
    protected $oSQL = null;

    /**
     * @param array $aParams
     */
    public function __construct($aParams = [])
    {
        global $sql_tbl;
        self::$sql_tbl = $sql_tbl;

        if (!empty($aParams)) {
            $this->aPrimaryKeysValues = array_intersect_key($aParams, array_flip($this->aPrimaryKeys));
            $this->fillPrimaryTableInfo();
        }
        $this->oSQL = new classSQLBuilder();
    }

    protected function _clone()
    {
        return clone $this;
    }

    public function _insert($is_replace = false)
    {
        func_array2insert($this->sPrimaryTable, $this->aPrimaryTableValue, $is_replace);
    }

    public function _refresh()
    {
        $this->fillPrimaryTableInfo();
    }

    protected function fillPrimaryTableInfo()
    {
        if (!empty($this->aPrimaryKeysValues))
            $this->aPrimaryTableValue = func_query_first("SELECT * FROM " . self::$sql_tbl[$this->sPrimaryTable] . " WHERE " . str_replace('&', ' AND ', http_build_query($this->aPrimaryKeysValues)));
    }

    public function fillPrimaryTableValues($aValues)
    {
        if (!empty($aValues)) {
            $this->aPrimaryTableValue = $aValues;
            $this->aPrimaryKeysValues = array_intersect_key($aValues, array_flip($this->aPrimaryKeys));
        }
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
        return $this;
    }

    public function setFields($aFieldNamesValues)
    {
        foreach ($aFieldNamesValues as $key => $value)
            $this->aPrimaryTableValue[$key] = $value;
        return $this;
    }

    public function updateField($sFieldName, $sNewValue)
    {
        $this->setField($sFieldName, $sNewValue);
        $aToUpdate[$sFieldName] = $sNewValue;
        if (empty($this->aPrimaryKeysValues))
            throw new Exception('Empty primary keys values for update field');
        func_array2update($this->sPrimaryTable, $aToUpdate, str_replace('&', ' AND ', http_build_query($this->aPrimaryKeysValues)));
        return $this;
    }

    public function updateFields($aFieldNamesValues = [])
    {
        if (!empty($aFieldNamesValues)) {
            $this->setFields($aFieldNamesValues);
            if (empty($this->aPrimaryKeysValues))
                throw new Exception('Empty primary keys values for update fields');
            func_array2update($this->sPrimaryTable, $aFieldNamesValues, str_replace('&', ' AND ', http_build_query($this->aPrimaryKeysValues)));
        }
        return $this;
    }
}