<?php
namespace Modules\Brand\Models;

use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\IntField;

/**
 * @property mixed brandid
 */
class BrandModel extends AutoMetaModel
{
    public static function tableName()
    {
        return 'xcart_brands';
    }

    public static function getFields()
    {
        return [
            'brandid' => [
                'class' => AutoField::className(),
                'primary' => true,
                'null' => false,
            ],
        ];
    }
}