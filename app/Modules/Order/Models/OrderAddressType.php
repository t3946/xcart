<?php

namespace Modules\Order\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

/**
 * @property int address_type_id
 * @property string name
 * @property string code
 */
class OrderAddressType extends Model
{
    public const ADDRESS_TYPE_BILLING = 'billing';
    public const ADDRESS_TYPE_SHIPPING = 'shipping';
    public const ADDRESS_TYPE_OWNER_SHIPPING = 'owner_shipping';
    public const ADDRESS_TYPE_OWNER_BILLING = 'owner_billing';
    public const ADDRESS_TYPE_PHONE_LOCATION = 'phone_location';
    public const ADDRESS_TYPE_IP_LOCATION = 'ip_location';

    public static function tableName(): string
    {
        return 'xcart_order_addresses_type';
    }

    public static function getFields(): array
    {
        return [
            'address_type_id' => AutoField::class,
            'name' => CharField::class,
            'code' => CharField::class
        ];
    }
}