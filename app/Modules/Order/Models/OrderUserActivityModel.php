<?php
namespace Modules\Order\Models;

use Modules\User\Models\UserModel;
use Xcart\App\Main\Xcart;

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
class OrderUserActivityModel extends AbstractOrderUserActivityModel
{
    public static function tableName()
    {
        return 'xcart_order_user_actives';
    }

    public function save(array $fields = [])
    {
        $filter = [
            'user_id'=> $this->user_id,
            'order_id' => $this->order_id,
            'created_at__gte' => (new \DateTime())->modify( '-2 minutes' )
        ];

        if (!static::objects()->filter($filter)->count()) {
            return parent::save($fields);
        }

        $this->afterSave($this, false);

        return false;
    }

    public function afterSave($owner, $isNew)
    {
        OrderUserLastActivityModel::objects()->updateOrCreate(['user_id'=> $this->user_id, 'order_id' => $this->order_id], ['created_at' => $this->created_at]);
    }

    public static function userView($owner = null, $order_id)
    {
        $user = Xcart::app()->user;
        if (!$user->getIsGuest() && $order_id) {
            (new static(['user_id' => $user->id, 'order_id' => $order_id]))->save();
        }
    }
}