<?php
namespace Modules\User\Models;

use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\CharField;

class User extends AutoMetaModel
{
    public static function tableName()
    {
        return 'xcart_customers';
    }

    public static function getFields()
    {
        return [
            'login' => [
                'class' => CharField::className(),
                'primary' => true,
                'null' => false
            ]
        ];
    }
}