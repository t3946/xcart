<?php


namespace Modules\Order\Models;


use Doctrine\DBAL\Types\Types;
use Modules\User\Models\UserModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class AttentionTagUserModel extends Model
{
    public static function tableName()
    {
        return 'xcart_attention_tags_values_logins';
    }

    public static function getFields()
    {
        return [
            'id' => AutoField::class,
            'status' => [
                'field' => 'status_id',
                'class' => ForeignField::class,
                'modelClass' => AttentionTagModel::class,
                'link' => ['status_id' => 'status_id'],
            ],
            'user' => [
                'filed' => 'login',
                'class' => ForeignField::class,
                'modelClass' => UserModel::class,
                'sqlType' => Types::STRING,
                'link' => ['login' => 'login']
            ],
            'action' => [
                'class' => CharField::class,
                'choices' => [
                    'set' => 'Set',
                    'unset' => 'Unset'
                ]
            ]
        ];
    }
}