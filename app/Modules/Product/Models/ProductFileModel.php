<?php

namespace Modules\Product\Models;

use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\DateTimeField;

class ProductFileModel extends AutoMetaModel
{
    public static function tableName()
    {
        return 'xcart_product_files';
    }

    public static function getFields()
    {
        return [
            'fileid' => [
                'class' => AutoField::className()
            ],
            'date' => [
                'class' => DateTimeField::className(),
                'autoNowAdd' => true
            ]
        ];
    }
}