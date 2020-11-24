<?php


namespace Modules\PBX\Models;


use Modules\User\Models\UserModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class AnveoListensModel extends Model
{
    public static function tableName()
    {
        return 'anveo_calls_listens';
    }

    public static function getFields()
    {
        return [
            'id' => AutoField::class,
            'call' => [
                'field' => 'call_id',
                'class' => ForeignField::class,
                'modelClass' => PbxAnveoCallModel::class,
                'link' => ['call_id' => 'id']
            ],
            'user' => [
                'field' => 'user_id',
                'class' => ForeignField::class,
                'modelClass' => UserModel::class,
                'link' => ['user_id' => 'id']
            ],
            'listen_at' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
            ]
        ];
    }
}