<?php
namespace Xcart\App\Orm;

use Mindy\QueryBuilder\Q\QAndNot;
use Mindy\QueryBuilder\QueryBuilder;
use Xcart\App\Exceptions\MultipleObjectsReturned;
use Xcart\App\Traits\Configurator;
use Xcart\Connection;
use Xcart\Data;

class QuerySet
{
    use Configurator;

    public $modelClass;

    /** @var Data */
    public $model;

    /**
     * @var \Doctrine\DBAL\Connection
     */
    public $db;

    /** @var QueryBuilder  */
    private $qb = null;
    private $asSql = false;
    private $asArray = false;
    private $_group = [];
    private $data = null;

    public function init()
    {
        $this->db = Connection::getInstance();
        $this->qb = QueryBuilder::getInstance($this->db);

        $this->qb->from($this->model->getTableName());
        $this->qb->setAlias('t');
    }

    public function setAlias($alias)
    {
        $this->qb->setAlias($alias);
        return $this;
    }

    public function getAlias()
    {
        return $this->qb->getAlias();
    }

    public function filter($where = [])
    {
        $this->qb->where($where);
        return $this;
    }

    public function orFilter($where = [])
    {
        $this->qb->orWhere($where);
        return $this;
    }

    public function exclude($where = [])
    {
        if (!empty($where)) {
            $this->qb->where([new QAndNot($where)]);
        }
        return $this;
    }

    public function orExclude($where = [])
    {
        if (!empty($where)) {
            $this->qb->orWhere([new QAndNot($where)]);
        }

        return $this;
    }

    public function offset($offset = 0)
    {
        $this->qb->offset($offset);
        return $this;
    }

    public function limit($limit = 0)
    {
        $this->qb->limit($limit);
        return $this;
    }

    public function count($q = '*', $distinct = false)
    {
        $count = Connection::getInstance()->fetchAll($this->countSql($q, $distinct));

        if (count($count) > 1)
        {
            return count($count);
        }
        else {
            return empty($count) ? 0 : reset($count[0]);
        }

    }

    public function countSql($q = '*', $distinct = false)
    {
        $qb = clone $this->qb;

        $qb->select("count({$q})", $distinct);
        $qb->group($this->_group);
        $qb->limit(null);

        return $qb->toSQL();
    }

    /**
     * Sets the [[asArray]] property.
     * @param boolean $value whether to return the query results in terms of arrays instead of Active Records.
     * @return static the query object itself
     */
    public function asSql($value = true)
    {
        $this->asSql = $value;
        return $this;
    }

    public function asArray($value = true)
    {
        $this->asArray = $value;
        return $this;
    }

    public function getSql(array $where = [])
    {
        $this->qb->where($where);

        return $this->prepareSql();
    }

    public function using(\Doctrine\DBAL\Connection $db)
    {
        $this->db = $db;
        $this->qb->setConnection($this->db);
        return $this;
    }

    public function select($columns, $option = null)
    {
        $this->qb->select($columns);
        if ($option) {
            $this->qb->setOptions($option);
        }
        return $this;
    }

    public function get($filter = [])
    {
        $this->filter($filter);
        $rows = $this->db->fetchAll($this->getSql());
        if (count($rows) > 1) {
            throw new MultipleObjectsReturned();
        } elseif (count($rows) === 0) {
            return null;
        }

        $row = array_shift($rows);
        return $this->asArray ? $row : $this->createModel($row);
    }

    public function all($where = [])
    {
        $this->filter($where);
        return $this->getData();
    }

    public function getData()
    {
        if (empty($this->_data))
        {
            $this->_data = $this->db->fetchAll($this->prepareSql());
        }

        return $this->asArray ? $this->_data : $this->createModels($this->_data);
    }

    public function createModels(array $rows)
    {
        $models = [];
        foreach ($rows as $row) {
            $models[] = $this->createModel($row);
        }
        return $models;
    }

    public function createModel(array $row)
    {
        /** @var Data $className */
        $className = $this->modelClass;
        if (!$className) {
            throw new \Exception('$className must be a string in createModel method of qs');
        }
        return $className::create($row);
    }



    public function addGroupBy($column)
    {
        $this->_group[] = $column;
        return $this;
    }


    public function distinct($fields = true)
    {
        return $this->qb->distinct($fields);
    }

    public function group($fields)
    {
        $this->_group = $fields;

        return $this;
    }

    private function prepareSql()
    {
        $this->qb->group($this->_group);
        return $this->qb->toSQL();
    }


    public function join($type, $table, $on = '', $alias = '')
    {
        $this->qb->join($type, $table, $on, $alias);
        return $this;
    }

    /**
     * @return array
     */
    public function getJoins()
    {
        return $this->qb->getJoins();
    }

    public function from($tables)
    {
        $this->qb->from($tables);
        return $this;
    }

    public function order($columns)
    {
        $this->qb->order($columns);
        return $this;
    }
}