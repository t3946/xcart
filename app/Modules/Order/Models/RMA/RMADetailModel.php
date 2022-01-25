<?php

namespace Modules\Order\Models\RMA;

use Modules\Goods\Models\ProductModel;
use Modules\Order\Models\OrderDetailModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class RMADetailModel extends Model
{
    public static function tableName()
    {
        return 'xcart_rma_details';
    }

    public static function getFields()
    {
        return [
            'rma_id' => AutoField::class,
            'product_item' => [
                'field' => 'productid',
                'class' => ForeignField::class,
                'modelClass' => ProductModel::class,
                'link' => ['productid' => 'productid'],
            ],
            'detail_item' => [
                'field' => 'itemid',
                'class' => ForeignField::class,
                'modelClass' => OrderDetailModel::class,
                'link' => ['itemid' => 'itemid'],
            ],
            'productcode' => [
                'class' => CharField::class,
                'default' => '',
                'null' => false
            ],
            'product' => [
                'class' => CharField::class,
                'default' => '',
                'null' => false
            ],
            'amount' => [
                'class' => IntField::class,
            ],
            'would_like' => [
                'class' => CharField::class,
            ],
        ];
    }
}