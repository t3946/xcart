<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 05/12/2016
 * Time: 21:26
 */

namespace Xcart\App\Pagination\DataSource;

use Xcart\App\Orm\Manager;
use Xcart\App\Orm\QuerySet;

interface DataSourceInterface
{
    /**
     * @param $source
     * @return int
     */
    public function getTotal($source);

    /**
     * select rows for one pagination page
     * @param Manager|QuerySet $source query builder
     * @param int $page pagination page number
     * @param int $pageSize number objects on pagination page
     * @return array
     */
    public function applyLimit($source, $page, $pageSize);

    /**
     * @param $source
     * @return bool
     */
    public function supports($source);
}