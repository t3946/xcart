<?php

namespace Modules\Amazon\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\TextField;
use Xcart\App\Orm\Fields\TimestampField;
use Xcart\App\Orm\Model;

class AmazonReorderBatchModel extends Model
{
    public static function tableName()
    {
        return 'amazon_reorder_batch';
    }
    public static  function getFields()
    {
        return [
            'batch_id' => [
                'class' => AutoField::className(),
            ],
            'user_id' => [
                'class' => IntField::className(),
            ],
            'status' => [
                'class' => TextField::className(),
                'default' => 'processing'
            ],
            'created_at' => [
                'class' => TimestampField::className(),
            ],
            'assortment' => [
                'class' => TextField::className(),
                'default' => 'Y'
            ],
        ];
    }
}