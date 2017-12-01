<?php

namespace Modules\Amazon\Models;

use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

class AmazonListInboundShipment extends Model
{
    use AutoMetaTrait;

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
        ];
    }
}