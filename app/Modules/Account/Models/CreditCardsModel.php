<?php


namespace Modules\Account\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class CreditCardsModel extends Model
{
    public static function tableName()
    {
        return 'account_credit_cards';
    }

    public static function getFields()
    {
        return [
            'credit_card_id' => [
                'class' => AutoField::class,
            ],
            'name' => [
                'class' => CharField::class,
            ],
            'address_model' => [
                'field' => 'address_id',
                'class' => ForeignField::class,
                'modelClass' => AddressesModel::class,
                'link' => ['address_id' => 'addresses_id'],
            ],
            'is_default' => [
                'class' => BooleanField::class,
            ],
            'card_type' => [
                'class' => CharField::class,
            ],
            'card_number' => [
                'class' => CharField::class,
            ],
            'expires' => [
                'class' => IntField::class,
            ],
        ];
    }
}