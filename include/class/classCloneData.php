<?php
class classCloneData
{
    protected static $sql_tbl = array();
    protected $arrCheckFields = array();
    protected $sPrimaryKeyFiled;
    protected $sPrimaryTable;
    protected $primaryKeyValue;
    protected $aPrimaryTableValue = array();
    protected $arrCloneTableStructure = array();
    private $aClonedData;
    public $message = array();

    public function __construct($iId = null)
    {
        global $sql_tbl;
        self::$sql_tbl = $sql_tbl;
        if (!is_null($iId) && is_numeric($iId)) {
            $this->fillPrimaryTableInfo($iId);
        } else if (!empty($iId) && is_array($iId)) {
            $this->setPrimaryTableInfo($iId);
        }
    }

    private function fillPrimaryTableInfo($iId) {
        $this->aPrimaryTableValue = func_query_first("SELECT * FROM ".self::$sql_tbl[$this->sPrimaryTable]." WHERE ".$this->sPrimaryKeyFiled." = $iId");
        if (!empty($this->aPrimaryTableValue) && is_array($this->aPrimaryTableValue))
            $this->primaryKeyValue = $this->aPrimaryTableValue[$this->sPrimaryKeyFiled];
    }

    protected function setPrimaryTableInfo ($aValue) {
        $this->aPrimaryTableValue = $aValue;
        $this->primaryKeyValue = $this->aPrimaryTableValue[$this->sPrimaryKeyFiled];
    }

    protected function recursive_escape(&$item) {
        $item = addslashes($item);
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

    protected function deleteFromTableByKeyValue($sTable, $sKeyField, $iKeyValue) {
        db_query("DELETE FROM ".self::$sql_tbl[$sTable]. " WHERE $sKeyField = '$iKeyValue'");
    }


public function checkDBChanges () {
        $bResult = true;
        $currentDBSchema = array();

        foreach (array_keys($this->arrCheckFields) as $sTable){
            if (isset(self::$sql_tbl[$sTable]) && !empty(self::$sql_tbl[$sTable])) {
                $currentDBSchema[$sTable] = array_keys(func_query_first("SELECT * FROM " . self::$sql_tbl[$sTable] . " LIMIT 1"));
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

    protected function getClonedData($aParams)  {
        $aSelectResult = array();

        foreach ($this->arrCloneTableStructure as $sTable) {
            $aSelectResult[$sTable['table']]['result'] = func_query("SELECT * FROM ".self::$sql_tbl[$sTable['table']]." WHERE ".$sTable['key_field']." = ".$aParams[$this->sPrimaryKeyFiled]);
            if (isset($aSelectResult[$sTable['table']]['result']) && is_array($aSelectResult[$sTable['table']]['result']))
                foreach ($aSelectResult[$sTable['table']]['result']  as &$aRows) {
                    if ($sTable['primary_key'] != $sTable['key_field']) {
                        unset($aRows[$sTable['primary_key']]);
                    }
                }
            $aSelectResult[$sTable['table']]['key_field'] =  $sTable['key_field'];
        }

        return $aSelectResult;
    }

    protected function DublicatePrimaryTable ($aCloneParam, $onlyFeelClonedData = false){

        $this->aClonedData = $this->getClonedData($aCloneParam);
        if ($onlyFeelClonedData) return;

        $insertRow = reset($this->aClonedData[$this->sPrimaryTable]['result']);
        foreach ($aCloneParam as $key => $value) {
            if (in_array($key, array_keys($insertRow)) && $key != $this->sPrimaryKeyFiled) {
                $insertRow[$key] = $value;
            }
        }
        unset($insertRow[$this->sPrimaryKeyFiled]);
        array_walk_recursive($insertRow, array(__CLASS__,'recursive_escape'));
//        func_print_r($insertRow);
        return func_array2insert($this->sPrimaryTable, $insertRow);
    }

    protected function DublicateNonPrimaryTable ($aCloneParam, $deleteBeforeInsert = false){
        //$this->aClonedData = $this->getClonedData($aCloneParam); //added after tests

        unset ($this->aClonedData[$this->sPrimaryTable]);

        if (isset($this->aClonedData) && is_array($this->aClonedData) && count($this->aClonedData)>0) {
            foreach ($this->aClonedData as $sTable => $aRowsToClone) {
                if ($deleteBeforeInsert) {
                    $this->deleteFromTableByKeyValue($sTable, $aRowsToClone['key_field'], $aCloneParam[$this->sPrimaryKeyFiled]);
                }
                if (isset($aRowsToClone['result']) && is_array($aRowsToClone['result']) && count($aRowsToClone['result']) > 0)
                    foreach ($aRowsToClone['result'] as $aRow) {
                        foreach ($aCloneParam as $keyParam => $valueParam) {
                            if (in_array($keyParam, array_keys($aRow))) {
                                $aRow[$keyParam] = $valueParam;
                            }
                        }

                        $aRow[$aRowsToClone['key_field']] = $aCloneParam[$this->sPrimaryKeyFiled];


                        array_walk_recursive($aRow, array(__CLASS__,'recursive_escape'));
//func_print_r($aRow);
                        if ($sTable == 'pricing' && $deleteBeforeInsert == false) {
                            func_backprocess_log("clone_products_cron", "Clone pricing table. productid = ".$aCloneParam[$this->sPrimaryKeyFiled]."; aCloneRow - ".serialize($aRow));
                        }

                        if ((func_array2insert($sTable, $aRow)) === false) {
                            func_backprocess_log("clone_products_cron_errors", "Error clone table - ".$sTable." - ".serialize($aRow));
                            return false;
                        }

                    }
            }
        }

        return true;
    }

    public function getField($sFieldName = null) {
        if (empty($sFieldName))
            $res = $this->getFields();
        else $res = $this->aPrimaryTableValue[$sFieldName];
        return $res;
    }

    public function getFields($aFields = array()) {
        if (is_array($aFields)) {
            if (empty($aFields)) return $this->aPrimaryTableValue;
            return array_intersect($this->aPrimaryTableValue, $aFields);
        } else {
            return $this->aPrimaryTableValue;
        }
    }

    public function setField($sFieldName, $sNewValue) {
        $this->aPrimaryTableValue[$sFieldName] = $sNewValue;
    }

}