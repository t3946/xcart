<?php

namespace Modules\Amazon\Models;

use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;

class AmazonFbaProductModel extends AutoMetaModel
{
    public static function tableName()
    {
        return 'xcart_cidev_amazon_fba_products';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::className(),
            ]
        ];
    }
}