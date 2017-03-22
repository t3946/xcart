<?php
namespace Modules\Sites\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class SiteModel extends Model
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
            ],
            'code' => [
                'class' => CharField::className(),
                'length' => 10,
                'null' => false,
                'default' => '',
            ],
            'domain' => [
                'class' => CharField::className(),
                'null' => false,
            ],
            'prefix' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => '',
            ],
            'status' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => 'D',
                'choices' => [
                    'Y' => 'Enabled',
                    'E' => 'Service',
                    'D' => 'Disabled'
                ],
            ],
            'orderby' => [
                'class' => IntField::className(),
                'null' => false,
                'default' => 10
            ],
        ];
    }
}