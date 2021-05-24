<?php


namespace Modules\Forms\Models;

use Modules\User\Models\UserModel;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class EmailActionModel extends Model
{

    public static function tableName()
    {
        return 'forms_email_action';
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
            'date' => [
                'field' => 'date',
                'class' => DateTimeField::class,
                'autoNow' => true
            ]
        ];
    }
}