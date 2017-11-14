<?php

namespace Modules\Amazon\Models;

use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\IntField;

class AmazonReorderBatchDataModel extends AutoMetaModel
{
    public static function tableName()
    {
        return 'amazon_reorder_batch_data';
    }

    public static  function getFields()
    {
        return [
            'batch_id' => [
                'class' => IntField::className(),
                'primary' => true,
                'null' => false,
                'default' => 0
            ],
            'productid' => [
                'class' => IntField::className(),
                'primary' => true,
                'null' => false,
                'default' => 0
            ],
        ];
    }
}