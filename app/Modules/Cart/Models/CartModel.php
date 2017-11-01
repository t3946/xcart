<?php

namespace Modules\Cart\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\SerializeField;
use Xcart\App\Orm\Model;

class CartModel extends Model
{
    public static function tableName()
    {
        return 'xcart_cart';
    }

    public static function getFields()
    {
        return [
            'id' => AutoField::class,
            'data' => SerializeField::class,
            'created_at' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
            ],
        ];
    }
}