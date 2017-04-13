<?php
namespace Modules\Product\Models;

use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\IntField;

/**
 * @property string categoryid_path
 * @property mixed categoryid
 */
class CategoryModel extends AutoMetaModel
{
    public static function tableName()
    {
        return 'xcart_categories';
    }

    public static function getFields()
    {
        return [
            'categoryid' => [
                'class' => AutoField::className(),
                'primary' => true,
                'null' => false,
            ],
            'storefrontid' => [
                'class' => IntField::className(),
                'primary' => true,
                'null' => false,
            ],
            'description' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => ''
            ],
            'google_product_category' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => ''
            ],
        ];
    }
}