<?php

namespace Modules\Amazon\Models;

use Modules\Product\Models\ProductModel;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Model;

class AmazonFbaMissingSkuModel extends Model
{
    public static function tableName()
    {
        return 'xcart_fba_missing_sku';
    }

    public static function getFields()
    {
        return [
            'missing_productcode' => [
                'class' => CharField::className(),
                'primary' => true,
                'null' => false,
            ],
            'product' => [
                'field' => 'productid',
                'class' => ForeignField::className(),
                'modelClass' => ProductModel::className(),
                'null' => false,
            ],
        ];
    }
}