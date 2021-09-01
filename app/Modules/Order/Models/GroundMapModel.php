<?php


namespace Modules\Order\Models;


use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

class GroundMapModel extends Model
{
    public static function tableName()
    {
        return 'xcart_ground_map';
    }
    public static function getFields()
    {
        return [
            'zipcode' => [
                'class' => CharField::class,
                'primary' => true
            ],
            'map_url' => [
                'class' => CharField::class
            ],
        ];
    }
}