<?php

namespace Modules\Distributor\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanCharField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

class RequestAvailabilityOptionModel extends Model
{
    public static function tableName()
    {
        return 'xcart_request_availability_options';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::class
            ],
            'name' => [
                'class' => CharField::class,
                'null' => false,
                'default' => ''
            ],
            'date_mm_dd_yyyy' => [
                'class' => CharField::class,
                'null' => true,
                'default' => ''
            ],
            'active' => [
                'class' => BooleanCharField::class,
                'null' => false,
                'default' => 'Y'
            ],
        ];
    }
}