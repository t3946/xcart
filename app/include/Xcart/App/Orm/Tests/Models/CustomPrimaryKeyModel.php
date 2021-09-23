<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 16/09/16
 * Time: 19:04
 */

namespace Xcart\App\Orm\Tests\Models;

use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\AbstractModel;

class CustomPrimaryKeyModel extends AbstractModel
{
    public static function getFields()
    {
        return [
            'id' => [
                'class' => IntField::class,
                'primary' => true
            ],
        ];
    }
}