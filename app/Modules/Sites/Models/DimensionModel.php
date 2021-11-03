<?php

namespace Modules\Sites\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

class DimensionModel extends Model
{
    public static function tableName(): string
    {
        return 'xcart_dimensions';
    }

    public static function getFields(): array
    {
        return [
            'dimension_id' => AutoField::class,
            'name' => CharField::class,
            'value' => CharField::class,
            'code' => CharField::class,
            'type' => [
                'class' => CharField::class,
                'choices' => [
                    'weight' => 'weight',
                    'size' => 'size'
                ]
            ]
        ];
    }
}