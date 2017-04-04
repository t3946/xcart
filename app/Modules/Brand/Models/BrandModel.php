<?php
namespace Modules\Brand\Models;

use Xcart\App\Orm\AutoMetaModel;
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
                'class' => IntField::className(),
                'primary' => true,
                'null' => false,
            ],
        ];
    }
}