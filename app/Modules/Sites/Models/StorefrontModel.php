<?php
namespace Modules\Sites\Models;

use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;

class StorefrontModel extends AutoMetaModel
{
    public static function tableName()
    {
        return 'xcart_storefronts';
    }

    public static function getFields()
    {
        return [
            'storefrontid' => [
                'class' => AutoField::className(),
            ]
        ];
    }
}