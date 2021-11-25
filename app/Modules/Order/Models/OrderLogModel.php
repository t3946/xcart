<?php

namespace Modules\Order\Models;

use Modules\User\Models\UserModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\TextField;
use Xcart\App\Orm\Fields\TimestampField;
use Xcart\App\Orm\Fields\UnixTimestampField;
use Xcart\App\Orm\Model;

class OrderLogModel extends Model
{
    public const LOG_TYPE_CUSTOMER = 'C';
    public const LOG_TYPE_XCART = 'X';
    public const LOG_TYPE_SYSTEM = 'S';
    public const LOG_TYPE_PAYMENT_PROCESS = 'PP';
    public const LOG_TYPE_END_LINE = 'EL';

    public static function tableName()
    {
        return 'xcart_order_logs';
    }

    public static function getFields()
    {
        return [
            'id' => AutoField::class,
            'order' => [
                'field' => 'orderid',
                'class' => ForeignField::class,
                'modelClass' => OrderModel::class,
                'link' => ['orderid' => 'orderid'],
                'null' => false,
            ],
            'type' => [
                'class' => CharField::class,
                'length' => 2,
                'default' => '',
                'null' => false,
            ],
            'date' => [
                'class' => UnixTimestampField::class,
                'autoNowAdd' => true,
                'autoNow' => true,
            ],
            'login' => [
                'class' => CharField::class,
                'length' => 40,
                'default' => '',
                'null' => false,
            ],
            'log' => [
                'class' => TextField::class,
                'null' => false,
            ]
        ];
    }

    public static function createLog(int $order_id, string $type, string $log_text, string $login = null): bool
    {
        if (!$log_text) {
            return false;
        }

        Xcart::app()->order_logger->add($order_id, $type, $log_text, $login);

        return true;
    }
}