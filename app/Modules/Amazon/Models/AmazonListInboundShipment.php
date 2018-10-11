<?php

namespace Modules\Amazon\Models;

use Modules\Order\Models\OrderModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Model;

/**
 * @property AmazonFulfillmentLocationsModel warehouse
 * @property AmazonListInboundShipmentItemModel[] items
 * @property string shipment_name
 * @property int|null order_id
 */
class AmazonListInboundShipment extends Model
{
    use AutoMetaTrait;

    public const SHIPMENT_STATUS_WORKING = 'WORKING';
    public const SHIPMENT_STATUS_SHIPPED = 'SHIPPED';
    public const SHIPMENT_STATUS_IN_TRANSIT = 'IN_TRANSIT';
    public const SHIPMENT_STATUS_DELIVERED = 'DELIVERED';
    public const SHIPMENT_STATUS_CHECKED_IN = 'CHECKED_IN';
    public const SHIPMENT_STATUS_RECEIVING = 'RECEIVING';
    public const SHIPMENT_STATUS_CLOSED = 'CLOSED';
    public const SHIPMENT_STATUS_CANCELLED = 'CANCELLED';
    public const SHIPMENT_STATUS_DELETED = 'DELETED';
    public const SHIPMENT_STATUS_ERROR = 'ERROR';

    public static function tableName()
    {
        return 'xcart_amazon_list_inbound_shipments';
    }

    public static function getFields()
    {
        return [
            'shipment_id' => [
                'class' => CharField::className(),
                'primary' => true,
                'null' => false,
                'default' => ''
            ],
            'are_cases_required' => [
                'class' => BooleanField::className(),
                'null' => false,
                'default' => false,
            ],
            'order' => [
                'field' => 'order_id',
                'class' => ForeignField::class,
                'modelClass' => OrderModel::class,
                'link' => ['order_id' => 'orederid'],
                'null' => true,
            ],
            'warehouse' => [
                'field' => 'destination_fulfillment_center_id',
                'class' => ForeignField::class,
                'modelClass' => AmazonFulfillmentLocationsModel::class,
                'link' => ['destination_fulfillment_center_id' => 'code'],
            ],
            'items' => [
                'class' => HasManyField::class,
                'modelClass' => AmazonListInboundShipmentItemModel::class,

                'link' => ['shipment_id' => 'shipment_id']
            ]
        ];
    }
}