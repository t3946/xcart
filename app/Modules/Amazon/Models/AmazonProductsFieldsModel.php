<?php
namespace Modules\Amazon\Models;


use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;

class AmazonProductsFieldsModel extends AutoMetaModel
{
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