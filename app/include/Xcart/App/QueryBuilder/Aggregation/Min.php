<?php

namespace Xcart\App\QueryBuilder\Aggregation;

use Xcart\App\QueryBuilder\QueryBuilder;

class Min extends Aggregation
{
    public function toSQL(QueryBuilder $qb = null)
    {
        return 'MIN(' . parent::toSQL($qb) . ')' . (empty($this->alias) ? '' : ' AS ' . $this->getQuotedAlias($qb));
    }
}