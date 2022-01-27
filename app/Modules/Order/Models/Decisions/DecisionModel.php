<?php

namespace Modules\Order\Models\Decisions;

use Modules\Order\Models\OrderModel;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\JsonField;
use Xcart\App\Orm\Model;

class DecisionModel extends Model
{
    public const types = [
        'estimated-time-arrival',
        'payment-required',
        'license-required',
        'unpaid-order',
        'send-us-po',
        'increase-shipping-charge',
        'send-check',
        'street-address-required',
        'questions-ltl-freight-shipment',
        'responsibility-for-custom-duties',
        'alternative-items-offer',
        'additional-shipping-charge',
    ];

    public static function tableName()
    {
        return 'account_decisions';
    }

    public function isValid()
    {
        //unknown type
        if (!in_array($this->type, self::types)) {
            return false;
        }

        return parent::isValid();
    }

    public function solve($options) {
        $this->options = $options;
        $this->solved = true;
        $this->save();
    }

    public function save(array $fields = [])
    {
        if (!$this->isValid()) {
            return false;
        }

        return parent::save($fields);
    }

    public static function getFields()
    {
        return [
            'decision_id' => [
                'class' => AutoField::class,
            ],

            'type' => [
                'class' => CharField::class,
                'null' => false,
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

            'order' => [
                'field' => 'order_id',
                'class' => ForeignField::class,
                'modelClass' => OrderModel::class,
                'link' => ['order_id' => 'orderid'],
                'null' => false
            ]
        ];
    }
}