<?php

namespace Modules\Payment\Models;

use Doctrine\DBAL\Types\Types;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

/**
 * @property mixed processor_name
 */
class ProcessorModel extends Model
{
    use AutoMetaTrait;

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
            ]

        ];
    }

    public function getAuthorizeDays(): int
    {
        if ($this->processor_name === 'Stripe') {
            return 7;
        }
        return 30;
    }
}