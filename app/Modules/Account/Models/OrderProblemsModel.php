<?php

namespace Modules\Account\Models;

use Modules\Order\Models\OrderModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class OrderProblemsModel extends Model
{
    public static function tableName()
    {
        return 'account_order_problems';
    }

    public static function getFields()
    {
        return [
            'problem_id' => [
                'class' => AutoField::class,
            ],
            'status' => [
                'field' => 'order_id',
                'class' => ForeignField::class,
                'modelClass' => OrderModel::class,
                'link' => ['problem_status' => 'status_id'],
            ],
            'order' => [
                'field' => 'order_id',
                'class' => ForeignField::class,
                'modelClass' => OrderModel::class,
                'link' => ['order_id' => 'orderid'],
            ],
            'problem_text' => [
                'class' => CharField::class,
            ],
        ];
    }
}