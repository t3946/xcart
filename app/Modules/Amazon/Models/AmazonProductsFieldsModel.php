<?php
namespace Modules\Amazon\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Model;

class AmazonProductsFieldsModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_products_amz_fields';
    }

    public static function getFields()
    {
        return [
            'productid' => [
                'class' => AutoField::className(),
            ]
        ];
    }
}