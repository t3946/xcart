<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 24/07/16
 * Time: 07:35
 */

namespace Xcart\App\Orm\Tests\Pgsql\Fields;

use Xcart\App\Orm\Tests\Fields\OneToOneFieldTest;

class PgsqlOneToOneFieldTest extends OneToOneFieldTest
{
    public $driver = 'pgsql';
}