<?php
namespace Modules\Cart\Models;

use Modules\Order\Models\OrderModel;
use Modules\User\Models\UserModel;
use Xcart\App\Cli\Cli;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasToOneField;
use Xcart\App\Orm\Model;

/**
 * Class CouponOrderModel
 *
 *  Linked Coupon to Customer
 *
 * @property (int) $id PK
 * @property OrderModel $order
 * @property (int) $order_id
 * @property (int) $coupon_id
 * @property \Modules\Cart\Models\CouponKitModel $coupon
 * @property UserModel $customer
 * @property (string) $uid
 * @property (string) $code
 * @property (string) $login
 * @property \DateTime $created_at
 *
 * @package Modules\Cart\Models
 */
class CouponOrderModel extends Model
{
    public static function tableName()
    {
        return "cart_coupon_orders";
    }

    public static function getFields()
    {
        return [
            'id' => AutoField::className(),

            'order' => [
                'class'=> ForeignField::className(),
                'modelClass' => OrderModel::className(),
                'link' => ['order_id' => 'orderid'],
                'null' => true,
            ],

            'coupon' => [
                'class' => ForeignField::className(),
                'modelClass' => CouponKitModel::className(),
                'link' => ['coupon_id' => 'id'],
                'managerFunction' => 'objectsAll',
                'null' => false,
            ],

            'customer' => [
                'class' => HasToOneField::className(),
                'modelClass' => UserModel::className(),
                'link' => ['login' => 'login'],
            ],

            'login' => [
                'class' => CharField::className(),
                'null' => false,
            ],
            'created_at' => [
                'class' => DatetimeField::className(),
                'autoNowAdd' => true,
            ]
        ];
    }

    protected function afterInsertInternal()
    {
        parent::afterInsertInternal();

        if (!Cli::isCli()) {
            Xcart::app()->request->session->remove('coupon_code');
        }
    }
}