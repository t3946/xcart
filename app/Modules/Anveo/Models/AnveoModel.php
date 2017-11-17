<?php

namespace Modules\Anveo\Models;

use Modules\User\Models\PbxOptionsModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class AnveoModel extends Model
{

    public static function tableName()
    {
        return 'anveo_calls';
    }

    public static function getFields()
    {
        return [

            'id' => AutoField::className(),

            'account' => [
                'field' => 'anveo_account',
                'class' => ForeignField::className(),
                'modelClass' => PbxOptionsModel::className(),
                'link' => [ 'anveo_account' => 'anveo_account'],
            ],

            'session' => [
                'class' => CharField::className(),
                'null' => false,
            ],

            'file' => [
                'class' => CharField::className(),
                'null' => true,
                'default' => null
            ],

            'cname' => [
                'class' => CharField::className(),
                'null' => true,
                'default' => null
            ],

            'e164' => [
                'class' => CharField::className(),
                'null' => true,
                'default' => null
            ],

            'rdnis' => [
                'class' => CharField::className(),
                'null' => true,
                'default' => null
            ],

            'start_at' => [
                'class' => DateTimeField::className(),
                'null' => false
            ],

            'end_at' => [
                'class' => DateTimeField::className(),
                'null' => true,
                'default' => null,
            ],

            'is_lost' => [
                'class' => BooleanField::className(),
                'null' => false,
                'default' => false
            ],

            'is_outgoing' => [
                'class' => BooleanField::className(),
                'null' => false,
                'default' => false
            ],



        ];
    }

}