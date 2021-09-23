<?php


namespace Modules\Forms\Models;


use Modules\User\Models\UserModel;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\FloatField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class EmailActionLogModel extends Model
{
    public static function tableName()
    {
        return 'forms_email_actions_log';
    }

    public static function getFields()
    {
        return [
            'user' => [
                'field' => 'user_id',
                'class' => ForeignField::class,
                'modelClass' => UserModel::class,
                'primary' => true,
                'link' => ['user_id' => 'id'],
            ],
            'email' => [
                'field' => 'email_id',
                'class' => ForeignField::class,
                'modelClass' => EmailModel::class,
                'primary' => true,
                'link' => ['email_id' => 'id'],
            ],
            'action_value' => [
                'field' => 'action_value',
                'class' => BooleanField::class,
            ]
        ];
    }
}