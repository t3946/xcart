<?php
namespace Modules\Order\Models;

use Modules\User\Models\UserModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

/**
 * Class OrderUserActivityModel
 *
 * @property (int) $user_id
 * @property (int) $order_id
 * @property \DateTime $created_at
 *
 * @property UserModel $user
 * @property \Modules\Order\Models\OrderModel $order
 *
 * @package Modules\Order\Models
 */
abstract class AbstractOrderUserActivityModel extends Model
{
    public static function getFields()
    {
        return [
            'user' => [
                'field' => 'user_id',
                'class' => ForeignField::className(),
                'modelClass' => UserModel::className(),
                'link' => ['id', 'user_id'],
                'primary' => true,
            ],
            'order' => [
                'field' => 'order_id',
                'class' => ForeignField::className(),
                'modelClass' => OrderModel::className(),
                'link' => ['order_id' => 'orderid'],
                'primary' => true,
            ],
            'created_at' => [
                'class' => DateTimeField::className(),
                'default' => (new \DateTime())->modify('+5 seconds'),
                'autoNowAdd' => true,
                'autoNow' => true,
            ]
        ];
    }
}