<?php

namespace Modules\Payment\Models;

use Doctrine\DBAL\Types\Types;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

/**
 * @property string|null processor_name
 * @property string param01
 * @property string param02
 * @property string param03
 * @property string test_mode
 */
class ProcessorModel extends Model
{
    public const PAYMENT_NAME_PAYPAL = 'PayPal';
    public const PAYMENT_NAME_STRIPE = 'Stripe';
    public const PAYMENT_NAME_BLUEPAY = 'BluePay';

    public static function tableName()
    {
        return 'xcart_payment_processor';
    }

    public static function getFields()
    {
        return [
            'processor_id' => [
                'class' => AutoField::class,
            ],
            'processor_name' => [
                'class' => CharField::class,
                'default' => '',
                'null' => false
            ],
            'transaction_link' => [
                'class' => CharField::class,
                'default' => '',
                'null' => false
            ],
            'cc_processor' => [
                'field' => 'processor_name',
                'class' => ForeignField::class,
                'modelClass' => PaymentProcessorModel::class,
                'link' => ['processor_name' => 'module_name'],
                'sqlType' => Types::STRING,
            ],
            'test_mode' => [
                'class' => CharField::class,
                'default' => 'Y',
                'null' => true
            ],
            'param3' => [
                'class' => CharField::class,
                'default' => null,
                'null' => true
            ],
            'param2' => [
                'class' => CharField::class,
                'default' => null,
                'null' => true
            ],
            'param1' => [
                'class' => CharField::class,
                'default' => null,
                'null' => true
            ]
        ];
    }

    public function getTestMode(): bool
    {
        return $this->test_mode === 'Y';
    }

    public function getAuthorizeDays(): int
    {
        if ($this->processor_name === 'Stripe') {
            return 7;
        }
        return 30;
    }
}