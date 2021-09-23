<?php

/**
 * All rights reserved.
 *
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 06/02/15 19:10
 */

namespace Xcart\App\Orm\Tests\Databases\Sqlite;

use Xcart\App\Orm\Tests\QueryBuilder\OrderByLookupTest;

class SqliteOrderByLookupTest extends OrderByLookupTest
{
    public $driver = 'sqlite';
}
