<?php
namespace Xcart;

class SQLBuilder
{
    protected static $sql_tbl = [];
    private $aSelect = [];
    private $aTables = [];
    private $aInnerJoinTables = [];
    private $aConditions = [];
    private $aOrders = [];
    private $aGroups = [];
    private $aHaving = [];
    private $sLimit = null;
    private $sqlQuery = null;
    private $aSqlQueryResult = [];
    private $cacheTime = null;

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
        $this->sLimit = null;
        $this->sqlQuery = null;
        $this->aSqlQueryResult = [];
        $this->cacheTime = null;
        return $this;
    }

    public static function getInstance()
    {
        return new self();
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

    public function setSelect($sSelect, $sAlias = null)
    {
        $this->aSelect = [];
        $this->addSelect($sSelect, $sAlias);
        return $this;
    }

    public function addInnerJoin($sTable, $sAlias = null, $sCondition, $sType = 'INNER JOIN')
    {
        $this->aInnerJoinTables[] = ['type' => $sType, 'condition' => self::$sql_tbl[$sTable] . ((!empty($sAlias)) ? ' as ' . $sAlias : '') . ' ON ' . $sCondition];
        return $this;
    }

    public function addFromTable($sTable, $sAlias = null)
    {
        $this->aTables[] = self::$sql_tbl[$sTable] . ((!empty($sAlias)) ? ' as ' . $sAlias : '');
        return $this;
    }

    public function setFromTable($sTable, $sAlias = null)
    {
        $this->aTables = [];
        $this->addFromTable($sTable, $sAlias);
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
        $this->sLimit = $sLimit;
        return $this;
    }

    private function generateSQL()
    {
        if (empty($this->sqlQuery)) {
            if (!empty($this->aSelect)) {
                $this->sqlQuery = "SELECT " . implode(',', $this->aSelect);
            }
            if (!empty($this->aTables)) {
                $this->sqlQuery .= " FROM " . implode(',', $this->aTables);
            }
            if (!empty($this->aInnerJoinTables)) {
                foreach ($this->aInnerJoinTables as $aJoin) {
                    $this->sqlQuery .= ' ' . $aJoin['type'] . ' ';
                    $this->sqlQuery .= $aJoin['condition'];
                }
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
            if (!empty($this->sLimit)) {
                $this->sqlQuery .= " LIMIT " . $this->sLimit;
            }
        }
    }

    public function Execute($hashColumn = null)
    {
        $this->generateSQL();
        if (!empty($hashColumn)) {
            $this->aSqlQueryResult = func_query_hash($this->sqlQuery, $hashColumn, true, false, $this->cacheTime);
        } else
            $this->aSqlQueryResult = func_query($this->sqlQuery, $this->cacheTime);
        return $this;
    }

    public function query($hashColumn = null)
    {
        return $this->Execute($hashColumn);
    }

    public function query_first()
    {
        $this->setLimit('1');
        $this->generateSQL();
        $this->aSqlQueryResult = func_query_first($this->sqlQuery, $this->cacheTime);
        return $this;
    }

    public function getQuery()
    {
        return $this->sqlQuery;
    }

    public function setQuery($sSql)
    {
        $this->sqlQuery = $sSql;
        return $this;
    }

    public function getQueryResult()
    {
        return $this->aSqlQueryResult;
    }

    public function addFilter($aParams)
    {
        if (!empty($aParams)) {
            foreach ($aParams as $key => $value) {
                if (is_array($value)) {
                    $this->addCondition("$key IN('" . implode("','", $value) . "')");
                } else {
                    $this->addCondition("$key='" . addslashes($value) . "'");
                }
            }
        }
        return $this;
    }

    public function getFoundRows()
    {
        return func_query_first_cell('SELECT FOUND_ROWS()');
    }

    public function getQueryResultFirst()
    {
        return reset($this->aSqlQueryResult);
    }

    public function cache(int $time = 0):self
    {
        $this->cacheTime = $time;

        return $this;
    }

}