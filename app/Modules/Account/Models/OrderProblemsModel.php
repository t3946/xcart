<?php

namespace Modules\Account\Models;

use Modules\Order\Models\OrderModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class OrderProblemsModel extends Model
{
    public static function tableName(): string
    {
        return 'account_order_problems';
    }

    public static function getFields(): array
    {
        return [
            'problem_id' => [
                'class' => AutoField::class,
            ],
            'status' => [
                'field' => 'status_id',
                'class' => ForeignField::class,
                'modelClass' => OrderProblemStatusesModel::class,
                'link' => ['status_id' => 'status_id'],
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