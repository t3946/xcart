<?php


namespace Modules\Order\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DecimalField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\UnixTimestampField;
use Xcart\App\Orm\Model;

class CheckDepositedOrderModel extends Model
{
    public static function tableName()
    {
        return 'xcart_checks_deposited_orders';
    }

    public static function getFields()
    {
        return [
            'id' => AutoField::class,
            'checks_deposited' => [
                'field' => 'checks_deposited_id',
                'class' => ForeignField::class,
                'modelClass' => CheckDepositedModel::class,
                'link' => ['checks_deposited_id' => 'checks_deposited_id'],
            ],
            'order' => [
                'field' => 'orderid',
                'class' => ForeignField::class,
                'modelClass' => OrderModel::class,
                'link' => ['orderid' => 'orderid'],
                'verboseName' => 'Order #'
            ],
            'check_number' => [
                'class' => CharField::class,
                'verboseName' => 'Customer Check #'
            ],
            'amount' => [
                'class' => DecimalField::class,
                'default' => 0
            ],
            'notes' => [
                'class' => CharField::class,
                'default' => '',
                'verboseName' => 'Internal Notes'
            ],
            'date_added' => [
                'class' => UnixTimestampField::class,
                'autoNowAdd' => true
            ]
        ];
    }
}