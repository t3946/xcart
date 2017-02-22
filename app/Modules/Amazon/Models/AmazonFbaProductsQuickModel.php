<?php
namespace Modules\Amazon\Models;

use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;


class AmazonFbaProductsQuickModel extends AutoMetaModel
{
    public static function tableName()
    {
        return 'xcart_cidev_amazon_fba_products_quick';
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