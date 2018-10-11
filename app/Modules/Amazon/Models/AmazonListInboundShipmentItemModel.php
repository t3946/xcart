<?php

namespace Modules\Amazon\Models;

use Doctrine\DBAL\Types\Type;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class AmazonListInboundShipmentItemModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_amazon_list_inbound_shipment_items';
    }

    public static function getFields()
    {
        return [
            'product' => [
                'field' => 'productid',
                'class' => ForeignField::class,
                'modelClass' => ProductModel::class,
                'primary' => true,
                'null' => false,
            ],
            'shipment' => [
                'field' => 'shipment_id',
                'class' => ForeignField::class,
                'classModel' => AmazonListInboundShipment::class,
                'primary' => true,
                'null' => false,
                'sqlType' => Type::STRING,
            ]
        ];
    }
}