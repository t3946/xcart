<?php

namespace Modules\Amazon\Models;

use Modules\Product\Models\ProductModel;
use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;

class AmazonFbaMissingSkuModel extends AutoMetaModel
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