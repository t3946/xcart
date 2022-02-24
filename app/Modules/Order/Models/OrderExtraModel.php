<?php

namespace Modules\Order\Models;


use Modules\GeoIp\Models\GeoipLitecityLocationModel;
use Modules\User\Models\UserModel;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\SerializeField;
use Xcart\App\Orm\Model;

/**
 * Class OrderExtraModel
 * @property string latitude
 * @property string longitude
 * @property string ip
 * @property array purchase_order
 * @property OrderModel order
 * @package Modules\Order\Models
 */
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
                'class' => SerializeField::class,
                'null' => false,
            ],

            'ip' => [
                'class' => CharField::class,
                'null' => true,
            ],
            'latitude' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
            'longitude' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ]
        ];
    }

    public function getIP(): ?string
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

    public function getFrontendPurchase(): array
    {
        if ($data = $this->purchase_order) {
            return [
                'poNumber' => $data['po_number'],
                'company' => $data['company_name'],
                'managerName' => $data['name_of_purchaser'],
                'managerPhoneExt' => $data['purchase_manager_phone_ext'],
                'managerEmail' => $data['managerEmail'],
                'managerFax' => $data['purchase_manager_fax'] ?? null,
                'managerPhone' => $data['purchase_manager_phone'],
                'accountsPayablePhone' => $data['accounts_payable_phone'],
                'accountsPayableFax' => $data['accounts_payable_fax'] ?? null,
                'accountsPayableEmail' => $data['accounts_payable_email'],
                'accountsPayableName' => $data['accounts_payable_full_name']
            ];
        }
        return [];
    }
}