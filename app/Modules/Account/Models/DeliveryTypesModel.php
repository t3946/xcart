<?php


namespace Modules\Account\Models;


use Modules\Forms\Models\EmailModel;
use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class DeliveryTypesModel extends Model
{
    public static function tableName()
    {
        return 'account_delivery_types';
    }

    public static function getFields()
    {
        return [
            'delivery_type_id' => [
                'class' => AutoField::class,
            ],
            'name' => [
                'class' => CharField::class,
            ],
        ];
    }
}