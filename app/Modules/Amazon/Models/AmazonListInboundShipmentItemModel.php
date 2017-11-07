<?php

namespace Modules\Amazon\Models;

use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\IntField;
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
            'productid' => [
                'class' => IntField::className(),
                'primary' => true,
                'null' => false,
                'default' => 0
            ],
            'shipment_id' => [
                'class' => CharField::className(),
                'primary' => true,
                'null' => false,
                'default' => 0
            ]
        ];
    }
}