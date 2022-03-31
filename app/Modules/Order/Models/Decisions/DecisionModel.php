<?php

namespace Modules\Order\Models\Decisions;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\JsonField;
use Xcart\App\Orm\Model;

/**
 * @property bool $solved
 */
class DecisionModel extends Model
{
    public static function tableName()
    {
        return 'account_decisions';
    }

    public function solve($options) {
        $this->options = $options;
        $this->solved = true;
        $this->save();
    }

    public static function getFields()
    {
        return [
            'decision_id' => [
                'class' => AutoField::class,
            ],

            'solved' => [
                'class' => BooleanField::class,
                'null' => false,
                'default' => false
            ],

            'options' => [
                'class' => JsonField::class,
                'null' => true,
            ],

            'order_number' => [
                'class' => CharField::class,
            ],

            'created' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
            ],

            'updated' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
            ],

            'decision_type_id' => [
                'class' => IntField::class,
            ],

            'type' => [
                'class' => ForeignField::class,
                'modelClass' => DecisionTypeModel::class,
                'link' => ['decision_type_id' => 'decision_type_id'],
            ]
        ];
    }
}