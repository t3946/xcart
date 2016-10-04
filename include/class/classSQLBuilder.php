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
    private $aHaving = [];
    private $aLimit = [];
    private $sqlQuery = null;
    private $aSqlQueryResult = [];

    public function __construct()
    {
        global $sql_tbl;
        self::$sql_tbl = $sql_tbl;
    }

    public function init()
    {
        $this->aSelect = [];
        $this->aTables = [];
        $this->aInnerJoinTables = [];
        $this->aConditions = [];
        $this->aOrders = [];
        $this->aGroups = [];
        $this->aHaving = [];
        $this->aLimit = [];
        $this->sqlQuery = null;
        $this->aSqlQueryResult = [];
        return $this;
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

    public function addInnerJoin($sTable, $sAlias=null, $sCondition)
    {
        $this->aInnerJoinTables[] = self::$sql_tbl[$sTable] . ((!empty($sAlias)) ? ' as ' . $sAlias : '') . ' ON ' . $sCondition;
        return $this;
    }

    public function addFromTable($sTable, $sAlias=null)
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

    public function addHaving($sHaving)
    {
        $this->aHaving[] = $sHaving;
        return $this;
    }

    public function setLimit($sLimit)
    {
        $this->aLimit[] = $sLimit;
        return $this;
    }

    private function generateSQL()
    {
        if (!empty($this->aSelect)) {
            $this->sqlQuery = "SELECT " . implode(',', $this->aSelect);
        }
        if (!empty($this->aTables)) {
            $this->sqlQuery .= " FROM " . implode(',', $this->aTables);
        }
        if (!empty($this->aInnerJoinTables)) {
            $this->sqlQuery .= ' INNER JOIN ';
            $this->sqlQuery .= implode(' INNER JOIN ', $this->aInnerJoinTables);
        }
        if (!empty($this->aConditions)) {
            $this->sqlQuery .= " WHERE " . implode(' AND ', $this->aConditions);
        }
        if (!empty($this->aGroups)) {
            $this->sqlQuery .= " GROUP BY " . implode(',', $this->aGroups);
        }
        if (!empty($this->aHaving)) {
            $this->sqlQuery .= " HAVING " . implode(',', $this->aHaving);
        }
        if (!empty($this->aOrders)) {
            $this->sqlQuery .= " ORDER BY " . implode(',', $this->aOrders);
        }
        if (!empty($this->aLimit)) {
            $this->sqlQuery .= " LIMIT " . implode(',', $this->aLimit);
        }
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

    public function getQuery()
    {
        return $this->sqlQuery;
    }

    public function getQueryResult()
    {
        return $this->aSqlQueryResult;
    }

    public function getQueryResultFirst()
    {
        return reset($this->aSqlQueryResult);
    }

}