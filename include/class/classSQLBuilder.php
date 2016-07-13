<?php

class classSQLBuilder
{
    protected static $sql_tbl = [];
    private $aSelect = [];
    private $aTables = [];
    private $aInnerJoinTables = [];
    private $aConditions = [];
    private $aOrders = [];
    private $aGroups = [];
    private $sqlQuery = null;
    private $aSqlQueryResult = [];

    public function __construct()
    {
        global $sql_tbl;
        self::$sql_tbl = $sql_tbl;
    }

    public function addCondition($sCondition)
    {
        $this->aConditions[] = $sCondition;
        return $this;
    }

    public function addSelect($sSelect, $sAlias = null)
    {
        $this->aSelect[] = $sSelect . ((!empty($sAlias)) ? ' as ' . $sAlias : '');
        return $this;
    }

    public function addInnerJoin($sTable, $sAlias, $sCondition)
    {
        $this->aInnerJoinTables[] = self::$sql_tbl[$sTable] . ((!empty($sAlias)) ? ' as ' . $sAlias : '') . ' ON ' . $sCondition;
        return $this;
    }

    public function addFromTable($sTable, $sAlias)
    {
        $this->aTables[] = self::$sql_tbl[$sTable] . ((!empty($sAlias)) ? ' as ' . $sAlias : '');
        return $this;
    }

    public function addOrderBy($sOrderBy)
    {
        $this->aOrders[] = $sOrderBy;
        return $this;
    }

    public function addGroupBy($sGroupBy)
    {
        $this->aGroups[] = $sGroupBy;
        return $this;
    }

    private function generateSQL()
    {
        $this->sqlQuery = "SELECT " . implode(',', $this->aSelect);
        $this->sqlQuery .= " FROM " . implode(',', $this->aTables);
        if (!empty($this->aInnerJoinTables))
            $this->sqlQuery .= ' INNER JOIN ';
        $this->sqlQuery .=  implode(' INNER JOIN ', $this->aInnerJoinTables);
        $this->sqlQuery .= " WHERE " . implode(' AND ', $this->aConditions);
        $this->sqlQuery .= " GROUP BY " . implode(',', $this->aGroups);
        $this->sqlQuery .= " ORDER BY " . implode(',', $this->aOrders);
    }

    public function Execute($hashColumn = null)
    {
        $this->generateSQL();
        if (!empty($hashColumn)) {
            $this->aSqlQueryResult = func_query_hash($this->sqlQuery, $hashColumn);
        } else
            $this->aSqlQueryResult = func_query($this->sqlQuery);
        return $this;
    }

    public function getQueryResult()
    {
        return $this->aSqlQueryResult;
    }

}