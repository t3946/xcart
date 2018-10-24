<?php

namespace Modules\Amazon\Models;


use Modules\Goods\Models\ProductModel;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class AmazonInventoryQueueModel extends Model
{
    public static function tableName()
    {
        return 'amazon_inventory_queue';
    }

    public static function getFields()
    {
        return [
            'product' => [
                'class' => ForeignField::class,
                'modelClass' => ProductModel::class,
                'field' => 'product_id',
                'primary' => true,
                'null' => false,
            ],
            'type' => [
                'class' => CharField::class,
                'null' => false,
                'default' => 'MFN'
            ],
            'created_at' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
            ],
        ];
    }
}