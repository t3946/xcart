<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 22/06/16
 * Time: 10:02
 */

namespace Xcart\App\QueryBuilder;

use Doctrine\DBAL\Driver\Connection;
use Xcart\App\QueryBuilder\Interfaces\ICallback;
use Xcart\App\QueryBuilder\Interfaces\ILookupBuilder;

class QueryBuilderFactory
{
    /**
     * @var BaseAdapter
     */
    protected $adapter;
    /**
     * @var ILookupBuilder
     */
    protected $lookupBuilder;
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * QueryBuilder constructor.
     * @param Connection $connection
     * @param BaseAdapter $adapter
     * @param ILookupBuilder $lookupBuilder
     * @internal param ICallback $callback
     */
    public function __construct(Connection $connection, BaseAdapter $adapter, ILookupBuilder $lookupBuilder)
    {
        $this->connection = $connection;
        $this->adapter = $adapter;
        $this->lookupBuilder = $lookupBuilder;
    }

    public function getQueryBuilder()
    {
        return new QueryBuilder($this->connection, $this->adapter, $this->lookupBuilder);
    }
}