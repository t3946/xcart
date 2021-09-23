<?php

namespace Xcart\App\QueryBuilder\Aggregation;

use Xcart\App\QueryBuilder\QueryBuilder;

class Sum extends Aggregation
{
    public function toSQL(QueryBuilder $qb = null)
    {
        return 'SUM(' . parent::toSQL($qb) . ')' . (empty($this->alias) ? '' : ' AS ' . $this->getQuotedAlias($qb));
    }
}