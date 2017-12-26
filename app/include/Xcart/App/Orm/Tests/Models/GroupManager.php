<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 24/07/16
 * Time: 13:12
 */

namespace Xcart\App\Orm\Tests\Models;

use Xcart\App\Orm\Manager;

class GroupManager extends Manager
{
    public function published()
    {
        return $this;
    }
}