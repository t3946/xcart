<?php

namespace Modules\Sites\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

/**
 * @property int address_id
 * @property string name
 * @property string address
 * @property string company
 */
class AddressModel extends Model
{
    public static function tableName(): string
    {
        return 'xcart_addresses';
    }
    public function __toString()
    {
        return "$this->company, $this->address";
    }

    public static function getFields(): array
    {
        return [
            'address_id' => AutoField::class,
            'name' => [
                'class' => CharField::class,
                'requires' => true,
                'verboseName' => 'Name'
            ],
            'company' => [
                'class' => CharField::class,
                'requires' => true,
                'verboseName' => 'Company'
            ],
            'address' => [
                'class' => CharField::class,
                'requires' => true,
                'verboseName' => 'Address'
            ],
            'address_state' => [
                'class' => CharField::class,
                'requires' => true,
                'verboseName' => 'Address state'
            ],
            'country' => [
                'class' => CharField::class,
                'requires' => true,
                'verboseName' => 'Country'
            ],
        ];
    }
}