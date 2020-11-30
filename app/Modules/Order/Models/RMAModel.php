<?php


namespace Modules\Order\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\UnixTimestampField;
use Xcart\App\Orm\Model;

class RMAModel extends Model
{
    public static function tableName()
    {
        return 'xcart_rmas';
    }

    public static function getFields()
    {
        return [
            'rma_id' => AutoField::class,
            'order' => [
                'field' => 'orderid',
                'class' => ForeignField::class,
                'modelClass' => OrderModel::class,
                'link' => ['orderid' => 'orderid'],
            ],
            'zipcode' => [
                'class' => CharField::class,
                'default' => '',
                'null' => false
            ],
            'email' => [
                'class' => CharField::class,
                'default' => '',
                'null' => false
            ],
            'explanation' => [
                'class' => CharField::class,
                'default' => '',
                'null' => false
            ],
            'date' => [
                'class' => UnixTimestampField::class
            ]

        ];
    }
}