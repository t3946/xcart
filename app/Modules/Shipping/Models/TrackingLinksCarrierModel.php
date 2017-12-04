<?php

namespace Modules\Shipping\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Model;

class TrackingLinksCarrierModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_tracking_links_carrier';
    }

    public static function getFields()
    {
        return [
            'carrier_id' => [
                'class' => AutoField::className()
            ],
        ];
    }
}