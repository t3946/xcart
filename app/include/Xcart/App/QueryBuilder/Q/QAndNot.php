<?php

namespace Xcart\App\QueryBuilder\Q;


use Xcart\App\QueryBuilder\QueryBuilder;

class QAndNot extends QAnd
{
    public function toSQL(QueryBuilder $queryBuilder)
    {
        return 'NOT (' . parent::toSQL($queryBuilder) . ')';
    }
}