<?php

namespace Xcart\App\QueryBuilder\Interfaces;

use Xcart\App\QueryBuilder\QueryBuilder;

/**
 * Interface ILookupBuilder
 * @package Mindy\QueryBuilder
 */
interface ILookupBuilder
{
    /**
     * @param $lookup
     * @param $value
     * @return array
     */
    public function parseLookup(QueryBuilder $queryBuilder, $lookup, $value);

    /**
     * @param array $where
     * @return mixed
     */
    public function parse(QueryBuilder $queryBuilder, array $where);

    /**
     * @param \Closure $callback
     * @return mixed
     */
    public function setCallback($callback);

    /**
     * @param ILookupCollection $lookupCollection
     * @return $this
     */
    public function addLookupCollection(ILookupCollection $lookupCollection);

    /**
     * @param IAdapter $adapter
     * @param $lookup
     * @param $column
     * @param $value
     * @return mixed
     */
    public function runLookup(IAdapter $adapter, $lookup, $column, $value);
}