<?php

namespace Modules\Order\Models;


use Modules\GeoIp\Models\GeoipLitecityLocationModel;
use Modules\User\Models\UserModel;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\SerializeField;
use Xcart\App\Orm\Model;

class OrderExtraModel extends Model
{
    public static function tableName()
    {
        return 'order_extra';
    }

    public static function getFields()
    {
        return [

            'order' => [
                'field' => 'order_id',
                'class' => ForeignField::class,
                'modelClass' => OrderModel::class,
                'link' => ['order_id' => 'orderid'],
                'primary' => true,
            ],

            'submit_operator' => [
                'field' => 'submit_operator_id',
                'class' => ForeignField::class,
                'modelClass' => UserModel::class,
                'link' => ['submit_operator_id' => 'id'],
                'null' => true,
            ],

            'payment_operator' => [
                'field' => 'payment_operator_id',
                'class' => ForeignField::class,
                'modelClass' => UserModel::class,
                'link' => ['payment_operator_id' => 'id'],
                'null' => true,
            ],

            'purchase_order' => [
                'class' => SerializeField::className(),
                'null' => false,
            ],

            'ip' => [
                'class' => CharField::class,
                'null' => true,
            ],
        ];
    }

    public function getIP():? string
    {
        if (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $this->ip, $match)) {
            return $match[0];
        }
        return null;
    }

    public function getGeoLocation(): ?GeoipLitecityLocationModel
    {
        if (preg_match('/(\w+),\s(\w+),\s(\D+),\s(\d+)/', $this->ip, $match)) {
            return new GeoipLitecityLocationModel(
                [
                    'country' => $match[1] ? $match[1] : null,
                    'region' => $match[2] ? $match[2] : null,
                    'city' => $match[3] ? $match[3] : null,
                    'postalCode' => $match[4] ? $match[4] : null,
                ]
            );
        }
        return null;
    }
}