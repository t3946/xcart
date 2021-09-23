<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 16/09/16
 * Time: 19:45
 */

namespace Xcart\App\Orm\Tests\Models;

use Xcart\App\Orm\Fields\IntField;

class CompositeModel extends DummyModel
{
    public static function getFields()
    {
        return [
            'order_id' => [
                'class' => IntField::class,
                'primary' => true
            ],
            'user_id' => [
                'class' => IntField::class,
                'primary' => true
            ]
        ];
    }
}