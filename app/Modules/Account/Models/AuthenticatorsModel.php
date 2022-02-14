<?php


namespace Modules\Account\Models;


use Doctrine\DBAL\Types\Types;
use Modules\Core\Models\CountryModel;
use Modules\Core\Models\StateModel;
use Modules\Forms\Models\EmailModel;
use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class AuthenticatorsModel extends Model
{
    public static function tableName()
    {
        return 'xcart_authenticators';
    }

    public static function getFields()
    {
        return [
            'authenticator_id' => [
                'class' => AutoField::class,
            ],
            'user_id' => [
                'class' => IntField::class,
            ],
            'secret' => [
                'class' => CharField::class,
            ],
        ];
    }
}