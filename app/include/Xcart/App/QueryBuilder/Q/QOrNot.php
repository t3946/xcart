<?php

namespace Xcart\App\QueryBuilder\Q;


use Xcart\App\QueryBuilder\QueryBuilder;

class QOrNot extends QOr
{
    public function toSQL(QueryBuilder $queryBuilder)
    {
        return 'NOT (' . parent::toSQL($queryBuilder) . ')';
    }
}