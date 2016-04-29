<?php
class classCloneData
{
    protected $sql_tbl = array();
    protected $arrCheckFields = array();
    protected $sPrimaryKeyFiled;
    protected $sPrimaryTable;
    protected $primaryKeyValue;
    public $message = array();

    public function __construct()
    {
        global $sql_tbl;
        $this->sql_tbl = $sql_tbl;

    }

    protected function recursive_escape(&$item) {
        $item = mysql_real_escape_string($item);
    }

    protected function search_array_key_value ($array, $key, $value)
    {
        $results = array();

        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }

            foreach ($array as $subarray) {
                $results = array_merge($results, $this->search_array_key_value($subarray, $key, $value));
            }
        }

    return $results;
    }


public function checkDBChanges () {
        $bResult = true;
        $currentDBSchema = array();

        foreach (array_keys($this->arrCheckFields) as $sTable){
            if (isset($this->sql_tbl[$sTable]) && !empty($this->sql_tbl[$sTable])) {
                $currentDBSchema[$sTable] = array_keys(func_query_first("SELECT * FROM " . $this->sql_tbl[$sTable] . " LIMIT 1"));
            }
        }

        $diffArray = func_array_compare($this->arrCheckFields,$currentDBSchema);

        if (count($diffArray) > 0) $bResult = false;

        return $bResult;
    }

    protected function getDiffTableStructure($aExcludeFields) {
        $aResult = array();
        foreach ($this->arrCheckFields as $key => $value) {
            $aResult[$key] = array_diff($this->arrCheckFields[$key], empty($aExcludeFields[$key]['exclude'])?array():$aExcludeFields[$key]['exclude']);
        }
        return $aResult;
    }

    protected function getClonedData($aExcludeFields, $aParams, $bExclude = true)  {
        $aSelectResult = array();
        if ($bExclude)
            $aDiffStructure = $this->getDiffTableStructure($aExcludeFields);
        else
            $aDiffStructure = $this->getDiffTableStructure(array());

        foreach ($aDiffStructure as $sTable => $aFields) {
            $sSelectFields = implode(", ", $aFields);
            $aSelectResult[$sTable] = func_query("SELECT $sSelectFields FROM ".$this->sql_tbl[$sTable]." WHERE ".$aExcludeFields[$sTable]['primarykey']." = ".$aParams[$this->sPrimaryKeyFiled]);
        }

        return $aSelectResult;
    }

    protected function DublicatePrimaryTable ($aCloneData, $aCloneParam){
        $insertRow = reset($aCloneData[$this->sPrimaryTable]);
        foreach ($aCloneParam as $key => $value) {
            if (in_array($key, $this->arrCheckFields[$this->sPrimaryTable]) && $key != $this->sPrimaryKeyFiled) {
                $insertRow[$key] = $value;
            }
        }
        array_walk_recursive($insertRow, array('classClonedata','recursive_escape'));

        return func_array2insert($this->sPrimaryTable, $insertRow);
    }

}