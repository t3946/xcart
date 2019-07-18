<?php


namespace Modules\Shipping\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Model;

class ApproximationShippingModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_approximation_shipping_rates';
    }
}