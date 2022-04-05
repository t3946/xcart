<?php

namespace Modules\Order\Models\Decisions;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

/**
 * @property int $decision_type_id
 */
class DecisionTypeModel extends Model
{
    public static function tableName()
    {
        return 'decision_types';
    }

    public static function getFields()
    {
        return [
            'decision_type_id' => [
                'class' => AutoField::class,
            ],

            'name' => [
                'class' => CharField::class,
                'null' => false,
                'default' => false
            ],

            'slug' => [
                'class' => CharField::class,
                'null' => true,
            ],
        ];
    }
}