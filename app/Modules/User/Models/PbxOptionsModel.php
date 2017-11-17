<?php


namespace Modules\User\Models;

use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class PbxOptionsModel extends Model
{
    public static function tableName()
    {
        return 'xcart_pbx_options';
    }

    public static function getFields()
    {
        return [

            'id' => AutoField::className(),

            'extension' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => ''
            ],

            'anveo_account' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => ''
            ],

            'anveo_password' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => ''
            ]

        ];
    }


}