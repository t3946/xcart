<?php

namespace Modules\Shipping\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

/**
 * @property mixed carrier
 * @property mixed|\Xcart\App\Orm\Fields\Field|\Xcart\App\Orm\Fields\FileField|\Xcart\App\Orm\Fields\ModelFieldInterface|null aftership_code
 */
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
                'class' => AutoField::class
            ],
            'aftership_code' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
            ]
        ];
    }

    public function __toString()
    {
        return (string) $this->carrier;
    }
}