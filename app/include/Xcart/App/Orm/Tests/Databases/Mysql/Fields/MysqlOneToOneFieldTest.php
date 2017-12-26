<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 24/07/16
 * Time: 07:35
 */

namespace Xcart\App\Orm\Tests\Databases\Mysql\Fields;

use Xcart\App\Orm\Tests\Fields\OneToOneFieldTest;

class MysqlOneToOneFieldTest extends OneToOneFieldTest
{
    public $driver = 'mysql';
}