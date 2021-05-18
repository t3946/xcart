<?php

namespace Xcart\App\QueryBuilder\Aggregation;

use Xcart\App\QueryBuilder\QueryBuilder;

class Max extends Aggregation
{
    public function toSQL(QueryBuilder $qb = null)
    {
        return 'MAX(' . parent::toSQL($qb) . ')' . (empty($this->alias) ? '' : ' AS ' . $this->getQuotedAlias($qb) );
    }
}