<?php


namespace Modules\User\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

/**
 * Class PbxOptionsModel
 *
 * @property (string) $extension
 * @property (string) $anveo_account
 * @property (string) $anveo_password
 *
 * @package Modules\User\Models
 */
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